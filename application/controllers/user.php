<?php
class User extends CI_Controller
{
	function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('User_model', 'user', TRUE);
	  	$this->load->model('Instansi_model', 'instansi', TRUE);
	  	$this->load->model('User_level_model', 'user_level', TRUE);
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
				'title' 			=> 'User', 
				'main_view' 		=> 'user/user', 
				'form_view' 		=> 'user/user_form',
				'table_controller' 	=> 'user'
			);

			$instansis = $this->instansi->get_list_instansi();		
			$opt_instansi = array('' => 'Semua Instansi');
		    foreach ($instansis as $i => $v) {
		        $opt_instansi[$i] = $v;
		    }
		    $data['form_instansi'] = form_dropdown('id_instansi',$opt_instansi,'','id="id_instansi" class="form-control"');
			$data['form_instansi2'] = form_dropdown('id_instansi2',$opt_instansi,'','id="id_instansi2" class="form-control"');
			$data['options_instansi'] = $opt_instansi;

			$this->load->view('admin/template', $data);
		}	  	
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->user->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			$aktif = '<label class="label label-danger">Tidak</label>';
			if ($user->user_aktif==1)
				$aktif = '<label class="label label-success">Aktif</label>';
			$row = array();
			$row[] = $user->nama_instansi;
			$row[] = $user->user_full_name;
			$row[] = $user->user_name;
			$row[] = $user->user_level_name;
			$row[] = $aktif;
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" name="tombol_tambah" href="javascript:void(0)" title="Edit" onclick="edit_user('."'".$user->user_id."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
				  <a class="btn btn-sm btn-danger" name="tombol_hapus" href="javascript:void(0)" title="Hapus" onclick="delete_user('."'".$user->user_id."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->user->count_all(),
		"recordsFiltered" 	=> $this->user->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->user->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
		'id_instansi'=> $this->input->post('id_instansi', TRUE),
		'user_full_name'=> $this->input->post('user_full_name', TRUE),
		'user_email'=> $this->input->post('user_email', TRUE),
		'user_name'=> $this->input->post('user_name', TRUE),
		'user_level_id'=> $this->input->post('user_level_id', TRUE),
		'user_aktif'=> $this->input->post('user_aktif', TRUE),
		'user_date_entri' => date('Y-m-d')
		);
		
		if ($this->input->post('user_password', TRUE)!="")
			$data['user_password'] = md5($this->input->post('user_password', TRUE));
		
		if(!empty($_FILES['user_photo']['name']))
		{
			$upload = $this->_do_upload();
			$data['user_photo'] = $upload;
		}
		
		$insert = $this->user->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'id_instansi'=> $this->input->post('id_instansi', TRUE),
		'user_full_name'=> $this->input->post('user_full_name', TRUE),
		'user_email'=> $this->input->post('user_email', TRUE),
		'user_name'=> $this->input->post('user_name', TRUE),
		'user_level_id'=> $this->input->post('user_level_id', TRUE),
		'user_aktif'=> $this->input->post('user_aktif', TRUE),
		);
		
		if ($this->input->post('user_password', TRUE)!="")
			$data['user_password'] = md5($this->input->post('user_password', TRUE));

		if($this->input->post('remove_photo')) // if remove user_photo checked
		{
			if(file_exists('upload/user/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
			{
				unlink('upload/user/'.$this->input->post('remove_photo'));
				unlink('upload/user/thumbs/'.$user->user_photo);
			}
			$data['user_photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			
			//delete file
			$user = $this->user->get_by_id($this->input->post('id'));
			if(file_exists('upload/user/'.$user->user_photo) && $user->user_photo)
			{
				unlink('upload/user/'.$user->user_photo);
				unlink('upload/user/thumbs/'.$user->user_photo);
			}

			$data['user_photo'] = $upload;
		}
	
		$this->user->update(array('user_id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		
		//delete file
		$user = $this->user->get_by_id($id);
		if(file_exists('upload/user/'.$user->user_photo) && $user->user_photo)
		{
			unlink('upload/user/'.$user->user_photo);
			unlink('upload/user/thumbs/'.$user->user_photo);
		}
	
		$this->user->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	
	private function _do_upload()
	{
		$config['upload_path']    = 'upload/user/';
        $config['allowed_types']  = 'gif|jpg|png';
        $config['max_size']       = 1024; //set max size allowed in Kilobyte
        $config['max_width']      = 1024; // set max width image allowed
        $config['max_height']     = 1024; // set max height allowed
        $config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		$file 		= $this->upload->data();
		$nama_file 	= $file['file_name'];					
									  
		
		$config = array(
			'source_image' 		=> $file['full_path'],
			'new_image' 		=> './upload/user/thumbs/',
			'maintain_ration' 	=> TRUE,
			'width' 			=> 100,
			'height' 			=> 100
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

		if ($this->input->post('user_name')=='')
		{
			$data['inputerror'][] = 'user_name';
			$data['error_string'][] = 'User Name is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('user_level_id')=='')
		{
			$data['inputerror'][] = 'user_level_id';
			$data['error_string'][] = 'Please select Level';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('user_aktif')=='')
		{
			$data['inputerror'][] = 'user_aktif';
			$data['error_string'][] = 'Please choose Aktif';
			$data['status'] = FALSE;							
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}	

	function profile()
	{
		if ($this->session->userdata('login') != TRUE)
		{
		  redirect('login');
		}
		else
		{
			$this->load->helper('url');
			
			$agamas = $this->agama->get_list_agama();		
			$opt_agama = array('' => 'All Agama');
		    foreach ($agamas as $i => $v) {
		        $opt_agama[$i] = $v;
		    }

		    $data['form_agama'] = form_dropdown('id_agama',$opt_agama,'','id="id_agama" class="form-control"');
			$data['form_agama2'] = form_dropdown('id_agama2',$opt_agama,'','id="id_agama2" class="form-control"');
			$data['options_agama'] = $opt_agama;
			
			$jenis_pegawais = $this->jenis_pegawai->get_list_jenis_pegawai();		
			$opt_jenis_pegawai = array('' => 'All Jenis Pegawai ');
		    foreach ($jenis_pegawais as $i => $v) {
		        $opt_jenis_pegawai[$i] = $v;
		    }

		    $data['form_jenis_pegawai'] = form_dropdown('id_jenis_pegawai',$opt_jenis_pegawai,'','id="id_jenis_pegawai" class="form-control"');
			$data['form_jenis_pegawai2'] = form_dropdown('id_jenis_pegawai2',$opt_jenis_pegawai,'','id="id_jenis_pegawai2" class="form-control"');
			$data['options_jenis_pegawai'] = $opt_jenis_pegawai;
			
			$status_pegawais = $this->status_pegawai->get_list_status_pegawai();		
			$opt_status_pegawai = array('' => 'All Status Pegawai ');
		    foreach ($status_pegawais as $i => $v) {
		        $opt_status_pegawai[$i] = $v;
		    }

		    $data['form_status_pegawai'] = form_dropdown('id_status_pegawai',$opt_status_pegawai,'','id="id_status_pegawai" class="form-control"');
			$data['form_status_pegawai2'] = form_dropdown('id_status_pegawai2',$opt_status_pegawai,'','id="id_status_pegawai2" class="form-control"');
			$data['options_status_pegawai'] = $opt_status_pegawai;
		    
		    $data = array(
			'title' 	=> 'Profile', 
			'main_view' => 'pegawai/pegawai2', 
			'form_view' => 'pegawai/pegawai2_form',
			);

		    $this->load->view('admin/template', $data);
		}
	}	

	function ubah_password()
	{
		$id_pegawai = $this->input->post('id', TRUE);
		$password_lama = $this->input->post('password_lama', TRUE);
		$password_baru = $this->input->post('password_baru', TRUE);

		$validate = array();
		if ($password_lama=="") $validate[] = 'Masukkan password lama';
		if ($password_baru=="") $validate[] = 'Masukkan password baru';

		$jml_validate = count($validate);
		if ($jml_validate < 1)
		{
			$cek = $this->db->query("select * from user where id_pegawai='$id_pegawai' and user_password='".md5($password_lama)."'");
			if ($cek->num_rows() == 1)
			{
				$ubah_passwrod = $this->db->query("update user set user_password='".md5($password_baru)."' where id_pegawai='$id_pegawai'");
				print(json_encode(array("code" => 1, "message"=>"Password Berhasil Diubah.")));	
			}
			else
			{
				print(json_encode(array("code" => 0, "message"=>"Password salah.")));		
			}
		}
		else
		{
			print(json_encode(array("code" => 0, "message"=>"Password lama atau baru masih kosong.")));
		}
	}  

	function login_pegawai()
	{
		$user_name = $this->input->post('username', TRUE);
        $user_password = $this->input->post('password', TRUE);
        $imei = $this->input->post('imei', TRUE);
        $sim_serial = $this->input->post('sim_serial', TRUE);

        $error = array();
        if ($user_name=="")
        	$error[] = "";
        if ($user_password=="")
        	$error[] = "";

        if (count($error)==0) 
        {
	        $sql = "
	                select 
	                    user.*, 
	                    status_pegawai.nama_status_pegawai,
	                    instansi.nama_instansi,
	                    verifikator.verifikator,
	                    pegawai.nip, pegawai.nama_pegawai, pegawai.tempat_lahir, pegawai.tgl_lahir, pegawai.no_telp, pegawai.id_status_pegawai, pegawai.id_instansi, pegawai.photo, pegawai.aktif 
	                from 
	                    user 
	                    left join pegawai on user.id_pegawai=pegawai.id_pegawai 
	                    left join status_pegawai  on pegawai.id_status_pegawai=status_pegawai.id_status_pegawai 
	                    left join instansi on pegawai.id_instansi=instansi.id_instansi 
	                    left join verifikator on pegawai.id_pegawai=verifikator.id_pegawai 
	                where 
	                    user.user_name='$user_name' and user.user_password='".md5($user_password)."' and status='N'";

	        // if ($sim_serial!="") 
	        // 	$sql .= " and user.sim_serial_number='$sim_serial'";
	        // if ($imei!="")
	        // 	$sql .= " and user.device_id='$imei'";
	                    
	        $result = $this->db->query($sql);
	        
	        if ($result->num_rows() > 0){
	        	$pegawais=array();
	        	foreach ($result->result() as $row) {
	        	
	        		array_push($pegawais, 
                        array(
                            "id"                =>$row->id_pegawai,
                            "nip"               =>$row->nip,
                            "nama_pegawai"      =>$row->nama_pegawai,
                            "tempat_lahir"      =>$row->tempat_lahir,
                            "tgl_lahir"         =>$row->tgl_lahir,
                            "no_telp"           =>$row->no_telp,
                            "id_status_pegawai" =>$row->id_status_pegawai,
                            "nama_status_pegawai" =>$row->nama_status_pegawai,
                            "id_instansi"       =>$row->id_instansi,
                            "id_verifikator"    =>$row->verifikator,
                            "nama_instansi"     =>$row->nama_instansi,
                            "password"          =>$row->user_password,
                            "aktif"             =>$row->aktif,
                            "photo"             =>$row->photo
                        )
                    );
	        	}

	        	$sql_update = "update user set status='Y'";
	        	if ($sim_serial!="")
	        		$sql_update .= ", sim_serial_number='".$sim_serial."'";
	        	if ($imei!="")
	        		$sql_update .= ", device_id='".$imei."'";
	        	
	        	$this->db->query($sql_update." where id_pegawai='".$row->id_pegawai."' and sim_serial_number='' and device_id=''");
	        	
	        	print(json_encode(array('code' => 1, 'message' => 'Login Sukses', "result" => $pegawais)));
	        } else {
	        	print(json_encode(array('code' => 2, 'message' => 'Login Gagal. Kemungkinan anda sudah login di perangkat lain.')));
	        }
    	}
    	else
    	{
    		print(json_encode(array('code' => 2, 'message' => 'Login Gagal. Username dan atau password kosong.')));
    	}
	} 

	function cek_device()
	{
		$id = $this->input->post('id', TRUE);
		$device_id = $this->input->post('device_id', TRUE);
		$sim_serial_number = $this->input->post('sim_serial_number', TRUE);
		
		$required = array();

		if ($id=="") $required['id'];
		if ($sim_serial_number=="") $required['sim_serial_number'];
		if ($device_id=="") $required['device_id'];

		$jml_required = count($required);
		if ($jml_required < 1)
		{
			$qry = $this->db->query("select * from user where id_pegawai='$id' and device_id='$device_id' and sim_serial_number='$sim_serial_number'");

			if ($qry->num_rows() > 0)
			{
				print(json_encode(array("exists" => TRUE, "err_msg"=>"")));
			}
			else
			{
				print(json_encode(array("exists" => FALSE, "err_msg"=>"Data Not Found")));
			}	
		}
		else
		{
			print(json_encode(array("exists" => FALSE, "err_msg"=>"Required parameter Empty")));
		}
		
	}

	function pass_gen()
	{
		$qry = $this->db->query("select * from user");
		foreach ($qry->result() as $row) {
			$uniqid = uniqid();
			$this->db->query("update user set user_password='".md5($uniqid)."' where user_name='$row->user_name'");
			echo $row->user_name.' '.$uniqid.'<br />';
		}
	}
}
// END User Class
/* End of file user.php */
/* Location: ./sytem/application/controlers/user.php */		
