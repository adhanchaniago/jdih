<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @author		Ifan Lumape
 * @Email		fnnight@gmail.com.
 * @Start		22 April 2014
 * @Web			http://www.ifanlumape.com
 *
 */
class Coomingsoon extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		$this->load->view('coomingsoon');
	}
}
// END Coomingsoon Class
/* End of file coomingsoon.php */
/* Location: ./sytem/application/controlers/coomingsoon.php */