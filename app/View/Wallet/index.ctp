<p class="h3">財布</p>
<p>こんにちは<?php echo $username; ?>！</p>
<div>所有コイン数</div>
<div class="bg-lighten-3 rounded mb2">
  <div><span class="h3 mr1"><?php echo $coin; ?></span>SFCoin</div>
</div>
<div class="mb2">
<table>
  <caption>送金履歴</caption>
  <thead class="bg-lighten-3">
    <tr>
      <th class="col-5 center">日付</th>
      <th class="col-3 center">相手</th>
      <th class="col-4 center">金額</th>
    </tr>
  </thead>
  <tbody class="bg-lighten-2">
<?php
if(empty($todata)){
  echo '<td class="center" colspan="3">送金履歴がありません</td>';
}else{
  for($i = 0 ;$i < count($todata);$i++){
    $data = array($todata[$i]["Sent"]["date"],$todata[$i]["Sent"]["to_id"],$todata[$i]["Sent"]["sent"]);
    echo $this->Html->tableCells($data);
  }
}
?>
  </tbody>
</table>
</div>
<div class="mb2">
<table>
  <caption>着金履歴</caption>
  <thead class="bg-lighten-3">
    <tr>
      <th class="col-5 center">日付</th>
      <th class="col-3 center">相手</th>
      <th class="col-4 center">金額</th>
    </tr>
  </thead>
  <tbody class="bg-lighten-2">
<?php
if(empty($getdata)){
  echo '<td class="center" colspan="3">着金履歴がありません</td>';
}else{
  for($i = 0 ;$i < count($getdata);$i++){
    $data = array($getdata[$i]["Sent"]["date"],$getdata[$i]["Sent"]["from_id"],$getdata[$i]["Sent"]["sent"]);
    echo $this->Html->tableCells($data);
  }
}
    ?>
  </tbody>
</table>
</div>
