<doctype HTML>
<html>
  <head>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="templates/madows/css/screen.css" type="text/css" media="screen">
    <link rel="stylesheet" href="templates/madows/css/print.css" type="text/css" media="print">
  </head>
  <body>
    <section id="TOC">
      <?php echo $toc ?>  
    </section>
    <section class="article">
      <?php echo $body ?>
    </section>
  </body>
</html>