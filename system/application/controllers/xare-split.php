<?php
class Xare extends Controller {

	var $base_dir = "/xare/";

	function Xare()	{
		parent::Controller();
	}

	function parseURL() {
		$url = str_replace($this->base_dir, "", $this->input->server('REQUEST_URI'));
		$url = str_replace("/index.html", "", $url);
		$aurl = split("/", $url);
		$newpath = array();

		$page = "page:1";
		$sort = "sort:name:name";

		foreach($aurl as $dir) {
		    if(strpos($dir, "page") !== false || strpos($dir, "sort") !== false) {
                $page = (strpos($dir, "page") !== false) ? $dir : "page:1";
                $sort = (strpos($dir, "sort") !== false) ? $dir : "sort:name:name";
            } else {
                if(strlen($dir)) $newpath[] = $dir;
            }
        }

        $page = array_pop(explode(":", $page));
        $page = (is_numeric($page)) ? $page : 1;

        $sorta = explode(":", $sort);
        $sort_1 = (isset($sorta[1])) ? $sorta[1] : "name";
        $sort_2 = (isset($sorta[2])) ? $sorta[2] : "name";

		$parsed['current'] = array_pop($newpath);
		$parsed['url'] = $newpath;
		$parsed['path'] = implode("/", $newpath);
		$parsed['page'] = $page;
		$parsed['sort'] = array($sort_1, $sort_2);
		return $parsed;
	}

	function index() {
		$url = $this->parseURL();
		$this->load->model('xare_model');
		$this->load->plugin('pagerank');

		$root = $this->xare_model->getContentByParent(0, 0);

		$st1 = ($url['sort'][0] == "name") ? "ASC" : "DESC";
		$st2 = ($url['sort'][1] == "name") ? "ASC" : "DESC";
        $sr = ($url['page'] * 25) - 25;

		$parent_ids = $this->xare_model->getParentIDsFromPath($url['path']);
		$content = $this->xare_model->getContent($url['current'], $parent_ids);

		if(!$content) {
            show_404();
		}

		$this->xare_model->updateContentViews($content->id);
		$previous = $this->xare_model->getRelationFromContentID($content->id);

    	$related = $this->xare_model->getContentByParent($content->id, 2, $url['sort'][0], $st1, $sr);
    	$links = $this->xare_model->getContentByParent($content->id, 1, $url['sort'][1], $st2, $sr);
        $comments = $this->xare_model->getCommentsByContentID($content->id);

		$data['related'] = $related;
		$data['links'] = $links;

		$path = implode("/", $url['url']);
		$base = ($path) ? $path."/" : "";

		$title = "";
		if(count($previous)) {
			foreach($previous as $row) {
				$title .= $row[0]." > ";
			}
		}

		$data['page'] = $url['page'];
		$data['sort_1'] = $url['sort'][0];
		$data['sort_2'] = $url['sort'][1];

		$data['base'] = $this->base_dir.$base.$url['current'];
		$data['previous'] = $previous;
		$data['page_title'] = $title.$content->name;
		$data['content'] = $content;
		$data['tree'] = $url['url'];
		$data['root'] = $root;
        $data['comments'] = $comments;

		$this->load->view('core/main', $data);
	}

