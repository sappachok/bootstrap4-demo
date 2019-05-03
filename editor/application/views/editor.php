<link rel="stylesheet" href="<?php echo base_url(); ?>/../../codemirror/lib/codemirror.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/../../codemirror/addon/hint/show-hint.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/../../codemirror/theme/duotone-dark.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/../../codemirror/theme/bespin.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/../../codemirror/theme/base16-dark.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/../../codemirror/theme/3024-night.css ">

<script src="<?php echo base_url(); ?>/../../codemirror/lib/codemirror-custom.js"></script>
<script src="<?php echo base_url(); ?>/../../codemirror/addon/hint/show-hint.js"></script>
<script src="<?php echo base_url(); ?>/../../codemirror/addon/hint/xml-hint.js"></script>
<script src="<?php echo base_url(); ?>/../../codemirror/addon/hint/html-hint.js"></script>
<script src="<?php echo base_url(); ?>/../../codemirror/mode/xml/xml.js"></script>
<script src="<?php echo base_url(); ?>/../../codemirror/mode/javascript/javascript.js"></script>
<script src="<?php echo base_url(); ?>/../../codemirror/mode/css/css.js"></script>

<script src="<?php echo base_url(); ?>/../../codemirror/mode/htmlmixed/htmlmixed.js"></script>
<style>
body {
    min-width: 1200px;
}
.CodeMirror {
    border-top: 1px solid #888;
    border-bottom: 1px solid #888;
    height: 480px;
    background-color: #eeeeee;
    font-size: 14px;
}

#main {
    border: 1px solid #888;
    margin-left: 420px;
    height: 480px;
    /*width: 75%;*/
}

#sidebar {
    width: 420px;
    float: left;
    height: 480px;
}

#split-bar {
    height: 100%;
    float: right;
    width: 6px;
    cursor: col-resize;
}

.filetype-menu {
    display: block;
}

.filetype-menu li {
    float: left;
    list-style-type: none;
    cursor: pointer;
    padding: 5px 10px;
    margin-right: 5px;
    border-top: 1px solid #aaaaaa;
    border-left: 1px solid #aaaaaa;
    border-right: 1px solid #aaaaaa;
}

.filetype-menu li.active {
    background: #343a40;
    color: #ffffff;
}

#preview_code {
    height: 500px;
}

fieldset.form-group {
    padding:20px;
    border:1px solid #dddddd;
}
</style>


<div class="container-fluid">
<div class="row">
    <div class="col-6"> 
    <p>
        <div class="input-group">
            <input type="hidden" name="mode" value="<?php echo $mode; ?>">
        <?php if($mode=="add") { ?>
            <input type="text" name="project_name" class="form-control" value="<?php echo $project_name; ?>">
        <?php } else if($mode=="edit") { ?>
            <input type="text" name="project_name" class="form-control" value="<?php echo $project_name; ?>" readonly>
        <?php } ?>
            <div class="input-group-append">
                <button id="saveBtn" type="button" class="btn btn-primary"><i class="fa fa-save"></i> Save Project</button>
                <!--<button id="optionBtn" type="button" class="btn btn-dark" data-toggle="collapse" data-target="#setting"><i class="fas fa-ellipsis-v"></i> Options</button>-->
            </div>
        </div>
    </p>
    </div>
    <div class="col-6">
    <p>
        <div class="form-group">
        <button id="runBtn" type="button" class="btn btn-success"><i class="fas fa-play-circle"></i> Run</button>
        <a href="<?php echo site_url("project/zip"); ?>/<?php echo path_encode($project_name); ?>" class="btn btn-dark" target="_blank"><i class="fas fa-download"></i> Download</a>

		<!--<button type="button" class="btn btn-dark"><i class="fas fa-download"></i> Download</button>-->
        <!--<button id="viewcodeBtn" type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fas fa-code"></i> View Code</button>-->
        <!-- Button to Open the Modal -->

        <!-- The Modal -->
        <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Source Code Preview</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <iframe id="preview_code" frameborder="0" style="width:100%; height:520px;"></iframe>
                <textarea id="preview_template" style="display:none;"><?php echo $preview_template; ?></textarea>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
        </div>

        </div>
    </p>
    </div>
</div>
<div id="setting" class="collapse row card">
    <div class="card-body">
    <div class="col-4">
    <p>    
        <label>Template</label>
        <?php 
            echo form_dropdown("project_template", 
            Array(
                "bootstrap4"=>"Bootstrap 4",
                "html5"=>"HTML 5",
                "blank"=>"Blank",
            ),
            $project_template, "class='form-control'");             
        ?>
    <p>
    </div>
    </div>
