<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$jenis_produk_hukums = $this->Jenis_produk_hukum_model->get_list_jenis_produk_hukum();		
		$opt_jenis_produk_hukum = array('' => '-- Pilih Jenis Produk Hukum --');
	    foreach ($jenis_produk_hukums as $i => $v) {
	        $opt_jenis_produk_hukum[$i] = $v;
	    }

	    	
		$data = array(
		'title' => 'Jaringan Data dan Informasi Hukum (JDIH) Bolaang Mongondow', 
		'main_view' => 'welcome',
		'opt_jenis_produk_hukum' => $opt_jenis_produk_hukum
		);

		$this->load->view('template', $data);
	}
}
