  <h1>新規ユーザー登録</h1>
  <?php echo $this->Session->flash('Auth'); ?>
  <?php echo $this->Form->create('User', array('url' => 'add')); ?>
  <?php echo $this->Form->input('User.username',array('label'=>'ユーザ名')); ?>
  <?php echo $this->Form->input('User.password',array('label'=>'パスワード')); ?>
  <?php echo $this->Form->input('User.pass_check',array('label'=>'パスワード確認','type'=>"password")); ?>
  <?php echo $this->Form->end('新規ユーザを作成する'); ?>
