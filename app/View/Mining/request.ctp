<h1>採掘確認</h1>
<?php

echo $this->Session->flash();

echo $this->Form->create(false,array('type' => 'post','action'=>'./mining'));
?>採掘コードを入力してください<?php
echo $this->Form->text('security');
echo $this->Form->end("採掘コード生成");
echo $s_oppo;
