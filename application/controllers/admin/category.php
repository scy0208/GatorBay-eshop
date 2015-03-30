<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
		$this->load->library('form_validation');
	}

	public function add()
	{
		$data['cates'] = $this->category_model->list_cate();
		$this->load->view('admin/cat_add.html',$data);
	}
	public function index()
	{
		$data['cates'] = $this->category_model->list_cate();
		$this->load->view('admin/cat_list.html',$data);
	}
	public function edit($cat_id)
	{  	
	
		$data['item'] = $this->category_model->select_cate($cat_id);
		$data['cates']=$this->category_model->list_cate();
		$this->load->view('admin/cat_edit.html', $data);

	}

	public function update($cat_id)
	{
		$this->form_validation->set_rules('cat_name','Category Name', 'trim|required');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$data['url']=site_url('admin/category/add');
			$this->load->view('message.html',$data);			
		}
		else
		{
			$data['cat_id']=$cat_id;
			$data['cat_name']=$this->input->post('cat_name',true);
			$data['parent_id']=$this->input->post('parent_id');
			$data['unit']=$this->input->post('unit',true);
			$data['sort_order']=$this->input->post('sort_order',true);
			$data['is_show']=$this->input->post('is_show');
			$data['cat_desc']=$this->input->post('cat_desc',true);
			if($this->category_model->update_cate($data))
			{
				$data['type'] = "1";
				$data['message'] = "Update Successful";
				$data['wait']=2;
				$data['url']=site_url('admin/category/index');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = "Update Failed";
				$data['wait']=3;
				$data['url']=site_url('admin/category/index');
				$this->load->view('message.html',$data);
			}
		}

	}
	
	public function insert()
	{
		$this->form_validation->set_rules('cat_name','Category Name', 'trim|required');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$data['url']=site_url('admin/category/add');
			$this->load->view('message.html',$data);			
		}
		else
		{
			$data['cat_name']=$this->input->post('cat_name',true);
			$data['parent_id']=$this->input->post('parent_id');
			$data['unit']=$this->input->post('unit',true);
			$data['sort_order']=$this->input->post('sort_order',true);
			$data['is_show']=$this->input->post('is_show');
			$data['cat_desc']=$this->input->post('cat_desc',true);
			if($this->category_model->insert_cate($data))
			{
				$data['type'] = "1";
				$data['message'] = "Insert Successful";
				$data['wait']=2;
				$data['url']=site_url('admin/category/index');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = "Insert Failed";
				$data['wait']=3;
				$data['url']=site_url('admin/category/index');
				$this->load->view('message.html',$data);
			}
		}

	}

	public function delete($cat_id)
	{

		if($this->category_model->delete_cate($cat_id))
		{
			$data['type'] = "1";
			$data['message'] = "Delete Successful";
			$data['wait']=3;
			$data['url']=site_url('admin/category/index');
			$this->load->view('message.html',$data);
		}
		else
		{
			$data['type'] = "0";
			$data['message'] = "Delete Failed";
			$data['wait']=3;
			$data['url']=site_url('admin/category/index');
			$this->load->view('message.html',$data);
		}


	}

}