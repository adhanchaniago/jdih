<?php
class Menu extends CI_Controller
{
	function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('Menu_model', 'menu', TRUE);
	}
		  	  
	function index()
	{
	  	$this->load->helper('url');
		$data = array(
			'title' 	  => 'Menu', 
			'main_view' => 'menu/menu', 
			'form_view' => 'menu/menu_form',
		);
		$this->load->view('admin/template', $data);	  	
	}

	public function ajax_list()
	{
		$list = $this->menu->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $menu) {
			$no++;
			$row = array();
			$menu_active = '<small class="label label-danger"><i class="fa fa-clock-o"></i> Tidak</small>';
			if ($menu->menu_active==1)
				$menu_active = '<small class="label label-success"><i class="fa fa-clock-o"></i> Ya</small>';

			$row[] = '<span class="'.$menu->menu_class.'"></span>';
			$row[] = $menu->menu_name;
			$row[] = $menu->menu_link;
			$row[] = $menu_active;
			$row[] = $menu->menu_position;
	
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_menu('."'".$menu->menu_id."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_menu('."'".$menu->menu_id."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->menu->count_all(),
		"recordsFiltered" 	=> $this->menu->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->menu->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
		'menu_name'=> $this->input->post('menu_name', TRUE),
		'menu_link'=> $this->input->post('menu_link', TRUE),
		'menu_class'=> $this->input->post('menu_class', TRUE),
		'menu_active'=> $this->input->post('menu_active', TRUE),
		'menu_position'=> $this->input->post('menu_position', TRUE),
		'user_id_entri'=> $this->session->userdata('user_id'),
		'menu_date_entri'=> date('Y-m-d'), 
		'user_id_update'=> $this->session->userdata('user_id'),
		'menu_date_update'=> date('Y-m-d'), 
		);	
		$insert = $this->menu->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'menu_name'=> $this->input->post('menu_name', TRUE),
		'menu_link'=> $this->input->post('menu_link', TRUE),
		'menu_class'=> $this->input->post('menu_class', TRUE),
		'menu_active'=> $this->input->post('menu_active', TRUE),
		'menu_position'=> $this->input->post('menu_position', TRUE),
		'user_id_update'=> $this->session->userdata('user_id'),
		'menu_date_update'=> date('Y-m-d'), 
		);
		$this->menu->update(array('menu_id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->menu->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		
		if ($this->input->post('menu_name')=='')
		{
			$data['inputerror'][] = 'menu_name';
			$data['error_string'][] = 'Menu Name is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('menu_link')=='')
		{
			$data['inputerror'][] = 'menu_link';
			$data['error_string'][] = 'Menu Link is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('menu_active')=='')
		{
			$data['inputerror'][] = 'menu_active';
			$data['error_string'][] = 'Menu Active is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('menu_position')=='')
		{
			$data['inputerror'][] = 'menu_position';
			$data['error_string'][] = 'Menu Position is required';
			$data['status'] = FALSE;							
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}		  
}
// END Menu Class
/* End of file menu.php */
/* Location: ./sytem/application/controlers/menu.php */		
