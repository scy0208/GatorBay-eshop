<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attribute extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->model('attribute_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['types'] = $this->goodstype_model->get_all_type();
		$data['attributes']=$this->attribute_model->list_attr();
		$this->load->view('admin/attribute_list.html',$data);
	}

	public function add()
	{
		$data['types'] = $this->goodstype_model->get_all_type();
		$this->load->view('admin/attribute_add.html',$data);
	}
	public function insert()
	{
		$this->form_validation->set_rules('attr_name','Attribute Name', 'trim|required');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$data['url']=site_url('admin/attribute/add');
			$this->load->view('message.html',$data);			
		}
		else
		{
			$data['attr_name']=$this->input->post('attr_name',true);
			$data['type_id']=$this->input->post('type_id');
			$data['attr_type']=$this->input->post('attr_type',true);
			$data['attr_input_type']=$this->input->post('attr_input_type',true);
			$data['attr_values']=$this->input->post('attr_values');
			$data['sort_order']=$this->input->post('sort_order',true);
			if($this->attribute_model->insert_attr($data))
			{
				$data['type'] = "1";
				$data['message'] = "Insert Successful";
				$data['wait']=2;
				$data['url']=site_url('admin/attribute/index');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = "Insert Failed";
				$data['wait']=3;
				$data['url']=site_url('admin/attribute/index');
				$this->load->view('message.html',$data);
			}
		}

	}
}