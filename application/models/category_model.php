<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model
{
	const TBL_CATE = "ci_category";

	public function select_cate($cat_id)
	{
		$query = $this->db->query("select * from ".self::TBL_CATE." where cat_id= '".$cat_id."'");
		return $query->row_array();
	}


	public function list_cate($pid = 0)
	{
		$query = $this->db->query("select * from ".self::TBL_CATE);
		$cates = $query->result_array();
		return $this->__tree($cates, $pid);
		
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

	public function insert_cate($data)
	{

		return $this->db->query("insert into ".self::TBL_CATE." (cat_name,parent_id,cat_desc,sort_order,unit,is_show) values ('".$data['cat_name']."','".$data['parent_id']."','".$data['cat_desc']."','".$data['sort_order']."','".$data['unit']."','".$data['is_show']."')");
		
	}

	public function delete_cate($cat_id)
	{
		$query = $this->db->query("select * from ". self::TBL_CATE);
		$cates = $query->result_array();
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

}