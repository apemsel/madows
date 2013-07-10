<?php

namespace apemsel\madows;

require("vendor/autoload.php");

const CONFIG_FILE = "madows.json";
const TEMPLATE_DIR = "templates";

if (!$config = file_get_contents(CONFIG_FILE)) {
  error("unable to read ".CONFIG_FILE);
}

$config = json_decode(file_get_contents("madows.json"), true);
if (!$config) {
  error("unable to parse ".CONFIG_FILE);
}

$parser_class = "dflydev\\markdown\\".$config["parser"];
if (!class_exists($parser_class))
{
  error("parser not found");
}

$parser = new $parser_class();

if (!isset($_SERVER["REQUEST_URI"]))
{
  error("REQUEST_URI not set");
}
$url_parts = parse_url($_SERVER['REQUEST_URI']);
if (!$url_parts)
{
  error("unable to parse URI");
}

if (!isset($url_parts["path"]) or preg_match("/^\./", $url_parts["path"]))
{
  error("illegal path");
}

$requested = basename($url_parts["path"]);
if (!file_exists($requested))
{
  error("file not found: $requested", 404);
}

$markdown = file_get_contents($requested);
if (!$markdown)
{
  error("unable to read: $requested");
}

$body = $parser->transformMarkdown($markdown);

$template_file = TEMPLATE_DIR.DIRECTORY_SEPARATOR.$config["template"];
if(!file_exists($template_file))
{
  error("file not found: ".$template_file);
}

require($template_file);

function error($message, $code = 500)
{
  @header("HTTP/1.1 $code");
  @header("Content-type: text/html");
  @header("Connection: Close");
  ?><!DOCTYPE HTML>
<html><head>
<title><?php echo $code ?></title>
</head><body>
<h1><?php echo $code.' - '.$message ?></h1>
</body></html><?php
  die();
}