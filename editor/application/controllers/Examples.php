<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examples extends CI_Controller {

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

	public $edit_mode = false;

	public function __construct()
	{
			parent::__construct();
			// Your own constructor code
			$this->load->helper("url");
			$this->load->helper("path");
			$this->load->helper("file");
			$this->load->helper("form");			
			$this->load->helper("directory");
			
			$this->project_dir = realpath(FCPATH.'/examples');
			$this->boardcast_file = realpath(FCPATH.'/'.$this->boardcast_file); 
			//echo $this->project_dir;
			//echo "config: ".$this->boardcast_file;
			if(@$_GET["edit"]==true) $this->edit_mode = true;
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
	
	public function index() {

	}

	public function show($cid="", $pid="", $data="")
	{
		$project_template = "bootstrap4";
		$example_path = $cid."/".$pid;
		if(!@$example_path) {		
			$load_project = $this->get_projectname();
			$project_config = json_decode('{"name":"workshop-1","template":"'.$project_template.'"}');
			$data["mode"] = "add";
		} else {
			$load_project = $example_path;			
			$project_config = json_decode(file_get_contents($this->project_dir."/".$example_path."/config.json"));
			$data["mode"] = "edit";
			//var_dump($project_config);
		}
		
		$data["project"] = $this->load_project($load_project);
		$data["project_config"] = $project_config;
		$data["project_name"] = $load_project;
		$data["project_path"] = $example_path;
		$data["template"] = $this->load->view($this->template[$project_config->template], null, true);
		$data["preview_template"] = $this->get_preview_template();
		$data["project_template"] = $project_config->template;
		$data["edit_mode"] = $this->edit_mode;

		//$this->load->view('index', $data);
		$data["page_detail"] = $this->load->view('examples', $data, true);
		$this->view_template($data);
		
		$start = date("d-m-Y H:i:s");
		if(@$_GET["p"]) $this->set_boardcast(Array("project_id"=>$_GET["p"],"start"=>$start));
	}

	function css($name="") {
		$data = Array();
		if($name=="") {
			$topic = Array(
				"CSS Example" => "css/css-example",
				);
			$data["title"] = "CSS";
			$data["back"] = site_url("examples/css");
			$data["topic"] = $topic;
			$data["page_detail"] = $this->load->view('examples/list', $data, true);
			$this->view_template($data);
		} else {
			$data["title"] = "CSS";
			$data["back"] = site_url("examples/css");
			$this->show("css", $name, $data);
		}
	}

	function bootstrap4($name="") {
		$data = Array();
		if($name=="") {
			$topic = Array(
				"Grids system" => "bootstrap4/grids",
				"Tables" => "bootstrap4/tables",
				"Colors" => "bootstrap4/colors",
				"Images" => "bootstrap4/images",
				"Alerts" => "bootstrap4/alerts",
				"Bedges" => "bootstrap4/bedges",
				"Cards" => "bootstrap4/cards",
				);
			$data["title"] = "Boostrap 4";
			$data["back"] = site_url("examples/css");
			$data["topic"] = $topic;
			$data["page_detail"] = $this->load->view('examples/list', $data, true);
			$this->view_template($data);
		} else {
			$data["title"] = "Bootstrap 4";
			$data["back"] = site_url("examples/bootstrap4");
			$this->show("bootstrap4", $name, $data);
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

	function save() {
		$project_name = $_POST["project_name"];
		$project_path = $_POST["project_path"];
		if($project_path=="") return false;

		if($_POST["mode"]=="add") {
			if(@file_exists($this->project_dir.'/'.$project_path)) {
				echo "folder exist";
				return false;
			}
		}
		//var_dump($_POST);

		if(!@file_exists($this->project_dir.'/'.$project_path)) mkdir($this->project_dir.'/'.$project_path, 0775, TRUE);

		$config = json_encode(Array(
			"name" => $project_name,
			"template" => $_POST["template"]
		));

		if ( ! write_file($this->project_dir.'/'.$project_path.'/config.json', $config))
		{
				echo "Unable to write the config file\n";
		}
		else
		{
				echo "File html written!\n";
		}

		$data = $_POST["files"]["html"];
		if ( ! write_file($this->project_dir.'/'.$project_path.'/code.html', $data))
		{
				echo "Unable to write the html file\n";
		}
		else
		{
				echo "File html written!\n";
		}

		$data = $_POST["files"]["css"];
		if ( ! write_file($this->project_dir.'/'.$project_path.'/code.css', $data))
		{
				echo "Unable to write the css file\n";
		}
		else
		{
				echo "File css written!\n";
		}
		
		$data = $_POST["files"]["js"];
		if ( ! write_file($this->project_dir.'/'.$project_path.'/code.js', $data))
		{
				echo "Unable to write the js file\n";
		}
		else
		{
				echo "File js written!\n";
		}		
	}

	function get_template($name="bootstrap4") {
		if($name=="bootstrap4") $template = $this->load->view("bootstrap4_template", "", true);
		else if($name=="html5") $template = $this->load->view("html5_template", "", true);
		else $template="";

		echo $template;
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

	function message($text="", $class="") {
		$data["message"] = "<div class='".$class."'>".$text."</div>";
		return $this->load->view("message", $data, true);
	}
}
