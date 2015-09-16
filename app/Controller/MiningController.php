<?php
App::uses('AppController', 'Controller');
class MiningController extends AppController {
  var $uses = array('Wallet', 'User');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->User->find('all',array('conditions' => array('user.id' => 2)));
    $user_id = $userdatas[0]['User']['id'];
    $username = $userdatas[0]['User']['username'];
    //保有coinの取得

    //データ受け渡し
    $this->set("id",$user_id);
    $this->set("username",$username);
  }
}
