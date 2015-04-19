<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends Seller_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('commodity_model');
		$this->load->model('category_model');
		$this->load->model('order_model');
		$this->load->library('form_validation');

	}
	public function order_list()
	{
		$user=$this->session->userdata('email');
		$data['goods'] = $this->order_model->order_list($user);
		$this->load->view('seller/list_order.html',$data);
	}

	public function sale_search()
	{
		$data['cates']=$this->category_model->list_cate();
		$this->load->view('seller/sale_search.html',$data);
	}

	public function create_sale_html()
	{
		$begin_date=$this->input->get('begin_date');
		$end_date=$this->input->get('end_date');
		$cat_id=$this->input->get('cat_id');
		$seller=$this->session->userdata('email');
		$items=$this->order_model->sale_analysis($begin_date,$end_date,$cat_id,$seller);

		$html="<table class='table'>
				<caption>All Categories</caption>
					<thead>
						<tr>
							<th>Thumb</th>
							<th>Commodity name</th>
							<th>Price</th>
							<th>Sales Volume</th>
						</tr>
					</thead>
					<tbody>";

			foreach($items as $good):
				$html.="<tr>";
				$html.="<td>
						<img src=".base_url('src/commodity/main/'.$good['goods_id'].'.jpg')." width='120px' length='120px'>
						</img>
						</td>";
				$html.="<td>". $good['goods_name']."</td>";
				$html.="<td>".$good['shop_price']."</td>";
				$html.="<td>".$good['sum']."</td>";
				$html.="</tr>";
			endforeach;



		$html.="</tbody>
				</table>";
		echo $html;
	}


	public function income_analysis()
	{
		$this->load->view('seller/income_analysis.html');
	}

}