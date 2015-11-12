<p class="h3">採掘</p>
<?php
  echo $this->Session->flash();
  ?>
  <p class="h4">QRによる発行</p>
  <?php
  echo $this->Form->create(false,array('type' => 'post','action'=>'./post'));
  ?>
  <p class="h4">ログイン名を入力してください</p>
  <?php
  echo $this->Form->text('loginname',array('class' => 'field mb2 col-8 mr1'));
  echo $this->Form->submit('採掘QR生成',array('class' => 'btn btn-primary black bg-white col-6'));
  echo $this->Form->end();
