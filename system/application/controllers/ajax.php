<?php
class Ajax extends Controller {

	function Ajax()	{
	    parent::Controller();
	}

	function index() {
	    //$this->load->model('xare_model');
	}

	function getDir() {
		$nl = $this->input->post("lvl") + 1;
		$id = $this->input->post("id");

		$this->load->model('xare_model');
		$dir = $this->xare_model->getContentByParent($id);

		if($dir->num_rows()) {

			echo '<div id="ddir_'.$nl.'" style="display: inline;">';
	        echo '<div style="display: inline;">';
	        echo '<select name="dir_'.$nl.'" id="dir_'.$nl.'" onchange="parseDir(this)">';
	        echo '<option value="0">Selecione</option>';
	        echo '<option value="0" style="color: #dddddd">---------------</option>';

	        foreach($dir->result() as $row) {
	            echo "<option value='".$row->id."'$sel>".utf8_encode($row->name)."</option>";
	        }

	        echo '<option value="0" style="color: #dddddd">---------------</option>';
	        echo '<option value="nd_'.$nl.'" style="color: #ee2222"> > Incluir grupo aqui</option>';
	        echo '</select>';
	        echo '</div>';

	        echo '<div style="display: inline; vertical-align: middle; padding: 0px 3px" id="remove_'.$nl.'"><a href="javascript:removeDir('.$nl.');"><img src="images/icons/dir_remove.gif" border="0" title="Remover este sub-grupo" /></a></div>';
	        echo '<div id="sep" style="display: inline; padding: 0px 2px"><span class="nav_sep">&raquo;</span></div>';
	        echo '</div>';

		} else {
			echo "NONE";
		}
		return true;
	}

	function getPR() {
		//$this->load->plugin('pagerank');
		//echo "<b>".getPR($this->input->post("url"))."/10</b>";

		$url = $this->input->post("url");
		$req = fopen("http://www.gamelib.com.br/pr.php?url=".$url, "r");
		$pr = stream_get_contents($req);
		$pr = (strlen($pr)) ? $pr : "0";
		echo "<b>$pr/10</b>";
		return true;
	}

	function validateURL() {
	    $url = $this->input->post("url");
	    $this->load->model('xare_model');
	    $check = $this->xare_model->validateURL($url);
	    echo ($check) ? "OK" : "FOUND";
	    exit;
	}

	function userSaveLink() {
	    $id = $this->input->post("id");
	    $this->load->model('xare_model');

        $save = $this->xare_model->userSaveLink($id);
        echo ($save) ? "OK" : "ERROR";
        return true;
	}

	function userRemoveLink() {
	    $id = $this->input->post("id");
	    $this->load->model('xare_model');

        $this->xare_model->userRemoveLink($id);
        echo "OK";
        return true;
	}

    function userValidate() {
        // Obrigatório o envio do Content-type para chamadas AJAX!
        header("HTTP/1.0 200 OK");
        header('Content-type: text/html; charset=iso-8859-1');
        $this->load->model("users_model");
        if($uinfo = $this->users_model->validateUser(
                            $this->input->post('login'),
                            $this->input->post('passwd'))) {
            $is_adm = ($uinfo->level >= 5) ? 1 : 0;
            $this->session->logUser($uinfo->id,
                                    $uinfo->login,
                                    $uinfo->level,
                                    $is_adm);
            echo "LOGIN_SUCESS";
        } else {
            echo "LOGIN_FAILED";
        }
    }
	
	function userLogOut() {
        $this->session->logOut();
	}
	
	function getMeta() {
		$url = $this->input->post("url");
		$page_title = "error";
		$meta_descr = "error";
		
		if($handle = @fopen($url, "r")) {
		    $content = "";
		    while (!feof($handle)) {
		        $part = fread($handle, 1024);
		        $content .= $part;
		        if (eregi("</head>", $part)) break;
		    }
		    fclose($handle);
		    $lines = preg_split("/\r?\n|\r/", $content); // turn the content in rows
		    $is_title = false;
		    $is_descr = false;
			$xhtml = false;
			
		    $close_tag = ($xhtml) ? " />" : ">"; // new in ver. 1.01
		    foreach ($lines as $val) {
		        if(eregi("<title>(.*)</title>", $val, $title)) {
		            $page_title = $title[1];
		            $is_title = true;
		        }
		        if(eregi("<meta name=\"description\" content=\"(.*)\"([[:space:]]?/)?>", $val, $descr)) {
		            $meta_descr = $descr[1];
		            $is_descr = true;
		        }
		        if($is_title && $is_descr) break;
		    }
		}
		echo utf8_encode($page_title)."|||||".utf8_encode($meta_descr);
		return true;
	}
	
	function loginValidate() {
		$this->load->model("users_model");
		$login = $this->input->post('reg_login');
		echo "0";
		return true;
	}
	
	function getCurrentTags() {
		header("HTTP/1.0 200 OK");
        header('Content-type: text/html; charset=iso-8859-1');
		$this->load->model("xare_model");
		$cid = $this->input->post("cid");
		$uid = $this->session->userdata("uid");
		$tags = $this->xare_model->getTagsByContentAndUser($cid, $uid);
		foreach($tags as $row) {
			echo $row->tag." ";
		}
	}
	
	function getUserTags() {
		header("HTTP/1.0 200 OK");
        header('Content-type: text/html; charset=iso-8859-1');
		$this->load->model("xare_model");
		$uid = $this->session->userdata("uid");
		$str = $this->input->post("tag");
		$tags = $this->xare_model->getUserTags($uid, $str);
		$ret = array();
		$str = "";
		
		foreach($tags as $row) {
			$ret[] = utf8_encode($row->tag);
			$str .= "\"".$row->tag."\",";
		}
		//echo json_encode($ret);
		echo "[".substr($str, 0, -1)."]";
	}
	
	function saveTags(){
		$this->load->model("xare_model");
		$cid = $this->input->post("cid");
		$tags = explode(" ", $this->input->post("tags"));
		$uid = $this->session->userdata("uid");

		$this->xare_model->clearContentTags($cid, $uid);
		$ids = $this->xare_model->checkBaseTags($tags);
		$this->xare_model->updateContentTags($cid, $uid, $ids);
		echo "done";
	}
}
?>