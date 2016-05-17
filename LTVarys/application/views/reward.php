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

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <script type="text/javascript" language="javascript" src="<?php echo $viewspath;?>/highchars/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $viewspath;?>/highchars/js/highcharts.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $viewspath;?>/highchars/js/modules/exporting.js"></script>
<script type="text/javascript">


</script>


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
</div>

<div class="row">

    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-list-alt"></i>奖励发放</h2>
                <div style="float:right; font-size:12px; margin-right:10px;">
                    <select type="text" id="server" name="server" class="lstime">
                        <option value='0' selected>选择游戏服务器</option>
                        <?php foreach($server as $k=>$v) :?>
                    <option value=<?php echo $v['number'];?>><?php echo $v['name'];?></option>
                    <?php endforeach;?>
                    </select>
                    日期：<input type="text" <img src="<?php echo $viewspath;?>/img/date.png" height="20" width="20" style="margin-left:2px;" value=<?php echo $dates;?> autocomplete="off" onfocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  id="date" name="date" class="lstime">
                    <!--<input type="submit" id="submitBtn" value="查询" class="lsbtn"><span id="errSpan"></span>-->
                    <input type="button" id="submitBtn" value="查询" class="lsbtn"><span id="errSpan"></span>
                    <script>
                        $('#submitBtn').click(function(){
                            var server = $('#server').val();
                            var date = $('#date').val();
                            window.location.href="<?php echo site_url('gindex/reward');?>/"+date+"/"+server+"/";
                        });
                    </script>
                </div>

                
            </div>
            <div class="box-content">
                <table class="items table table-striped table-bordered table-condensed">
                    <thead>
                        <th>日期</th><th>登录用户数</th><th>金币数</th><th>金币平均数</th><th>彩晶数</th><th>彩晶平均数</th><th>材料数</th><th>材料平均数</th>
                        <?php foreach($reward_arr as $k=>$v) :?>
                   <tr><td><?php echo $v['date'];?></td><td><?php echo $v['login_user'];?></td><td><?php echo $v['gold'];?></td><td><?php echo $v['gold_average'];?></td><td><?php echo $v['jewel'];?></td><td><?php echo $v['jewel_average'];?></td><td><?php echo $v['material'];?></td><td><?php echo $v['material_average'];?></td></tr>
                        <?php endforeach;?>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div><!--/row-->

<!--/row-->

<!-- chart libraries start -->
<script type="text/javascript" language="javascript" src="<?php echo $viewspath;?>/js/DatePicker/WdatePicker.js"></script>
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
