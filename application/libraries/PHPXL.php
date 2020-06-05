<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phpxl
{
	function __construct()
	{
		require_once APPPATH.'/libraries/PHPXL/PHPExcel.php';
		require_once APPPATH.'/libraries/PHPXL/PHPExcel/IOFactory.php';
	}
}