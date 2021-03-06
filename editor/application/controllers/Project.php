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
	public $zip_dir = "";
	public $boardcast_url = "https://ict.nstru.ac.th/bootstrap4-tutor/editor/index.php/boardcast";
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
			
			$base_dir = realpath(FCPATH);
			$this->zip_dir = $base_dir.'/zip';
			$this->project_dir = $base_dir.'/projects';
			//echo $this->project_dir;
			//$this->boardcast_file = $base_dir.'/'.$this->boardcast_file; 
			//echo $this->project_dir;
			//echo "config: ".$this->boardcast_file;
			if(!file_exists($this->project_dir)) {
				//echo $this->project_dir;
				if(!@mkdir($this->project_dir, 0775, true))
				{
					echo "Create Project Folder Failed!!<br>";
				} 
			}
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
		$project_template = "blank";

		if(!@$_GET["p"]) {		
			$project_name = "";//$this->get_projectname();
			$project_config = json_decode('{"name":"workshop-1","template":"'.$project_template.'"}');
			$data["mode"] = "add";
		} else {
			$project_name = $_GET["p"];			
			if(!@file_exists($this->project_dir."/".$_GET["p"]."/config.json")) {
				echo "Cannot load config file!!<br>";
				return false;
			}
			$project_config = json_decode(file_get_contents($this->project_dir."/".$_GET["p"]."/config.json"));
			$data["mode"] = "edit";
			//var_dump($project_config);
		}

		//$this->load->view('index', $data);
		if($project_name!="") {
			$data["project"] = $this->load_project($project_name);
			$data["project_config"] = $project_config;
			$data["project_name"] = $project_name;
			//$data["template"] = $this->load->view($this->template[$project_config->template], null, true);
			$data["template"] = "<code></code>";
			//$data["page_preview"] = "projects/".$project_name."/code.html";
			$data["page_preview"] = "projects/".$project_name."/prev.php";
			//$data["page_preview"] = "http://127.0.0.1/bootstrap4-tutor/editor/projects/".$project_name."/edit.php";

			$data["preview_template"] = $this->get_preview_template();
			$data["project_template"] = $project_config->template;

			$data["page_detail"] = $this->load->view('editor', $data, true);
		} else {

		}
		$this->view_template($data);
		
		$start = date("d-m-Y H:i:s");
		if(@$_GET["p"]) $this->set_boardcast(Array("project_id"=>str_replace("/","",$_GET["p"]),"template"=>$project_config->template,"start"=>$start));
	}

	function manage() {
		$data = Array();
		$map = directory_map($this->project_dir, 1);
		$myproject = Array();
		foreach($map as $dir => $val) {
			$myproject[] = str_replace("/","",$val);
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

	function get_source_dir() {
		$pid = $_POST["pid"];
		$path = (@$_POST["path"]) ? $_POST["path"] : "";
		//echo $pid;
		if($path) $target_dir = $this->project_dir."/".$pid."/".$path;
		else $target_dir = $this->project_dir."/".$pid;
		$map = directory_map($target_dir, 2);
		
		$data["project_name"] = $pid;
		$data["project_dir"] = $map;
		$data["src_path"] = $path;

		$data["reserved_file"] = Array(
			"code.html", "code.css", "code.js"
		);

		$data["hidden_file"] = Array( 
			"config.json", "prev.php"
		);

		$this->load->view("source-dir", $data);
	}

	function create_dir() {
		$pid = $_POST["pid"];
		$folder_name = $_POST["folder_name"];
		$path = (@$_POST["path"]) ? $_POST["path"] : "";

		if(!$path) $target_dir = $this->project_dir."/".$pid."/".$folder_name;
		else $target_dir = $this->project_dir."/".$pid."/".$path.$folder_name;

		if(file_exists($target_dir)) {
			echo "Folder has exists!!";
		} else {
			if(@mkdir($target_dir, 0775, true)) {
				echo "Create Folder Success!!";
			} else {
				echo "Create Folder Failed!!";
			}
		}
	}

	function rename_dir() {
		$pid = $_POST["pid"];
		$path = $_POST["path"];
		$fname = $_POST["fname"];
		$newname = $_POST["newname"];

		if($fname == "" || $newname=="") return false;

		$target_dir = $this->project_dir."/".$pid."/".$path."".$fname;
		$new = $this->project_dir."/".$pid."/".$path."".$newname;
		
		if(!file_exists($target_dir)) return false;
		//echo $target_dir." => ".$new;
		
		if(rename($target_dir, $new)) {
			echo "Rename Success!!";
		} else {
			echo "Rename Failed!!";
		}
	}

	function delete_dir() {
		$pid = $_POST["pid"];
		$path = $_POST["path"];
		$target_dir = $this->project_dir."/".$pid."/".$path;

		if(file_exists($target_dir)) {
			if(@rmdir($target_dir)) {
				echo "Folder Deleted!!";
			} else {
				echo "Delete Folder Failed!!";
			}			
		} else {
			echo "Folder has not exists!!";
		}
	}	

	function delete_file() {
		$pid = $_POST["pid"];
		$path = $_POST["path"];
		$target_dir = $this->project_dir."/".$pid."/".$path;

		if(file_exists($target_dir)) {
			if(@unlink($target_dir)) {
				echo "File Deleted!!";
			} else {
				echo "Delete File Failed!!";
			}			
		} else {
			echo "File has not exists!!";
		}
	}

	function get_template($name="bootstrap4") {
		if($name=="bootstrap4") $template = $this->load->view("bootstrap4_template", "", true);
		else if($name=="html5") $template = $this->load->view("html5_template", "", true);
		else $template="";

		echo $template;
	}

	function zip($pname) {
		if($pname == "") return false;

		$pname = path_decode($pname);
		//$project_name = "Test";
		$this->load->library('zip');

		if(!@file_exists($this->zip_dir)) mkdir($this->zip_dir, 0775, TRUE);

		$target_dir = $this->project_dir."/".$pname;

		if(!@file_exists($target_dir)) return false;

		

		$dir = $this->get_dir($target_dir);

		foreach($dir as $val) {
			//echo $val."<br>";
			if(is_dir($target_dir."/".$val)) {
				$this->zip->add_dir($val);
				//echo $val."<br>";
			} else {
				//echo $val."<br>";
				$data = file_get_contents($target_dir."/".$val);
				$this->zip->add_data($val, $data);
			}
		}

		$this->zip->download($pname.'-'.date('Ymd-His').'.zip');
	}

	function get_dir($target_dir, $root="", $data="") {
		$dir = directory_map($target_dir);
		$data = Array();
		foreach($dir as $name => $val) {
			if(!is_array($val)) {
				$path = $root.$val;
				//echo $path."<br>";
				if($val!="prev.php") $data[$path] = $path;
				//$data = file_get_contents($target_dir."/".$val);
				//$this->zip->add_data($val, $data);
			} else {
				$path = $root.$name;
				//echo $path."<br>";
				$data[$path] = $path;
				//$this->zip($name, $val);
				$data = array_merge($data, $this->get_dir($target_dir."/".$name, $path));
			}
			//$data = file_get_contents($target_dir."/".$val);
			//$this->zip->add_data($val, $data);
		}
		return $data;
	}

	function file_upload()
	{
		header('Content-Type: application/json');
		$file = $_FILES["file_data"];
		$pid = $_POST["pid"];
		$path = $_POST["path"];
		/*
		$pid = $_POST["pid"];
		$path = $_POST["path"];
		*/
		//$upload_path = $this->project_dir . "/" .$student_id."/".$project_id."/photo";
		//echo json_encode($file);
		
		if(!$path) $target_dir = $this->project_dir."/".$pid;
		else  $target_dir = $this->project_dir."/".$pid."/".$path;
		//$target_file = $target_dir."/".basename($file["name"]);
		$upload = $this->do_upload("file_data", $target_dir);
		//$result = $this->_upload_file($file["tmp_name"], $target_file, true);
		
		//$name = $file['name'];
		//$ext = pathinfo($name, PATHINFO_EXTENSION);			
		//$dest_file_name = str_replace(array(".","_"," "),"-",time().microtime()).".".$ext;

		//$result = $this->_upload_file_2("file_data", $dest_file_name, $upload_path, true, Array("resize"=>Array("width"=>1500, "height"=>1500)));

		//echo json_encode(Array("result"=>$result, "destination"=>$upload_path."/".$dest_file_name, "file"=>$file));
		echo json_encode(Array("result"=>true, "file"=>$file, "path"=>$target_dir, "upload"=>$upload));
	}

	public function do_upload($file, $path)
	{
		$config['upload_path']          = $path;
		$config['allowed_types']        = 'gif|jpg|png';

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($file))
		{
			$error = array('error' => $this->upload->display_errors());
			//$this->load->view('upload_form', $error);
			return $error;
		}
		else
		{
			$upload_data = $this->upload->data();
			$fname = $upload_data['file_name'];
			$fullpath = $upload_data['full_path'];
			$ch = chmod($fullpath, 0777);
			
			$data = array('upload_data' => $this->upload->data());
			return $data;
			//$this->load->view('upload_success', $data);
		}
	}

	function create() {
		$project_name = $_POST["project_name"];
		$template = $_POST["template"];

		if($_POST["mode"]=="add") {
			if(@file_exists($this->project_dir.'/'.$project_name)) {
				echo "folder exist";
				return false;
			}
		}

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

		$data = $this->load->view("project_templates/".$template."/code.html", null, true);
		if ( ! write_file($this->project_dir.'/'.$project_name.'/code.html', $data))
		{
				echo "Unable to write the html file\n";
		}
		else
		{
				echo "File html written!\n";
		}

		$data = $this->load->view("project_templates/".$template."/code.css", null, true);
		if ( ! write_file($this->project_dir.'/'.$project_name.'/code.css', $data))
		{
				echo "Unable to write the css file\n";
		}
		else
		{
				echo "File css written!\n";
		}
		
		$data = $this->load->view("project_templates/".$template."/code.js", null, true);
		if ( ! write_file($this->project_dir.'/'.$project_name.'/code.js', $data))
		{
				echo "Unable to write the js file\n";
		}
		else
		{
				echo "File js written!\n";
		}

		$data = "<?php include(\"../../preview.php\"); ?>";
		if ( ! write_file($this->project_dir.'/'.$project_name.'/prev.php', $data))
		{
				echo "Unable to write the prev file\n";
		}
		else
		{
				echo "File prev written!\n";
		}
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

		$data = "<?php include(\"../../preview.php\"); ?>";
		if ( ! write_file($this->project_dir.'/'.$project_name.'/prev.php', $data))
		{
				echo "Unable to write the prev file\n";
		}
		else
		{
				echo "File prev written!\n";
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
			"last_update"=>$now,
			"template"=>$config["template"]
			));
		//var_dump($data);

		if(!write_file($this->boardcast_file, $data, "wa"))
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
		//echo $this->boardcast_host."/".$this->boardcast_file;

		$project_config = file_get_contents($this->boardcast_url);
		//echo $this->boardcast_url;
		$project_data = unserialize(base64_decode($project_config));
		//var_dump($project_data);
		//return false;
		//return false;
		$data["mode"] = "read";
		$data["project_data"] = $project_data; //$this->load_project($load_project);
		$data["source_code"] = $project_data["source_code"]; //$this->load_project($load_project);
		//$data["project_config"] = $project_config;
		$data["project_name"] = $project_data["project_name"];
		$data["template"] = "<code></code>";
		$data["page_preview"] = $project_data["preview_url"];
		$data["preview_template"] = $this->get_preview_template(); //$this->load->view("preview_template", $data, true);
		$data["project_template"] = $project_data["template"];

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
		if($map)
		foreach($map as $dir => $val) {
			$myproject[] = str_replace("/","",$val);
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