    function search($t = "s") {
        $url = $this->parseURL();
        $this->load->model('xare_model');
        $this->load->plugin('pagerank');
        
        $qs = $t.":".$this->uri->segment(2);
        if($t == "u") {
        	$uid = $this->xare_model->getUIDByLogin($this->uri->segment(2));
        	if($uid) {
        	   $qs = "u:".$uid;
        	} else {
        	   $qs = "s:Usuário não encotrado.";
        	}
        }

        $st1 = ($url['sort'][0] == "name") ? "ASC" : "DESC";
        $st2 = ($url['sort'][1] == "name") ? "ASC" : "DESC";
        $sr = ($url['page'] * 25) - 25;

        $root = $this->xare_model->getContentByParent(0, 0);
        $rss = ($this->uri->segment(3) == "feed") ? true : false;
        
        if(!$rss) {        
	        $related = $this->xare_model->getContentByParent(false, 2, $url['sort'][0], $st1, $sr, $qs);
	        $links = $this->xare_model->getContentByParent(false, 1, $url['sort'][1], $st2, $sr, $qs);

	        $data['page'] = $url['page'];
	        $data['sort_1'] = $url['sort'][0];
	        $data['sort_2'] = $url['sort'][1];
	        $data['qs'] = $this->uri->segment(2);
	        $data['related'] = $related;
	        $data['links'] = $links;
	        $data['root'] = $root;
	        $data['base'] = $this->base_dir.$t."/".$this->uri->segment(2);
	
	        $data['page_title'] = "Resultado da busca por: ".$this->uri->segment(2);
        } else {
        	$data['feed'] = $this->xare_model->getContentByParent(false, 0, $url['sort'][1], $st2, $sr, $qs);
        }

        $rss = ($this->uri->segment(3) == "feed") ? true : false;
        
        $data['rss'] = true;
        $data['feed_name'] = $this->uri->segment(2)."'s links!";
        $data['feed_url'] = $this->base_dir.$t."/".$this->uri->segment(2)."/feed";
        
        if($rss) {
            $this->load->view('core/feed', $data);
            return true;
        }
        $this->load->view('core/search', $data);
    }

    function newLink() {
    	$this->load->model('xare_model');

    	$path = str_replace("rel=", "", $this->uri->segment(2));
    	if($path) {
            $rel = $this->xare_model->getContent($path);
            $rel_p = ($rel->parent != 0) ? $rel->parent.",0" : 0;
            $parents = ($rel->parent != 0) ? $path.",".$rel->parent : $path;
    	} else {
            $rel_p = false;
    	}

    	$tree = ($rel_p !== false) ? array_reverse(explode(",", $rel_p)) : 0;

    	if($rel_p !== false) {
    		for($i = 0; $i < count($tree); $i++) {
    			$cur = "lvl_".$i;
    			$$cur = $this->xare_model->getContentByParent($tree[$i])->result();
    			$data[$cur] = $$cur;
    		}
    	}

    	$next = $this->xare_model->getContentByParent($path);
    	$showb = 0;
    	if($next->num_rows()) {
    		$nd = "lvl_".$i;
    		$data[$nd] = $next->result();
    		array_push($tree, $path);
    		$showb = 1;
    	}

    	$data['rel'] = $path;

    	$data['showb'] = $showb;
    	$data['parents'] = array_reverse(explode(",", $parents));
    	$data['parents_cnt'] = count($tree);
    	$data['page_title'] = "Novo link...";

        $this->load->view('core/newlink', $data);
    }

    function editLink() {
    	$this->load->model('xare_model');

    	$path = str_replace("rel=", "", $this->uri->segment(2));
    	if($path) {
            $rel = $this->xare_model->getContent($path);
            $rel_p = ($rel->parent != 0) ? $rel->parent.",0" : 0;
            $parents = ($rel->parent != 0) ? $path.",".$rel->parent : $path;
    	} else {
            $rel_p = false;
    	}

    	$tree = ($rel_p !== false) ? array_reverse(explode(",", $rel_p)) : 0;
		$content = $this->xare_model->getContent($path);

    	if($rel_p !== false) {
    		for($i = 0; $i < count($tree); $i++) {
    			$cur = "lvl_".$i;
    			$$cur = $this->xare_model->getContentByParent($tree[$i])->result();
    			$data[$cur] = $$cur;
    		}
    	}

    	$next = $this->xare_model->getContentByParent($path);
    	$showb = 0;
    	if($next->num_rows()) {
    		$nd = "lvl_".$i;
    		$data[$nd] = $next->result();
    		array_push($tree, $path);
    		$showb = 1;
    	}

    	$data['rel'] = $path;

        $data['base'] = $this->getPathFromContentID($path);
        $data['content'] = $content;
    	$data['showb'] = $showb;
    	$data['parents'] = array_reverse(explode(",", $parents));
    	$data['parents_cnt'] = count($tree);
    	$data['page_title'] = "Editing link...";

        $this->load->view('core/editlink', $data);
    }

