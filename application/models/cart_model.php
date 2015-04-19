<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_model extends CI_Model
{
	const TBL_CART = "ci_cart";
	const TBL_COMM = "ci_commodity";

	public function list_cart($user)
	{
		$query = $this->db->query("select * from ".self::TBL_CART." a natural join ".self::TBL_COMM." b where a.user_id='".$user."'");
		$items = $query->result_array();
		return $this->change_array($items);
	}


	public function add_cart($data)
	{
		$query =$this->db->query("select * from ".self::TBL_CART." where goods_id='".$data['goods_id']."'");
		$exist=$this->change_row($query->row_array());
		if(empty($exist))
		{
			return $this->db->query("insert into ".self::TBL_CART." (goods_id,user_id,num) values ('".$data['goods_id']."','".$data['user_id']."','".$data['num']."')");
		}
		else
		{
			$number=$data['num']+$exist['num'];
			return $this->db->query("update ".self::TBL_CART." set num='".$number."' where goods_id='".$data['goods_id']."'");

		}
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