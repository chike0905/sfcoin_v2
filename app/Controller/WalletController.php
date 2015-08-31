<?php
App::uses('AppController', 'Controller');
class WalletController extends AppController {
  //public $scaffold;
  var $uses = array('Wallet', 'User');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->User->find('all',array('conditions' => array('user.id' => 2)));
    $user_id = $userdatas[0]['User']['id'];
    $username = $userdatas[0]['User']['username'];
    //保有coinの取得
    $datas = $this->Wallet->find('all',array('conditions' => array('Wallet.id' => $user_id)));
    $coin = $datas[0]['Wallet']['coin'];

    //データ受け渡し
    $this->set("coin",$coin);
    $this->set("id",$user_id);
    $this->set("username",$username);
  }
}
