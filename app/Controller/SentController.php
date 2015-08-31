<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
class SentController extends AppController {
  var $uses = array('Wallet', 'User','Sent','Network');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->User->find('all',array('conditions' => array('user.id' => 2)));
    $user_id = $userdatas[0]['User']['id'];
    $username = $userdatas[0]['User']['username'];

    $networkdatas = $this->Network->find('all',array('conditions' =>
      array('OR' => array('network.usr_id_1' => $user_id,'network.usr_id_2' => $user_id))
    ));

    //[id][name]の友人情報の二次元配列
    $friend = array();
    foreach($networkdatas as $networkdata){
      if($networkdata['Network']['usr_id_1'] === $user_id){
        $frienddata = $this->User->find('all',array('conditions' => array('user.id' => $networkdata['Network']['usr_id_2'])));
        $friend[$frienddata[0]["User"]["id"]] = $frienddata[0]["User"]["username"];
      }else if($networkdata['Network']['usr_id_2'] === $user_id){
        $frienddata = $this->User->find('all',array('conditions' => array('user.id' => $networkdata['Network']['usr_id_1'])));
        $friend[$frienddata[0]["User"]["id"]] = $frienddata[0]["User"]["username"];
      }
    }
    //データ受け渡し
    $this->set("friend",$friend);
    $this->set("id",$user_id);
    $this->set("username",$username);
  }
  public function action(){
    //入力データの取得
    if($this->request->data){
      $amount = Sanitize::stripAll(
        $this->request->data['text1']);
      $friend = Sanitize::stripAll(
        $this->request->data['friend']);
    } else {
      $result = "no data.";
    }
    $this->set("amount",$amount);
    $this->set("friend",$friend);

  }
}
