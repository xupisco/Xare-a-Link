<?php
class Tools extends Controller {

	function Tools()	{
	    parent::Controller();
	}

	function index() {
	    echo "Ops...";
		exit;
	}
	
	function register() {
		$this->load->model('xare_model');
		
		$root = $this->xare_model->getContentByParent(0, 0);
		
		$data['root'] = $root;
		$data['page_title'] = "Cadastro";
		
		$this->load->view('tools/register', $data);
	}
	
	function register_do() {
		$login = $this->input->post("reg_login");
		$passwd = $this->input->post("reg_passwd");
		$email = $this->input->post("reg_email");
		
		$this->load->model('users_model');
		$this->users_model->register($login, $passwd, $email);
		
		echo "Cadastro OK. Clique em voltar e efetue login com: <b>$login / $passwd</b>.";
	}

}
?>