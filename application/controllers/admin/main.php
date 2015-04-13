<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Admin_Controller
{
	public function index()
	{
		$this->load->view('admin/index.html');
	}

}