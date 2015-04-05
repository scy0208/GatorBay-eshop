<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commodity extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->model('attribute_model');
		$this->load->model('brand_model');
		$this->load->model('category_model');
		$this->load->model('commodity_model');
		$this->load->library('form_validation');
	}
	public function index()
	{		
		$this->load->view('seller/add_comm.html');
		#echo site_url('seller/top');
	}
	public function add_comm()
	{		
		$data['types']=$this->goodstype_model->get_all_type();
		$data['cates']=$this->category_model->list_cate();
		$data['brands']=$this->brand_model->list_brand();
		$this->load->view('seller/add_comm.html',$data);
		#echo site_url('seller/top');
	}
	public function create_attr_html()
	{
		$type_id=$this->input->get('type_id');
		$attrs=$this->attribute_model->get_attr($type_id);
		$html="";
		
		foreach($attrs as $attr) :
			$html.= "<div class='form-group'>";
			$html.="<label>".$attr['attr_name']."</label>";
			$html.="<input class='form-control' type='hidden' name='attr_id_list[]' id='attr_id_list[]' value='".$attr['attr_id']."'>";
			switch ($attr['attr_input_type']) {
				case '0':
					$html.="<input class='form-control' type='text' name='attr_value_list[]' id='attr_value_list[]' >";
					break;
				case '1':
					$choices=explode(PHP_EOL, $attr['attr_value']);
					$html.="<select class='form-control' name='attr_value_list[]' id='attr_value_list[]' >";
						foreach($choices as $choice):
							$html.="<option value='".$choice."'>".$choice."</option>";
						endforeach;
					$html.="</select>";
					break;
				case '2':
					$html.="<textarea class='form-control' name='attr_value_list[]' id='attr_value_list[]' ></textarea>";
					break;			
				default:
					# code...
					break;
			}
			$html.= "</div>";
		endforeach;
		echo $html;
		//var_dump($attrs);
	}
	public function insert()
	{
		$user=$this->session->userdata('email');
		$usertype=$this->session->userdata('usertype');
		$this->form_validation->set_rules('goods_name','Commodity Name', 'trim|required');
		if($user!=NULL&&$usertype=="seller")
		{
			if ($this->form_validation->run()==false) 
			{
				$data['type'] = "0";
				$data['message'] = validation_errors();
				$data['wait']=3;
				$data['url']=site_url('seller/commodity/add_comm');
				$this->load->view('message.html',$data);			
			}
			else
			{
				$data['goods_name']=$this->input->post('goods_name',true);
				$data['brand_id']=$this->input->post('brand_id',true);
				$data['cat_id']=$this->input->post('cat_id',true);
				$data['shop_price']=$this->input->post('shop_price',true);
				$data['market_price']=$this->input->post('market_price',true);
				$data['type_id']=$this->input->post('type_id',true);
				$data['goods_desc']=$this->input->post('goods_desc',true);
				$data['goods_number']=$this->input->post('goods_number',true);
				$data['is_onsale']=$this->input->post('is_onsale',true);
				$data['seller_id']=$this->session->userdata('email');
				$data['add_time']=date('y-m-d h:i:s',time());
				$result=$this->commodity_model->insert_comm($data);
				if(!$result)
				{
					$data['type'] = "0";
					$data['message'] = "Add Failed";
					$data['wait']=3;
					$data['url']=site_url('seller/commodity/add_comm');
					$this->load->view('message.html',$data);
				}
				else
				{
					$config['upload_path']="./src/commodity/main/";
					$config['allowed_types']='gif|png|jpg';
					$config['file_name']=$result['max(goods_id)'];
					$config['max_size']=1000;
					$this->load->library('upload',$config);
					$this->upload->do_upload('goods_img');
					$attr_ids=$this->input->post('attr_id_list');
					$attr_values=$this->input->post('attr_value_list');
					foreach($attr_values as $k=>$v)
					{
						if(!empty($v))
						{
							$data['goods_id']=$result['max(goods_id)'];
							$data['attr_id']=$attr_ids[$k];
							$data['attr_value']=$v;
							$this->commodity_model->insert_goods_attr($data);
						}
					}
					$data['type'] = "1";
					$data['message'] = "Add Succeed";
					$data['wait']=3;
					$data['url']=site_url('seller/commodity/add_comm');
					$this->load->view('message.html',$data);


				}
				
			}
		}
		else
		{
			$data['type'] = "0";
			$data['message'] = "You are not a seller";
			$data['wait']=3;
			$data['url']=site_url('seller/commodity/add_comm');
			$this->load->view('message.html',$data);
		}
				
	}



	public function list_comm()
	{
		$user=$this->session->userdata('email');
		$data['goods']=$this->commodity_model->list_comm($user);
		$this->load->view('seller/list_comm.html',$data);
	}
		
}