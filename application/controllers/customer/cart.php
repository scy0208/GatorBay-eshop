<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends Customer_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cart_model');
		$this->load->model('commodity_model');
		$this->load->library('form_validation');
	}
	public function list_cart($user)
	{

		$data['goods'] = $this->cart_model->list_cart($user);
		$this->load->view('customer/list_cart.html',$data);
	}

	public function insert()
	{
		$this->form_validation->set_rules('goods_id','Commodity', 'trim|required');
		$this->form_validation->set_rules('user_id','User', 'trim|required');
		$this->form_validation->set_rules('number','Number', 'trim|required|integer|is_natural_no_zero');
		if ($this->form_validation->run()==false) 
		{
			$data['type'] = "0";
			$data['message'] = validation_errors();
			$data['wait']=3;
			$goods_id=$this->input->post('goods_id',true);
			$data['url']=site_url('home/detail/'.$goods_id);
			$this->load->view('message.html',$data);			
		}
		else
		{
			$data['goods_id']=$this->input->post('goods_id',true);
			$data['user_id']=$this->input->post('user_id');
			$data['number']=$this->input->post('number',true);
			if($this->cart_model->add_cart($data))
			{
				$data['type'] = "1";
				$data['message'] = "Add Successful";
				$data['wait']=2;
				$data['url']=site_url('customer/main/');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "0";
				$data['message'] = "Insert Failed";
				$data['wait']=3;
				$goods_id=$this->input->post('goods_id',true);
				$data['url']=site_url('home/detail/'.$goods_id);
				$this->load->view('message.html',$data);
			}
		}

	}


}