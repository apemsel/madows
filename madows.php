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
    "template" => "madows/madows.php",
    "index" => "index.md",
    "serveExtensions" => array("markdown", "mdown", "mkd", "md", "gif", "png", "jpg", "jpeg")
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

    $path_parts = pathinfo($url_parts["path"]);
    if (!in_array($path_parts["extension"], $this->config["serveExtensions"])) {
      self::error("access denied", 401);
    }
    
    $markdown_file = $path_parts["basename"];
    if (empty($markdown_file)) {
      $markdown_file = $this->config["index"];
    }
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
    
    list($toc, $body) = $this->buildToc($body);
    
    // No reason to support anything but UTF-8 by now
    header("Content-Type: text/html; charset=UTF-8"); 
    
    $this->render($template_file, array(
      "body" => $body,
      "title" => preg_match("/<h[1-6]{1}[^<>]*>(.+)<\/h[1-6]{1}>/", $body, $matches) ? strip_tags($matches[1]) : $markdown_file,
      "toc" => $toc
    ));
  }
  
  public function render($template_file, $context) 
  {
    extract($context, true);
    require($template_file);
  }
  
  public function slugify($s)
  {
    $patterns = array(
      "/[^a-z0-9_ ]/",
      "/[ _]+/"
    );
    
    $replacements = array(
      "",
      "_"
    );
    
    return trim(preg_replace($patterns, $replacements, mb_strtolower(strip_tags($s), "UTF-8")));
  }
  
  protected function buildToc($body)
  {
    $toc = '<ul>';
    $level = 1;
    $search = $replace = $anchors = array();
    
    // Find all headings and create TOC
    preg_match_all("/(<h([1-6]{1})[^<>]*>)(.+)(<\/h[1-6]{1}>)/", $body, $matches, PREG_SET_ORDER);
    foreach($matches as $match) {
      if ($match[2]>$level) {
        $toc .= str_repeat("<li><ul>", $match[2]-$level);
      }
      elseif ($match[2]<$level) {
        $toc .= str_repeat("</ul></li>", $level-$match[2]);
      }
      $level = $match[2];
      
      $anchor = $this->slugify($match[3]);
      
      // Ensure anchors are unique
      if (in_array($anchor, $anchors)){
        $anchor .= '-1';
      }
      $num = 1;
      while (in_array($anchor, $anchors)) {
        $anchor = str_replace('-'.$num, '-'.($num+1), $anchor);
      }
      
      $toc .= '<li><a href="#'.$anchor.'">'.$match[3]."</a></li>";
      $search[] = $match[0];
      $replace[] = "<h".$match[2].' id="'.$anchor.'">'.$match[3]."</h".$match[2].">";
    }
    
    // Close any remaining open <ul>s
    for(; $level; $level--) {
      $toc .= "</ul>";  
    }
    
    // Replace headings with headings that have IDs
    $body = str_replace($search, $replace, $body);
    
    return array($toc, $body);
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