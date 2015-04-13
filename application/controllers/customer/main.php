<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Customer_Controller
{
	public function __construct()
	{
		parent::__construct();
			
		
	}

	public function index()
	{
		$this->load->view('customer/index.html');
		#echo site_url('seller/top');
	}

}