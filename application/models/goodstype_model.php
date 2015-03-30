<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goodstype_model extends CI_Model
{
	const TBL_TYPE="ci_goods_type";
	public function list_type()
	{		
		$query = $this->db->query("select * from ".self::TBL_TYPE);
		$types = $query->result_array();
		return $types;		
	}
	public function get_all_type()
	{		
		$query = $this->db->query("select * from ".self::TBL_TYPE);
		$types = $query->result_array();
		return $types;		
	}

	public function select_type($type_id)
	{
		$query = $this->db->query("select * from ".self::TBL_TYPE." where type_id= '".$type_id."'");
		return $query->row_array();
	}
	public function update_type($data)
	{
		return $this->db->query("update ".self::TBL_TYPE." set type_name='".$data['type_name']."' where type_id='".$data['type_id']."'");
	}
	public function insert_type($data)
	{
		return $this->db->query("insert into ".self::TBL_TYPE." (type_name) values ('".$data['type_name']."')");		
	}
	public function delete_type($type_id)
	{
		return $this->db->query("delete from ".self::TBL_TYPE." where type_id = '".$type_id."'");
	}
}