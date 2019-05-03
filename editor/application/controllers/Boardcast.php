<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boardcast extends CI_Controller {

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
	public $zip_dir = "";
	public $boardcast_host = "https://ict.nstru.ac.th/bootstrap4-tutor/editor";
	public $boardcast_file = "boardcast.json";

	public $template = Array(
		"html5" => "html5_template",
		"bootstrap4" => "bootstrap4_template",
		"blank" => "blank_template",
    );

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->helper("url");
        $this->load->helper("path");
        $this->load->helper("file");
        
        $base_dir = realpath(FCPATH);        
        $this->project_dir = $base_dir.'/projects';
        //echo $this->project_dir;
        $this->boardcast_file = $base_dir.'/'.$this->boardcast_file;
    }
        
    function index() {
		$config = file_get_contents($this->boardcast_file);
		//echo $this->boardcast_file;
        $project_config = json_decode($config);
        //echo $config;
        //var_dump($config);
		$project_id = $project_config->project_id;
		if($project_id) {
			$project_code = $this->load_project($project_id);
			//echo json_encode($project_data);
			$project_data = Array(
				"project_name" => $project_id,
				"template" => $project_config->template,
				"source_code" => $project_code,
				"preview_url" => base_url()."projects/".$project_id."/code.html"
			);
			echo base64_encode(serialize($project_data));
		}
    }

	function load_project($name) {
		$project = Array(
			"html" => @file_get_contents($this->project_dir."/".$name."/code.html"),
			"css" => @file_get_contents($this->project_dir."/".$name."/code.css"),
			"js" => @file_get_contents($this->project_dir."/".$name."/code.js")
		);

		return $project;
	}    
    
}
