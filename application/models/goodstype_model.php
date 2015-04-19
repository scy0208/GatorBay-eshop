<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goodstype_model extends CI_Model
{
	const TBL_TYPE="ci_goods_type";
	public function list_type()
	{		
		$query = $this->db->query("select * from ".self::TBL_TYPE);
		$types = $query->result_array();
		return $this->change_array($types);		
	}
	public function get_all_type()
	{		
		$query = $this->db->query("select * from ".self::TBL_TYPE);
		$types = $query->result_array();
		return $this->change_array($types);		
	}

	public function select_type($type_id)
	{
		$query = $this->db->query("select * from ".self::TBL_TYPE." where type_id= '".$type_id."'");
		$result=$query->row_array();
		return $this->change_row($result);
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

	public function change_array($inputs)
	{
		if(!empty($inputs))	
		{
			foreach($inputs as $index=>$input):
				foreach($input as $key=>$value):
					$new_key=strtolower($key);
					$output[$new_key]=$value;
				endforeach;
			$outputs[]=$output;
			endforeach;
			return $outputs;
		}
		else
		{return $inputs;}	
		
	}
	
	public function change_row($input)
	{
		if(!empty($input))	
		{
			foreach($input as $key=>$value):
					$new_key=strtolower($key);
					$output[$new_key]=$value;
			endforeach;
			return $output;
		}
		else
		{
			return $input;
		}
	}
}