<h1>送金</h1>
<?php
  var_dump($friend);
  echo $this->Form->create(false,array('type' => 'post','action'=>'./action'));
  echo $this->Form->input('friend' , array(
    'type' => 'select' ,
    'options' => $friend
    ));
  echo $this->Form->text('text1');
  echo $this->Form->end("送金");
?>