</div>
    <p>
        <div class="filetype-menu float-left">
            <ul>
                <li id="html_code_sheet" class="code-sheet active">HTML</li>
                <li id="css_code_sheet" class="code-sheet">CSS</li>
                <li id="js_code_sheet" class="code-sheet">JS</li>
            </ul>
        </div>
        <div class="filetype-menu float-right">
            <ul>
                <li id="invisible" class="size-view"><i class="fas fa-ban"></i></li>
                <li id="desktop" class="size-view active"><i class="fas fa-desktop"></i></li>
                <li id="tablet" class="size-view"><i class="fas fa-tablet-alt"></i></li>
                <li id="mobile" class="size-view"><i class="fas fa-mobile-alt"></i></li>
            </ul>
        </div>
    </p>
    <div class="clearfix"></div>
    <div id="sidebar">
        <div id="split-bar"></div>
        <form>
            <div id="test"></div>
            <textarea id="htmlcode" name="htmlcode" style="display:none"><?php echo ($project["html"]) ? $project["html"] : "" ?></textarea>
            <textarea id="csscode" name="csscode" style="display:none"><?php echo ($project["css"]) ? $project["css"] : "" ?></textarea>
            <textarea id="jscode" name="jscode" style="display:none"><?php echo ($project["js"]) ? $project["js"] : "" ?></textarea>
            <textarea id="template" name="template" style="display:none"><?php echo $template; ?></textarea>
        </form>
    </div>
    <div id="main" style="display:none;"><iframe id="preview" src="<?=$page_preview?>" frameborder="0" style="width:100%; height:100%;" scrolling="no"></iframe></div>
