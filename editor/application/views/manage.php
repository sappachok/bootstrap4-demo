<div class="container">
    <h2>My Project</h2>   
    <br> 
    <table class="table">
    <tr>
        <th>ชื่อ</th>
        <th></th>
    </tr>
    <?php
        foreach($project_library as $pname) {
            echo "<tr>";
            echo "<td><a href='".base_url()."?p=".$pname."'>".$pname."</a></td>";
            echo "<td><a href='#delete' class='delete-btn'><i class='fas fa-trash-alt'></i></a></td>";       
            echo "</tr>";
        }
    ?>
    </table>
</div>