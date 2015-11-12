<p class="h3">採掘</p>
<?php
  echo $this->Session->flash();
  ?>
  <p class="h4">発行リクエスト作成</p>
  <?php
  echo $this->Form->create(false,array('type' => 'post','action'=>'./post'));
  ?>
  <p class="h4">ログイン名を入力してください</p>
  <?php
  echo $this->Form->text('loginname',array('class' => 'field mb2 col-8 mr1'));
  echo $this->Form->submit('採掘QR生成',array('class' => 'btn btn-primary black bg-white col-6'));
  echo $this->Form->end();
  ?>
  <p class="h4 mt3">発行リクエスト受信</p>
  <p>QRコードを画面幅いっぱいになるよう撮影してください</p>
  <?php
  echo $this->Form->create(false,array('type' => 'file','action'=>'./qrread'));
  ?>
  <input type="file" accept="image/*" name="capture" style="display:none;" id="file">
  <input type="button" onClick="$('#file').click();" value="QR撮影" class='btn btn-primary black bg-white col-4 mb2'>
  <?php
  echo $this->Form->submit('リクエスト受付',array('class' => 'btn btn-primary black bg-white col-6'));
  echo $this->Form->end();
