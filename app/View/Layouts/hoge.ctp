<?php
$cakeDescription = __d('cake_dev', 'SFCoin');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo $cakeDescription; ?>
    </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php
      echo $this->Html->meta('icon');
      echo $this->Html->css('basscss');
      echo $this->fetch('meta');
      echo $this->fetch('css');
      echo $this->fetch('script');
    ?>
  </head>
  <body class="white bg-black m2 center">
      <header>
      <?php echo $this->Html->image('rogo.png',array('alt' => 'SFCoin'))?>
      </header>
      <article>
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
      </article>
      <footer>
      </footer>
  </body>
</html>
