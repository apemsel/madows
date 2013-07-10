<?php

namespace apemsel\madows;

require("vendor/autoload.php");


$madows = new Madows();
$madows->serve($_SERVER["REQUEST_URI"]);

if (!isset($_SERVER["REQUEST_URI"])) {
  die("REQUEST_URI not set");
}

class Madows
{
  const CONFIG_FILE = "madows.json";
  const TEMPLATE_DIR = "templates";

  protected $config;
  protected $parser;

  public function __construct() 
  {
    if (!$config = file_get_contents(self::CONFIG_FILE)) {
      error("unable to read ".self::CONFIG_FILE);
    }
    
    $this->config = json_decode(file_get_contents("madows.json"), true);
    if (!$this->config) {
      error("unable to parse ".self::CONFIG_FILE);
    }

    $parser_class = "dflydev\\markdown\\".$this->config["parser"];
    if (!class_exists($parser_class)) {
      error("parser not found");
    }

    $this->parser = new $parser_class();
  }

  public function serve($request)
  {
    $url_parts = parse_url($request);
    if (!$url_parts) {
      error("unable to parse URI");
    }
    
    if (!isset($url_parts["path"]) or preg_match("/^\./", $url_parts["path"])) {
      error("illegal path");
    }

    $markdown_file = basename($url_parts["path"]);
    if (!file_exists($markdown_file)) {
      error("file not found: $markdown_file", 404);
    }
    
    $markdown = file_get_contents($markdown_file);
    if (!$markdown) {
      error("unable to read: $requested");
    }
    
    $body = $this->parser->transformMarkdown($markdown);

    $template_file = self::TEMPLATE_DIR.DIRECTORY_SEPARATOR.$this->config["template"];
    if(!file_exists($template_file)) {
      error("file not found: ".$template_file);
    }
    
    $this->render($template_file, array(
      "body" => $body,
      "title" => $markdown_file
    ));
  }
  
  function render($template_file, $context) 
  {
    extract($context, true);
    require($template_file);
  }
  
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
}