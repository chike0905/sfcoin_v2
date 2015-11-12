<p class="h3">採掘確認</p>
<p><?php echo $username ?>と採掘を行います</p>
<p>30分以内に採掘を行ってください</p>
<?php
ob_start();
QRCode::png($url, null, 'H', 5, 2);
$img_base64 = base64_encode( ob_get_contents() );
ob_end_clean();
echo $this->Html->div('qrcode', "<img class='col-10' src='" .sprintf('data:image/png;base64,%s', $img_base64). "'/>");

