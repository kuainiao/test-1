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
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'spline',
                marginRight: 10,
                marginBottom: 50
            },
            title: {
                text: <?php echo "'".$sys.$chan.$ser."'"; ?>,
                x: -20 //center
            },
          
            xAxis: {
				title:{text:'日期',align: 'high'},
                 categories:  [<?php foreach($last_7day_daily_return as $v) :?>
                     <?php echo "'".date('Y-m-d', $v[0]['date'])."',";?>
                     <?php endforeach;?>]
            },
            yAxis: {
               title: {
                    text: '最高在线',
                    align: 'high',
                    rotation: 0
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
				min:0
            },
            tooltip: {
                 formatter: function () {
                return '<b>' +this.x+ this.series.name 
                    + ':' + this.y+'个';
				}
            },
            legend: {
                layout: 'horizontal',
                align: 'top',
                verticalAlign: 'bottom',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                name: '最高在线',
                   data: [<?php foreach($last_7day_daily_return as $v) :?>
                       <?php echo $v[0]['number'].',';?>
                       <?php endforeach;?>]
            }]
        });
    });

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
                <h2><i class="glyphicon glyphicon-list-alt"></i> 最高在线统计</h2>
                <div style="float:right; font-size:12px; margin-right:10px;">
                    <select type="text" id="system" name="system" class="lstime">
                        <option value='0' selected>选择手机系统</option>
                        <?php foreach($system as $k=>$v) :?>
                            <option value=<?php echo $v['number'];?>><?php echo $v['name'];?></option>
                        <?php endforeach;?>
                    </select>
                    <select type="text" id="channel" name="channel" class="lstime">
                        <option value='0' selected>选择运营平台</option>
                        <?php foreach($channel as $k=>$v) :?>
                            <option value=<?php echo $v['number'];?>><?php echo $v['name'];?></option>
                        <?php endforeach;?>
                    </select>
                    <select type="text" id="server" name="server" class="lstime">
                        <option value='0' selected>选择游戏服务器</option>
                        <?php foreach($server as $k=>$v) :?>
                    <option value=<?php echo $v['number'];?>><?php echo $v['name'];?></option>
                    <?php endforeach;?>
                    </select>
                    开始时间：<input type="text" <img src="<?php echo $viewspath;?>/img/date.png" height="20" width="20" style="margin-left:2px;" value=<?php echo $start_date;?> autocomplete="off" onfocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  id="start_date" name="start_date" class="lstime">
                    结束时间：<input type="text" <img src="<?php echo $viewspath;?>/img/date.png" height="20" width="20" style="margin-left:2px;" value=<?php echo $stop_date;?> autocomplete="off" onfocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  id="stop_date" name="stop_date" class="lstime">
                    <!--<input type="submit" id="submitBtn" value="查询" class="lsbtn"><span id="errSpan"></span>-->
                    <input type="button" id="submitBtn" value="查询" class="lsbtn"><span id="errSpan"></span>
                    <script>
                        $('#submitBtn').click(function(){
                            var system = $('#system').val();
                            var channel = $('#channel').val();
                            var server = $('#server').val();
                            var start_date = $('#start_date').val();
                            var stop_date = $('#stop_date').val();
                            window.location.href="<?php echo site_url('gindex/daily_max_online_user');?>/"+start_date+"/"+stop_date+"/"+server+"/"+channel+"/"+system+"/";
                        });
                    </script>
                </div>

                
            </div>
            <div class="box-content">
                
                <div id="container" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
               
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
