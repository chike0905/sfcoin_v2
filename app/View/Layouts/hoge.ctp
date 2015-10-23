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
      echo $this->Html->script('jquery');
      echo $this->Html->meta('icon');
      echo $this->Html->css('basscss');
      echo $this->fetch('meta');
      echo $this->fetch('css');
      echo $this->fetch('script');
    ?>
  </head>
  <body class="white bg-black m2 center">
      <header class="mb2">
      <?php
      echo $this->Html->image('rogo.png',array(
        'alt' => 'SFCoin',
        'class' => 'col-5 left'));?>
      <div class="relative inline-block right" data-disclosure>
        <button type="button" class="btn btn-primary black bg-white">
          &#9662;
        </button>
        <div data-details class="fixed top-0 right-0 bottom-0 left-0" id="menu"></div>
        <div data-details class="absolute right-0 nowrap black bg-white rounded" id="menu">
          <a href="#!" class="btn block">Sent</a>
          <a href="#!" class="btn block">Mining</a>
          <a href="#!" class="btn block">Setting</a>
          <a href="#!" class="btn block">Logout</a>
        </div>
      </div>
      </header>
      <article>
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
      </article>
      <footer>
      </footer>
  </body>
</html>
