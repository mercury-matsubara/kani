<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<?php
	session_start();
	require_once("f_Construct.php");
	start();
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
        
?>

<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<style>
    .logoimg {  max-width: 100%;
                height: auto;
                vertical-align: bottom}
    
    .doba { margin: 0;
            padding: 15px;
            background-color: rgba(255,255,255,0.7);
            font-size: 30px;
            border: solid 3px #000000;/*ê¸*/
            border-radius: 10px;/*äpÇÃä€Ç›*/
          }
    .logo   {position: relative}
    
    .doba   {position: absolute;
            bottom: 65%;
            left: 35%}
    
    
</style>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
		set_button_size();
	});
	$(window).resize(function()
	{
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
	});
--></script>
</head>
<body>
<?php
	require_once("f_Button.php");
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo "<left>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</left><br><br><br><br>";
	echo "<center>";
	echo "<a class='title'>".$title."</a><br><br><br>\n";
?>
        <div class="logo">
	<img src="./image/rogo.png" alt="" class="logoimg">
     
        </div>
<?php
	echo "</center>";
	echo "<br><br>";
	echo "<div class = 'left' id = 'space_button'>Å@</div>";
	echo "<div><table id = 'button'><tr><td>";
	echo makebutton($filename,'center');
	echo "</td></tr></table></div>";
	echo "</form>";
?>
</body>
</html>
