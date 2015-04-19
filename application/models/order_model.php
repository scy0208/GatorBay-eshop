<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
	const TBL_ORDER ="ci_order";
	const TBL_COMM = "ci_commodity";
	const TBL_CART = "ci_cart";

	public function list_order($user)
	{
		$query = $this->db->query("select * from ".self::TBL_ORDER." a natural join ".self::TBL_COMM." b where user_id='".$user."'");
		$items = $query->result_array();
		return $this->change_array($items);
	}

	public function order_list($seller)
	{
		$query = $this->db->query("select * from ".self::TBL_ORDER." a natural join ".self::TBL_COMM." b where seller_id='".$seller."'");
		$items = $query->result_array();
		return $this->change_array($items);
	}

	public function create_order($data)
	{
		return ($this->db->query("insert into ".self::TBL_ORDER." (goods_id,user_id,num,order_time) values ('".$data['goods_id']."','".$data['user_id']."','".$data['num']."',to_date('".$data['order_time']."','yyyy-mm-dd hh24:mi:ss'))"))&&
			($this->db->query("update ".self::TBL_COMM." set goods_number=goods_number-'".$data['num']."', trade=trade+'".$data['num']."' where goods_id='".$data['goods_id']."'"))&&
			($this->db->query("delete from ".self::TBL_CART." where goods_id = '".$data['goods_id']."'"));
	}

	public function sale_analysis($begin_date, $end_date, $cat_id, $seller)
	{
		
		if($cat_id=='0')
		{
			$query=$this->db->query(
					"select * from 
						(select goods_id, sum(num) as sum 
							from ".self::TBL_ORDER." natural join ".self::TBL_COMM."
							where order_time between to_date('".$begin_date."','yyyy-mm-dd') and to_date('".$end_date."','yyyy-mm-dd')  
							group by goods_id) a 
						natural join ".self::TBL_COMM." 
						where seller_id='".$seller."'
						order by sum desc"
					);
		}
		else
		{
			$query=$this->db->query(
					"select * from 
						(select goods_id, sum(num) as sum 
							from ".self::TBL_ORDER." natural join ".self::TBL_COMM."
							where order_time between to_date('".$begin_date."','yyyy-mm-dd') and to_date('".$end_date."','yyyy-mm-dd') 
							group by goods_id) a 
						natural join ".self::TBL_COMM." 
						where seller_id='".$seller."'
						and cat_id in (
					  		SELECT t1.cat_id 
					      		FROM ci_category t1
					 			LEFT JOIN ci_category t2 ON t2.cat_id = t1.parent_id
							START WITH t1.cat_id = '".$cat_id."'
							CONNECT BY PRIOR t1.cat_id = t1.parent_id
							) 
						order by sum desc"
					);
		}

		return $this->change_array($query->result_array());
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