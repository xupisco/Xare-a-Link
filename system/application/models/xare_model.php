<?php
class xaRe_model extends Model {

	function xaRe_model() {
		parent::Model();
	}

	function getContent($content = "", $parents = "") {
		$this->db->select("c.id, c.user_id, parent, name, name_url, short_desc, link, views, clicks, comments, related, pagerank, score, users, c.date_added");
	    if(is_numeric($content)) {
            $this->db->where("c.id = $content");
        } else {
            $this->db->where("name_url = '$content'");
            $this->db->where("parent = '$parents'");
        }
	    $uid = $this->session->userdata("uid");
        if($uid !== false) {
            $this->db->select("ul.id AS ul_id");
            $this->db->join("users_links as ul", "ul.content_id = c.id and ul.user_id = $uid", "left");
        }
        $q = $this->db->get("contents as c");
		return ($q->num_rows() > 0) ? $q->row() : false;
	}

	function getParentIDsFromPath($path = "") {
		if(!strlen($path)) return "0";
		$apath = split("/", $path);
		$step = "";
		$parents = "";
		foreach($apath as $dir) {
			if($dir != "xare" || $dir != "s") {
				$xtra = (!strlen($step)) ? " AND parent = '0'" : " AND parent = '$step'";
				$q = $this->db->query("SELECT id from contents WHERE name_url = '$dir' $xtra");
				$parents .= $q->row()->id.",";
				if(!$step) {
	                $step .= $q->row()->id;
				} else {
					$step = $q->row()->id.",".$step;
				}
			}
		}
		return implode(",", array_reverse(explode(",", substr($parents, 0, -1))));
	}

	// Related:
	//  0 - Qualquer um
	//  1 - Sem sub-itens
	//  2 - Com sub-itens (diretório)

	function getContentByParent($parent = false, $related = 0, $sort = "related, name", $st = "ASC", $sr = 0, $qs = false) {
	    $this->db->select("c.id, name, parent_name, name_url, short_desc, link, views, clicks, comments, related, pagerank, score, users, c.date_added, if(related, concat('0_', name), concat('1_', name)) as tmpsorter");
	    if($related) {
	       $rel = ($related == 1) ? "related = 0" : "related <> 0";
	       $this->db->where($rel);
	    }
	    if($parent !== false) {
		    if(is_numeric($parent)) {
		        $this->db->where("parent = $parent");
		    } else {
		        $this->db->where("name_url = '$parent'");
		    }
	    }
	    if($qs) {
	    	if(substr($qs, 0, 2) != "u:") {
	    		$qs = str_replace("s:", "", $qs);
                $this->db->where("(name like '%$qs%' or short_desc like '%$qs%')");
	    	} else {
	    		$qs = str_replace("u:", "", $qs);
	    		$this->db->where("c.id IN (SELECT content_id from users_links WHERE user_id = $qs)");
	    	}
	    }
        $uid = $this->session->userdata("uid");
	    if($uid !== false) {
            $this->db->select("ul.id AS ul_id");
            $this->db->join("users_links as ul", "ul.content_id = c.id and ul.user_id = $uid", "left");
        }
	    $sort = ($sort == "date") ? "c.date_added" : $sort;
	    $sort = ($sort == "related") ? "tmpsorter" : $sort;
	    $st = ($sort == "tmpsorter") ? "ASC" : $st;
        $this->db->orderby($sort, $st);
        $this->db->limit(25, $sr);
	    $q =  $this->db->get("contents as c");
        return $q;
	}

	function getCommentsByContentID($id) {
		$this->db->select("*");
		$this->db->orderby("date_added DESC");
		$this->db->where("content_id", $id);
		$q = $this->db->get("comments");
		return ($q->num_rows() > 0) ?  $q : false;
	}

	function getRelationFromContentID($id = 0) {
        $parents = $this->db->query("SELECT parent FROM contents WHERE id = $id")->row()->parent;
        $arr = array();
        foreach(array_reverse(explode(",", $parents)) as $parent) {
            $q = $this->db->query("SELECT name, name_url FROM contents WHERE id = $parent");
            if($q->result()) $arr[] = array($q->row()->name, $q->row()->name_url);
        }
		return $arr;
	}

	function updateContentViews($id = 0) {
		$this->db->query("UPDATE contents SET views = views + 1 WHERE id = $id");
		return true;
	}

    function updateContentClicks($id = 0) {
        $this->db->query("UPDATE contents SET clicks = clicks + 1 WHERE id = $id");
        return true;
    }

    function updateContentComments($id = 0) {
        $this->db->query("UPDATE contents SET comments = comments + 1 WHERE id = $id");
        return true;
    }

	function addDir($name, $parent) {
		$post = array(
		    'user_id' => $this->session->userdata('uid'),
            'parent' => $parent,
            'name' => $name,
		    'name_url' => url_title($name),
		    'short_desc' => "",
		    'link' => 0,
		    'views' => 0,
            'clicks' => 0,
            'comments' => 0,
		    'pagerank' => 0,
		    'score' => 0,
		    'related' => 0,
            'date_added' => time()
        );
        $this->db->insert('contents', $post);
        return $this->db->insert_id();
	}

	function addLink($data) {
		$post = array(
            'user_id' => $this->session->userdata('uid'),
            'parent' => $data['parent'],
		    'parent_name' => $data['parent_name'],
            'name' => $data['name'],
            'name_url' => url_title($data['name']),
            'short_desc' => $data['desc'],
            'link' => $data['link'],
            'views' => 0,
            'clicks' => 0,
            'comments' => 0,
            'pagerank' => $data['pagerank'],
            'score' => 0,
            'related' => 0,
            'date_added' => time()
        );
        $this->db->insert('contents', $post);
        return $this->db->insert_id();
	}

