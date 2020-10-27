<?php 
	session_start();
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Twitter -->
		<meta name="twitter:site" content="@themepixels">
		<meta name="twitter:creator" content="@themepixels">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="Shamcey">
		<meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
		<meta name="twitter:image" content="http://themepixels.me/shamcey/img/shamcey-social.png">
		<!-- Facebook -->
		<meta property="og:url" content="http://themepixels.me/shamcey">
		<meta property="og:title" content="Shamcey">
		<meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">
		<meta property="og:image" content="http://themepixels.me/shamcey/img/shamcey-social.png">
		<meta property="og:image:secure_url" content="http://themepixels.me/shamcey/img/shamcey-social.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="600">
		<!-- Meta -->
		<meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
		<meta name="author" content="ThemePixels">
		<title>List of Audio</title>
		<!-- Vendor css -->
		<link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="lib/Ionicons/css/ionicons.css" rel="stylesheet">
		<link href="lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
		<!-- Shamcey CSS -->
		<link rel="stylesheet" href="css/shamcey.css">
		<link rel="stylesheet" type="text/css" href="css/style2.css">
	</head>

	<body>
		<?php include("leftmenu.php");  ?>
		<?php include("header-panel.php"); ?>

		<div class="sh-mainpanel">
			<div class="sh-breadcrumb">
				<nav class="breadcrumb">
					<a class="breadcrumb-item" href="index.html">Audio</a>
					<span class="breadcrumb-item active">Form</span>
				</nav>
			</div><!-- sh-breadcrumb -->

			<div class="sh-pagebody" ng-app="homeApp" ng-controller="formCtrl" ng-init="init()">
				<div class="list-data">
					<div class="list-title">
						<div class="list-title-option">
							<select style="width: 130px; display: inline-block;" class="form-control" ng-model="filter.language" ng-change="searchAudio()">
								<option value="">All Language</option>
								<option value="en">English</option>
								<option value="fr">France</option>
							</select>
						</div>
					</div>
					<div class="table-list">
						<table class="table-listing">
							<thead>
								<tr>
									<th>No</th>
									<th>Audio</th>
									<th>Language</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="audio in data track by $index">
									<td>{{$index + 1}}</td>
									<td>
										<b>{{audio.name}}</b><br>
										<audio controls="">
											<source ng-src="{{audio.url}}">
										</audio>
									</td>
									<td>
										<select class="form-control" ng-model="audio.language" ng-disabled="!audio.update">
											<option value="en">English</option>
											<option value="fr">France</option>
										</select>
									</td>
									<td>
										<button ng-if="!audio.update" class="btn btn-primary" ng-click="loadUpdate(audio)">Update</button>
										<button ng-if="audio.update" class="btn btn-success" ng-click="saveUpdate(audio)">Save</button>
										|
										<button class="btn btn-danger" ng-click="deleteAudio($index)">Delete</button>
									</td>
								</tr>
							</tbody>
						</table>
						<br>
						<ul class="pagination">
				    		<li ng-if="currentPage <= 1" class="page-item disabled">
								<a href="javascript:" class="page-link">Prev</a>
							</li>
							<li ng-if="currentPage > 1" class="page-item">
								<a href="javascript:" ng-click="selectPage(currentPage-1)" class="page-link">Prev</a>
							</li>
							<li ng-repeat="page in paginationDisplay track by $index" class="page-item">
								<a href="javascript:" ng-if="page != '...' && page != currentPage" ng-click="selectPage(page)" class="page-link">
									{{page}}
								</a>
								<a href="javascript:" ng-if="page == currentPage" class="page-link active">
									{{page}}
								</a>
								<span ng-if="page == '...'">{{page}}</span>
							</li>
							<li ng-if="currentPage >= dataPagination" class="page-item disabled">
								<a href="javascript:" class="page-link">Next</a>
							</li>
							<li ng-if="currentPage < dataPagination" class="page-item">
								<a href="javascript:" ng-click="selectPage(currentPage+1)" class="page-link">Next</a>
							</li>
						</ul>
					</div>
			 	</div>
			 	<!-- ./list-data -->
			</div><!-- sh-pagebody -->
		</div><!-- sh-mainpanel -->

		<script src="lib/jquery/jquery.js"></script>
		<script src="lib/popper.js/popper.js"></script>
		<script src="lib/bootstrap/bootstrap.js"></script>
		<script src="lib/jquery-ui/jquery-ui.js"></script>
		<script src="lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
		<script src="lib/moment/moment.js"></script>
		<!-- <script src="lib/Flot/jquery.flot.js"></script> -->
		<!-- <script src="lib/Flot/jquery.flot.resize.js"></script> -->
		<!-- <script src="lib/flot-spline/jquery.flot.spline.js"></script> -->
		<script src="js/shamcey.js"></script>
		<!-- <script src="js/dashboard.js"></script> -->
	</body>
