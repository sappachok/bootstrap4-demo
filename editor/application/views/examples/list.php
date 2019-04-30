<br>
<div class="container">
<h2><?php echo $title; ?></h2>
<hr>
<ul>
<?php foreach($topic as $title => $link) { ?>
	<li><a href="<?php echo site_url("examples")."/".$link; ?>"><?php echo $title; ?></a></li>
<?php } ?>
</ul>
</div>