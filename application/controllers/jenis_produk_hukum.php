
<?php
class Jenis_produk_hukum extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Jenis_produk_hukum_model', 'jenis_produk_hukum', TRUE);

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
			'title' 	=> 'Data Jenis produk hukum', 
			'main_view' => 'jenis_produk_hukum/jenis_produk_hukum', 
			'form_view' => 'jenis_produk_hukum/jenis_produk_hukum_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->jenis_produk_hukum->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $jenis_produk_hukum) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$jenis_produk_hukum->id_jenis_produk_hukum.'">';
			$row[] = $no;
			$row[] = $jenis_produk_hukum->nama_jenis_produk_hukum; 
			$row[] = tgl_indonesia2($jenis_produk_hukum->tgl_input); 
			$row[] = tgl_indonesia2($jenis_produk_hukum->tgl_update); 
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_jenis_produk_hukum('."'".$jenis_produk_hukum->id_jenis_produk_hukum."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_jenis_produk_hukum('."'".$jenis_produk_hukum->id_jenis_produk_hukum."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->jenis_produk_hukum->count_all(),
		"recordsFiltered" 	=> $this->jenis_produk_hukum->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->jenis_produk_hukum->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'nama_jenis_produk_hukum'=> $this->input->post('nama_jenis_produk_hukum', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);
		$insert = $this->jenis_produk_hukum->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'nama_jenis_produk_hukum'=> $this->input->post('nama_jenis_produk_hukum', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);
		$this->jenis_produk_hukum->update(array('id_jenis_produk_hukum' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->jenis_produk_hukum->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->jenis_produk_hukum->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('nama_jenis_produk_hukum')=='')
		{
			$data['inputerror'][] = 'nama_jenis_produk_hukum';
			$data['error_string'][] = 'Nama Jenis Produk Hukum  is required';
			$data['status'] = FALSE;							
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Jenis_produk_hukum Class
/* End of file jenis_produk_hukum.php */
/* Location: ./sytem/application/controlers/jenis_produk_hukum.php */		
  