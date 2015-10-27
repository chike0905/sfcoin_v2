<?php
App::uses('AppController', 'Controller');
class WalletController extends AppController {
  //public $scaffold;
  var $uses = array('Wallet', 'User', 'Sent');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->Auth->user();
    $user_id = $userdatas['id'];
    $username = $userdatas['username'];

    //保有coinの取得
    $datas = $this->Wallet->find('all',array('conditions' => array('Wallet.id' => $user_id)));
    $coin = $datas[0]['Wallet']['coin'];

    //送金履歴の取得
    $todatas = $this->Sent->find('all',array('conditions' => array('Sent.from_id' => $user_id)));
    for($i = 0;$i < count($todatas);$i++ ){
      $touser = $this->User->find('all',array('conditions' => array('User.id' => $todatas[$i]['Sent']['to_id']),
                                              'fields' => array('username'))
                                 );
      $todatas[$i]['Sent']['to_id'] = $touser[0]['User']['username'];
    }

    //着金履歴
    $getdatas = $this->Sent->find('all',array('conditions' => array('Sent.to_id' => $user_id)));
    for($i = 0;$i < count($getdatas);$i++ ){
      $getuser = $this->User->find('all',array('conditions' => array('User.id' => $getdatas[$i]['Sent']['from_id']),
                                              'fields' => array('username'))
                                 );
      $getdatas[$i]['Sent']['to_id'] = $getuser[0]['User']['username'];
    }

    //データ受け渡し
    $this->set("coin",$coin);
    $this->set("id",$user_id);
    $this->set("username",$username);
    $this->set("todata",$todatas);
    $this->set("getdata",$getdatas);
  }
}
