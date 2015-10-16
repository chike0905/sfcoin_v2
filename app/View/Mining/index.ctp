<h1>採掘</h1>
<?php
  echo $this->Session->flash();

  echo $this->Form->create(false,array('type' => 'post','action'=>'./post'));
  ?>ログイン名を入力してください<?php
  echo $this->Form->text('loginname');
  echo $this->Form->end("採掘コード生成");
?>
