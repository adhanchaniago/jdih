<?php
class Berita extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Berita_model', 'berita', TRUE);

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
			'title' 	=> 'Data Berita', 
			'main_view' => 'berita/berita', 
			'form_view' => 'berita/berita_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->berita->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $berita) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$berita->id_berita.'">';
			$row[] = $no;
			$row[] = $berita->judul_berita; 
			$row[] = $berita->isi_berita; 
			$row[] = $berita->user_full_name; 
			$row[] = $berita->dilihat; 
			$row[] = tgl_indonesia2($berita->tgl_input); 
			$row[] = tgl_indonesia2($berita->tgl_update); 
						
			 if($berita->photo)
			 	$row[] = '<a href="'.base_url('upload/berita/thumbs/'.$berita->photo).'" target="_blank"><img src="'.base_url('upload/berita/thumbs/'.$berita->photo).'" class="img-responsive" /></a>';
			 else
			 	$row[] = '(No photo)';
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_berita('."'".$berita->id_berita."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_berita('."'".$berita->id_berita."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->berita->count_all(),
		"recordsFiltered" 	=> $this->berita->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->berita->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'judul_berita'=> $this->input->post('judul_berita', TRUE),
		'isi_berita'=> $this->input->post('isi_berita', TRUE),
		'user_id'=> $this->session->userdata('user_id', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);		
		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
		 	$data['photo'] = $upload;
		}
		$insert = $this->berita->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'judul_berita'=> $this->input->post('judul_berita', TRUE),
		'isi_berita'=> $this->input->post('isi_berita', TRUE),
		'user_id'=> $this->session->userdata('user_id', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);		
		
		if($this->input->post('remove_photo')) // if remove photo checked
		{
		 	if(file_exists('upload/berita/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
		 	{
				unlink('upload/berita/'.$this->input->post('remove_photo'));
		 		unlink('upload/berita/thumbs/'.$berita->photo);
		 	}
		 	$data['photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
			
		 	//delete file
		 	$berita = $this->berita->get_by_id($this->input->post('id'));
		 	if(file_exists('upload/berita/'.$berita->photo) && $berita->photo)
		 	{
		 		unlink('upload/berita/'.$berita->photo);
		 		unlink('upload/berita/thumbs/'.$berita->photo);
		 	}

		 	$data['photo'] = $upload;
		}
		$this->berita->update(array('id_berita' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{	
		$berita = $this->berita->get_by_id($id);
		if(file_exists('upload/berita/'.$berita->photo) && $berita->photo)
		{
		 	unlink('upload/berita/'.$berita->photo);
		 	unlink('upload/berita/thumbs/'.$berita->photo);
		}
		$this->berita->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->berita->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }	
	private function _do_upload()
	{
	 	$config['upload_path']    = 'upload/berita/';
        $config['allowed_types']  = 'gif|jpg|png';
        $config['max_size']       = 2024; //set max size allowed in Kilobyte
        $config['max_width']      = 2000; // set max width image allowed
        $config['max_height']     = 2000; // set max height allowed
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
									  
		
	 	$config = array(
	 		'source_image' 	=> $file['full_path'],
	 		'new_image' 		=> './upload/berita/thumbs/',
	 		'maintain_ration' => TRUE,
	 		'width' 			=> 110,
	 		'height' 			=> 82
	 	);
							
	 	$this->load->library('image_lib', $config);
	 	$this->image_lib->resize();	
							
	 	return $nama_file;
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('judul_berita')=='')
		{
			$data['inputerror'][] = 'judul_berita';
			$data['error_string'][] = 'Judul Berita is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('isi_berita')=='')
		{
			$data['inputerror'][] = 'isi_berita';
			$data['error_string'][] = 'Isi Berita is required';
			$data['status'] = FALSE;							
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Berita Class
/* End of file berita.php */
/* Location: ./sytem/application/controlers/berita.php */		
  