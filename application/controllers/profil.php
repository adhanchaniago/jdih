
<?php
class Profil extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Profil_model', 'profil', TRUE);

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
			'title' 	=> 'Data Profil', 
			'main_view' => 'profil/profil', 
			'form_view' => 'profil/profil_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->profil->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $profil) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$profil->id_profil.'">';
			$row[] = $no;
			$row[] = $profil->judul_profil; 
			$row[] = $profil->isi_profil; 
			$row[] = $profil->photo; 
			$row[] = tgl_indonesia2($profil->tgl_input); 
			$row[] = tgl_indonesia2($profil->tgl_update); 
						
			 if($profil->photo)
			 	$row[] = '<a href="'.base_url('upload/profil/thumbs/'.$profil->photo).'" target="_blank"><img src="'.base_url('upload/profil/thumbs/'.$profil->photo).'" class="img-responsive" /></a>';
			 else
			 	$row[] = '(No photo)';
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_profil('."'".$profil->id_profil."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_profil('."'".$profil->id_profil."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->profil->count_all(),
		"recordsFiltered" 	=> $this->profil->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->profil->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'judul_profil'=> $this->input->post('judul_profil', TRUE),
		'isi_profil'=> $this->input->post('isi_profil', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);		
		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
		 	$data['photo'] = $upload;
		}
		$insert = $this->profil->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'judul_profil'=> $this->input->post('judul_profil', TRUE),
		'isi_profil'=> $this->input->post('isi_profil', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);		
		
		if($this->input->post('remove_photo')) // if remove photo checked
		{
		 	if(file_exists('upload/profil/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
		 	{
				unlink('upload/profil/'.$this->input->post('remove_photo'));
		 		unlink('upload/profil/thumbs/'.$profil->photo);
		 	}
		 	$data['photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
			
		 	//delete file
		 	$profil = $this->profil->get_by_id($this->input->post('id'));
		 	if(file_exists('upload/profil/'.$profil->photo) && $profil->photo)
		 	{
		 		unlink('upload/profil/'.$profil->photo);
		 		unlink('upload/profil/thumbs/'.$profil->photo);
		 	}

		 	$data['photo'] = $upload;
		}
		$this->profil->update(array('id_profil' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{	
		$profil = $this->profil->get_by_id($id);
		if(file_exists('upload/profil/'.$profil->photo) && $profil->photo)
		{
		 	unlink('upload/profil/'.$profil->photo);
		 	unlink('upload/profil/thumbs/'.$profil->photo);
		}
		$this->profil->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->profil->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }	
	private function _do_upload()
	{
	 	$config['upload_path']    = 'upload/profil/';
        $config['allowed_types']  = 'gif|jpg|png';
        $config['max_size']       = 1024; //set max size allowed in Kilobyte
        $config['max_width']      = 1000; // set max width image allowed
        $config['max_height']     = 1000; // set max height allowed
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
	 		'new_image' 		=> './upload/profil/thumbs/',
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
		

		if ($this->input->post('judul_profil')=='')
		{
			$data['inputerror'][] = 'judul_profil';
			$data['error_string'][] = 'Judul Profil is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('isi_profil')=='')
		{
			$data['inputerror'][] = 'isi_profil';
			$data['error_string'][] = 'Isi Profil is required';
			$data['status'] = FALSE;							
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
// END Profil Class
/* End of file profil.php */
/* Location: ./sytem/application/controlers/profil.php */		
  