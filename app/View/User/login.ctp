<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
<p class="h3">ユーザー名とパスワードを入力してください</p>
<?php
echo $this->Form->input('username',
  array('label' => array(
    'class' => 'h2' // labelタグに付与するclass
  ),
  'class' => 'field mb2 col-12'));
echo $this->Form->input('password',
  array('label' => array(
    'class' => 'h2' // labelタグに付与するclass
  ),
  'class' => 'field mb2 col-12'));
echo $this->Form->submit('Login',array('class' => 'btn btn-primary black bg-white col-6'));
echo $this->Form->end();
?>
