<!-- bootstrap 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
<link href="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/css/bootstrap3-glyphicon.css" media="all" rel="stylesheet" type="text/css" />

<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you 
    wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. 
    This must be loaded before fileinput.min.js -->
<script src="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for 
    HTML files. This must be loaded before fileinput.min.js -->
<script src="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- popper.min.js below is needed if you use bootstrap 4.x. You can also use the bootstrap js 
   3.3.x versions without popper.min.js. -->
<!-- the main fileinput plugin file -->
<script src="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/js/fileinput.min.js"></script>
<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
<script src="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/themes/fas/theme.js"></script>
<!-- optionally if you need translation for your language then include  locale file as mentioned below -->
<script src="<?php echo base_url("assets/plugins"); ?>/bootstrap-fileinput/js/locales/uk.js"></script>
<style>
.cur-path {
    display: block;
    padding: 10px 0px;
    color: gray;
}
</style>
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link  active" href="#directory" role="tab" data-toggle="tab" aria-selected="true">Directory</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#uploader" role="tab" data-toggle="tab">Upload</a>
  </li>
</ul>
<input type="hidden" name="project_name" value="<?php echo $project_name; ?>">
<input type="hidden" name="src_path" value="<?php echo $src_path; ?>">

<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="directory">
    <div class="row">
        <div class="col-12"> 
        <p>
            <form id="createDirForm" class="form-group">
            <div class="input-group  mb-3">
                <input type="text" name="new_folder" class="form-control" value="">
                <div class="input-group-btn input-group-append">
                    <button id="createDirBtn" type="submit" class="btn btn-primary"><i class="fa fa-folder"></i> Create Directory</button>
                </div>
            </div>
            </form>
        </p>
        </div>
        <div class="col-12">
        <p>
            <button id="srcBackBtn" type="button" class="btn btn-light" data-path="<?php echo $src_path; ?>"><i class="fas fa-long-arrow-alt-left"></i> Back</button>
            <span class="cur-path"><b>Path:</b> <?php echo $src_path; ?></span>
        </p>
        <p>
        <table class="table table-hover">
        <thead>
            <tr>
                <th>รายการ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            if($project_dir)
            foreach($project_dir as $name => $val) {
                if(is_array($val)) {
                    echo "<tr data-path='".$name."'>";
                    echo "<td><a href='#rename' data-path='".$src_path.$name."' class='goto-dir'><i class='far fa-folder'></i> ".$name."</a></td>";
                    echo "<td class='text-right'><a href='#rename' class='btn btn-default rename-folder'>Rename</a> <a href='#delete' class='btn btn-default delete-folder'>Delete</a></td>";
                    echo "</tr>"; 
                }
            }
        ?>
        <?php
            if($project_dir)
            foreach($project_dir as $name => $val) {
                if(!is_array($val)) {
                    echo "<tr>";
                    echo "<td><i class='far fa-file'></i> ".$val."</td>";
                    echo "<td></td>";
                    echo "</tr>";
                }
            }
        ?>
        </tbody>
        </table>
        </p>
        </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="uploader">
    <div class="row">
        <div class="col-12"> 
        <p>
            <form method="post" enctype="multipart/form-data">
            <input id="input-id" type="file" class="file" data-preview-file-type="text" >
            </form>
        </p>
        </div>
    </div>    
  </div>  
</div>
<script>
jQuery(document).ready(function() {
    var projectname = jQuery("input[name=project_name]").val();

    jQuery("#srcBackBtn").click(function() {
        datapath = jQuery(this).attr("data-path");
        //alert(datapath);
        //datapath = datapath.substr(0, datapath.length-1);
        ex = datapath.split("/");
        //console.log(datapath);
        goto_path = "";
        for(i=0; i<ex.length-2; i++) {
            goto_path += ex[i]+"/";
        }
        console.log(goto_path);
        source_dir(goto_path);
    });

    jQuery(".goto-dir").click(function() {
        datapath = jQuery(this).attr("data-path");
        source_dir(datapath);
    });

    jQuery("#input-id").fileinput();

    jQuery("#createDirForm").submit(function(e) {
        e.preventDefault();
        
        var curpath = jQuery("input[name=src_path]").val();
        
        var new_folder = jQuery("input[name=new_folder]").val();
        jQuery.post('<?php echo site_url("project/create_dir"); ?>', { pid : projectname, path : curpath, folder_name : new_folder }, function(data) {
            alert(data);
            source_dir(curpath);
        });        
    });

    jQuery(".delete-folder").click(function() {
        var curpath = jQuery("input[name=src_path]").val();
        if(window.confirm("Are you sure delete this folder.")) {
            var path = jQuery(this).closest("tr").attr("data-path");
            if(curpath) path = curpath+path;
            
            if(path) {
                jQuery.post('<?php echo site_url("project/delete_dir"); ?>', { pid : projectname, path: path }, function(data) {
                    alert(data);
                    source_dir(curpath);
                });
            }
        }
    });

    function source_dir(path)
    {
        //alert(path);
        var srcBodyModal = jQuery("#sourceModal").find(".modal-body");
        //srcBodyModal.html("Loading...");
        jQuery.post('<?php echo site_url("project/get_source_dir"); ?>', { pid : projectname, path : path }, function(data) {
            srcBodyModal.html(data);
        });
    }
});
</script>