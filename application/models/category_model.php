<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model
{
	const TBL_CATE = "ci_category";
	const TBL_COMM = "ci_commodity";
	public function select_cate($cat_id)
	{
		$query = $this->db->query("select * from ".self::TBL_CATE." where cat_id= '".$cat_id."'");
		$cate=$query->row_array();
		return $this->change_row($cate);
	}


	public function list_cate($pid = 0)
	{
		$query = $this->db->query("select * from ".self::TBL_CATE);
		$cates = $this->change_array($query->result_array());

		return $this->__tree($cates, $pid);
		
	}

	public function get_catalog()
	{
		$query = $this->db->query("select * from ".self::TBL_CATE." where parent_id='0'");
		$cates = $this->change_array($query->result_array());
		return $cates;
		
	}

	private function __tree($arr, $pid=0, $level=0)
	{
		static $tree = array();
		foreach($arr as $v)
		{
			if($v['parent_id']==$pid)
			{
				$v['level']=$level;
				$tree[]=$v;
				$this->__tree($arr, $v['cat_id'],$level+1);
			}

		}
		return $tree;
	}

	public function display_cate($pid, $level_display)
	{
		$pbase=$pid;
		$query = $this->db->query("select * from ".self::TBL_CATE);
		$cates = $this->change_array($query->result_array());
		
		return $this->__level_display($cates,$pid,$level_display,1);
		//return $cates;		
	}

	public function __display($arr,$pid=0,$pbase=0)
	{
		$data=array();
		$result['children']=array();
		static $goods=array();
		
		foreach($arr as $v)
		{

			if($v['parent_id']==$pid)
			{
				$data=$v;
				$temp=$this->__display($arr,$v['cat_id'],$pbase,$goods);
				$data['children']=$temp['children'];
				$result['children'][]=$data;
			}
		}
		$temp=$this->db->query("select goods_id, goods_name, shop_price from ".self::TBL_COMM." where cat_id='".$pid."'")->result_array();
		$querys=$this->change_array($temp);
		if(!empty($querys))
		{
			foreach ($querys as $query)
			{
				$goods[]=$query;
			}
		}
		if($pid==$pbase)
		{
			$result['goods']=$goods;
			$goods=NULL;
		}		
		return $result;
	}

	public function __level_display($arr, $pid, $level_display, $level)
	{
		$data=array();
		$result['children']=array();
		$result['goods']=array();
		static $goods=array();
		
		foreach($arr as $v)
		{

			if($v['parent_id']==$pid)
			{
				$data['cate']=$v;
				$temp=$this->__level_display($arr, $v['cat_id'],$level_display,$level+1);
				$data['children']=$temp['children'];
				$data['goods']=$temp['goods'];
				$result['children'][]=$data;
			}
		}
		if($level >= $level_display)
		{
			
			$temp=$this->db->query("select goods_id, goods_name, shop_price from ".self::TBL_COMM." where cat_id='".$pid."'")->result_array();
			$querys=$this->change_array($temp);
			if(!empty($querys))
			{
				foreach ($querys as $query)
				{
					$goods[]=$query;
				}
			}
			if($level==$level_display)
			{
				
				$result['goods']=$goods;
				$goods=NULL;
			}
		}
		return $result;

	}

	public function insert_cate($data)
	{

		return $this->db->query("insert into ".self::TBL_CATE." (cat_name,parent_id,cat_desc,sort_order,unit,is_show) values ('".$data['cat_name']."','".$data['parent_id']."','".$data['cat_desc']."','".$data['sort_order']."','".$data['unit']."','".$data['is_show']."')");
		
	}

	public function delete_cate($cat_id)
	{
		$query = $this->db->query("select * from ". self::TBL_CATE);
		$cates = $this->change_array($query->result_array());
		$deletes=$this->__tree($cates, $cat_id);
		foreach($deletes as $delete_item):
		
			$this->db->query("delete from ".self::TBL_CATE." where cat_id = '".$delete_item['cat_id']."'");
		
		endforeach;
		return $this->db->query("delete from ".self::TBL_CATE." where cat_id = '".$cat_id."'");
	}

	public function update_cate($data)
	{
		return $this->db->query("update ".self::TBL_CATE." set cat_name='".$data['cat_name']."', parent_id='".$data['parent_id']."', cat_desc='".$data['cat_desc']."', sort_order='".$data['sort_order']."', unit='".$data['unit']."', is_show='".$data['is_show']."' where cat_id='".$data['cat_id']."'");
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