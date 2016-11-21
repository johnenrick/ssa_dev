<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>St. Scholastica's Academy </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link href="<?=load_asset()?>css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="<?=load_asset()?>css/bootstrap-responsive.min.css" rel="stylesheet">-->
    <link href="<?=load_asset()?>css/styles.css" rel="stylesheet">
    <link href="<?=load_asset()?>css/default.css" rel="stylesheet" type="text/css" media="screen">
    <link href="<?=load_asset()?>css/jquery-ui.css" rel="stylesheet">

    <link rel="shortcut icon" href="<?=load_asset()?>img/system_img/favicon.png">

    <script src="<?=load_asset()?>js/jquery-2.1.3.min.js"></script>
    <script src="<?=load_asset()?>js/bootstrap.min.js"></script>
    
    <script src="<?=load_asset()?>js/main.js"></script>
    <!--    <script src="./js/jquery.easing.1.3.js"></script>-->
    
    <script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
    <script src="<?=load_asset()?>js/jquery-confirm.js"></script>
	<link href="<?=load_asset()?>css/jquery-confirm.css" rel="stylesheet">

</head>

<body style="zoom: 1;">


    <div class="container full-width">

        <div class="masthead clearfix">
            <div class="main-nav box-shadow">
                <div class="main-nav-wrapper limit-wrap">
                    <div id="brand" class="">
                        <a href="#" class="logo brand pull-left">
                            <img class="animateFast" src="<?=load_asset()?>img/system_img/logo.png">
                        </a>
                    </div>
                    <div class="navbar">
                        <div class="navbar-inner">

                            <div class="container  pull-right">
                                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </a>

                                <ul class="nav nav-collapse collapse">
                                    <li class="active"><a href="#">ACADEMIC</a>
                                    </li>
                                    <li><a href="#">ASSESSMENT</a>
                                    </li>
                                    <li><a href="#">PARENT'S PORTAL</a>
                                    </li>
                                    <li><a href="#">CONTACT</a>
                                    </li>
                                    <?php if(user_type()): ?>
                                    <li><a href="<?=base_url("portal/c_portal/logOutAccount")?>" style="font-weight:bold">Log Out</a>
                                        </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <!-- /.navbar -->
                </div>
            </div>
            <!-- /.main-nav -->
        </div>
        <!-- /.masthead -->

        <div class="hero">
            <img src="<?=load_asset()?>img/system_img/SSA-hero.jpg" alt="" title="#htmlcaption" style="width: 100%; display: inline;">

        </div>

        <div id="primary" class="site">

            <div id="page-nav" class="limit-wrap">
                <ul class="menu-items" id="headerModule">
                    <li class="menu-item inline dropdown">
                        <a href="#" class="dropdown">
                            <!--<div class="icon icon-user"></div>-->
                            HOME
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                            <li>
                                <a tabindex="-1" data-toggle="modal" data-target="#headerAccountChangePassword">
                                    Change Password
                                </a>
                            </li>
                            <li>
                                <a tabindex="-1" href="<?=  base_url()?>portal/c_portal/logOutAccount">
                                    Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
            <!--#prototype for system utility-->
            <div class="prototype" style="display:none">
                <div class="alert alertMessagePrototype" data-dismiss="alert" style="text-align:center">
                    <button type="button" class="close"> <span aria-hidden="true" >&times;</span></button>
                </div>
                <li class="menu-item inline dropdown moduleHead">
                    <a href="#" class="dropdown moduleHeadName">
                        <!--<div class="icon icon-faculty"></div>-->
                        MANAGEMENT
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                    </ul>
                </li>
                <li class="subModule">
                    <a class="moduleHeadName" tabindex="-1" href="<?=  base_url()?>registrar/c_registrar/studentAccountManagement">
                        Student Management
                    </a>
                </li>
            </div>
            <!-- ##[SITE CONTENT]#############################################-->
            <div class="site-content limit-wrap">
            <div class="modal fade" id="headerAccountChangePassword" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm ">
                    <div class="modal-content "  >
                        <div class="modal-body" >
                            <div class="form-group">
                                <div id="headerAccountChangePasswordMessage">

                                </div>
                            </div>
                            <div class="form-group">
                                <label>Old Password</label>
                                <input id="headerAccountChangePasswordOldPassword" class=" form-control " type="password" name="old_password" placeholder="Old Password">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input id="headerAccountChangePasswordNewPassword" class=" form-control " type="password" name="new_password" placeholder="New Password">
                            </div>
                            <div class="form-group">
                                <label>Verify Password</label>
                                <input id="headerAccountChangePasswordVerifyPassword" class=" form-control " type="password" name="verify_password" placeholder="Verify New Password">
                            </div>
                            <div class="form-group" style="text-align:center">
                                <button data-loading-text="Changing" type="submit" class="btn center btn-primary "  id="headerAccountChangePasswordSubmitButton">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>