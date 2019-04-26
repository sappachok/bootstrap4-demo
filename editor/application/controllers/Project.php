<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

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

	public $template = Array(
		"html5" => "html5_template",
		"bootstrap4" => "bootstrap4_template",
	);

	public function __construct()
	{
			parent::__construct();
			// Your own constructor code
			$this->load->helper("url");
			$this->load->helper("path");
			$this->load->helper("file");
			$this->load->helper("form");			
			$this->load->helper("directory");
			
			$this->project_dir = realpath(FCPATH.'/projects');

			//echo $this->project_dir;
	}

	public function index()
	{
		$data = Array();
		$project_template = "bootstrap4";

		if(!@$_GET["p"]) {
			$load_project = "Untitled";
			$project_config = json_decode('{"name":"workshop-1","template":"'.$project_template.'"}');
		} else {
			$load_project = $_GET["p"];			
			$project_config = json_decode(file_get_contents($this->project_dir."/".$_GET["p"]."/config.json"));			
			//var_dump($project_config);
		}		
		
		$data["project"] = $this->load_project($load_project);
		$data["project_config"] = $project_config;
		$data["project_name"] = $load_project;
		$data["template"] = $this->load->view($this->template[$project_config->template], null, true);
		$data["preview_template"] = $this->load->view("preview_template", null, true);
		$data["project_template"] = $project_config->template;

		$map = directory_map($this->project_dir, 1);
		$myproject = Array();
		foreach($map as $dir => $val) {
			$myproject[] = str_replace("\\","",$val);
		}
		
		
		$data["project_library"] = $myproject;

		$this->load->view('index', $data);
	}

	function load_project($name) {
		$project = Array(
			"html" => @file_get_contents($this->project_dir."/".$name."/code.html"),
			"css" => @file_get_contents($this->project_dir."/".$name."/code.css"),
			"js" => @file_get_contents($this->project_dir."/".$name."/code.js")
		);

		return $project;
	}

	function get_template($name="bootstrap4") {
		if($name=="bootstrap4") $template = $this->load->view("bootstrap4_template", "", true);
		else if($name=="html5") $template = $this->load->view("html5_template", "", true);
		else $template="";

		echo $template;
	}

	function save() {
		//var_dump($_POST);
		$project_name = $_POST["project_name"];
		if(!@file_exists($this->project_dir.'/'.$project_name)) mkdir($this->project_dir.'/'.$project_name, 0775, TRUE);

		$config = json_encode(Array(
			"name" => $project_name, 
			"template" => $_POST["template"]
		));

		if ( ! write_file($this->project_dir.'/'.$project_name.'/config.json', $config))
		{
				echo "Unable to write the config file\n";
		}
		else
		{
				echo "File html written!\n";
		}

		$data = $_POST["files"]["html"];
		if ( ! write_file($this->project_dir.'/'.$project_name.'/code.html', $data))
		{
				echo "Unable to write the html file\n";
		}
		else
		{
				echo "File html written!\n";
		}

		$data = $_POST["files"]["css"];
		if ( ! write_file($this->project_dir.'/'.$project_name.'/code.css', $data))
		{
				echo "Unable to write the css file\n";
		}
		else
		{
				echo "File css written!\n";
		}
		
		$data = $_POST["files"]["js"];
		if ( ! write_file($this->project_dir.'/'.$project_name.'/code.js', $data))
		{
				echo "Unable to write the js file\n";
		}
		else
		{
				echo "File js written!\n";
		}		
	}
}
