<!doctype HTML>
<html>
  <head>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="templates/madows/css/screen.css" type="text/css" media="screen">
    <link rel="stylesheet" href="templates/madows/css/print.css" type="text/css" media="print">
    <script src="templates/madows/js/madows.js"></script>
  </head>
  <body>
    <header>
      <nav>
        <a href="/">Index</a>
        <a id="toggle_toc" href="javascript:toggleDisplay('TOC')">Table of Contents</a>
        <a href="<?php echo $_SERVER['REQUEST_URI'].'?source' ?>">Source</a>
      </nav>
      <section id="TOC">
        <?php echo $toc ?>  
      </section>
    </header>
    <article>
      <?php echo $body ?>
    </article>
  </body>
</html>