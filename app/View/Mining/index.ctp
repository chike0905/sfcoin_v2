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

?>
  <p class="h4 mt3">位置情報による発行</p>
  <script>
  function successFunc(position){
    var f = document.forms["geo"];
    f.method = "POST"; // method(GET or POST)を設定する
    f.action = "./Mining/geopost";    // action(遷移先URL)を設定する
    $('<input>').attr({
      type: 'hidden',
        id: 'latitude',
        name: 'latitude',
        value: position.coords.latitude
    }).appendTo('#minig_info');
    $('<input>').attr({
      type: 'hidden',
        id: 'longitude',
        name: 'longitude',
        value: position.coords.longitude
    }).appendTo('#minig_info');
    f.submit();        // submit する
  }

  function errorFunc(error){
    // エラーコードのメッセージを定義
    var errorMessage = {
        0: "原因不明のエラーが発生しました" ,
        1: "位置情報の取得が許可されませんでした" ,
        2: "電波状況などで位置情報が取得できませんでした" ,
        3: "位置情報の取得に時間がかかり過ぎてタイムアウトしました" ,
    } ;

    // エラーコードに合わせたエラー内容をアラート表示
    alert( errorMessage[error.code] ) ;

  }
    var optionObj = {
      "enableHighAccuracy": false ,
        "timeout": 8000 ,
        "maximumAge": 5000 ,
    };

  function geopost(){
    if( navigator.geolocation ){
      // 現在位置を取得できる場合の処理
      navigator.geolocation.getCurrentPosition( successFunc , errorFunc , optionObj ) ;
    }else{
      alert( "あなたの端末では位置情報を取得できません、QRを用いて発行してください" ) ;
    }
    return true;
  }
  </script>
  <form method="post" name="geo">
    <div id="mining_info">
      <input type="text" name="data[loginname]" id="loginname" class="field mb2 col-8 mr1">
      <input type="button" class="btn btn-primary black bg-white col-6 center" value="採掘位置情報登録" onclick="geopost()">
    </div>
  </form>
