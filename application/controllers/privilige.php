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
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('usertype');
		$this->load->view('login.html');
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


	public function signin($target="background")
	{
		//$this->session->sess_destroy();
		$this->form_validation->set_rules('email','Email','required');
		$this->form_validation->set_rules('password','Password','required');

		if ($this->form_validation->run() == false)
		{
			$data['type'] = '0';
			$data['message'] = validation_errors();
			$data['url'] = site_url('privilige/login');
			$data['wait'] = 3;
			$this->load->view('message.html',$data);
		} 
		else
		{
			
			$captcha = strtolower($this->input->post('captcha'));
			$code = strtolower($this->session->userdata('code'));
			if ($captcha!=$code)
			{
				$usertype = strtolower($this->input->post('usertype'));
				$email = strtolower($this->input->post('email'));
				$password = $this->input->post('password');

				if($this->user_model->check_identity($usertype,$email,$password))
				{
					$user_info = array('usertype' => $usertype, 'email' => $email);
					$this->session->set_userdata($user_info);
					//echo "SUCCESS";
					if($target=='background')
					{
						redirect($usertype.'/main/index');
					}
					else
					{
						redirect($target);
					}
				
				}
				else
				{
					$data['type'] = '0';
					$data['url'] = site_url('privilige/login');
					$data['message'] = 'Invalid Email or Password';
					$data['wait'] = 3;
					$this->load->view('message.html',$data);
				}
			}
			else
			{
				$data['type'] = '0';
				$data['url'] = site_url('privilige/login');
				$data['message'] = 'Wrong Verification Code';
				$data['wait'] = 3;
				$this->load->view('message.html',$data);
			}

		}
	}
	
	

}