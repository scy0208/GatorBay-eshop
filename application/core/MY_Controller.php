<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$user=$this->session->userdata('email');
		$usertype=$this->session->userdata('usertype');
		if($usertype!='admin'||$user==NULL)	
		{
			$this->load->helper('url');
			redirect(site_url('privilige/login','refresh'));
		}
	}
}

class Seller_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$user=$this->session->userdata('email');
		$usertype=$this->session->userdata('usertype');
		if($usertype!='seller'||$user==NULL)	
		{
			$this->load->helper('url');
			redirect(site_url('privilige/login','refresh'));
		}
	}
}

class Customer_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$user=$this->session->userdata('email');
		$usertype=$this->session->userdata('usertype');
		if($usertype!='customer'||$user==NULL)	
		{
			$this->load->helper('url');
			redirect(site_url('privilige/login','refresh'));
		}
	}
}