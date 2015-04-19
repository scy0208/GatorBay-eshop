<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends Customer_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cart_model');
		$this->load->model('commodity_model');
		$this->load->model('order_model');
		$this->load->library('form_validation');

	}
	public function list_order()
	{
		$user=$this->session->userdata('email');
		$data['goods'] = $this->order_model->list_order($user);
		$this->load->view('customer/list_order.html',$data);
	}

	public function check_alone($goods_id,$number)
	{
		//$user=$this->session->userdata('email');
		$comm=$this->commodity_model->get_comm($goods_id);
		if($comm['goods_number']>$number)
		{
			$data['goods_id']=$goods_id;
			$data['num']=$number;
			$data['user_id']=$this->session->userdata('email');
			$data['order_time']=date('Y-m-d h:i:s',time());


			$result=$this->order_model->create_order($data);
			if(!$result)
			{
				$data['type'] = "0";
				$data['message'] = "Order Failed";
				$data['wait']=3;
				$data['url']=site_url('customer/order/list_cart');
				$this->load->view('message.html',$data);
			}
			else
			{
				$data['type'] = "1";
				$data['message'] = "Order Successful";
				$data['wait']=3;
				$data['url']=site_url('customer/order/list_cart');
				$this->load->view('message.html',$data);
			}
		}
		else
		{
			$data['type'] = "0";
			$data['message'] = "Out of Stock";
			$data['wait']=3;
			$data['url']=site_url('customer/order/list_cart');
			$this->load->view('message.html',$data);
		}

		

	}

}