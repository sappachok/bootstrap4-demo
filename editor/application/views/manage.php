<style>
.project-name {
    padding:5px;
}
</style>
<br>
<div class="container">
    <h2><i class="fas fa-laptop-code"></i> My Project</h2>   
    <br> 
    <table class="table">
    <tr>
        <th>ชื่อ</th>
        <th></th>
    </tr>
    <?php
        foreach($project_library as $pname) {
            $pid = md5($pname);
            echo "<tr>";
            echo "<td><a href='".base_url()."?p=".$pname."' class='link' data-pid='".$pid."'>".$pname."</a>";
            echo "<form class='edit' data-pid='".$pid."' data-pname='".$pname."' style='display:none;'>";
            echo "<p><input type='text' name='newname' data-pid='".$pid."' class='project-name form-control' value='".$pname."'></p>";
            echo "<p><button type='submit' class='btn btn-primary save-btn'>Save</button> <button type='button' class='btn btn-default cancel-btn'>Cancel</button></p>";
            echo "</form></td>";
            echo "<td><a href='#rename' data-pid='".$pid."' data-pname='".$pname."' class='rename'><i class='fas fa-edit'></i></a></td>";
            echo "<td><a href='#delete' data-pid='".$pid."' data-pname='".$pname."' class='delete-btn'><i class='fas fa-trash-alt'></i></a></td>";       
            echo "</tr>";
        }
    ?>
    </table>
</div>
<script language="javascript">
$('.delete-btn').click(function() {
    if(window.confirm('Are you sure want delete this project ?')) {
        pid = $(this).attr("data-pid");
        pname = $(this).attr("data-pname");
        $.post("<?php echo site_url("project/delete"); ?>", { pid: pid, pname: pname},
        function(data) {
            console.log(data);
            window.location.reload();
        });
    }
});

$('.rename').click(function() {
    //alert("rename");
    var pid = $(this).attr("data-pid");
    //var $div=$('div.project-name[data-pid='+pid+']'), isEditable=$div.is('.editable');
    //$div.prop('contenteditable',!isEditable).toggleClass('editable');
    //var $div=$('div.project-name[data-pid='+pid+']');
    //$div.prop('contenteditable').toggleClass('editable');
    var $div=$('input[data-pid='+pid+']');
    $div.closest("form").show();
    $div.focus();

    $("a[data-pid="+pid+"]").hide();
})

$('.cancel-btn').click(function() {
    var pid = $(this).closest("form").attr("data-pid");
    var newname = $("input[data-pid="+pid+"]").val(pid);
    $("a[data-pid="+pid+"]").show();
    $("form[data-pid="+pid+"]").hide();
});

$('form.edit').submit(function(event) {
    event.preventDefault();
});

$('.save-btn').click(function() {
    var pid = $(this).closest("form").attr("data-pid");
    var pname = $(this).closest("form").attr("data-pname");
    var newname = $("input[data-pid="+pid+"]").val();
    
    $.post('<?php echo site_url("project/rename"); ?>',
    {
        pid : pid,
        pname : pname,
        newname : newname
    },
    function (data) {
        //console.log(data);
        window.location.reload();
    });
});

</script>