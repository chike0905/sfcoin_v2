<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::import('Vendor', 'phpqrcode/qrlib');
class MiningController extends AppController {
  public $components = array('Session');
  var $uses = array('User','Network','Wallet','Mining');
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
      $opponent = Sanitize::stripAll(
        $this->request->data['loginname']);
      $oppo_data = $this->User->find('all',array('conditions' => array('user.username' => $opponent)));
      //ユーザーが存在するかチェック
      if(empty($oppo_data)){
        $this->Session->setFlash('入力値が不正です');
        $this->redirect(['controller'=>'mining','action'=>'index']);
      } else {
        //ユーザー名とtimestampを結合してハッシュ化し、mining codeとする
        $mining_code = $oppo_data[0]['User']['username'].time();
        $mining_code = Security::hash($mining_code, 'sha1', true);
        $url = "http://localhost/sfcoin_v2/mining/request?code=".urlencode ($mining_code);

        //miningdataをDBへ保存
        $miningdata = array("Mining" =>array(
          'authcode' => $mining_code,
          'myid' => $user_id ,
          'oppoid' => $oppo_data[0]['User']['id'],
          'date' =>date("Y-m-d H:i:s")
        ));
        $fields = array('authcode','myid','oppoid','date');
        $this->Mining->save($miningdata, false, $fields);

        $this->set("url",$url);
        $this->set("username",$oppo_data[0]['User']['username']);
        $this->set("id",$oppo_data[0]['User']['id']);
      }
    }
  }

  public function request(){
    //自身のuser_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];

    //miningcodeを受け取りminingdataの取り出し、照合
    $mining_code = $this->request->query('code');
    $mining_data = $this->Mining->find('all',array('conditions' => array('mining.authcode' => $mining_code)));
    //照合
    if($user_id == $mining_data[0]["Mining"]["oppoid"]){
      //miningcode30分以内に実行させたか判定
      $now = date("Y-m-d H:i:s",strtotime("- 30 minute"));
      if(strtotime($now) > strtotime($mining_data[0]["Mining"]["date"])){
        $this->Session->setFlash('Mining Codeの生成から30分以内に採掘を行ってください');
        $this->redirect(['controller'=>'mining','action'=>'index']);
      } else {
        $oppoid = $mining_data[0]["Mining"]["myid"];
        $this->set("oppoid",$oppoid);
      }
    } else {
        $this->Session->setFlash('Mining Codeが不正です');
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
      $opponent = Sanitize::stripAll(
        $this->request->data['oppoid']);
      $oppo_data = $this->User->find('all',array('conditions' => array('user.id' => $opponent)));
      //user table行数（user 数）の取得
      $user_num = $this->User->find('count');

      for($i = 1; $i <= $user_num; $i++){
        //友人リストの取得
        $friend_lists = $this->Network->find('all',array(
          'fields' => array('network.usr_id_1','network.usr_id_2','network.cost'),
          'conditions' => array(
            'OR' => array('network.usr_id_1' => $i,
            'network.usr_id_2' => $i
          )
        )
      ));
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
      $distance = $this->_dijkstra($link,$user_id,$oppo_data[0]['User']['id']);

      //発行量の調節
      $mining_basic = 10;
      $mining_amount = $mining_basic * $distance;


      $this->set("amount",$mining_amount);
      $this->set("username",$oppo_data[0]['User']['username']);
      $this->set("id",$oppo_data[0]['User']['id']);
    }
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
    //echo implode(' -> ', array_reverse($route)) . "\n";
    return $distance[$goal];
  }
}
