<?php
class Menu_sub extends CI_Controller
{
	function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('Menu_sub_model', 'menu_sub', TRUE);
	  	$this->load->model('Menu_model', 'menu', TRUE);
	}
		  	  
	function index()
	{
	  	$this->load->helper('url');
		$data = array(
			'title' 	  => 'Menu Sub', 
			'main_view' => 'menu_sub/menu_sub', 
			'form_view' => 'menu_sub/menu_sub_form',
		);
		$this->load->view('admin/template', $data);	  	
	}

	public function ajax_list()
	{
		$list = $this->menu_sub->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $menu_sub) {
			$no++;
			$row = array();

			// $aktif = $menu_sub->menu_sub_active;
			// $label_aktif = '<i class="fa fa-circle text-danger"></i> Tidak';
			// if ($aktif==1)
			// 	$label_aktif = '<i class="fa fa-circle text-success"></i> Aktif';
			
			$row[] = $menu_sub->menu_sub_name;
			$row[] = $menu_sub->menu_sub_link;
			// $row[] = $label_aktif;
			$row[] = $menu_sub->menu_sub_position;
			$row[] = $menu_sub->menu_name;
	
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_menu_sub('."'".$menu_sub->menu_sub_id."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_menu_sub('."'".$menu_sub->menu_sub_id."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->menu_sub->count_all(),
		"recordsFiltered" 	=> $this->menu_sub->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->menu_sub->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
		'menu_sub_name'=> $this->input->post('menu_sub_name', TRUE),
		'menu_sub_link'=> $this->input->post('menu_sub_link', TRUE),
		'menu_sub_position'=> $this->input->post('menu_sub_position', TRUE),
		'menu_id'=> $this->input->post('menu_id', TRUE),
		'user_id_entri'=> $this->session->userdata('user_id'),
		'menu_sub_date_entri'=> date('Y-m-d'), 
		);	
		$insert = $this->menu_sub->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'menu_sub_name'=> $this->input->post('menu_sub_name', TRUE),
		'menu_sub_link'=> $this->input->post('menu_sub_link', TRUE),
		'menu_sub_position'=> $this->input->post('menu_sub_position', TRUE),
		'menu_id'=> $this->input->post('menu_id', TRUE),
		'user_id_entri'=> $this->session->userdata('user_id'),
		'menu_sub_date_entri'=> date('Y-m-d'), 
		);
		$this->menu_sub->update(array('menu_sub_id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->menu_sub->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		
		if ($this->input->post('menu_sub_name')=='')
		{
			$data['inputerror'][] = 'menu_sub_name';
			$data['error_string'][] = 'Name  is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('menu_sub_link')=='')
		{
			$data['inputerror'][] = 'menu_sub_link';
			$data['error_string'][] = 'Link  is required';
			$data['status'] = FALSE;							
		}

		if ($this->input->post('menu_sub_position')=='')
		{
			$data['inputerror'][] = 'menu_sub_position';
			$data['error_string'][] = 'Position  is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('menu_id')=='')
		{
			$data['inputerror'][] = 'menu_id';
			$data['error_string'][] = 'Menu is required';
			$data['status'] = FALSE;							
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}		  
}
// END Menu_sub Class
/* End of file menu_sub.php */
/* Location: ./sytem/application/controlers/menu_sub.php */		
