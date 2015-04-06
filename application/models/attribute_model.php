<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attribute_model extends CI_Model
{
	const TBL_ATTR="ci_attribute";
	const TBL_GA='ci_goods_attr';

	public function insert_attr($data)
	{
		return $this->db->query("insert into ".self::TBL_ATTR." (attr_name,type_id,attr_type,attr_input_type,attr_values,sort_order) values ('".$data['attr_name']."','".$data['type_id']."','".$data['attr_type']."','".$data['attr_input_type']."','".$data['attr_values']."','".$data['sort_order']."')");				
	}

	public function list_attr()
	{
		$query = $this->db->query("select * from ".self::TBL_ATTR);
		return $query->result_array();
	}

	public function get_attr($type_id)
	{
		$query=$this->db->query("select * from ".self::TBL_ATTR." where type_id='".$type_id."'");
		return $query->result_array();
	}

	public function join_attr($type_id)
	{
		$query=$this->db->query("select a.attr_id as attr_id, attr_name, type_id,attr_type,attr_input_type,attr_value,attr_values,goods_id,goods_attr_id,attr_price from ".self::TBL_ATTR." as a left join ".self::TBL_GA." as b on a.attr_id=b.attr_id where type_id='".$type_id."'");
		return $query->result_array();
	}
}