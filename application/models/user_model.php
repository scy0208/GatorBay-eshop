<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function check_identity($usertype,$email,$password)
	{ 
		$query = $this->db->query("select * from $usertype where email = "."'".$email ."'"."and password = "."'".$password."'");
		return $query->num_rows() > 0 ? true : false;
	}

	public function insert_user($usertype,$email,$password)
	{
		return $this->db->query("insert into $usertype (email, password) values ('".$email."', '".$password."')");
	}
}