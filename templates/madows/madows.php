<!doctype HTML>
<html>
  <head>
    <title><?php echo $title ?></title>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="templates/madows/css/screen.css" type="text/css" media="screen">
    <link rel="stylesheet" href="templates/madows/css/print.css" type="text/css" media="print">
    <script src="templates/madows/js/madows.js"></script>
  </head>
  <body>
    <nav>
      <a href="/"><i class="icon-home" title="Home"></i></a>
      <a id="toggle_toc" href="javascript:toggleDisplay('TOC')"><i class="icon-list" title="Table of Contents"></i></a>
      <a href="<?php echo $_SERVER['REQUEST_URI'].'?source' ?>"><i class="icon-file-text" title="Markdown"></i></a>
    </nav>
    <section class="page">
      <header>
        <section id="TOC">
          <?php echo $toc ?>  
        </section>
      </header>
      <article>
        <?php echo $body ?>
      </article>
    </section>
  </body>
</html>