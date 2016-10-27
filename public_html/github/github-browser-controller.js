app.controller("GitHubBrowserController", ["$scope", "GitHubBrowserService", function($scope, GitHubBrowserService) {
	$scope.files = [];
	$scope.repository = "angular2-diceware";
	$scope.username = "dylan-mcdonald";

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
