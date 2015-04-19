<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand_model extends CI_Model
{
	const TBL_BRAND = "ci_brand";

	public function list_brand()
	{
		$query = $this->db->query("select * from ".self::TBL_BRAND);
		$brands = $query->result_array();
		return $this->change_array($brands);
	}

	public function insert_brand($data)
	{
		return $this->db->query("insert into ".self::TBL_BRAND." (brand_name,logo,brand_desc,sort_order,url,is_show) values ('".$data['brand_name']."','".$data['logo']."','".$data['brand_desc']."','".$data['sort_order']."','".$data['url']."','".$data['is_show']."')");
		
	}

	public function delete_brand($brand_id)
	{
		return $this->db->query("delete from ".self::TBL_BRAND." where brand_id = '".$brand_id."'");
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