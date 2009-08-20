<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session {

    function MY_Session() {
    	parent::CI_Session();
		$this->validateSession();
    }

    function validateSession() {
    	if(!$this->userdata("login")) $this->startNewSession();
    }

    function startNewSession() {
        delete_cookie("dpr_session");
    	$newdata = array(
    				'uid'		=> '0',
                    'login'		=> 'johndoe',
                   	'level'     => 0,
                   	'admin'     => 0,
                   	'logged'	=> 0
               );

		$this->set_userdata($newdata);
    }

    function logUser($uid, $login, $level, $admin) {
        delete_cookie("dpr_session");
    	$newdata = array(
			'uid'		=> $uid,
            'login'		=> $login,
           	'level'     => $level,
           	'admin'     => $admin,
           	'logged'	=> 1
        );
        $this->set_userdata($newdata);
    }

    function logOut() {
    	$this->startNewSession();
    }
}

?>