<!doctype HTML>
<html>
  <head>
    <title><?php echo $title ?></title>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="templates/madows/css/screen.css" type="text/css" media="screen,print">
    <link rel="stylesheet" href="templates/madows/css/print.css" type="text/css" media="print">
    <script src="templates/madows/js/madows.js"></script>
  </head>
  <body>
    <nav>
      <a href="/"><i class="icon-home icon-fixed-width"></i><span>Home</span></a>
      <a id="toggle_toc" href="javascript:toggleTOC()"><i class="icon-list icon-fixed-width"></i><span>TOC</span></a>
      <a href="<?php echo $_SERVER['REQUEST_URI'].'?source' ?>"><i class="icon-file-text icon-fixed-width"></i><span>Markdown</span></a>
    </nav>
    <section class="page">
      <header>
        <section id="toc">
          <?php echo $toc ?>  
        </section>
      </header>
      <article>
        <?php echo $body ?>
      </article>
    </section>
  </body>
</html>