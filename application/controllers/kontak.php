
<?php
class Kontak extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Kontak_model', 'kontak', TRUE);

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
			'title' 	=> 'Data Kontak', 
			'main_view' => 'kontak/kontak', 
			'form_view' => 'kontak/kontak_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->kontak->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $kontak) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$kontak->id_kontak.'">';
			$row[] = $no;
			$row[] = $kontak->nama; 
			$row[] = $kontak->email; 
			$row[] = $kontak->subjek; 
			$row[] = $kontak->isi_komentar; 
			$row[] = tgl_indonesia2($kontak->tgl_input); 
			$row[] = tgl_indonesia2($kontak->tgl_update); 
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_kontak('."'".$kontak->id_kontak."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_kontak('."'".$kontak->id_kontak."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->kontak->count_all(),
		"recordsFiltered" 	=> $this->kontak->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->kontak->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'nama'=> $this->input->post('nama', TRUE),
		'email'=> $this->input->post('email', TRUE),
		'subjek'=> $this->input->post('subjek', TRUE),
		'isi_komentar'=> $this->input->post('isi_komentar', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);
		$insert = $this->kontak->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'nama'=> $this->input->post('nama', TRUE),
		'email'=> $this->input->post('email', TRUE),
		'subjek'=> $this->input->post('subjek', TRUE),
		'isi_komentar'=> $this->input->post('isi_komentar', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);
		$this->kontak->update(array('id_kontak' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->kontak->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->kontak->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('nama')=='')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('email')=='')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Email is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('subjek')=='')
		{
			$data['inputerror'][] = 'subjek';
			$data['error_string'][] = 'Subjek is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('isi_komentar')=='')
		{
			$data['inputerror'][] = 'isi_komentar';
			$data['error_string'][] = 'Isi Komentar is required';
			$data['status'] = FALSE;							
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Kontak Class
/* End of file kontak.php */
/* Location: ./sytem/application/controlers/kontak.php */		
  