<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @author		Ifan Lumape
 * @Email		ifanlumape@yahoo.co.id
 * @Start		11 Agustus 2016
 * @Web			http://www.ifanlumape.com
 *
 */ 
class Login extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model', '', TRUE);
		$this->load->model('User_model', '', TRUE);
		//$this->load->driver('session');
	}

	function admin()
	{
		if($this->session->userdata('login') == TRUE)
		{
			redirect(base_url('dashboard'));
		}
		else
		{
			$this->load->view('login/login_view');
		}	
	}
	
	function index()
	{
		if($this->session->userdata('login') == TRUE)
		{
			redirect(base_url('login/admin'));
		}
		else
		{
			redirect(base_url());
		}
	}

	function process_login()
	{
		$this->load->model('Masyarakat_model', 'masyarakat', TRUE);


		$user_name = $this->input->post('username', TRUE);
		$user_password = $this->input->post('password', TRUE);

		if ($user_name!="" && $user_password!="")
		{
			if ($this->masyarakat->check_user($user_name, $user_password) == TRUE)
			{
				$user = $this->masyarakat->get_user_by_user_name($user_name);
				$data = array(
				'user_id' 			=> $user->id_masyarakat,
				'user_name' 		=> $user->nama_masyarakat, 
				'user_full_name'	=> $user->nama_masyarakat,
				'user_email'		=> $user->email,
				'user_level_id' 	=> 4,
				'user_level_name'	=> 'Masyarakat',
				'user_photo'		=> $user->photo,
				'user_date_entri'	=> date('Y-m-d'),
				'login'				=> TRUE,
				);
				$this->session->set_userdata($data);
				echo json_encode(array("status" => TRUE));
			}	
		}
	}
	
	function process_logout()
	{
		$this->session->sess_destroy();
		redirect(base_url(), 'refresh');
	}
}

// END Login Class
/* End of file login.php */
/* Location : ./system/appliction/controlers/login.php */