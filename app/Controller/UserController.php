<?php
class UserController extends AppController {
  public $layout = "user";
  public $uses = array('User','Wallet');
  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('login', 'add');
  }
  public function login() {
    if ($this->request->is('post')) {
      if ($this->Auth->login()) {
        $this->redirect($this->Auth->redirect());
      } else {
        $this->Session->setFlash(__('Invalid username or password, try again'));
      }
    }
  }
  public function logout() {
    $this->redirect($this->Auth->logout());
  }
  public function add(){
    //POST送信なら
    if($this->request->is('post')) {
      //パスワードとパスチェックの値をハッシュ値変換
      $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
      $this->request->data['User']['pass_check'] = AuthComponent::password($this->request->data['User']['pass_check']);
      //入力したパスワートとパスワードチェックの値が一致
      if($this->request->data['User']['pass_check'] === $this->request->data['User']['password']){
        //$this->User->create();//ユーザーの作成
        $mse = ($this->User->save($this->request->data))? '新規ユーザーを追加しました' : '登録できませんでした。やり直して下さい';
        $newuserdata = $this->User->find('all',array(
                                          'conditions' =>array(
                                            'User.username' => $this->request->data['User']['username']
                                          )
                                        ));

        $data = array("Wallet" => array("coin" => 0));
        $this->Wallet->save($data);
        $this->Session->setFlash(__($mse),'errorFlash');
      }else{
        $this->Session->setFlash(__('パスワード確認の値が一致しません．','errorFlash'));
      }
      $this->redirect(array('action' => 'login'));//リダイレクト
    }
  }
}
