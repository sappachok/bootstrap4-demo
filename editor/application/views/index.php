<link rel="stylesheet" href="../codemirror/lib/codemirror.css">
<link rel="stylesheet" href="../codemirror/addon/hint/show-hint.css">
<script src="../codemirror/lib/codemirror-custom.js"></script>
<script src="../codemirror/addon/hint/show-hint.js"></script>
<script src="../codemirror/addon/hint/xml-hint.js"></script>
<script src="../codemirror/addon/hint/html-hint.js"></script>
<script src="../codemirror/mode/xml/xml.js"></script>
<script src="../codemirror/mode/javascript/javascript.js"></script>
<script src="../codemirror/mode/css/css.js"></script>
<script src="../codemirror/mode/htmlmixed/htmlmixed.js"></script>
<style>
    .CodeMirror {
        border-top: 1px solid #888;
        border-bottom: 1px solid #888;
        height: 600px;
    }
    
    #main {
        border: 1px solid #888;
        background-color: #eeeeee;
        margin-left: 600px;
        height: 600px;
    }
    
    #sidebar {
        width: 600px;
        float: left;
        height: 600px;
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
        background: #333333;
        color: #ffffff;
    }

    #preview_code {
        height: 500px;
    }
</style>

<body>
    <br>
    <div class="container-fluid">
    <div class="row">
        <div class="col-6"> 
        <p>
            <div class="input-group">
                <input type="text" name="project_name" class="form-control" value="<?php echo $project_name; ?>">
                <button id="saveBtn" type="button" class="btn btn-primary"><i class="fas fa-play-circle"></i> Save Project</button>
            </div>
        </p>
        </div>
        <div class="col-6">
        <p>
            <div class="form-group">
            <button id="runBtn" type="button" class="btn btn-success"><i class="fas fa-play-circle"></i> Run</button>
            <button id="viewcodeBtn" type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fas fa-play-circle"></i> View Code</button>            
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
                    <iframe id="preview_code" frameborder="0" style="width:100%; height:500px;"></iframe>
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
    <div class="row">        
        <div class="col-4">
        <p>
            <label>Template</label>
            <?php 
                echo form_dropdown("project_template", 
                Array(
                    "bootstrap4"=>"Bootstrap 4",
                    "html5"=>"HTML 5",
                ),
                $project_template, "class='form-control'");             
            ?>
        <p>
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
        <div id="main"><iframe id="preview" frameborder="0" style="width:100%; height:100%;"></iframe></div>
    </div>
    <script>
        var htmleditor = "";
        var csseditor = "";
        var jseditor = "";

        function update_preview(code) {
            var previewcodeFrame = document.getElementById('preview_code');
            var previewcode = previewcodeFrame.contentDocument || previewcodeFrame.contentWindow.document;
            //$("#preview_code").val(code);
            //$("#preview_code").html(code);
            var template = $('#preview_template').val();
            template = template.replace("{code}", code);
            previewcode.open();
            previewcode.write(template);
            previewcode.close();            
        }
        function indent_tab(code, num) {
            rows = code.split("\n");

            indentcode = "";
            $.each(rows, function(i, k) {
                indent = "";
                for(t=1; t<=num; t++) {
                    indent += "\t";
                }
                indentcode += indent + k + "\n"                
            })

            return indentcode;
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

            var template = $("#template").val();            
            template = template.replace("<body></body>", "<body>\n" + indent_tab(body, 1) + "</body>");
            template = template.replace("<style></style>", "<style>\n" + indent_tab(css, 1) + "</style>");
            template = template.replace("<script></"+"script>", "<script language='javascript'>\n" + indent_tab(js, 1) + "</"+"script>");
            //console.log(template);
            preview.open();
            preview.write(template);            
            /*
            preview.write("");

            preview.write("<style>\n" + css + "\n</style>\n");
            preview.write("<body>\n" + body + "\n</body>");
            if (jseditor) {
                preview.write("<script>\n" + js + "\n</" + "script>\n");
            }
            preview.write("</html>");
            */
            preview.close();
            update_preview(template);
        }

        var loader = 1;
        function load_project(name) {
            initHtmlEditor();
            initCssEditor();
            initJsEditor();

            run_update();
        }

        function initHtmlEditor() {

            htmleditor = CodeMirror.fromTextArea(document.getElementById("htmlcode"), {
                lineNumbers: true,
                extraKeys: {
                    "Ctrl-Space": "autocomplete"
                }
            });
        };

        function initCssEditor() {

            csseditor = CodeMirror.fromTextArea(document.getElementById("csscode"), {
                lineNumbers: true,
                extraKeys: {
                    "Ctrl-Space": "autocomplete"
                },
                mode: "text/css",
            });

            csseditor.hide();
        };

        function initJsEditor() {

            jseditor = CodeMirror.fromTextArea(document.getElementById("jscode"), {
                lineNumbers: true,
                extraKeys: {
                    "Ctrl-Space": "autocomplete"
                },
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

        $('#split-bar').mousedown(function(e) {
            $('#preview').hide();
            e.preventDefault();
            $(document).mousemove(function(e) {
                e.preventDefault();
                var x = e.pageX - $('#sidebar').offset().left;
                if (x > min && x < max && e.pageX < ($(window).width() - mainmin)) {
                    $('#sidebar').css("width", x);
                    $('#main').css("margin-left", x);
                }
            })
        });

        $(document).mouseup(function(e) {
            $('#preview').show();
            $(document).unbind('mousemove');
        });

        $(document).ready(function() {
            load_project("workshop-1");

            $('#runBtn').click(function() {
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
            
            $('.size-view').click(function(event ) {
                viewid = $(this).attr("id");
                if(viewid=="mobile") {
                    var x = event.pageX - 500;
                    view_unactive($("#desktop"));
                    view_unactive($("#tablet"));
                    view_active($("#mobile"));
                } else if(viewid=="tablet") {
                    var x = event.pageX - 800;
                    view_unactive($("#desktop"));
                    view_unactive($("#mobile"));
                    view_active($("#tablet"));
                } else if(viewid=="desktop") {
                    var x = event.pageX - 1000;
                    view_unactive($("#mobile"));
                    view_unactive($("#tablet"));
                    view_active($("#desktop"));
                }

                $('#sidebar').css("width", x);
                $('#main').css("margin-left", x);
            });

            $('.code-sheet').click(function() {
                sheetid = $(this).attr("id");
                if (sheetid == "html_code_sheet") {
                    editor_unactive(jseditor, $("#js_code_sheet"));
                    editor_unactive(csseditor, $("#css_code_sheet"));
                    editor_active(htmleditor, $("#html_code_sheet"));
                    
                } else if (sheetid == "css_code_sheet") {
                    editor_unactive(jseditor, $("#js_code_sheet"));
                    editor_unactive(htmleditor, $("#html_code_sheet"));
                    editor_active(csseditor, $("#css_code_sheet"));
                } else if (sheetid == "js_code_sheet") {
                    editor_unactive(htmleditor, $("#html_code_sheet"));
                    editor_unactive(csseditor, $("#css_code_sheet"));
                    editor_active(jseditor, $("#js_code_sheet"));
                }
            });
            
            $("select[name=project_template]").change(function() {
                
                $.get('<?php echo base_url("project/get_template"); ?>/' + $(this).val(), function(data) {                    
                    $("#template").val(data);
                });
                //$("#template").val
            });

            $("#viewcodeBtn").click(function() {
                run_update();
            });

            $("#saveBtn").click(function() {
                console.log('<?php echo site_url("project/save"); ?>');
                $.post('<?php echo site_url("project/save"); ?>', 
                {
                    project_name : $("input[name=project_name]").val(),
                    template : $("select[name=project_template]").val(),
                    files : {
                        template : $('#preview_template').val(),
                        html : htmleditor.getValue(),
                        css : csseditor.getValue(),
                        js : jseditor.getValue(),
                    }
                }, 
                function(data) {
                    console.log(data);
                });
            });

        })
    </script>

</body>

</html>