<h1>採掘確認</h1>
<?php

echo $this->Session->flash();

echo $this->Form->create(false,array('type' => 'post','action'=>'.'));
?>採掘コードを入力してください<?php
echo $this->Form->text('security');
echo $this->Form->end("採掘コード生成");

echo $username ?>（user id : <?php echo $id ?>）と採掘を行うと<?php echo $amount ?>SFCoin採掘されます。<br/>
<?php
  ob_start();
QRCode::png($url, null, 'H', 5, 2);
$img_base64 = base64_encode( ob_get_contents() );
ob_end_clean();
echo $this->Html->div('qrcode', "<img src='" .sprintf('data:image/png;base64,%s', $img_base64). "'/>");

