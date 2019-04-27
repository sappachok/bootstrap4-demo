<html>
<head>
    <title>Preview Code</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome5/css/all.min.css">
    <script src="<?php echo base_url(); ?>assets/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap4/js/bootstrap.min.js"></script>
    <style>
    .preview_code,
    .preview_code:focus {        
        background-color: #333333;
        color: #ffffff;
    }
    </style>
</head>
<body>
    <textarea class="form-control preview_code" rows="20">{code}</textarea>
</body>
<html>
