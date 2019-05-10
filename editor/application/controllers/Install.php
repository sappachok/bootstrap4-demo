<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    public $project_dir = "";

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->helper("url");
        $this->load->helper("path");
        $this->load->helper("file");
        
        $base_dir = realpath(FCPATH);        
        $this->project_dir = $base_dir.'/projects';
    }
        
    function index() {
        //echo $this->project_dir;
        //chmod($this->project_dir,0775);
    }
}