<!DOCTYPE html>
<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	require_once("f_DB.php");
	$isexist = true;
	$checkResultarray = selectID($_SESSION['listUser']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>ÇímF</title>
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
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$pass = "";
	$passLength = 0;
	$passLength = mb_strlen( $_SESSION['result_array']['LUSERPASS'] ,"UTF-8");
	for ($i = 0; $i < $passLength ; $i++)
	{
		$pass .="";
	}
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	echo "<left>";
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "</left>";
	
	if($isexist)
	{
		echo "<center>";
		echo "<a class = 'title'>ÇímF</a>";
		echo "<br><br>";
		$_SESSION['pre_post'] = $_SESSION['post'] ;
		$_SESSION['post']['true'] = true;
		echo '<form action="listUserJump.php" method="post">';
		echo "<table><tr><td id = 'item'>ÇÒID</td>";
		echo "<td>".$_SESSION['result_array']['LUSERNAME']."</td>";
		echo "</tr><tr><td id = 'item'>pX[h</td>";
		echo "<td>".$pass."</td>";
		echo "</tr></table>";
		echo "<br>";
		echo '<input type="submit" name = "delete" value = "í" 
				class="free">';
		echo '<input type="submit" name = "cancel" value = "êÉßé" 
				class = "free">';
		echo "</form>";
		echo "</center>";
	}
	else
	{
		echo "<div = class='center'>";
		echo "<a class = 'title'>ÇÒXVsÂ</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>¼Ì[Å·ÅÉf[^ªí³êÄ¢é½ßAXVÅ«Ü¹ñB</a>
				</div>";
		echo "<br>";
		echo '<form action="listUserJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "êÉßé" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
</body>

<script language="JavaScript"><!--
	window.onload = function(){
		var judge_go = '<?php echo $isexist ; ?>';
		if(judge_go)
		{
			if(confirm("üÍàe³ímFB\nîñXVµÜ·ªæëµ¢Å·©H\nÄxmF·éêÍuLZv{^ðµÄ­¾³¢B"))
			{
				location.href = "./deleteUserComp.php";
			}
		}
	}
--></script>

</html>
