<?php
class Produk_hukum extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Produk_hukum_model', 'produk_hukum', TRUE);
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
			'title' 	=> 'Data Produk hukum', 
			'main_view' => 'produk_hukum/produk_hukum', 
			'form_view' => 'produk_hukum/produk_hukum_form',
			);

			$jenis_produk_hukums = $this->jenis_produk_hukum->get_list_jenis_produk_hukum();		
			$opt_jenis_produk_hukum = array('' => '-- Pilih --');
		    foreach ($jenis_produk_hukums as $i => $v) {
		        $opt_jenis_produk_hukum[$i] = $v;
		    }

		    $data['form_jenis_produk_hukum'] = form_dropdown('id_jenis_produk_hukum',$opt_jenis_produk_hukum,'','id="id_jenis_produk_hukum" class="form-control"');
			$data['form_jenis_produk_hukum2'] = form_dropdown('id_jenis_produk_hukum2',$opt_jenis_produk_hukum,'','id="id_jenis_produk_hukum2" class="form-control"');
			$data['options_jenis_produk_hukum'] = $opt_jenis_produk_hukum;
			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->produk_hukum->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $produk_hukum) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$produk_hukum->id_produk_hukum.'">';
			$row[] = $no;
			$row[] = $produk_hukum->nama_jenis_produk_hukum; 
			$row[] = $produk_hukum->nomor; 
			$row[] = $produk_hukum->tahun; 
			$row[] = $produk_hukum->nama_produk_hukum; 
			$row[] = $produk_hukum->didownload; 
			$row[] = tgl_indonesia2($produk_hukum->tgl_input); 
			$row[] = tgl_indonesia2($produk_hukum->tgl_update); 
						
			 if($produk_hukum->photo)
			 	$row[] = '<a href="'.base_url('upload/produk_hukum/'.$produk_hukum->photo).'" target="_blank">'.$produk_hukum->photo.'</a>';
			 else
			 	$row[] = '(No photo)';
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_produk_hukum('."'".$produk_hukum->id_produk_hukum."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_produk_hukum('."'".$produk_hukum->id_produk_hukum."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->produk_hukum->count_all(),
		"recordsFiltered" 	=> $this->produk_hukum->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->produk_hukum->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'id_jenis_produk_hukum'=> $this->input->post('id_jenis_produk_hukum', TRUE),
		'nomor'=> $this->input->post('nomor', TRUE),
		'tahun'=> $this->input->post('tahun', TRUE),
		'nama_produk_hukum'=> $this->input->post('nama_produk_hukum', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);		
		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
		 	$data['photo'] = $upload;
		}
		$insert = $this->produk_hukum->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'id_jenis_produk_hukum'=> $this->input->post('id_jenis_produk_hukum', TRUE),
		'nomor'=> $this->input->post('nomor', TRUE),
		'tahun'=> $this->input->post('tahun', TRUE),
		'nama_produk_hukum'=> $this->input->post('nama_produk_hukum', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);		
		
		if($this->input->post('remove_photo')) // if remove photo checked
		{
		 	if(file_exists('upload/produk_hukum/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
		 	{
				unlink('upload/produk_hukum/'.$this->input->post('remove_photo'));
		 		unlink('upload/produk_hukum/thumbs/'.$produk_hukum->photo);
		 	}
		 	$data['photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
			
		 	//delete file
		 	$produk_hukum = $this->produk_hukum->get_by_id($this->input->post('id'));
		 	if(file_exists('upload/produk_hukum/'.$produk_hukum->photo) && $produk_hukum->photo)
		 	{
		 		unlink('upload/produk_hukum/'.$produk_hukum->photo);
		 		unlink('upload/produk_hukum/thumbs/'.$produk_hukum->photo);
		 	}

		 	$data['photo'] = $upload;
		}
		$this->produk_hukum->update(array('id_produk_hukum' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{	
		$produk_hukum = $this->produk_hukum->get_by_id($id);
		if(file_exists('upload/produk_hukum/'.$produk_hukum->photo) && $produk_hukum->photo)
		{
		 	unlink('upload/produk_hukum/'.$produk_hukum->photo);
		 	unlink('upload/produk_hukum/thumbs/'.$produk_hukum->photo);
		}
		$this->produk_hukum->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->produk_hukum->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }	
	private function _do_upload()
	{
	 	$config['upload_path']    = 'upload/produk_hukum/';
        $config['allowed_types']  = 'pdf';
        $config['max_size']       = 10240; //set max size allowed in Kilobyte
        // $config['max_width']      = 1000; // set max width image allowed
        // $config['max_height']     = 1000; // set max height allowed
        $config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
	 		$data['error_string'][] = 'Upload error: '.$_FILES['photo']['type'].' '.$this->upload->display_errors('',''); //show ajax error
	 		$data['status'] = FALSE;
	 		echo json_encode($data);
	 		exit();
	 	}
	 	$file 		= $this->upload->data();
	 	$nama_file 	= $file['file_name'];					
									  
		
	 	// $config = array(
	 	// 	'source_image' 	=> $file['full_path'],
	 	// 	'new_image' 		=> './upload/produk_hukum/thumbs/',
	 	// 	'maintain_ration' => TRUE,
	 	// 	'width' 			=> 110,
	 	// 	'height' 			=> 82
	 	// );
							
	 	// $this->load->library('image_lib', $config);
	 	// $this->image_lib->resize();	
							
	 	return $nama_file;
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('id_jenis_produk_hukum')=='')
		{
			$data['inputerror'][] = 'id_jenis_produk_hukum';
			$data['error_string'][] = 'Jenis Produk Hukum  is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('nomor')=='')
		{
			$data['inputerror'][] = 'nomor';
			$data['error_string'][] = 'Nomor is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('tahun')=='')
		{
			$data['inputerror'][] = 'tahun';
			$data['error_string'][] = 'Tahun is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('nama_produk_hukum')=='')
		{
			$data['inputerror'][] = 'nama_produk_hukum';
			$data['error_string'][] = 'Nama Produk Hukum  is required';
			$data['status'] = FALSE;							
		}
		
		// if ($this->input->post('didownload')=='')
		// {
		// 	$data['inputerror'][] = 'didownload';
		// 	$data['error_string'][] = 'Didownload is required';
		// 	$data['status'] = FALSE;							
		// }

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Produk_hukum Class
/* End of file produk_hukum.php */
/* Location: ./sytem/application/controlers/produk_hukum.php */		
  