	function editLink($data, $id) {
		$post = array(
            'parent' => $data['parent'],
            'parent_name' => $data['parent_name'],
            'name' => $data['name'],
            'name_url' => url_title($data['name']),
            'short_desc' => $data['desc'],
            'link' => $data['link'],
            'pagerank' => $data['pagerank']
        );
        $this->db->where("id", $id);
        $this->db->update('contents', $post);

        $rel_parents = $id.",".$data['parent'];
        $rel_cparents = $id.",".$data['c_parents'];

        if($rel_parents != $rel_cparents) {
            $this->db->query("UPDATE contents set parent = replace(CONCAT(',',parent,','), ',$rel_cparents,', '$rel_parents') where CONCAT(',',parent,',') like '%,$rel_cparents,%'");
        }
        return $id;
	}

	function addComment($data) {
        $post = array(
            'user_id' => $this->session->userdata('uid'),
            'content_id' => $data['cid'],
            'foo_user' => $data['name'].":".$data['email'],
            'comment' => $data['cmm'],
            'date_added' => time()
        );
        $this->db->insert('comments', $post);
        $this->updateContentComments($data['cid']);
        return $this->db->insert_id();
	}

	function addRelation($ids) {
		$this->db->query("UPDATE contents SET related = related + 1 WHERE ID IN ($ids)");
		return true;
	}

	function updateRelation($is, $was) {
		$this->db->query("UPDATE contents SET related = related - 1 WHERE ID IN ($was)");
		$this->db->query("UPDATE contents SET related = related + 1 WHERE ID IN ($is)");
		return true;
	}

	function CR_Comment($data) {
		$this->db->select("id");
		$this->db->where("content_id", $data['cid']);
		$this->db->where("comment", $data['cmm']);
		$q = $this->db->get("comments");
		return ($q->num_rows() > 0) ? false : true;
	}

	function validateURL($url) {
		$url = (strpos($url, "http://") === false) ? "http://".$url : $url;
		$this->db->select("id");
		$this->db->where("link", $url);
		$q = $this->db->get("contents");
		return ($q->num_rows() > 0) ? false : true;
	}

	function verifyFav($id) {
	    $this->db->select("id");
	    $this->db->where("user_id", $this->session->userdata('uid'));
	    $this->db->where("content_id", $id);
	    $q = $this->db->get("users_links");
	    return ($q->num_rows() > 0) ? true : false;
	}

	function userSaveLink($id) {
        if(!$this->verifyFav($id)) {
    	    $post = array(
                'user_id' => $this->session->userdata('uid'),
                'content_id' => $id,
                'date_added' => time()
            );
            $this->db->insert('users_links', $post);
            $ret = $this->db->insert_id();
            $this->db->query("UPDATE contents SET users = users + 1 WHERE id = $id");
            return $ret;
        } else {
            return "1";
        }
	}

	function userRemoveLink($id) {
        $uid = $this->session->userdata("uid");
        $this->db->query("UPDATE contents SET users = users - 1 WHERE id = $id");
   	    $this->db->query("DELETE FROM users_links where content_id = $id AND user_id = $uid");
        return true;
	}
	
	function getUIDByLogin($login) {
		$q = $this->db->query("SELECT id FROM users WHERE login = '$login'");
		return ($q->num_rows() > 0) ? $q->row()->id : false;
	}
	
	function getContentTags($cid) {
		$this->db->select("count(t.tag) as rel, t.tag, t.tag_url");
		$this->db->where("tm.tag_id = t.id AND tm.content_id = $cid");
		$this->db->groupby("t.tag");
		$this->db->orderby("RAND()");
		return $this->db->get("tags AS t, tagmap AS tm")->result();
	}
	
	function getUserTags($uid, $str = 0) {
		$this->db->select("count(t.tag) as rel, t.tag, t.tag_url");
		$this->db->where("tm.tag_id = t.id");
		$this->db->where("tm.user_id = $uid");
		$this->db->groupby("t.tag");
		if($str) $this->db->where("t.tag LIKE '$str%'");
		return $this->db->get("tags AS t, tagmap AS tm")->result();
	}

	function getTagsByContentAndUser($cid = 0, $uid) {
		$this->db->select("t.tag, t.tag_url");
		$this->db->join("tagmap AS tm", "tm.tag_id = t.id", "LEFT");
		$this->db->where("tm.content_id = $cid AND tm.user_id = $uid");
		return $this->db->get("tags AS t")->result();
	}
	
	function checkBaseTags($tags) {
		$tags_id = array();
		foreach($tags as &$val) {
			$val = utf8_decode($val);
			if(strlen($val)) {
				$q = $this->db->query("SELECT id from tags where tag = '$val'");
				if(!$q->num_rows()) {
					$post = array("tag" => $val, "tag_url" => url_title($val));
					$add = $this->db->insert("tags", $post);
					$tags_id[] = $this->db->insert_id();
				} else {
					$tags_id[] = $q->row()->id;
				}
			}
		}
		return $tags_id;
	}
	
	function clearContentTags($cid, $uid) {
		$this->db->where("content_id = $cid AND user_id = $uid");
		$this->db->delete("tagmap");
		return true;
	}
	
	function updateContentTags($cid, $uid, $tags) {
		foreach($tags as $id) {
			$post = array("tag_id" => $id, "content_id" => $cid, "user_id" => $uid);
			$this->db->insert("tagmap", $post);
		}
		return true;
	}
}
?>