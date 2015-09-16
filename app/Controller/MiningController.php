<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
class MiningController extends AppController {
  public $components = array('Session');
  var $uses = array('User','Network','Wallet');
  public function index(){
    //user_idとusername取得
    $userdatas = $this->User->find('all',array('conditions' => array('user.id' => 2)));
    $user_id = $userdatas[0]['User']['id'];
    $username = $userdatas[0]['User']['username'];
  }
  public function request(){
    //自身のuser_idとusername取得
    $userdatas = $this->User->find('all',array('conditions' => array('user.id' => 2)));
    $user_id = $userdatas[0]['User']['id'];
    $username = $userdatas[0]['User']['username'];
    if(strval($this->request->data['loginname'])){
      //入力データの取得
      $opponent = Sanitize::stripAll(
        $this->request->data['loginname']);
      $oppo_data = $this->User->find('all',array('conditions' => array('user.username' => $opponent)));
      //ユーザーが存在するかチェック
      if(empty($oppo_data)){
        $this->Session->setFlash('そのログイン名のユーザーはcoinを使用していません');
        $this->redirect(['controller'=>'Mining','action'=>'index']);
      } else {
        $this->set("username",$oppo_data[0]['User']['username']);
        $this->set("id",$oppo_data[0]['User']['id']);
      }
    } else {
      $this->Session->setFlash('入力値が不正です');
      $this->redirect(['controller'=>'Mining','action'=>'index']);
    }
  }
}
