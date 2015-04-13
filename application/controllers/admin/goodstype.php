<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goodstype extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['types'] = $this->goodstype_model->list_type();
		$this->load->view('admin/goods_type_list.html',$data);
	}
	public function add()
	{
		//$data['cates'] = $this->category_model->list_cate();
		$this->load->view('admin/goods_type_add.html');
	}
	public function edit($type_id)
	{  	
	
		$data['item'] = $this->goodstype_model->select_type($type_id);
		$this->load->view('admin/goods_type_edit.html', $data);

	}
	public function update($type_id)
	{
		$this->form_validation->set_rules('type_name','Type Name', 'trim|required');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$data['url']=site_url('admin/goodstype/edit/'.$type_id);
			$this->load->view('message.html',$data);			
		}
		else
		{
			$data['type_id']=$type_id;
			$data['type_name']=$this->input->post('type_name',true);
			if($this->goodstype_model->update_type($data))
			{
				$data['type'] = "1";
				$data['message'] = "Update Successful";
				$data['wait']=2;
				$data['url']=site_url('admin/goodstype/index');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = "Update Failed";
				$data['wait']=3;
				$data['url']=site_url('admin/goodstype/index');
				$this->load->view('message.html',$data);
			}
		}

	}
	public function insert()
	{
		$this->form_validation->set_rules('type_name','Type Name', 'trim|required');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$data['url']=site_url('admin/goodstype/add');
			$this->load->view('message.html',$data);			
		}
		else
		{
			$data['type_name']=$this->input->post('type_name',true);
			if($this->goodstype_model->insert_type($data))
			{
				$data['type'] = "1";
				$data['message'] = "Insert Successful";
				$data['wait']=2;
				$data['url']=site_url('admin/goodstype/index');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = "Insert Failed";
				$data['wait']=3;
				$data['url']=site_url('admin/goodstype/index');
				$this->load->view('message.html',$data);
			}
		}
	}
	public function delete($type_id)
	{

		if($this->goodstype_model->delete_type($type_id))
		{
			$data['type'] = "1";
			$data['message'] = "Delete Successful";
			$data['wait']=3;
			$data['url']=site_url('admin/goodstype/index');
			$this->load->view('message.html',$data);
		}
		else
		{
			$data['type'] = "0";
			$data['message'] = "Delete Failed";
			$data['wait']=3;
			$data['url']=site_url('admin/goodstype/index');
			$this->load->view('message.html',$data);
		}

	}
}