<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::import('Vendor', 'phpqrcode/qrlib');
App::import('Vendor','qrdecoder/QrReader');
class MiningController extends AppController {
  public $components = array('Session');
  public $uses = array('User','Network','Wallet','Mining');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];
  }

  public function post(){
    //自身のuser_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];
    if(isset($this->request->data['loginname'])){
      //入力データの取得
      $opponent = Sanitize::stripAll($this->request->data['loginname']);
      $oppo_data = $this->User->find('all',array('conditions' => array('User.username' => $opponent)));
      //ユーザーが存在するかチェック
      if(empty($oppo_data) || $opponent == $username){
        $this->Session->setFlash('入力値が不正です','errorFlash');
        $this->redirect(['controller'=>'mining','action'=>'index']);
      } else {
        $lastmining = $this->Mining->find('first',array(
          'order' => array('Mining.date DESC'),
          'conditions' => array(
            'AND' => array(
              'OR' => array(
                array(
                  'Mining.myid' => $user_id,
                  'Mining.oppoid' => $oppo_data[0]['User']['id']
                ),
                array(
                  'Mining.myid' => $user_id,
                  'Mining.oppoid' => $oppo_data[0]['User']['id']
                )
              ),
              'Mining.active' => 0
            )
          )));
        var_dump($lastmining);


        //ユーザー名とtimestampを結合してハッシュ化し、mining codeとする
        $mining_code = $oppo_data[0]['User']['username'].time();
        $mining_code = Security::hash($mining_code, 'sha1', true);
        $url = $mining_code;

        $mining = $this->_calucurate($opponent,$user_id);
        //miningdataをDBへ保存
        $miningdata = array("Mining" =>array(
          'authcode' => $mining_code,
          'myid' => $user_id ,
          'oppoid' => $oppo_data[0]['User']['id'],
          'date' =>date("Y-m-d H:i:s"),
          'distance' => $mining["distance"],
          'amount' => $mining["amount"]
        ));

        $fields = array('authcode','myid','oppoid','date','distance','amount');
        $this->Mining->save($miningdata, false, $fields);

        $this->set("amount",$mining["amount"]);
        $this->set("url",$url);
        $this->set("username",$oppo_data[0]['User']['username']);
        $this->set("id",$oppo_data[0]['User']['id']);
      }
    }
  }


  public function qrread(){
    $type = exif_imagetype($_FILES["capture"]["tmp_name"]);
    switch ($type) {
    case 1:
       $file = imagecreatefromgif($_FILES["capture"]["tmp_name"]);
        break;
    case 2:
       $file = imagecreatefromjpeg($_FILES["capture"]["tmp_name"]);
        break;
    case 3:
       $file = imagecreatefrompng($_FILES["capture"]["tmp_name"]);
        break;
    default:
       $file = false;
    }
    //画像でなければ弾く
    $file2 = $file;
    if($file2 =! false){
      $width = ImageSx($file); // 画像の幅を取得
      $height = ImageSy($file); // 画像の高さを取得
      $min_width = 600; // 幅の最低サイズ
      $min_height = 600; // 高さの最低サイズ

      if($width >= $min_width|$height >= $min_height){
        if($width == $height) {
          $new_width = $min_width;
          $new_height = $min_height;
        } else if($width > $height) {//横長の場合
          $new_width = $min_width;
          $new_height = $height*($min_width/$width);
        } else if($width < $height) {//縦長の場合
          $new_width = $width*($min_height/$height);
          $new_height = $min_height;
        }
        //　画像生成
        $out = ImageCreateTrueColor($new_width , $new_height);
        ImageCopyResampled($out, $file,0,0,0,0, $new_width, $new_height, $width, $height);
      }
      switch ($type) {
      case 1:
        $new_file = "./resized.gif";
        ImageGIF($out, $new_file);
        break;
      case 2:
        $new_file = "./resized.jpeg";
        ImageJPEG($out, $new_file);
        break;
      case 3:
        $new_file = "./resized.png";
        ImagePNG($out, $new_file);
        break;
      default:
      }
      $qrcode = new QrReader($new_file);
      $text = $qrcode->text();
      unlink($_FILES["capture"]["tmp_name"]);
      unlink($new_file);
      if($text == false){
        $this->Session->setFlash('QRコードが読めません','errorFlash');
        $this->redirect(['controller'=>'mining','action'=>'index']);
      }else{
        $mining_data = $this->Mining->find('all',array('conditions' => array('Mining.authcode' => $text)));
        if(empty($mining_data)){
          $this->Session->setFlash('QRデータが不正です','errorFlash');
          $this->redirect(['controller'=>'mining','action'=>'index']);
        }else{
          $this->redirect(['controller'=>'mining','action'=>'request','?'=>array('code' => $text)]);
        }
      }
    } else {
      $this->Session->setFlash('画像データが不正です','errorFlash');
      $this->redirect(['controller'=>'mining','action'=>'index']);
    }
  }

  public function request(){
    //自身のuser_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];

    //miningcodeを受け取りminingdataの取り出し、照合
    $mining_code = $this->request->query('code');
    $mining_data = $this->Mining->find('all',array('conditions' => array('Mining.authcode' => $mining_code)));
    //照合
    if($user_id == $mining_data[0]["Mining"]["oppoid"]){
      //miningcode30分以内に実行させたか判定
      $now = date("Y-m-d H:i:s",strtotime("- 30 minute"));
      if(strtotime($now) > strtotime($mining_data[0]["Mining"]["date"])){
        $this->Session->setFlash('Mining Codeの生成から30分以内に採掘を行ってください','errorFlash');
        $this->redirect(['controller'=>'mining','action'=>'index']);
      } else {
        if($mining_data[0]["Mining"]["active"] == 0){
          $this->Session->setFlash('このMining Codeでの採掘はすでに行われています','errorFlash');
          $this->redirect(['controller'=>'mining','action'=>'index']);
        }else{
          $oppoid = $mining_data[0]["Mining"]["myid"];
          $oppodata = $this->User->find('all',array('conditions' => array('User.id' => $oppoid)));
          $opponame = $oppodata[0]['User']['username'];

          $this->set("code",$mining_code);
          $this->set("opponame",$opponame);
          $this->set("oppoid",$oppoid);
        }
      }
    } else {
        $this->Session->setFlash('Mining Codeが不正です','errorFlash');
        $this->redirect(['controller'=>'mining','action'=>'index']);
    }
  }

  public function mining(){
    //自身のuser_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];
    if(isset($this->request->data['oppoid'])){
      //入力データの取得
      $opponent = Sanitize::stripAll($this->request->data['oppoid']);
      $mining_code = Sanitize::stripAll($this->request->data['code']);
      $miningdata = $this->Mining->find('all',array('fields' =>array('id','amount','distance','myid','oppoid'),
                                                    'conditions' => array('Mining.authcode' => $mining_code)));
      $mining_id = $miningdata[0]['Mining']['id'];
      $mining_amount = $miningdata[0]['Mining']['amount'];

      $oppo_data = $this->User->find('all',array('conditions' => array('user.id' => $opponent)));

      //miningをDBへ保存
      $oppocoin = $this->Wallet->find('all',array('fields' =>array('coin') ,'conditions' => array('Wallet.id' => $opponent)));
      $oppocoin = $oppocoin[0]["Wallet"]["coin"] + $mining_amount;

      $mining = array("Wallet" =>array('id' => $opponent,'coin' => $oppocoin));
      $fields = array('coin');
      $this->Wallet->save($mining, false, $fields);

      $mycoin = $this->Wallet->find('all',array('fields' =>array('coin') ,'conditions' => array('Wallet.id' => $user_id)));
      $mycoin = $mycoin[0]["Wallet"]["coin"] + $mining_amount;

      $mining = array("Wallet" =>array('id' => $user_id,'coin' => $mycoin));
      $fields = array('coin');
      $this->Wallet->save($mining, false, $fields);

      //マイニングコードの無効化
      $miningactiv = array("Mining" =>array('id' => $mining_id,'active' => false));
      $fields = array('active');
      $this->Mining->save($miningactiv, false, $fields);
      //距離の変更
      if($miningdata[0]['Mining']['distance'] > 10){
        $network = $this->Network->find('all',array('fields' =>array('id'),
          'conditions' => array(
            'OR' => array(
              array(
                'Network.usr_id_1' => $miningdata[0]['Mining']['myid'],
                'Network.usr_id_2' => $miningdata[0]['Mining']['oppoid']
              ),
              array(
                'Network.usr_id_1' => $miningdata[0]['Mining']['oppoid'],
                'Network.usr_id_2' => $miningdata[0]['Mining']['myid']
              )
            )
          )));
        if(empty($network)){
          $data = array("Network" => array(
            'usr_id_1' => $miningdata[0]['Mining']['myid'],
            'usr_id_2' => $miningdata[0]['Mining']['oppoid'],
            'cost' => $miningdata[0]['Mining']['distance']
          ));
          $fields = array('usr_id_1','usr_id_2','cost');
        }else{
          $new_distance = $miningdata[0]['Mining']['distance'] / 2;
          $data = array("Network" => array(
            'id' => $network[0]['Network']['id'],
            'cost' => $new_distance
          ));
          $fields = array('id','cost');
        }
        $this->Network->save($data, false, $fields);
      }

      $this->set("amount",$mining_amount);
      $this->set("username",$oppo_data[0]['User']['username']);
      $this->set("id",$oppo_data[0]['User']['id']);
    }
  }

  public function _calucurate($opponent,$user_id){
    //oppodataの取得
    $oppo_data = $this->User->find('all',array('conditions' => array('User.username' => $opponent)));
    //user table行数（user 数）の取得
    $user_num = $this->User->find('count');
    for($i = 1; $i <= $user_num; $i++){
      //友人リストの取得
      $friend_lists = $this->Network->find('all',array(
        'fields' => array('Network.usr_id_1','Network.usr_id_2','Network.cost'),
        'conditions' => array(
          'OR' => array('Network.usr_id_1' => $i,
          'Network.usr_id_2' => $i
        )
      )));
      //ネットワーク行列を生成
      foreach($friend_lists as $list){
        if($list["Network"]["usr_id_1"] == $i){
          $link[$i][$list["Network"]["usr_id_2"]] = $list["Network"]["cost"];
          $link[$list["Network"]["usr_id_2"]][$i] = $list["Network"]["cost"];
        } else if($list["Network"]["usr_id_2"] == $i){
          $link[$i][$list["Network"]["usr_id_1"]] = $list["Network"]["cost"];
          $link[$list["Network"]["usr_id_1"]][$i] = $list["Network"]["cost"];
        }
      }
    }
    //自分または相手がネットワークに所属しているかどうか
    if(empty($link[$oppo_data[0]['User']['id']]) || empty($link[$user_id])){
      $distance = 100;
    } else {
      $distance = $this->_dijkstra($link,$user_id,$oppo_data[0]['User']['id']);
    }
    //発行量の調節
    $mining_basic = 10;
    $mining_amount = $mining_basic * $distance;

    $return = array("distance"=>$distance,"amount"=>$mining_amount);
    return $return;
  }
  //引数
  //$graph:$array["自分"]["友人"] = costの二次元配列
  //$start:開始点の"自分"
  //$goal:終了点の"友人"
  //
  //返値
  //開始点から終了点の距離
  public function _dijkstra($graph, $start, $goal) {
    $distance = array($start => 0);
    $visit = array($start);
    $predecessor = array();
    foreach( $graph as $node => $edge ) {
      $distance[$node] = pow(10, 10);
      $predecessor[$node] = $start;
    }
    foreach( $graph[$start] as $next => $cost ) {
      $distance[$next] = $cost;
    }
    while( !in_array($goal, $visit) ) {
      $current = null;
      foreach( array_diff(array_keys($graph), $visit) as $unvisited ) {
        if(!$current || $distance[$current] > $distance[$unvisited])
          $current = $unvisited;
      }
      $visit[] = $current;
      foreach( $graph[$current] as $next => $cost ) {
        if( $distance[$current] + $cost < $distance[$next] ) {
          $distance[$next] = $distance[$current] + $cost;
          $predecessor[$next] = $current;
        }
      }
    }
    $route = array($goal);
    while( !($start == $route[count($route) - 1]) ) {
      $route[] = $predecessor[$route[count($route) - 1]];
    }
    return $distance[$goal];
  }
}
