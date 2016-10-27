<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
use GuzzleHttp\Client;
use Edu\Cnm\GitHubBrowser\{GitHubEntity, GitHubException};

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;

try {
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize inputs
	$repository = filter_input(INPUT_GET, "repository", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$username = filter_input(INPUT_GET, "username", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	if(empty($repository) === true) {
		throw(new RuntimeException("invalid repository", 400));
	}
	if(empty($username) === true) {
		throw(new RuntimeException("invalid username", 400));
	}

	if($method === "GET") {
		$guzzle = new Client(["base_uri" => "https://api.github.com/repos/$username/$repository/"]);
		$guzzleReply = $guzzle->get("branches/master");
		if($guzzleReply->getStatusCode() < 200 || $guzzleReply->getStatusCode() >= 300) {
			throw(new GitHubBrowserException("unable to contact github: " . $reply->getReasonPhrase(), $reply->getStatusCode()));
		}
		$reply->status = $guzzleReply->getStatusCode();

		$json = (string)$guzzleReply->getBody();
		$repositoryBase = json_decode($json);
		$sha = $repositoryBase->commit->sha;

		$guzzleReply = $guzzle->get("git/trees/$sha?recursive=1");
		$json = (string)$guzzleReply->getBody();
		$repositoryTree = json_decode($json)->tree;

		$gitHubEntities = [];
		foreach($repositoryTree as $entity) {
			$gitHubEntities[] = new GitHubEntity($entity);
		}
		usort($gitHubEntities, ["Edu\\Cnm\\GitHubBrowser\\GitHubEntity", "compareTo"]);
		$reply->data = $gitHubEntities;
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content-type: application/json");

// encode and return reply to front end caller
echo json_encode($reply);
