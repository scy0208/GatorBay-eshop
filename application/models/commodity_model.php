<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commodity_model extends CI_model
{
	const TBL_COMM="ci_commodity";
	const TBL_GA="ci_goods_attr";
	const TBL_ORDER="ci_order";
	public function insert_comm($data)
	{
		 $result=$this->db->query("insert into ".self::TBL_COMM." (goods_name,brand_id,cat_id,shop_price,market_price,type_id,goods_desc,goods_number,is_onsale,seller_id,add_time,click_count,trade) values ('".$data['goods_name']."','".$data['brand_id']."','".$data['cat_id']."','".$data['shop_price']."','".$data['market_price']."','".$data['type_id']."','".$data['goods_desc']."','".$data['goods_number']."','".$data['is_onsale']."','".$data['seller_id']."',to_date('".$data['add_time']."','yyyy-mm-dd hh24:mi:ss'),'0','0')");
		 if($result)
		 {
		 	$result=$this->db->query("select max(goods_id) from ".self::TBL_COMM);
		 	return $this->change_row($result->row_array());
		 }
		 else
		 {
		 	return $result;
		 }
	}
	public function insert_goods_attr($data)
	{
		return $this->db->query("insert into ".self::TBL_GA." (goods_id,attr_id,attr_value) values ('".$data['goods_id']."','".$data['attr_id']."','".$data['attr_value']."')");	
	}
	public function find_goods_attr($data)
	{
		$query=$this->db->query("select * from ".self::TBL_GA." where goods_id='".$data['goods_id']."' and attr_id='".$data['attr_id']."'");
		$result=$query->row_array();
		return $this->change_row($result);
	}
	public function list_comm($user)
	{
		$query=$this->db->query("select * from ".self::TBL_COMM." where seller_id='".$user."'");
		return $this->change_array($query->result_array());
	}

	public function list_comm_complex($user)
	{

		$query=$this->db->query(
			"select * from 
				(select goods_id, sum(num) as sum 
					from ".self::TBL_ORDER." natural join ".self::TBL_COMM." 
					group by goods_id) a 
				natural join ".self::TBL_COMM." 
				where seller_id='".$user."' 
				order by sum desc"
			);
		return $this->change_array($query->result_array());
	}

	


	public function delete_comm($goods_id)
	{
		return ($this->db->query("delete from ".self::TBL_COMM." where goods_id = '".$goods_id."'"))&&
				($this->db->query("delete from ".self::TBL_GA." where goods_id = '".$goods_id."'"));
	}
	public function get_comm($goods_id)
	{
		$query=$this->db->query("select * from ".self::TBL_COMM." where goods_id= '".$goods_id."'");
		return $this->change_row($query->row_array());
	}

	public function update_comm($data)
	{
		return $this->db->query("update ".self::TBL_COMM." set goods_name='".$data['goods_name']."', brand_id='".$data['brand_id']."', cat_id='".$data['cat_id']."', shop_price='".$data['shop_price']."', market_price='".$data['market_price']."', type_id='".$data['type_id']."', goods_desc='".$data['goods_desc']."', goods_number='".$data['goods_number']."', is_onsale='".$data['is_onsale']."' where goods_id='".$data['goods_id']."'");
	}

	public function update_goods_attr($data)
	{
		return $this->db->query("update ".self::TBL_GA." set attr_value='".$data['attr_value']."' where goods_attr_id='".$data['goods_attr_id']."'");
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