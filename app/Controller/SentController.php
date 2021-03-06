<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
class SentController extends AppController {
  public $components = array('Session');
  var $uses = array('Wallet', 'User','Sent','Network');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];
    $networkdatas = $this->Network->find('all',array('conditions' =>
      array('OR' => array('Network.usr_id_1' => $user_id,'Network.usr_id_2' => $user_id))
    ));

    //[id][name]の友人情報の二次元配列
    $friend = array();
    foreach($networkdatas as $networkdata){
      if($networkdata['Network']['usr_id_1'] === $user_id){
        $frienddata = $this->User->find('all',array('conditions' => array('User.id' => $networkdata['Network']['usr_id_2'])));
        $friend[$frienddata[0]["User"]["id"]] = $frienddata[0]["User"]["username"];
      }else if($networkdata['Network']['usr_id_2'] === $user_id){
        $frienddata = $this->User->find('all',array('conditions' => array('User.id' => $networkdata['Network']['usr_id_1'])));
        $friend[$frienddata[0]["User"]["id"]] = $frienddata[0]["User"]["username"];
      }
    }
    //データ受け渡し
    $this->set("friend",$friend);
    $this->set("id",$user_id);
    $this->set("username",$username);
  }
  public function action(){
    //user_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];

    if(ctype_digit(strval($this->request->data['text1']))){
    //入力データの取得
      $amount = Sanitize::stripAll(
        $this->request->data['text1']);
      $friend = Sanitize::stripAll(
        $this->request->data['friend']);

      //送金元wallet
      $from_wallet = $this->Wallet->find('all',array('conditions' => array('Wallet.id' => $user_id)));
      $from_coin = $from_wallet[0]["Wallet"]["coin"];
      if($from_coin < $amount){
        $this->Session->setFlash('所有コイン数より多い送金金額です','errorFlash');
        $this->redirect(['controller'=>'Sent','action'=>'index']);
      }
      //送金先wallet
      $to_wallet = $this->Wallet->find('all',array('conditions' => array('Wallet.id' => $friend)));
      $to_coin = $to_wallet[0]["Wallet"]["coin"];

      //送金処理
      $from_coin = $from_coin - $amount;
      $to_coin = $to_coin + $amount;

      $from_data = array("Wallet" =>array('id' => $user_id,'coin' => $from_coin ));
      $fields = array('coin');
      $this->Wallet->save($from_data, false, $fields);

      $to_data = array("Wallet" =>array('id' => $friend,'coin' => $to_coin ));
      $fields = array('coin');
      $this->Wallet->save($to_data, false, $fields);
      //送金データをDBへ挿入
      $sentdata = array("Sent" => array('from_id' => $user_id ,'to_id' => $friend ,'sent' => $amount));
      $fields = array('from_id','to_id','sent');
      $this->Sent->save($sentdata, false, $fields);

      $this->set("amount",$amount);
      $this->set("friend",$friend);
    } else {
      $this->Session->setFlash('入力値が不正です','errorFlash');
      $this->redirect(['controller'=>'Sent','action'=>'index']);
    }
  }
}
