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
			$this->load->helper("form");			
			$this->load->helper("directory");
			
			$this->project_dir = realpath(FCPATH.'/projects');
			$this->boardcast_file = realpath(FCPATH.'\\'.$this->boardcast_file); 
			//echo $this->project_dir;
			//echo "config: ".$this->boardcast_file;
	}

	public function get_projectname()
	{
		$name = "Untitled";

		$map = directory_map($this->project_dir, 1);
		$old = Array();
		foreach($map as $pname) {
			$pname = str_replace("\\","",$pname);
			if(strpos($pname, $name)!==false) {
				if(is_numeric(trim(str_replace($name,"",$pname)))) $old[$pname] = trim(str_replace($name,"",$pname));
			}
		}
		if(@max($old)) $newname = $name." ".(max($old)+1);
		else $newname = $name." 1";

		return $newname;
	}

	public function index()
	{
		$data = Array();
		$project_template = "bootstrap4";

		if(!@$_GET["p"]) {		
			$load_project = $this->get_projectname();
			$project_config = json_decode('{"name":"workshop-1","template":"'.$project_template.'"}');
			$data["mode"] = "add";
		} else {
			$load_project = $_GET["p"];			
			$project_config = json_decode(file_get_contents($this->project_dir."/".$_GET["p"]."/config.json"));
			$data["mode"] = "edit";
			//var_dump($project_config);
		}
		
		$data["project"] = $this->load_project($load_project);
		$data["project_config"] = $project_config;
		$data["project_name"] = $load_project;
		$data["template"] = $this->load->view($this->template[$project_config->template], null, true);
		$data["preview_template"] = $this->get_preview_template();
		$data["project_template"] = $project_config->template;

		//$this->load->view('index', $data);
		$data["page_detail"] = $this->load->view('editor', $data, true);
		$this->view_template($data);
		
		$start = date("d-m-Y H:i:s");
		if(@$_GET["p"]) $this->set_boardcast(Array("project_id"=>$_GET["p"],"start"=>$start));
	}

	function manage() {
		$data = Array();
		$map = directory_map($this->project_dir, 1);
		$myproject = Array();
		foreach($map as $dir => $val) {
			$myproject[] = str_replace("\\","",$val);
		}
				
		$data["project_library"] = $myproject;		
		
		$data["page_detail"] = $this->load->view('manage', $data, true);
		$this->view_template($data);
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
		$project_name = $_POST["project_name"];

		if($_POST["mode"]=="add") {
			if(@file_exists($this->project_dir.'/'.$project_name)) {
				echo "folder exist";
				return false;
			}
		}
		//var_dump($_POST);

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

	function rename() {

		//var_dump($_POST);
		//rename($oldDir.$file, $newDir.$file);
		$pid = $_POST["pid"];
		$pname = $_POST["pname"];
		$newname = $_POST["newname"];

		$project_folder = $this->project_dir.'/'.$pname;
		if(file_exists($project_folder)) {
			rename($this->project_dir.'/'.$pname, $this->project_dir.'/'.$newname);
		}
	}

	function delete() {
		if(!@$_POST["pname"]) return false;

		$project_folder = $this->project_dir.'/'.$_POST["pname"];

		if(file_exists($project_folder)) {
			delete_files($project_folder, true);
			rmdir($project_folder);
		}
	}

	function set_boardcast($config) {
		$now = date("d-m-Y H:i:s");
		$data = json_encode(Array(
			"project_id"=>$config["project_id"],
			"start"=>$config["project_id"],
			"last_update"=>$now
			));
		//var_dump($data);

		if(!write_file($this->boardcast_file, $data))
		{
				echo "Unable to write the boardcast config\n";
		}	
	}

	function get_boardcast() {
		$config = file_get_contents($this->boardcast_file);
		return $config;
	}

	function boardcast() {
		$data = Array();
		$project_template = "bootstrap4";
		$boardcast_config = json_decode($this->get_boardcast());
		if(!@$boardcast_config) return false;

		$project_id = $boardcast_config->project_id;

		$load_project = $project_id;
		$project_config = json_decode(file_get_contents($this->project_dir."/".$project_id."/config.json"));

		$data["mode"] = "read";
		$data["project"] = $this->load_project($load_project);
		$data["project_config"] = $project_config;
		$data["project_name"] = $load_project;
		$data["template"] = $this->load->view($this->template[$project_config->template], null, true);
		$data["preview_template"] = $this->get_preview_template(); //$this->load->view("preview_template", $data, true);
		$data["project_template"] = $project_config->template;

		//$this->load->view('index', $data);
		
		$data["page_detail"] = $this->load->view('boardcast', $data, true);
		$this->view_template($data);
	}

	function get_preview_template() {
		return $this->load->view("preview_template", null, true);
	}

	function view_template($_data) {

		$map = directory_map($this->project_dir, 1);
		$myproject = Array();
		foreach($map as $dir => $val) {
			$myproject[] = str_replace("\\","",$val);
		}
				
		$this->load->library('parser');
		$data = array(
			'page_title' => @$_data["page_title"],
			'page_detail' => @$_data["page_detail"]
		);
		
		$data["project_library"] = $myproject;

		$this->parser->parse('template', $data);
	}
}
