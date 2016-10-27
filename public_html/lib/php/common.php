<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

/**
 * highlights a PHP file using the style in the <head> tag and echoes it inline
 *
 * the style can be any highlight.js compatible CSS stylesheet
 *
 * @param string $filename file name to read
 * @param bool $echo true to echo directly to the output (default), false to return a string
 * @return void|string returns a string if echo is false, nothing otherwise
 * @see https://github.com/isagalaev/highlight.js/tree/master/src/styles Highlight.js CSS stylesheets
 **/
function highlightFile(string $filename, bool $echo = true) {
	$highlighter = new Highlight\Highlighter();
	$highlighter->setAutodetectLanguages(["apache", "css", "javascript", "php", "sql", "typescript", "xml"]);
	$highlighted = $highlighter->highlightAuto(file_get_contents($filename));
	$output = "<pre class=\"hljs " . $highlighted->language . "\">" . $highlighted->value . "</pre>";
	if($echo === true) {
		echo $output . PHP_EOL;
	} else {
		return($output);
	}
}
