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
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	require_once("f_DB.php");
	require_once("f_Button.php");
	start();
	$judge = false;
	if(isset($_SESSION['post']['true']))
	{
		if($_SESSION['post']['true'])
		{
			$judge = true;
			$_SESSION['post'] = $_SESSION['pre_post'];
			$_SESSION['pre_post'] = null;
		}
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>?Ǘ??ҍ폜????</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
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
		set_button_size();
	});
--></script>
</head>
<body>

<?php
	if($judge)
	{
		$filename = $_SESSION['filename'];
		echo "<left>";
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "</left>";
		if(countLoginUser())
		{
			deleteUser();
			$userName = $_SESSION['result_array']['LUSERNAME'];
			$password = $_SESSION['result_array']['LUSERPASS'];
			$_SESSION['result_array'] = null;
			$pass = "";
			$passLength = 0;
			$passLength = mb_strlen( $password ,"UTF-8");
			for ($i = 0; $i < $passLength ; $i++)
			{
				$pass .="??";
			}
			$password = null;
			echo "<center>";
			echo "<a class = 'title'>?Ǘ??ҍ폜????</a>";
			echo "<br><br>";
			echo "<table><tr><td id = 'item'>?Ǘ???ID</td>";
			echo '<td>';
			echo $userName;
			echo '</td>';
			echo "</tr><tr><td id = 'item'>?p?X???[?h</td>";
			echo '<td>';
			echo $pass;
			echo '</td>';
			echo "</tr></table>";
			echo "<br>";
			echo '<form action="listUserJump.php" method="post">';
			echo "<input type='submit' name='cancel' value = '?ꗗ?ɖ߂?' class='free'>";
			echo "</form>";
			echo "</center>";
		}
		else
		{
			echo "<center>";
			echo "<a class = 'title'>?Ǘ??ҍ폜?s??</a>";
			echo "<br><br>";
			echo "<a class ='error'>?Ǘ??҂??c???P?̂??ߍ폜?ł??܂????B</a>";
			echo "<br>";
			echo '<form action="listUserJump.php" method="post">';
			echo "<input type='submit' name='cancel' value = '?ꗗ?ɖ߂?' class='free'>";
			echo "</form>";
			echo "</center>";
		}
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
?>

</body>

</html>
