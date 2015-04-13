<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('commodity_model');
	}
	
	public function index()
	{
		$catalogs=$this->category_model->get_catalog();
		//var_dump($catalogs);
		$data['catalogs']=$catalogs;
		$list=array();
		foreach($catalogs as $catalog) :
			$list[]=$this->category_model->display_cate($catalog['cat_id'],2);
		endforeach;

		
		//var_dump($list);
		//var_dump($this->category_model->display_cate(30));
		//$data=$this->category_model->display_cate(30);
		//var_dump($data);
		$data['lists']=$list;
		//$data['']
		$this->load->view('home.html',$data);
	}

	public function detail($goods_id)
	{
		$data['comm']=$this->commodity_model->get_comm($goods_id);
		$this->load->view('detail.html',$data);

	}

}