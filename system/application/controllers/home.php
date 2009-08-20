<?php
class Home extends Controller {

	function Home()	{
	    parent::Controller();
	}

	function index() {
	    $this->load->model('xare_model');
	    $this->load->plugin("pagerank");
	    $content = $this->xare_model->getContentByParent(0, 0);
	    
	    $data['page_title'] = "Home";
	    $data['content'] = $content;
	    
		$this->load->view('home/main', $data);
	}

}
?>