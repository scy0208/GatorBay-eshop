<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commodity extends Seller_Controller
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
		$user=$this->session->userdata('email');
		$data['goods']=$this->commodity_model->list_comm($user);
		$this->load->view('seller/list_comm.html',$data);
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
					$choices=explode(PHP_EOL, $attr['attr_values']);
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

	public function delete_comm($goods_id)	
	{
		if($this->commodity_model->delete_comm($goods_id))
		{
			unlink('./src/commodity/main/'.$goods_id.'.jpg');
			$data['type'] = "1";
			$data['message'] = "Delete Successful";
			$data['wait']=3;
			$data['url']=site_url('seller/commodity/list_comm');
			$this->load->view('message.html',$data);
		}
		else
		{
			$data['type'] = "0";
			$data['message'] = "Delete Failed";
			$data['wait']=3;
			$data['url']=site_url('seller/commodity/list_comm');
			$this->load->view('message.html',$data);
		}


	}

	public function edit_comm($goods_id)
	{
		$data['item']=$this->commodity_model->get_comm($goods_id);
		$data['types']=$this->goodstype_model->get_all_type();
		$data['cates']=$this->category_model->list_cate();
		$data['brands']=$this->brand_model->list_brand();
		$data['attrs']=$this->attribute_model->join_attr($data['item']['type_id'],$data['item']['goods_id']);

		$this->load->view('seller/edit_comm.html',$data);

	}

	public function update_comm($goods_id)
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
				$data['url']=site_url('seller/commodity/edit_comm'.$goods_id);
				$this->load->view('message.html',$data);			
			}
			else
			{
				$data['goods_id']=$goods_id;
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
				$data['add_time']=date('Y-m-d h:i:s',time());
				$result=$this->commodity_model->update_comm($data);
				if(!$result)
				{
					$data['type'] = "0";
					$data['message'] = "Add Failed";
					$data['wait']=3;
					$data['url']=site_url('seller/commodity/edit_comm'.$goods_id);
					$this->load->view('message.html',$data);
				}
				else
				{
					$config['upload_path']="./src/commodity/main/";
					$config['allowed_types']='jpg';
					$config['file_name']=$goods_id;
					$config['max_size']=1000;
					$this->load->library('upload',$config);
					$this->upload->do_upload('goods_img');
					$attr_ids=$this->input->post('attr_id_list');
					$attr_values=$this->input->post('attr_value_list');
					foreach($attr_values as $k=>$v)
					{
						if(!empty($v))
						{
							//$data['goods_id']=$result['max(goods_id)'];
							$data['attr_id']=$attr_ids[$k];
							$data['attr_value']=$v;
							$result2=$this->commodity_model->find_goods_attr($data);
							if(empty($result2))
							{
								$this->commodity_model->insert_goods_attr($data);
							}
							else
							{
								$data['goods_attr_id']=$result2['goods_attr_id'];
								$this->commodity_model->update_goods_attr($data);
							}
						}
					}
					$data['type'] = "1";
					$data['message'] = "Update Succeed";
					$data['wait']=3;
					$data['url']=site_url('seller/commodity/list_comm');
					$this->load->view('message.html',$data);


				}
				
			}
		}

	}

public function test()
{
	//$data1=$this->category_model->display_cate(48);
	$data2=$this->category_model->display_cate(48,2);
	//$data3=$this->__recursion(10,0);
	var_dump($data2);
}

private function __recursion($setting,$input)
{
	if($input<=$setting)
	{
		echo $input." , ";
		$this->__recursion($setting, $input+1);
	}
	return $input;
}



}