<!DOCTYPE html>
<html ng-app="GitHubBrowser">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>

		<script type="text/javascript" src="github-browser.js"></script>
		<script type="text/javascript" src="github-browser-service.js"></script>
		<script type="text/javascript" src="github-browser-controller.js"></script>
		<title>GitHub Browser</title>
	</head>
	<body>
		<main class="container" ng-controller="GitHubBrowserController">
			<h1>GitHub Browser</h1>
			<p>
				Repository: {{ repository }}<br />
				Username: {{ username }}
			</p>
			<ul ng-repeat="file in files">
				<li ng-if="file.type === 'blob'"><a ng-click="getFile(file.path, file.downloadUrl);">{{ file.path }}</a></li>
			</ul>
		</main>
	</body>
</html>
