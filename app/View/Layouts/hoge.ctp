<!--User認証後用レイアウト-->
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
      echo $this->Html->script('menu');
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
        'class' => 'col-6 left'));?>
      <div class="relative inline-block right">
        <input type="button" value="&#9662;" class="btn btn-primary black bg-white" id="menu">
        <div class="fixed top-0 right-0 bottom-0 left-0" style="display:none;"></div>
        <div class="absolute right-0 nowrap black bg-white rounded  menu" style="display:none;">
          <?php
             echo $this->Html->link(
                    'Wallet',
                    array(
                      'controller' => 'Wallet',
                      'action' => 'index'
                    ),
                    array(
                      'class' => 'btn block'
                    )
                  );
             echo $this->Html->link(
                    'Sent',
                    array(
                      'controller' => 'Sent',
                      'action' => 'index'
                    ),
                    array(
                      'class' => 'btn block'
                    )
                  );
             echo $this->Html->link(
                    'Mining',
                    array(
                      'controller' => 'Mining',
                      'action' => 'index'
                    ),
                    array(
                      'class' => 'btn block'
                    )
                  );
             echo $this->Html->link(
                    'Setting',
                    array(
                      'controller' => 'Setting',
                      'action' => 'index'
                    ),
                    array(
                      'class' => 'btn block'
                    )
                  );
             echo $this->Html->link(
                    'Logout',
                    array(
                      'controller' => 'User',
                      'action' => 'logout'
                    ),
                    array(
                      'class' => 'btn block'
                    )
                  );
          ?>
        </div>
      </div>
      </header>
      <article class="py4">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
      </article>
      <footer>
      </footer>
  </body>
</html>
