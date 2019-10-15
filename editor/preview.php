<!DOCTYPE html>
<html>
<head>
    <title>Bootstrap Tutor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../../assets/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/fontawesome5/css/all.min.css">
    <script src="../../../assets/jquery/3.3.1/jquery.min.js"></script>
    <script src="../../../assets/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="../../../assets/bootstrap4/js/bootstrap.min.js"></script>
</head>
<body>
<iframe id="frm_preview" frameborder="0" style="height:480px; width:100%;"></iframe>
<script>
jQuery(document).ready(function() {
	var projectcode = window.parent.get_code();
	function load_page() {
		var previewFrame = document.getElementById('frm_preview');
		var preview = previewFrame.contentDocument || previewFrame.contentWindow.document;	

		jQuery.get('code.html', function(data) {
			var dt = new Date()
			t = dt.getTime()
			projectcode = projectcode.replace(/{%time}/gi, t);
			//alert(projectcode);
			preview.open("text/html", "replace");
			preview.write(projectcode);
			preview.close();
		});
	}
	load_page();
});
</script>
</div>
</html>