</div>
<script>
    var htmleditor = "";
    var csseditor = "";
    var jseditor = "";

    function update_preview(code) {
        var previewcodeFrame = document.getElementById('preview_code');
        var previewcode = previewcodeFrame.contentDocument || previewcodeFrame.contentWindow.document;

        var template = jQuery('#preview_template').val();
        template = template.replace("{code}", code);
        previewcode.open();
        previewcode.write(template);
        previewcode.close();
    }

    function indent_tab(code, num) {
        rows = code.split("\n");

        indentcode = "";
        jQuery.each(rows, function(i, k) {
            indent = "";
            for(t=1; t<=num; t++) {
                indent += "\t";
            }
            indentcode += indent + k + "\n"                
        })

        return indentcode;
    }

	function get_code() {
        var body = "";
        var css = "";
        var js = "";

        if (htmleditor) body = htmleditor.getValue();
        if (csseditor) css = csseditor.getValue();
        if (jseditor) js = jseditor.getValue();

        var template = jQuery("#template").val();
		template = template.replace("<code></code>", body);

		return template;
	}

    function run_update() {
        
        var body = "";
        var css = "";
        var js = "";

        if (htmleditor) body = htmleditor.getValue();
        if (csseditor) css = csseditor.getValue();
        if (jseditor) js = jseditor.getValue();

        var previewFrame = document.getElementById('preview');
        var preview = previewFrame.contentDocument || previewFrame.contentWindow.document;

		var dt = new Date()
		var page_preview = "<?php echo $page_preview; ?>?time="+dt.getTime();

		jQuery("#preview").attr("src", page_preview);
		//alert("reload");
		//preview.location.reload();
        var template = jQuery("#template").val();
		//jQuery('#preview').find("body").html(template);
		
        //template = template.replace("<body></body>", "<body>\n" + indent_tab(body, 1) + "</body>");
        //template = template.replace("<style></style>", "<style>\n" + indent_tab(css, 1) + "</style>");
        //template = template.replace("<script></"+"script>", "<script language='javascript'>\n" + indent_tab(js, 1) + "</"+"script>");
		template = template.replace("<code></code>", body);

		//document.getElementById('preview').test();
        //console.log(template);
		//jQuery("#preview").contents().find('body').html(template);
		//var context = jQuery('iframe')[1].contentWindow.document;
		//var jQuerybody = jQuery('body', context);
		//jQuerybody.html(template);
		//preview.srcdoc="Hello!!";
        //preview.open("text/html", "replace");
        //preview.write(template);
        //preview.close();

        update_preview(template);
    }

    var loader = 1;
    function load_project(name) {
        initHtmlEditor();
        initCssEditor();
        initJsEditor();
    }

    var extraKeyOptions = {
        "Ctrl-Space": "autocomplete",
        "Ctrl-S": function(instance) { jQuery("#saveBtn").click(); },
        "Ctrl-/": "undo"
    }

    function initHtmlEditor() {
        htmleditor = CodeMirror.fromTextArea(document.getElementById("htmlcode"), {
            lineNumbers: true,
            extraKeys: extraKeyOptions
        });
    };

    function initCssEditor() {
        csseditor = CodeMirror.fromTextArea(document.getElementById("csscode"), {
            lineNumbers: true,
            extraKeys: extraKeyOptions,
            mode: "text/css",
        });

        csseditor.hide();
    };

    function initJsEditor() {
        jseditor = CodeMirror.fromTextArea(document.getElementById("jscode"), {
            lineNumbers: true,
            extraKeys: extraKeyOptions,
            mode: {
                name: "javascript",
                globalVars: true
            }
        });

        jseditor.hide();
    };

    var min = 300;
    var max = 3600;
    var mainmin = 200;

    jQuery('#split-bar').mousedown(function(e) {
        jQuery('#preview').hide();
        e.preventDefault();
        jQuery(document).mousemove(function(e) {
            e.preventDefault();
            var x = e.pageX - jQuery('#sidebar').offset().left;
            if (x > min && x < max && e.pageX < (jQuery(window).width() - mainmin)) {
                jQuery('#sidebar').css("width", x);
                jQuery('#main').css("margin-left", x);
            }
        })
    });

    jQuery(document).mouseup(function(e) {
        jQuery('#preview').show();
        jQuery(document).unbind('mousemove');
    });

    jQuery(document).ready(function(e) {
        load_project("workshop-1");

        jQuery('#runBtn').click(function() {
            run_update();
        });

        function editor_active(editor, sheet) {
            editor.show();
            sheet.addClass("active");
        }

        function editor_unactive(editor, sheet) {
            editor.hide();
            sheet.removeClass("active");
        }

        function view_active(view) {                
            view.addClass("active");
        }

        function view_unactive(view) {                
            view.removeClass("active");
        }            
        
        jQuery('.size-view').click(function(event) {
            viewid = jQuery(this).attr("id");
            if(viewid=="invisible") {
                var x = event.pageX;
                view_unactive(jQuery("#desktop"));
                view_unactive(jQuery("#tablet"));
                view_unactive(jQuery("#mobile"));
                view_active(jQuery("#invisible"));

                jQuery('#sidebar').css("width", "100%");
                jQuery('#main').css("display", "none");
                //jQuery('#main').css("margin-left", "auto");
            } else if(viewid=="mobile") {
                var x = event.pageX - 500;
                view_unactive(jQuery("#desktop"));
                view_unactive(jQuery("#tablet"));
                view_unactive(jQuery("#invisible"));
                view_active(jQuery("#mobile"));

                jQuery('#sidebar').css("width", x);
                jQuery('#main').css("margin-left", x);
                jQuery('#main').css("display", "");
            } else if(viewid=="tablet") {
                var x = event.pageX - 800;
                view_unactive(jQuery("#desktop"));
                view_unactive(jQuery("#mobile"));
                view_unactive(jQuery("#invisible"));
                view_active(jQuery("#tablet"));

                jQuery('#sidebar').css("width", x);
                jQuery('#main').css("margin-left", x);
                jQuery('#main').css("display", "");
            } else if(viewid=="desktop") {
                var x = event.pageX - 1000;
                view_unactive(jQuery("#mobile"));
                view_unactive(jQuery("#tablet"));
                view_unactive(jQuery("#invisible"));
                view_active(jQuery("#desktop"));

                jQuery('#sidebar').css("width", x);
                jQuery('#main').css("margin-left", x);  
                jQuery('#main').css("display", "");
            }
        });

        jQuery('.code-sheet').click(function() {
            sheetid = jQuery(this).attr("id");
            if (sheetid == "html_code_sheet") {
                editor_unactive(jseditor, jQuery("#js_code_sheet"));
                editor_unactive(csseditor, jQuery("#css_code_sheet"));
                editor_active(htmleditor, jQuery("#html_code_sheet"));
                
            } else if (sheetid == "css_code_sheet") {
                editor_unactive(jseditor, jQuery("#js_code_sheet"));
                editor_unactive(htmleditor, jQuery("#html_code_sheet"));
                editor_active(csseditor, jQuery("#css_code_sheet"));
            } else if (sheetid == "js_code_sheet") {
                editor_unactive(htmleditor, jQuery("#html_code_sheet"));
                editor_unactive(csseditor, jQuery("#css_code_sheet"));
                editor_active(jseditor, jQuery("#js_code_sheet"));
            }
        });
        
        jQuery("select[name=project_template]").change(function() {
            
            jQuery.get('<?php echo base_url("project/get_template"); ?>/' + jQuery(this).val(), function(data) {                    
                jQuery("#template").val(data);
            });
            //jQuery("#template").val
        });

        jQuery("#viewcodeBtn").click(function() {
            run_update();
        });

        jQuery("#saveBtn").click(function() {
            //console.log('<?php echo site_url("project/save"); ?>');
	        run_update();

            var mode = jQuery("input[name=mode]").val();
            var projectname = jQuery("input[name=project_name]").val();
            jQuery.post('<?php echo site_url("project/save"); ?>', 
            {
                mode : mode,
                project_name : projectname,
                template : jQuery("select[name=project_template]").val(),
                files : {
                    template : jQuery('#preview_template').val(),
                    html : htmleditor.getValue(),
                    css : csseditor.getValue(),
                    js : jseditor.getValue(),
                }
            }, 
            function(data) {
                if(data=="folder exist") {
                    alert("This folder name is exist.");
                    return false;
                }

                //alert("Project save completed.");

                if(mode=="add") {
                    window.location = "<?php echo base_url()."?p="; ?>" + projectname;
                }

                console.log(data);
            });
        });

        jQuery(".size-view.active").click();
    })
</script>