<h1>採掘確認</h1>
<?php echo $username ?>（user id : <?php echo $id ?>）と採掘を行います<br/>
URL: <?php echo $url ?>
<?php
ob_start();
QRCode::png($url, null, 'H', 5, 2);
$img_base64 = base64_encode( ob_get_contents() );
ob_end_clean();
echo $this->Html->div('qrcode', "<img src='" .sprintf('data:image/png;base64,%s', $img_base64). "'/>");

