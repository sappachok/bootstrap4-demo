<!doctype html>
<html>
<head>
    <title>Bootstrap Tutor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome5/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
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
                        <a class="dropdown-item" href="#load" data-toggle="modal" data-target="#myLoadModal">Load</a>                        
                        <a class="dropdown-item" href="<?php echo site_url("project/manage"); ?>">Manage Project</a>
                    </div>
                </li>                           
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Tutorials
						</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Grids System</a>                        
                        <a class="dropdown-item" href="#">Table</a>
                        <a class="dropdown-item" href="#">Images</a>
                        <a class="dropdown-item" href="#">Form</a>
                        <a class="dropdown-item" href="#">Alert</a>
                        <a class="dropdown-item" href="#">Navbar</a>
                        <!--<div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">การติดต่อ</a>-->
                    </div>
                </li>                 
            </ul>          
            <!--
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="ค้นหา.." aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                            class="fas fa-search"></i> Search</button>
            </form>-->
        </div>
    </nav>    
    <div class="modal" id="myLoadModal">
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
    <br> 
    {page_detail}
</body>
</html>