<p class="h3">採掘確認</p>
<?php
echo $this->Session->flash();
?>
<p class="h4">USER ID<?php echo $oppoid; ?>とMiningを行います</p>
<?php
echo $this->Form->create(false,array('type' => 'post','action'=>'./mining'));
echo $this->Form->hidden('oppoid' ,array('value' => $oppoid));
echo $this->Form->hidden('code' ,array('value' => $code));

echo $this->Form->submit('採掘実行',array('class' => 'btn btn-primary black bg-white col-6'));
echo $this->Form->end();
