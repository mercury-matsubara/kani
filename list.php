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
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '�ꗗ';
		break;
	case 1:
		$title2 = '�ꗗ';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
        
        if(isset($_GET['doba']))
        {
             $doba = $_GET['doba'];
        }    
        
        if(isset($_SESSION['list']['doba']))
        {
            $_SESSION['doba'] = $_SESSION['list']['doba'];
        }    
        
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" >
<STYLE type="text/css">
<!--
.txtmode1 {
  ime-mode: active;   /* �S�p���[�h */
}
.txtmode2 {
  ime-mode: inactive;   /* ���p���[�h */
}
-->
</STYLE>
<!-- ��jQuery-UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<!-- ��jQuery-UI -->
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
                // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
                makeDatepicker();
                // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
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

                  
//                    n = showModalDialog(
//                            url,
//                            this,
//    //			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//                            "dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//                    );
                n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );               
	}
         
        function popup_modaldoba(GET)
	{
            //---------------------------------------��2018/09/14 �y��݌ɒǉ�---------------------------------//
                let doba = document.getElementById("DOBA").value;;
                
                    
                    var w = screen.availWidth;
                    var h = screen.availHeight;
                    w = (w * 0.7);
                    h = (h * 0.7);
                   
                           url = 'Modal.php?tablenum='+GET+'&form=edit&doba='+doba+'';


//                    n = showModalDialog(
//                            url,
//                            this,
//    //			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//                            "dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//                    );
                n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );
                //-----------------------------------��2018/09/14 �y��݌ɒǉ�-------------------------------------------//
	}
           
           
        
            function show_hide_row(row)
            {
                   //---------------------------------------��2018/09/27 �y��݌ɒǉ�---------------------------------//
                   
                        $("[id="+row+"]").toggle();
               
                  //-----------------------------------��2018/09/27 �y��݌ɒǉ�-------------------------------------------//
            }
        

           
--></script>
</head>
<body>
<center>
<?php
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
        $makeDatepicker = "";
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)

	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
	$formStrArray = makeformSerch_set($_SESSION['list'],"form");
	$form = $formStrArray[0];
        $makeDatepicker .= $formStrArray[1];
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
	
	
	
	
	if($filename == 'HENKYAKUINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// ���o�ג��̂ݕ\���̂���
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else if($filename == 'SYUKKAINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// ���o�ג��̂ݕ\���̂���
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else if($filename == 'GENBALIST_2' || $filename == 'SIZAILIST_2' || $filename == 'ZAIKOINFO_2')
	{
		$sql = itemListSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
        // 2018/06/29 �ǉ� ��
	else if($filename == 'SAIINFO_2')
	{
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
        // 2018/06/29 �ǉ� ��
        else
	{
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
	}
	
	
		
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
	echo "<form action='pageJump.php' name='form1' method='post'><div class = 'left'>";
        echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	if($isLavel == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="submit" name="label" class="free" 
				value = "���x�����s" >';
		echo "</div>";
	}
	if($isMail == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="button" name="mail" class="free" value = "���[�����s" 
				onClick = "click_mail();">';
		echo "</div>";
	}
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>��������</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
        //echo "<input type = 'hidden' name = 'doba' value = '".$doba."'>";
	echo '<input type="submit" name="serch" value = "�\��" class="free" >';
	echo "</td></tr></table>";
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		$year--;
		echo "<table><tr><td>��Ɠ� : </td><td>";
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
                $datepickerArray = datepickerDate_set(2,$year,0,"form_start","",$_SESSION['list'],"","form",1);
		echo $datepickerArray[0];
                $makeDatepicker .= $datepickerArray[1];
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
		echo "</td></tr></table>";
	}
	
	echo $list;
	echo "</form>";
        //echo"<div style='text-align:center;'>";
	if($isCSV == 1)
	{
		echo "<form action='download_csv.php' method='post'>";
		echo "<div class = 'listcenter'>";
               
		echo "<input type ='submit' name = 'csv' class='button' value = 'csv�t�@�C������' style ='height:30px;' >";
		echo "</div>";
		echo "</form>";
	}
	if(isset($form_ini[$filename_insert]))
	{
                if($filename != "ZAIKOINFO_2")
                {    
                    echo "<form action='pageJump.php' method='post'>";
                    //echo"<div class = 'listcenter' style = 'HEIGHT : 30px'>";
                    echo "<div class = 'listcenter'>";
                    echo "<input type ='submit' value = '�V�K�쐬' class = 'free' name = '".$filename_insert."_button'>";
                    echo "</div>";
                    echo "</form>";
                }    
	}
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
 	{
//		echo "<form action='insertrireki.php' method='post' onsubmit = 'return syukkacheck();' >";
		//echo "<div class = 'left'>";
                echo"<div style='text-align:center;'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '�ݒ�'>";
		echo "<input type ='submit' name = 'syukka' class='free' value = '�ݒ�' onclick = 'syukkacheck();'>";
		echo "</div>";
//		echo "</form>";
	}
        //  ��2018/06/29 �ǉ��Ή�
	if($filename == 'SAIINFO_2')
	{
		//echo "<div class = 'left'>";
            
		echo "<input type ='submit' name = 'sai' class='free' value = '�ݒ�' onclick = 'document.form.submit();'>";
		echo "</div>";
	}
        //  ��2018/06/29 �ǉ��Ή�
	echo "</div>";
       
?>
    </center>

</body>
<script language="JavaScript"><!--
	function makeDatepicker()
	{
                <?php echo $makeDatepicker; ?>
	}
--></script>
</html>
