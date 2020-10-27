<?php
	session_start();
	ini_set('mongo.long_as_object', 1);
	include('../connection.php');
	date_default_timezone_set('Asia/Phnom_Penh');
	$collection = new MongoCollection($db, 'audio_feedback');
	switch ($_SERVER['REQUEST_METHOD']) {
		case 'GET':
			if (isset($_GET['count'])) {
				echo countData();
			} else if (isset($_GET['search'])) {
				echo searchData();
			} else {
				echo getData();
			}

			break;
		case 'POST':
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			echo insertData($data);
			break;

		case 'PUT':
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			echo updateData($data);
			break;

		case 'DELETE':
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			echo deleteData($data);
			break;
		default:
			echo '{"error": "Method not created"}';
			break;
	}

	function insertData($data)
	{
		global $collection;
		$time = time()*1000;
		$insert = $collection->insert($data);

		return json_encode($data);
	}

	function getData()
	{
		global $collection;
		$limit = 25;
		$skip = 0;
		if (isset($_GET['entry'])) {$limit = $_GET['entry'];}
		if (isset($_GET['page'])) {$skip = ($_GET['page'] - 1) * $limit;}

		$filter = array();
		$mainKey = array('entry', 'page');
		foreach ($_GET as $key => $value) {
			if (array_search($key, $mainKey) !== false || $value == "") {
				continue;
			}
			$filter[$key] = $value;
		}
		$data = $collection->find($filter)
			->limit($limit)
			->skip($skip)
			->sort(array('_id' => -1));
		$result = array();
		foreach ($data as $value) {
			$result[] = $value;
		}
		return json_encode($result);
	}

	function countData()
	{
		global $collection;
		$search = new MongoRegex("/.*".$_GET['count'].".*/i");
		$filter = array();
		$mainKey = array('entry', 'page');
		foreach ($_GET as $key => $value) {
			if (array_search($key, $mainKey) !== false || $value == "") {
				continue;
			}
			$filter[$key] = $value;
		}

		$count = $collection->find($filter)->count();
		$result = array("count" => $count);
		return json_encode($result);
	}

	function searchData()
	{
		global $collection;
		$limit = 25;
		$skip = 0;
		if (isset($_GET['entry'])) { $limit = $_GET['entry']; }
		if (isset($_GET['page'])) {
			$skip = ($_GET['page'] - 1)*$limit;
		}
		$filter = array();
		$search = new MongoRegex("/.*".$_GET['search'].".*/i");
		$field = array();
		if (isset($_GET['fields']) && trim($_GET['search']) != "") {
			$arr = explode(",", $_GET['fields']);
			foreach ($arr as $value) {
				$field[] = array($value => $search);
			}
			$filter['$or'] = $field;
		}

		// return json_encode($filter);

		$userData = $collection->find($filter)
			->limit($limit)
			->skip($skip)
			->sort(array("_id" => -1));
		$data = array();
		foreach ($userData as $value) {
			$data[] = $value;
		}
		return json_encode($data);
	}

	function deleteData($data)
	{
		global $collection;
		if (!isset($data['id'])) { return '{"error": "request id to delete"}'; }	
		$id = new MongoId($data['id']);
		$find = array('_id' => $id);
		$collection->remove($find);
		return '{"status": "success"}';
	}

	function updateData($data)
	{
		global $collection;

		if (!isset($data['id'])) {
			http_response_code(400);
			return '{"error": "request id"}';
		} else if (!isset($data['data'])) {
			http_response_code(400);
			return '{"error": "request data to update"}';
		}
		
		$updateData = $data['data'];
		$id = new MongoId($data['id']);

		$result = $collection->update(
			array('_id' => $id),
			array('$set' => $updateData)
		);

		return '{"status": true}';
	}
