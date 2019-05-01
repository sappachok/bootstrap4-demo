<!DOCTYPE html>
<html>
<head>
    <title>Bootstrap Tutor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome5/css/all.min.css">
    <script src="<?php echo base_url(); ?>assets/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap4/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <a class="navbar-brand" href="<?php echo site_url(); ?>">Bootstrap 4 Tutor</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <!--
                <li class="nav-item">
                    <a class="nav-link" href="#load" data-toggle="modal" data-target="#myLoadModal">Load</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url("project/manage"); ?>">My Project</a>
                </li>    
            -->                  
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Project
						</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#create" data-toggle="modal" data-target="#createModal">New Project</a>
                        <a class="dropdown-item" href="#load" data-toggle="modal" data-target="#loadModal">Load</a>     
                        <div class="dropdown-divider"></div>               
                        <a class="dropdown-item" href="<?php echo site_url("project/manage"); ?>">Manage Project</a>
                    </div>
                </li>                           
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Examples
						</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item disabled" href="<?php echo site_url("examples/html"); ?>">HTML</a>                        
                        <a class="dropdown-item disabled" href="<?php echo site_url("examples/javascript"); ?>">Javascript</a>                        
                        <a class="dropdown-item" href="<?php echo site_url("examples/css"); ?>">CSS</a>                        
                        <a class="dropdown-item" href="<?php echo site_url("examples/bootstrap4"); ?>">Bootstrap 4</a>
                        <!--<div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">การติดต่อ</a>-->
                    </div>
                </li>                 
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url("project/boardcast"); ?>">Boardcast</a>
                </li>    
            </ul>
        </div>
    </nav>    
    <div class="modal" id="createModal">
            <div class="modal-dialog">
                <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create New Project</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
				<form name="createProjectForm">
                <div class="modal-body">
					<input type="text" name="new_project_name" class="form-control" placeholder="Please input your project name.">
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="create_project_btn" type="submit" class="btn btn-primary">Create</button>
                </div>
				</form>
                </div>
            </div>
    </div>
    <div class="modal" id="loadModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">My Project</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                <table class="table table-striped">
                <tr>
                    <th><b>title</b></th>
                </tr>
                <?php
                    foreach($project_library as $pname) {
                        echo "<tr>";
                        echo "<td><a href='".base_url()."?p=".$pname."'>".$pname."</a></td>";
                        echo "</tr>";
                    }
                ?>
                </table>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
    </div>
    {page_detail}
</body>
<script>
$(document).ready(function() {
	$('form[name=createProjectForm]').submit(function(e) {
		e.preventDefault();
		$.post('<?php echo site_url("project/save"); ?>', 
		{
			mode : 'add',
			project_name : $('input[name=new_project_name]').val(),
			template : 'blank',
			files : {
				template : '',
				html : '',
				css : '',
				js : '',
			}
		}, 
		function(data) {
			if(data=="folder exist") {
				alert("This folder name is exist.");
				return false;
			}

			//alert("Project save completed.");

			window.location = "<?php echo base_url()."?p="; ?>" + $('input[name=new_project_name]').val();

			console.log(data);
		});
	});
});
</script>
</html>