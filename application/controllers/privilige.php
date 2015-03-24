<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privilige extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('captcha');
		$this->load->library('form_validation');
		$this->load->model('user_model');
	}
	public function login()
	{ 
		$this->load->view('seller/login.html');
	}


	public function code()
	{ 
		$vals = array(
			'word_length' => 6,
		);
		$data = create_captcha($vals);
 		
		$this->session->set_userdata('code',$data['word']);
		header("Content-Type:image/jpeg");
		imagejpeg($data['image']);

		ImageDestroy($data['image']);

	}


	public function signin()
	{

		$this->form_validation->set_rules('email','Email','required');
		$this->form_validation->set_rules('password','Password','required');

		if ($this->form_validation->run() == false)
		{
			$data['message'] = validation_errors();
			$data['url'] = site_url('privilige/login');
			$data['wait'] = 3;
			$this->load->view('seller/message.html',$data);
		} 
		else
		{
			
			$captcha = strtolower($this->input->post('captcha'));
			$code = strtolower($this->session->userdata('code'));
			if ($captcha!=$code)
			{
				$usertype = strtolower($this->input->post('usertype'));
				$email = strtolower($this->input->post('email'));
				$password = strtolower($this->input->post('password'));

				if($this->user_model->check_identity($usertype,$email,$password))
				{
					redirect($usertype."/main/index");
				}
				else
				{
					$data['url'] = site_url('privilige/login');
					$data['message'] = 'Invalid Email or Password';
					$data['wait'] = 3;
					$this->load->view('seller/message.html',$data);
				}
			}
			else
			{
				$data['url'] = site_url('privilige/login');
				$data['message'] = 'Wrong Verification Code';
				$data['wait'] = 3;
				$this->load->view('seller/message.html',$data);
			}

		}
	}
	
	

}