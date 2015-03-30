<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attribute_model extends CI_Model
{
	const TBL_ATTR="ci_attribute";

	public function insert_attr($data)
	{
		return $this->db->query("insert into ".self::TBL_ATTR." (attr_name,type_id,attr_type,attr_input_type,attr_value,sort_order) values ('".$data['attr_name']."','".$data['type_id']."','".$data['attr_type']."','".$data['attr_input_type']."','".$data['attr_value']."','".$data['sort_order']."')");				
	}

	public function list_attr()
	{
		$query = $this->db->query("select * from ".self::TBL_ATTR);
		return $query->result_array();
	}
}