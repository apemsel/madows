<?php

namespace apemsel\madows;

require("vendor/autoload.php");

if (!isset($_SERVER["REQUEST_URI"])) {
  die("REQUEST_URI not set");
}

$madows = new Madows("madows.json");
$madows->serve($_SERVER["REQUEST_URI"]);

class Madows
{
  const TEMPLATE_DIR = "templates";

  protected $config;
  protected $parser;
  protected static $defaults = array(
    "parser" => "MarkdownExtraParser",
    "template" => "madows/madows.php"
  );

  public function __construct($config_file) 
  {
    if (!$json = file_get_contents($config_file)) {
      self::error("unable to read ".$config_file);
    }
    
    $this->config = json_decode($json, true);
    if (!$this->config) {
      self::error("unable to parse ".self::CONFIG_FILE);
    }
    
    $this->config = array_merge(self::$defaults, $this->config);

    $parser_class = "dflydev\\markdown\\".$this->config["parser"];
    if (!class_exists($parser_class)) {
      self::error("parser not found");
    }

    $this->parser = new $parser_class();
  }

  public function serve($request)
  {
    $url_parts = parse_url($request);
    if (!$url_parts) {
      self::error("unable to parse URI");
    }
    
    if (!isset($url_parts["path"]) or preg_match("/^\./", $url_parts["path"])) {
      self::error("illegal path");
    }

    $markdown_file = basename($url_parts["path"]);
    if (!file_exists($markdown_file)) {
      self::error("file not found: $markdown_file", 404);
    }
    
    $markdown = file_get_contents($markdown_file);
    if (!$markdown) {
      self::error("unable to read: $requested");
    }
    
    $body = $this->parser->transformMarkdown($markdown);

    $template_file = self::TEMPLATE_DIR.DIRECTORY_SEPARATOR.$this->config["template"];
    if(!file_exists($template_file)) {
      self::error("file not found: ".$template_file);
    }
    
    $this->render($template_file, array(
      "body" => $body,
      "title" => $markdown_file
    ));
  }
  
  public function render($template_file, $context) 
  {
    extract($context, true);
    require($template_file);
  }
  
  public static function error($message, $code = 500)
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