<?php
class Instansi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Instansi_model', 'instansi', TRUE);
	}

	function index()
	{
		if ($this->session->userdata('login') != TRUE)
		{
		  redirect('login');
		}
		else
		{
		    $this->load->helper('url');
		    $data = array(
			'title' 	=> 'Data Instansi', 
			'main_view' => 'instansi/instansi', 
			'form_view' => 'instansi/instansi_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->instansi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $instansi) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$instansi->id_instansi.'">';
			$row[] = $no;
			$row[] = $instansi->nama_instansi; 
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_instansi('."'".$instansi->id_instansi."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_instansi('."'".$instansi->id_instansi."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->instansi->count_all(),
		"recordsFiltered" 	=> $this->instansi->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->instansi->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'nama_instansi'=> $this->input->post('nama_instansi', TRUE),
		);
		$insert = $this->instansi->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'nama_instansi'=> $this->input->post('nama_instansi', TRUE),
		);
		$this->instansi->update(array('id_instansi' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->instansi->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->instansi->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('nama_instansi')=='')
		{
			$data['inputerror'][] = 'nama_instansi';
			$data['error_string'][] = 'Nama Instansi is required';
			$data['status'] = FALSE;							
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Instansi Class
/* End of file instansi.php */
/* Location: ./sytem/application/controlers/instansi.php */		
  