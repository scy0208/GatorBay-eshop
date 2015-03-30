<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{
	public function index()
	{
		$this->load->view('admin/index.html');
		#echo site_url('seller/top');
	}
	/*
	public function top()
	{
		$this->load->view('admin/top.html');
	}
	public function xmenu()
	{
		$this->load->view('admin/menu.html');
	}
	public function drag()
	{
		$this->load->view('admin/drag.html');
	}
	public function content()
	{
		$this->load->view('admin/main.html');
	}
	*/
}