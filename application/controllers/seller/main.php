<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Seller_Controller
{
	public function index()
	{
		$this->load->view('seller/index.html');
		#echo site_url('seller/top');
	}

}