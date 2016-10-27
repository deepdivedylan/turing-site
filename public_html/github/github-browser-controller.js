app.controller("GitHubBrowserController", ["$scope", "GitHubBrowserService", function($scope, GitHubBrowserService) {
	$scope.currentFilename = null;
	$scope.currentFileContent = null;
	$scope.files = [];
	$scope.repository = "angular2-diceware";
	$scope.username = "dylan-mcdonald";

	$scope.getFile = function(filename, url) {
		GitHubBrowserService.fetchUrl(url)
		.then(function(result) {
			if(result.data.status === 200) {
				$scope.currentFilename = filename;
				$scope.currentFileContent = result.data.data;
			}
		});
	};

	$scope.getFiles = function() {
		GitHubBrowserService.fetch($scope.repository, $scope.username)
		.then(function(result) {
			if(result.data.status === 200) {
				$scope.files = result.data.data;
			}
		});
	};

	if($scope.files.length === 0) {
		$scope.getFiles();
	}
}]);