    function postLink() {
    	$this->load->model('xare_model');
        $this->load->plugin('pagerank');
        
        $link_title = $this->input->post("link_title");
        $link_url = $this->input->post("link_url");
        $link_desc = $this->input->post("link_desc");

        if($link_url != "http://" || $link_url != "0") {
            $req = fopen("http://www.gamelib.com.br/pr.php?url=".$link_url, "r");
            $link_pr = stream_get_contents($req);;
        } else {
        	$link_pr = "0";
        }

        $link_url = ($link_url == "http://" || $link_url == "http://0") ? 0 : $link_url;

    	$parents = $this->input->post("h_parents");
    	$parents = explode("|||||", $parents);
        $flow = 0;

       	foreach($parents as $dir) {
    		$p = $dir;

       		if(strpos($dir, "new_") !== false) {
            	$d = str_replace("new_", "", $dir);

            	$new = $this->xare_model->addDir($d, implode(",", array_reverse(explode(",", $flow))));
            	$p = $new; // new_ID
            }

            //echo "insert ".$p." com parent = ".$flow."<br />";
            $flow = (!$flow) ? $p : $flow.",".$p;
    	}

    	// Add link and update relations (ID IN $flow)
    	$data['parent'] = implode(",", array_reverse(explode(",", $flow)));
    	$data['parent_name'] = str_replace(" / ", " > ", $this->input->post("n_parents"));
    	$data['name'] = $link_title;
    	$data['desc'] = $link_desc;
    	$data['link'] = ($link_url != 0 && strpos($link_url, "http://") === false) ? "http://".$link_url : $link_url;
        $data['pagerank'] = $link_pr;

        if(!$this->input->post("h_id")) {
            $link = $this->xare_model->addLink($data);
            $this->xare_model->addRelation($flow);
    	} else {
    	    $data['c_parents'] = $this->input->post("h_cparents");
            $link = $this->xare_model->editLink($data, $this->input->post("h_id"));
            $this->xare_model->updateRelation($flow, $this->input->post("h_cparents"));
        }
        $goto = $this->getPathFromContentID($link);

    	header("Location: $goto");
    	return true;
    }

    function postComment() {
    	$data['cid'] = $this->input->post("cmm_content_id");
    	$data['name'] = $this->input->post("cmm_name");
    	$data['email'] = $this->input->post("cmm_email");
    	$data['cmm'] = $this->input->post("cmm_comment");

    	// Validation
    	$data['name'] = (strlen($data['name'])) ? $data['name'] : "John Doe";
    	$data['email'] = (strlen($data['email'])) ? $data['email'] : "john@fox.net";

    	if($this->input->post("cmm_content_id")) {
    		$this->load->model("xare_model");

    		$proceed = $this->xare_model->CR_Comment($data);
    		if($proceed) $cmm_id = $this->xare_model->addComment($data);

    		$goto = $this->getPathFromContentID($this->input->post("cmm_content_id"));
            header("Location: $goto");
    	}

    }

    function getPathFromContentID($id = 0) {
        $this->load->model("xare_model");
		$rel = $this->xare_model->getRelationFromContentID($id);
		$content = $this->xare_model->getContent($id);
        $path = "";

		foreach($rel as $dir) {
            $path .= $dir[1].'/';
        }
		return $path.$content->name_url;
    }

    function updateRelations() {
    	$c = $this->db->query("select id from contents order by ID ASC")->result();
    	foreach($c as $dir) {
    		$id = $dir->id;
    		$rel = $this->db->query("SELECT count(id) as rel FROM contents c where concat('0,', parent, ',0') like '%,$id,%'")->row()->rel;
    		$u = $this->db->query("SELECT count(id) as users FROM users_links where content_id = $id")->row()->users;
    		$this->db->query("update contents set related = $rel, users = $u where id = $id");
    	}
    	echo "Done...";
    }

    function goToURLByID() {
    	$this->load->model("xare_model");
    	$id = $this->uri->segment(2);
    	$content = $this->xare_model->getContent($id);
    	if($content) {
            $this->xare_model->updateContentClicks($id);
            header("Location: ".$content->link);
    	} else {
    		header("Location: ".base_url());
    	}
    }

    function parseDirByID() {
        $id = $this->uri->segment(2);
        if(!$id) header("Location: ".base_url());
        $content = $this->getPathFromContentID($id);
        
        header("Location: ".base_url().$content);
        return true;
    }

}
?>