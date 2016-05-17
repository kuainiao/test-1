<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        ===
        This comment should NOT be removed.

        Charisma v2.0.0

        Copyright 2012-2014 Muhammad Usman
        Licensed under the Apache License v2.0
        http://www.apache.org/licenses/LICENSE-2.0

        http://usman.it
        http://twitter.com/halalit_usman
        ===
    -->

    <meta charset="utf-8">
    <title>龙塔数据后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link href="<?php echo $viewspath;?>/css/bootstrap-cerulean.min.css" rel="stylesheet">
    <link href="<?php echo $viewspath;?>/css/charisma-app.css" rel="stylesheet">
    <link href='<?php echo $viewspath;?>/bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='<?php echo $viewspath;?>/bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/jquery.noty.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/noty_theme_default.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/elfinder.min.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/elfinder.theme.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/uploadify.css' rel='stylesheet'>
    <link href='<?php echo $viewspath;?>/css/animate.min.css' rel='stylesheet'>

    <!-- jQuery -->
    <script src="<?php echo $viewspath;?>/bower_components/jquery/jquery.min.js"></script>

  

    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?php echo $viewspath;?>/img/favicon.ico">

</head>

<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
        <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo site_url('gindex/index'); ?>"> <!--<img alt="Charisma Logo" src="img/company_logo.png" class="hidden-xs"/>-->
                <span>龙塔数据后台</span></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
               <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> <?php echo $username;?></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  
                    <li><a href="<?php echo site_url('gindex/logout'); ?>">退出</a></li>
                </ul>
            </div>
    

        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
        <?php echo $side;?>
        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
    <ul class="breadcrumb">
        <li>
            <a href="#">首页</a>
        </li>
    </ul>
</div>
<div class=" row">
    <div class="col-md-3 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="昨日新增用户<?php foreach($yesterday_new_users as $v) :?>
                 <?php echo $v->number;?>
                <?php endforeach;?>个" class="well top-block" href="#">
            <i class="glyphicon glyphicon-user blue"></i>
            <div>用户</div>
            <div><?php foreach($users as $v) :?>
                 <?php echo $v->number;?>
                <?php endforeach;?></div>
        </a>
    </div>

    

    <div class="col-md-3 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="昨日新增充值<?php foreach($yesterday_new_money as $v) :?>
                 <?php echo $v->money;?>
                <?php endforeach;?>元" class="well top-block" href="#">
            <i class="glyphicon glyphicon-shopping-cart yellow"></i>
            <div>充值</div>
            <div><?php foreach($money as $v) :?>
                    <?php echo $v->money;?>
                <?php endforeach;?></div>
        </a>
    </div>

    
</div>
<!--/row-->

<!--/row-->

<!-- chart libraries start -->
<script src="<?php echo $viewspath;?>/bower_components/flot/excanvas.min.js"></script>
<script src="<?php echo $viewspath;?>/bower_components/flot/jquery.flot.js"></script>
<script src="<?php echo $viewspath;?>/bower_components/flot/jquery.flot.pie.js"></script>
<script src="<?php echo $viewspath;?>/bower_components/flot/jquery.flot.stack.js"></script>
<script src="<?php echo $viewspath;?>/bower_components/flot/jquery.flot.resize.js"></script>
<!-- chart libraries end -->
<script src="<?php echo $viewspath;?>/js/init-chart.js"></script>

    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
    
    <hr>

<br>

<!--    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Settings</h3>
                </div>
                <div class="modal-body">
                    <p>Here settings can be configured...</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Save changes</a>
                </div>
            </div>
        </div>
    </div>-->

    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; <a href="#" target="_blank">北京九天创世科技有限公司
                </a> @2015</p>

       
    </footer>

</div><!--/.fluid-container-->

<!-- external javascript -->

<script src="<?php echo $viewspath;?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="<?php echo $viewspath;?>/js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='<?php echo $viewspath;?>/bower_components/moment/min/moment.min.js'></script>
<script src='<?php echo $viewspath;?>/bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='<?php echo $viewspath;?>/js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="<?php echo $viewspath;?>/bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="<?php echo $viewspath;?>/bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="<?php echo $viewspath;?>/js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="<?php echo $viewspath;?>/bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="<?php echo $viewspath;?>/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="<?php echo $viewspath;?>/js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="<?php echo $viewspath;?>/js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="<?php echo $viewspath;?>/js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="<?php echo $viewspath;?>/js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo $viewspath;?>/js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="<?php echo $viewspath;?>/js/charisma.js"></script>


</body>
</html>
