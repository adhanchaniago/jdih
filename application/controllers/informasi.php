
<?php
class Informasi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Informasi_model', 'informasi', TRUE);

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
			'title' 	=> 'Data Informasi', 
			'main_view' => 'informasi/informasi', 
			'form_view' => 'informasi/informasi_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->informasi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $informasi) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$informasi->id_informasi.'">';
			$row[] = $no;
			$row[] = $informasi->judul_informasi; 
			$row[] = $informasi->alamat; 
			$row[] = $informasi->no_telp; 
			$row[] = $informasi->email; 
			$row[] = $informasi->jam_pelayanan; 
			$row[] = tgl_indonesia2($informasi->tgl_input); 
			$row[] = tgl_indonesia2($informasi->tgl_update); 
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_informasi('."'".$informasi->id_informasi."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_informasi('."'".$informasi->id_informasi."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->informasi->count_all(),
		"recordsFiltered" 	=> $this->informasi->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->informasi->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'judul_informasi'=> $this->input->post('judul_informasi', TRUE),
		'alamat'=> $this->input->post('alamat', TRUE),
		'no_telp'=> $this->input->post('no_telp', TRUE),
		'email'=> $this->input->post('email', TRUE),
		'jam_pelayanan'=> $this->input->post('jam_pelayanan', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);
		$insert = $this->informasi->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'judul_informasi'=> $this->input->post('judul_informasi', TRUE),
		'alamat'=> $this->input->post('alamat', TRUE),
		'no_telp'=> $this->input->post('no_telp', TRUE),
		'email'=> $this->input->post('email', TRUE),
		'jam_pelayanan'=> $this->input->post('jam_pelayanan', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);
		$this->informasi->update(array('id_informasi' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->informasi->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->informasi->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('judul_informasi')=='')
		{
			$data['inputerror'][] = 'judul_informasi';
			$data['error_string'][] = 'Judul Informasi is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('alamat')=='')
		{
			$data['inputerror'][] = 'alamat';
			$data['error_string'][] = 'Alamat is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('no_telp')=='')
		{
			$data['inputerror'][] = 'no_telp';
			$data['error_string'][] = 'No Telp is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('email')=='')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Email is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('jam_pelayanan')=='')
		{
			$data['inputerror'][] = 'jam_pelayanan';
			$data['error_string'][] = 'Jam Pelayanan is required';
			$data['status'] = FALSE;							
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Informasi Class
/* End of file informasi.php */
/* Location: ./sytem/application/controlers/informasi.php */		
  