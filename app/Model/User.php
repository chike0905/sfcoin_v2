<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

/*  public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
      $passwordHasher = new SimplePasswordHasher();
      $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
    }
    return true;
  }*/
}
