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
      <a class="btn" href="/"><i class="icon-home icon-fixed-width"></i><span>Home</span></a>
      <i class="btn icon-folder-close-alt icon-fixed-width">
        <section id="documents">
          <ul>
          <?php foreach($documents as $document): ?>
            <li><a href="<?php echo $document ?>"><?php echo $document; ?></a></li>
          <?php endforeach; ?>
          </ul>
        </section>
      </i><div style="clear:both"></div>
      <i class="btn icon-reorder icon-fixed-width">
        <section id="toc">
          <?php echo $toc ?>
        </section>
      </i>
      <a class="btn" href="<?php echo $_SERVER['REQUEST_URI'].'?source' ?>"><i class="icon-file-text-alt icon-fixed-width"></i><span>Markdown</span></a>
    </nav>
    <section class="page">
      <article>
        <?php echo $body ?>
      </article>
    </section>
  </body>
</html>