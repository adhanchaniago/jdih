<?php
class User_akses extends CI_Controller
{
	function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('User_akses_model', 'user_akses', TRUE);
	  	$this->load->model('Menu_sub_model', 'menu_sub', TRUE);
	  	$this->load->model('User_level_model', 'user_level', TRUE);
	}
		  	  
	function index()
	{
	  	$this->load->helper('url');
		$data = array(
			'title' 	=> 'User Akses', 
			'main_view' => 'user_akses/user_akses', 
			'form_view' => 'user_akses/user_akses_form',
		);

		$menu_subs = $this->menu_sub->get_list_menu_sub();		
		$opt_menu_sub = array('' => 'All Menu Sub');
	    foreach ($menu_subs as $i => $v) {
	        $opt_menu_sub[$i] = $v;
	    }

	    $data['form_menu_sub'] = form_dropdown('menu_sub_id',$opt_menu_sub,'','id="menu_sub_id" class="form-control"');
		$data['options_menu_sub'] = $opt_menu_sub;
		
		$user_levels = $this->user_level->get_list_user_level();		
		$opt_user_level = array('' => 'All User Level');
	    foreach ($user_levels as $i => $v) {
	        $opt_user_level[$i] = $v;
	    }

	    $data['form_user_level'] = form_dropdown('user_level_id',$opt_user_level,'','id="user_level_id" class="form-control"');
		$data['options_user_level'] = $opt_user_level;
		$this->load->view('admin/template', $data);	  	
	}

	public function ajax_list()
	{
		$list = $this->user_akses->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user_akses) {
			$no++;
			$row = array();

			$user_akses_aktif = '<label class="label label-danger">Tidak</label>';
			if ($user_akses->user_akses_aktif==1)
				$user_akses_aktif = '<label class="label label-success">Aktif</label>';
			$row[] = $user_akses->menu_sub_name;
			$row[] = $user_akses->user_level_name;
			$row[] = $user_akses_aktif;
	
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_user_akses('."'".$user_akses->user_akses_id."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_user_akses('."'".$user_akses->user_akses_id."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->user_akses->count_all(),
		"recordsFiltered" 	=> $this->user_akses->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->user_akses->get_by_id($id);
		$data->user_akses_date_update = ($data->user_akses_date_update == '0000-00-00') ? '' : $data->user_akses_date_update; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
		'menu_sub_id'=> $this->input->post('menu_sub_id', TRUE),
		'user_level_id'=> $this->input->post('user_level_id', TRUE),
		'user_akses_aktif'=> $this->input->post('user_akses_aktif', TRUE),
		'user_id_entri'=> $this->session->userdata('user_id'),
		'user_akses_date_entri'=> date('Y-m-d'), 
		'user_id_update'=> $this->session->userdata('user_id'),
		'user_akses_date_update'=> date('Y-m-d'), 
		);	
		$insert = $this->user_akses->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'menu_sub_id'=> $this->input->post('menu_sub_id', TRUE),
		'user_level_id'=> $this->input->post('user_level_id', TRUE),
		'user_akses_aktif'=> $this->input->post('user_akses_aktif', TRUE),
		'user_id_update'=> $this->session->userdata('user_id'),
		'user_akses_date_update'=> date('Y-m-d'), 
		);	
		$this->user_akses->update(array('user_akses_id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->user_akses->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		
		if ($this->input->post('menu_sub_id')=='')
		{
			$data['inputerror'][] = 'menu_sub_id';
			$data['error_string'][] = 'Menu Sub Id  is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('user_level_id')=='')
		{
			$data['inputerror'][] = 'user_level_id';
			$data['error_string'][] = 'User Level Id  is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('user_akses_aktif')=='')
		{
			$data['inputerror'][] = 'user_akses_aktif';
			$data['error_string'][] = 'User Akses Aktif  is required';
			$data['status'] = FALSE;							
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}		  
}
// END User_akses Class
/* End of file user_akses.php */
/* Location: ./sytem/application/controlers/user_akses.php */		