</html>

<script src="https://static.filestackapi.com/v3/filestack.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
	var app = angular.module('homeApp', []);
	app.controller('formCtrl', function($scope, $http, $timeout) {
		$scope.data = [];
		$scope.paginationDisplay = [];
		$scope.dataEntry = 25;
		$scope.dataPagination = 0;
		$scope.currentPage = 1;

		$scope.filter = {
			"language": ""
		};

		$scope.init = function() {
			$scope.getData(1);
			$scope.countData();
		}

		$scope.getData = function(page) {
			var url = "ajax/audio.php?entry="+$scope.dataEntry+"&page="+page;
			angular.forEach($scope.filter, function(value, key) {
				if (value.trim() != "") {
					url += "&"+key+"="+value;
				}
			});

			$http({
				method: 'GET',
				url: url
			}).then(function successCallback(response) {
				var data = response.data;
				$scope.data = data;
			}, function errorCallback(response) {
				console.log(response);
			});
		}

		// count product
		$scope.countData = function(count) {
			var url = "ajax/audio.php?count";
			angular.forEach($scope.filter, function(value, key) {
				if (value.trim() != "") {
					url += "&"+key+"="+value;
				}
			});
			$http({
				method: 'GET',
				url: url,
			}).then(function successCallback(response) {
				var count = response['data']['count'];
				// $scope.countData = count;
				$scope.dataPagination = Math.ceil(count/$scope.dataEntry);
				$scope.setPagination($scope.dataPagination);
			}, function errorCallback(response) {
				console.log(response);
			});
		}

		// set Pagination after load messenger data
		$scope.setPagination = function(pagination, currentPage) {
			if (typeof currentPage === 'undefined') {
				currentPage = 1;
			}
			$scope.currentPage = currentPage;
			var pageLeft = [];
			var pageCenter = [];
			var pageRight = [];

			if (pagination >= 10) {
				var last = pagination -1;
				pageRight = ['...'];
				pageCenter = [1, 2, 3, 4, 5];
				pageRight.push(pagination);
				if (currentPage > 4 && currentPage <= last-4) {
					pageLeft = [1, '...'];
					pageCenter = [currentPage-2, currentPage-1,currentPage, currentPage+1, currentPage+2];
				} else if (currentPage > last-4) {
					pageLeft = [1, '...'];
					pageCenter = [];
					pageRight = [last-3, last-2, last-1, last, pagination];
				}
			} else {
				for (var i = 0; i < pagination; i++) {
					var page = i+1;
					pageCenter.push(page);
				}
			}

			$scope.paginationDisplay = pageLeft;
			$scope.paginationDisplay.push.apply($scope.paginationDisplay, pageCenter);
			$scope.paginationDisplay.push.apply($scope.paginationDisplay, pageRight);
			// console.log($scope.paginationDisplay)
		}

		// Select Pagination
		$scope.selectPage = function(page) {
			$scope.getData(page);
			$scope.currentPage = page;
			$scope.setPagination($scope.dataPagination, page);
		}

		$scope.searchAudio = function() {
			$scope.getData(1);
			$scope.countData();
		}

		$scope.loadUpdate = function(obj) {
			obj.update = true;
		}

		$scope.saveUpdate = function(obj) {
			delete obj.update;
			var data = angular.copy(obj);
			var id = obj._id.$id;
			delete data._id;
			var dataJson = {
				"data": data,
				"id": id
			};
			var url = "ajax/audio.php";
			$http({
				method: 'PUT',
				url: url,
				data: JSON.stringify(dataJson),
				headers: {
				   'Content-Type': 'application/json'
				}
			}).then(function successCallback(response) {
				console.log(response)
			}, function errorCallback(response) {
				console.log(response);
			});
		}

		$scope.deleteAudio = function(index) {
			var data = $scope.data[index];
			var id = data._id.$id;
			var name = data.name;
			if(confirm('Are you sure to delete audio name '+name) == false) {
				return 0;
			}
			$scope.data.splice(index, 1);
			var dataJson = {"id": id};
			var url = "ajax/audio.php";
			$http({
				method: 'DELETE',
				url: url,
				data: JSON.stringify(dataJson),
				headers: {
				   'Content-Type': 'application/json'
				}
			}).then(function successCallback(response) {
				console.log(response)
			}, function errorCallback(response) {
				console.log(response);
			});
		}
	});
</script>