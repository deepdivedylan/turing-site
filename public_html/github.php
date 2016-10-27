<?php
require_once(dirname(__DIR__) . "/vendor/autoload.php");
require_once(dirname(__DIR__) . "/php/classes/autoload.php");
use GuzzleHttp\Client;
use Edu\Cnm\GitHubBrowser\{GitHubEntity, GitHubException};

$guzzle = new Client(["base_uri" => "https://api.github.com/repos/dylan-mcdonald/angular2-diceware/"]);
$reply = $guzzle->get("branches/master");
if($reply->getStatusCode() < 200 || $reply->getStatusCode() >= 300) {
	throw(new GitHubBrowserException("unable to contact github: " . $reply->getReasonPhrase(), $reply->getStatusCode()));
}

$json = (string)$reply->getBody();
$repositoryBase = json_decode($json);
$sha = $repositoryBase->commit->sha;

$reply = $guzzle->get("git/trees/$sha?recursive=1");
$json = (string)$reply->getBody();
$repositoryTree = json_decode($json)->tree;

$gitHubEntities = [];
foreach($repositoryTree as $entity) {
	$gitHubEntities[] = new GitHubEntity($entity);
}
usort($gitHubEntities, ["Edu\\Cnm\\GitHubBrowser\\GitHubEntity", "compareTo"]);
var_dump($gitHubEntities);
