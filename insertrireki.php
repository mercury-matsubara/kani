<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
	$title .= "��������";
?>
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

<head>
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
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
	
        // 2018/06/29 �ǉ��Ή� ��
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2'
           || $filename == 'SAIINFO_2'
           || $filename == 'HANSHUTUTEISEI_2' || $filename == 'HANNYUTEISEI_2')
	{
		$_SESSION['post'] = $_SESSION['pre_post'];
		unset($_SESSION['pre_post']);
                
                $post = $_SESSION['list'];
                if( isset( $post['form_start'] ) )
                {
                    $start_array = explode("/", $post['form_start']);
                    $post['form_start_0'] = $start_array[0];
                    $post['form_start_1'] = $start_array[1];
                    $post['form_start_2'] = $start_array[2];
                }

                // �����̏ꍇ�́A���݂̗������폜����
                if($filename == 'HANSHUTUTEISEI_2' || $filename == 'HANNYUTEISEI_2')
                {
                    deleteNyuusyukka($post);
                }
                
                // ���ق̏ꍇ�͍��ٍX�V
                if($filename == 'SAIINFO_2')
                {
                    updateSaiinfo($post);
                }
                else //����ȊO�͔����o����
                {
                    insertnyuusyukka($post);
                }
        // 2018/06/29 �ǉ��Ή� ��
		
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title."</a>";
		echo "</div>";
		echo "<div class = 'center'>";
		echo "<br>".$title."�������������܂����B<br>";
		echo "</div>";
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>�@</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton($filename,'center');
		echo "</td></tr></table></div>";
		echo "</form>";
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
?>
</body>
</html>
