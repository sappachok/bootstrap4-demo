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
        <th>เปลี่ยนชื่อ</th>
        <th>ลบทิ้ง</th>
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
            echo "<td><a href='#delete' data-pid='".$pid."' data-pname='".$pname."' class='delete-btn text-danger'><i class='fas fa-trash-alt'></i></a></td>";       
            echo "</tr>";
        }
    ?>
    </table>
</div>
<script language="javascript">
jQuery('.delete-btn').click(function() {
    if(window.confirm('Are you sure want delete this project ?')) {
        pid = jQuery(this).attr("data-pid");
        pname = jQuery(this).attr("data-pname");
        jQuery.post("<?php echo site_url("project/delete"); ?>", { pid: pid, pname: pname},
        function(data) {
            console.log(data);
            window.location.reload();
        });
    }
});

jQuery('.rename').click(function() {
    //alert("rename");
    var pid = jQuery(this).attr("data-pid");
    //var $div=jQuery('div.project-name[data-pid='+pid+']'), isEditable=$div.is('.editable');
    //$div.prop('contenteditable',!isEditable).toggleClass('editable');
    //var $div=jQuery('div.project-name[data-pid='+pid+']');
    //$div.prop('contenteditable').toggleClass('editable');
    var $div = jQuery('input[data-pid='+pid+']');
    $div.closest("form").show();
    $div.focus();

    jQuery("a[data-pid="+pid+"]").hide();
})

jQuery('.cancel-btn').click(function() {
    var pid = jQuery(this).closest("form").attr("data-pid");
    var newname = jQuery("input[data-pid="+pid+"]").val(pid);
    jQuery("a[data-pid="+pid+"]").show();
    jQuery("form[data-pid="+pid+"]").hide();
});

jQuery('form.edit').submit(function(event) {
    event.preventDefault();
});

jQuery('.save-btn').click(function() {
    var pid = jQuery(this).closest("form").attr("data-pid");
    var pname = jQuery(this).closest("form").attr("data-pname");
    var newname = jQuery("input[data-pid="+pid+"]").val();
    
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