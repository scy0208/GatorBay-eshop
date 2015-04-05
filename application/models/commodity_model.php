<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commodity_model extends CI_model
{
	const TBL_COMM="ci_commodity";
	const TBL_GA="ci_goods_attr";
	public function insert_comm($data)
	{
		 $result=$this->db->query("insert into ".self::TBL_COMM." (goods_name,brand_id,cat_id,shop_price,market_price,type_id,goods_desc,goods_number,is_onsale,seller_id,add_time) values ('".$data['goods_name']."','".$data['brand_id']."','".$data['cat_id']."','".$data['shop_price']."','".$data['market_price']."','".$data['type_id']."','".$data['goods_desc']."','".$data['goods_number']."','".$data['is_onsale']."','".$data['seller_id']."','".$data['add_time']."')");
		 if($result)
		 {
		 	$result=$this->db->query("select max(goods_id) from ".self::TBL_COMM);
		 	return $result->row_array();
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
	public function list_comm($user)
	{
		$query=$this->db->query("select * from ".self::TBL_COMM." where seller_id='".$user."'");
		return $query->result_array();
	}

}