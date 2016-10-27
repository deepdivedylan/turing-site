app.service("GitHubBrowserService", function($http) {
	this.baseUrl = "/api/github-browser/";

	this.fetch = function(repository, username) {
		return($http.get(this.baseUrl + "?repository=" + repository + "&username=" + username));
	};

	this.fetchUrl = function(url) {
		return($http.get(url));
	};
});
