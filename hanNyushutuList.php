<?php
	session_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.1.0  2014/07/03                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>

<?php
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	require_once("f_Construct.php");
	start();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$isCSV = $form_ini[$filename]['isCSV'];
	$filename_array = explode('_',$filename);
	$filename_insert = $filename_array[0]."_1";
	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title2 = '';
	$isMaster = false;
        
        // 独自処理開始
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
        $post = $_SESSION['list'];
        
	$result = array();
	$judge = false;

        // DBから履歴情報を取る
        $sql = "SELECT R.*, G.GENBAID, G.GENBANAME, K.KOKYAKUID, K.KOKYAKUNAME, G.10CODE ";
        $sql .= "FROM rirekiinfo R ";
        $sql .= "INNER JOIN genbainfo G ON R.4CODE = G.4CODE ";
        $sql .= "INNER JOIN kokyakuinfo K ON R.2CODE = K.2CODE ";
        $sql .= "WHERE 7CODE=".$_SESSION['list']['id'];

        $con = dbconect();												// db接続関数実行
	$result = $con->query( $sql ) or ($judge = true);																		// クエリ発行
	if($judge)
        {
		error_log($con->error,0);
        }
        if($result->num_rows == 1)
	{
                // 取ったデータを保持
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
                $post['IOTYPE'] = $result_array['IOTYPE'];
                $post['4CODE'] = $result_array['4CODE'];
                $post['2CODE'] = $result_array['2CODE'];
                $post['SAGYOUDATE'] = $result_array['SAGYOUDATE'];
                $post['readonlyHeader'] = true;
                $post['form_402_0'] = $result_array['GENBAID'];
                $post['form_403_0'] = $result_array['GENBANAME'];
                $post['form_202_0'] = $result_array['KOKYAKUID'];
                $post['form_203_0'] = $result_array['KOKYAKUNAME'];
                $post['10CODE'] = $result_array['10CODE'];
                list( $post['form_start_0'], $post['form_start_1'], $post['form_start_2'] ) = explode('-', $result_array['SAGYOUDATE']);
                $post['form_start'] = str_replace("-", "/", $result_array['SAGYOUDATE']);
                // 開始行位置を初期化
                $post['limitstart'] = 0;
 	}

        // 出荷・返却
        if ($post['IOTYPE'] == 1)
	{
		$title2 = '(出荷)';
                $_SESSION['filename'] =  "HANSHUTUTEISEI_2";
        }
        else
        {
		$title2 = '(返却)';
                $_SESSION['filename'] =  "HANNYUTEISEI_2";
	}
        $_SESSION['list'] = $post;
	$filename = $_SESSION['filename'];
	$title1 = $form_ini[$filename]['title'];
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<STYLE type="text/css">
<!--
.txtmode1 {
  ime-mode: active;   /* 全角モード */
}
.txtmode2 {
  ime-mode: inactive;   /* 半角モード */
}
-->
</STYLE>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./Modal.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./syukkacheck.js'></script>
<script language="JavaScript"><!--
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			var param = checkListArray[i].split("~");
			if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
			{
				judge = false;
			}
		}
		return judge;
	}
	function popup_modal(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.7);
		h = (h * 0.7);
		url = 'Modal.php?tablenum='+GET+'&form=edit';
//		n = showModalDialog(
//			url,
//			this,
//			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//		);
                n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );	
	}
--></script>
</head>
<body>
    <center>
<?php
        // 2018/06/29 追加対応 ↓(カレンダー)
        $makeDatepicker = "";
        // 2018/06/29 追加対応 ↑(カレンダー)
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
        // 2018/06/29 追加対応 ↓(カレンダー)
	$formStrArray = makeformSerch_set($_SESSION['list'],"form");
	$form = $formStrArray[0];
        $makeDatepicker .= $formStrArray[1];
        // 2018/06/29 追加対応 ↑(カレンダー)
	
	if($filename == 'HANSHUTUTEISEI_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuTeiseiSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else if($filename == 'HANNYUTEISEI_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuTeiseiSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else
	{
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
	}
	
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";

	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo "</td></tr></table>";
	
        echo "<table><tr><td>作業日 : </td><td>";
        echo "<input type=\"text\" value=\"".$post['form_start']."\" id=\"form_start\" name=\"form_start\" readonly=\"readonly\" />";
        echo "</td></tr></table>";
	
	echo $list;
	echo "</form>";
	if(isset($form_ini[$filename_insert]))
	{
		echo "<form action='pageJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
		echo "<input type ='submit' value = '新規作成' class = 'free' name = '".$filename_insert."_button'>";
		echo "</div>";
		echo "</form>";
	}
        echo "<div style='text-align:center;'>";
        echo "<input type ='submit' name = 'syukka' class='free' value = '設定' onclick = 'syukkacheck();'>";
        echo "</div>";
	echo "</div>";
?>
</center>
</body>
</html>
