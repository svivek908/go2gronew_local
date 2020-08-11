<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->config->item('title'); ?></title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="Tamerlan Soziev" name="author">
    <meta content="Admin dashboard html template" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="<?php echo base_url('admin_assets/img/favicon.png');?>" rel="shortcut icon">
    <link href="<?php echo base_url('admin_assets/img/apple-touch-icon.png');?>" rel="apple-touch-icon">
    <!--   <link href="<?php //echo base_url();?>fast.fonts.net/cssapi/175a63a1-3f26-476a-ab32-4e21cbdb8be2.css" rel="stylesheet" type="text/css"> -->
    <link href="<?php echo base_url('admin_assets/bower_components/select2/dist/css/select2.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('admin_assets/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('admin_assets/bower_components/dropzone/dist/dropzone.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('admin_assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('admin_assets/bower_components/fullcalendar/dist/fullcalendar.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('admin_assets/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('admin_assets/css/mainc599.css?version=3.3');?>" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <script src="<?php echo base_url('admin_assets/js/sweetalert.min.js');?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('admin_assets/css/sweetalert.min.css');?>">
    <!-- datepicker css -->
    <link href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" />
    <script src="<?php echo base_url('admin_assets/js/jquery.min.js');?>"></script>
    <!-- datepicker js-->
    <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="<?php echo base_url('admin_assets/js/moment.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/js/core.js');?>"></script>

    <link rel="stylesheet" href="<?php echo base_url('admin_assets/datatable/css/jquery.dataTables.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('admin_assets/datatable/css/buttons.dataTables.min.css');?>">

    <link rel="stylesheet" href="<?= base_url('admin_assets/css/croppie.css');?>">
    <style>
    .tooltip111 {
        position: relative;
        display: inline-block;
        color: #006080
    }
    .tooltip111 .tooltiptext {
        visibility: hidden;
        position: absolute;
        width: 300px;
        background-color: #555;
        color: #fff;
        text-align: center;
        padding: 5px 0;
        border-radius: 6px;
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip111-bottom {
        top: 91%;
        left: 50%;
        margin-left: -157px
    }

    .tooltip111-bottom::after {
        content: "";
        position: absolute;
        bottom: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #555 transparent;
    }

    .tooltip111:hover .tooltiptext {
      visibility: visible;
      opacity: 1;
    }
    </style>
</head>