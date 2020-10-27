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
		<title>Audio Form</title>
		<!-- Vendor css -->
		<link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="lib/Ionicons/css/ionicons.css" rel="stylesheet">
		<link href="lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
		<!-- Shamcey CSS -->
		<link rel="stylesheet" href="css/shamcey.css">
		<style type="text/css">
			.table-audio tr td {
				width: 50%;
			}
		</style>
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
				<div class="card bd-primary mg-t-20">
					<div class="card-header bg-primary tx-white">Audio Form</div>
					<div class="card-body pd-sm-30">
						<div class="form-layout">
							<div class="row mg-b-25">
								<table class="table table-audio">
									<tr>
										<td>
											<button class="btn btn-primary btn-block" ng-click="upload()">
												Upload Audio
											</button>
											<br>
											<audio controls="" ng-if="audio.url">
												<source ng-src="{{audio.url}}">
											</audio>
										</td>
										<td>
											<select class="form-control" ng-model="audio.language">
												<option value="en">English</option>
												<option value="fr">France</option>
											</select>
										</td>
									</tr>
								</table>
							</div>
							<div class="form-layout-footer">
				                <button class="btn btn-success mg-r-5" ng-click="saveData($event)">Save</button>
				                <button class="btn btn-secondary" ng-click="resetData()">Reset</button>
				            </div>
						</div>
					</div>
				</div>
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
		$scope.audio = {};

		$scope.init = function() {
			$scope.resetData();
		}

		$scope.upload = function() {
			var filestackToken = filestack.init("A2nnDYLIOT5GQA2MjbznMz");
			var acceptType = "audio/*";
	    	var accept = {"accept": acceptType};
	    	accept['maxSize'] = 26214400;

	    	filestackToken.pick(accept).then(function(result) {
	        	var data = result.filesUploaded[0];
	        	var url = data.url;
	        	var filename = data.filename;
	        	$scope.audio.name = filename;
	        	$scope.audio.url = url;

	        	$scope.$apply();
	        });
		}

		$scope.saveData = function(event) {
			if ($scope.audio.url == "") {
				alert("Please upload audio file");
				return 0;
			}
			var me = event.target;
			$(me).prop("disabled", true);
			$(me).html("Saving");
			var dataJson = angular.copy($scope.audio);
			$http({
				method: 'POST',
				url: "ajax/audio.php",
				data: JSON.stringify(dataJson),
				headers: {
				   'Content-Type': 'application/json'
				},
			}).then(function successCallback(response) {
				var data = response.data;
				$(me).prop("disabled", false);
				$(me).html("Save");
				$scope.resetData();
			}, function errorCallback(response) {
				console.log(response);
			});
		}

		$scope.resetData = function() {
			$scope.audio = angular.copy($scope.simpleAudio());
		}

		$scope.simpleAudio = function() {
			var data = {
				"url": "",
				"name": "",
				"language": "en",
			};
			return data;
		}
	});
</script>
