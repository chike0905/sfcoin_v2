<h1>採掘確認</h1>
<?php

echo $this->Session->flash();

echo "USER ID" .$oppoid. "とMiningを行います";

echo $this->Form->create(false,array('type' => 'post','action'=>'./mining'));
echo $this->Form->hidden('oppoid' ,array('value' => $oppoid));
echo $this->Form->end("採掘コード生成");
