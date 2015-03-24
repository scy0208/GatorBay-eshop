<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seller extends CI_Controller
{
	public function index()
	{
		$this->load->view('seller/index.html');
		#echo site_url('seller/top');
	}
	public function top()
	{
		$this->load->view('seller/top.html');
	}
	public function menu()
	{
		$this->load->view('seller/menu.html');
	}
	public function drag()
	{
		$this->load->view('seller/drag.html');
	}
	public function main()
	{
		$this->load->view('seller/main.html');
	}
}