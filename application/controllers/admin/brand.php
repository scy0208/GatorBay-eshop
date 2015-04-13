<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('brand_model');
		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['brands'] = $this->brand_model->list_brand();
		$this->load->view("admin/brand_list.html",$data);
	}
	public function add()
	{
		$this->load->view("admin/brand_add.html");
	}
	public function edit()
	{
		$this->load->view("admin/brand_edit.html");
	}

	public function insert()
	{
		$this->form_validation->set_rules('brand_name','Brand Name', 'trim|required');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$data['url']=site_url('admin/brand/add');
			$this->load->view('message.html',$data);			
		}
		else
		{
			$config['upload_path']="./src/uploads/";
			$config['allowed_types']='gif|png|jpg';
			$config['max_size']=100;
			$this->load->library('upload',$config);
			if($this->upload->do_upload('logo'))
			{
				$fileinfo=$this->upload->data();
				$data['logo']=$fileinfo['file_name'];
				$data['brand_name']=$this->input->post('brand_name',true);
				$data['url']=$this->input->post('url',true);
				$data['sort_order']=$this->input->post('sort_order',true);
				$data['is_show']=$this->input->post('is_show');
				$data['brand_desc']=$this->input->post('brand_desc',true);
				if($this->brand_model->insert_brand($data))
				{
					$data['type'] = "1";
					$data['message'] = "Insert Successful";
					$data['wait']=2;
					$data['url']=site_url('admin/brand/index');
					$this->load->view('message.html',$data);
				}
				else
				{
					$data['type'] = "0";
					$data['message'] = "Insert Failed";
					$data['wait']=3;
					$data['url']=site_url('admin/brand/index');
					$this->load->view('message.html',$data);
				}
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = $this->upload->display_errors();
				$data['wait']=3;
				$data['url']=site_url('admin/brand/index');
				$this->load->view('message.html',$data);
			}
			
		}

	}

	public function delete($brand_id)
	{

		if($this->brand_model->delete_brand($brand_id))
		{
			$data['type'] = "1";
			$data['message'] = "Delete Successful";
			$data['wait']=2;
			$data['url']=site_url('admin/brand/index');
			$this->load->view('message.html',$data);
		}
		else
		{
			$data['type'] = "0";
			$data['message'] = "Delete Failed";
			$data['wait']=3;
			$data['url']=site_url('admin/brand/index');
			$this->load->view('message.html',$data);
		}


	}
}