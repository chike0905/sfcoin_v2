<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
          <?php echo __('Please enter your username and password'); ?>
          <?php
               echo $this->Form->input('username');
               echo $this->Form->input('password');
          ?>
<?php echo $this->Form->end(__('Login')); ?>
