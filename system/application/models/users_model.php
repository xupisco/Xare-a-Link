<?php
class users_model extends Model {

	function users_model() {
		parent::Model();
	}
	
   function validateUser($login, $passwd) {
        if(!$login || !$passwd) {
            return false;
        }
        $this->db->select("u.id, u.login, u.email, u.level");
        $this->db->where("u.login", $login);
        $this->db->where("u.passwd", $passwd);
        $query = $this->db->get("users AS u");

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	function register($login, $passwd, $email) {
		$post = array("login" => $login, "passwd" => $passwd, "email" => $email);
		$this->db->insert("users", $post);
		return true;
	}
		
}
?>