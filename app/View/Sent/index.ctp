<p class="h3">送金</p>
<?php
echo $this->Session->flash();

echo $this->Form->create(false,array('type' => 'post','action'=>'./action'));
echo $this->Form->input('friend' , array(
  'label' => array(
    'text' => '送金先の友人を選択してください',
    'class' => 'h4' // labelタグに付与するclass
  ),
  'class' => 'field mb2 col-11',
  'type' => 'select' ,
  'options' => $friend
));
?>
  <span class="h4">送金金額を入力してください</span>
<?php
echo $this->Form->text('text1',array('class' => 'field mb2 col-8 mr1'));
?>
  <span class="col-4">SFCoin</span>
<?php
echo $this->Form->submit('Sent',array('class' => 'btn btn-primary black bg-white col-6'));
echo $this->Form->end();
?>
