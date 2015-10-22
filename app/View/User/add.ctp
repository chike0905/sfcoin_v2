<p class="h2">新規ユーザー登録</p>
<?php
echo $this->Session->flash('Auth');
echo $this->Form->create('User', array('url' => 'add'));
echo $this->Form->input('User.username',array('label' => array(
  'text' => 'Username',
  'class' => 'h2' // labelタグに付与するclass
),
'class' => 'field mb2 col-12'));
echo $this->Form->input('User.password',array('label' => array(
  'text' => 'Password',
  'class' => 'h2' // labelタグに付与するclass
),
'class' => 'field mb2 col-12'));
echo $this->Form->input('User.pass_check',array('label' => array(
  'text' => 'one more type password',
  'class' => 'h2' // labelタグに付与するclass
),
'class' => 'field mb2 col-12',
'type' => 'password'
));
echo $this->Form->submit('Create New User',array('class' => 'btn btn-primary black bg-white col-8'));
echo $this->Form->end();
?>
