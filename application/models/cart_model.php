<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_model extends CI_Model
{
	const TBL_CART = "ci_cart";
	const TBL_COMM = "ci_commodity";

	public function list_cart($user)
	{
		$query = $this->db->query("select * from ".self::TBL_CART." a natural join ".self::TBL_COMM." b where a.user_id='".$user."'");
		$items = $query->result_array();
		return $items;
	}


	public function add_cart($data)
	{
		$query =$this->db->query("select * from ".self::TBL_CART." where goods_id='".$data['goods_id']."'");
		$exist=$query->row_array();
		if(empty($exist))
		{
			return $this->db->query("insert into ".self::TBL_CART." (goods_id,user_id,number) values ('".$data['goods_id']."','".$data['user_id']."','".$data['number']."')");
		}
		else
		{
			$number=$data['number']+$exist['number'];
			return $this->db->query("update ".self::TBL_CART." set number='".$number."' where goods_id='".$data['goods_id']."'");

		}
	}


} 