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
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '編集';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = '編集';
		$isMaster = true;
		$isReadOnly = true;
		break;
	default:
		$title2 = '';
	}
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
	$isexist = true;
	$checkResultarray = existID($_SESSION['list']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	
	var totalcount  = "<?php echo $maxover; ?>";
	var iscansel = true;
	
	$(window).resize(function()
	{
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
	});
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
		set_button_size();
	});

	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		if(iscansel == true)
		{
			var checkListArray = checkList.split(",");
			var notNullArray = notnullcolumns.split(",");
			var notNullTypeArray = notnulltype.split(",");
			for (var i = 0 ; i < checkListArray.length ; i++ )
			{
				var param = checkListArray[i].split("~");
				if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
				{
					judge = false;
				}
			}
			for(var i = 0 ; i < notnullcolumns.length ; i++ )
			{
				var formelements = document.forms["edit"];
				for(var j = 0 ; j < formelements.length ; j++ )
				{
					if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
					{
						var tagname = formelements.elements[j].tagName;
						if(tagname == 'SELECT')
						{
							var selectnum = formelements.elements[j].selectedIndex;
							if(formelements.elements[j].options[selectnum].value == "")
							{
								formelements.elements[j].style.backgroundColor = '#ff0000';
								judge = false;
								alert('値を選択して下さい');
							}
							else
							{
								formelements.elements[j].style.backgroundColor = '';
							}
						}
					}
				}
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
////			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//		);
                n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );	
	}
	function AddTableRows(id){
		var table01 = document.getElementById('edit');
		var tr = table01.getElementsByTagName("TR");
		var tr_count = tr.length;
		var start = true;
		var start_count = 0;
		var end =true;
		var end_count = 0;
		totalcount++;
		for(count=0 ; count < tr_count ; count++)
		{
			if(tr[count].id==id){
				if(start)
				{
					start_count = count;
					start =false;
				}
			}
			else
			{
				if(start == false)
				{
					if(end)
					{
						end_count = count;
						end = false;
					}
				}
			}
		}
		if(end_count==0)
		{
			end_count=tr_count;
		}
		rows = new Array();
		cells = new Array();
		for(counter=0; counter<(end_count-start_count) ; counter++)
		{
			var row = table01.insertRow((end_count+counter));
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var row2 = table01.rows[start_count+counter];
			var cell4 = row2.cells[2];
			var cell5 = row2.cells[1];
			cell3.innerHTML = cell4.innerHTML;
			cell2.innerHTML = cell5.innerHTML;
			
			var inp = cell3.getElementsByTagName("INPUT");
			for( var count = 0, len = inp.length; count < len; count++ ){
				var id = inp[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
			var inp2 = cell3.getElementsByTagName("SELECT");
			for( var count = 0, len = inp2.length; count < len; count++ ){
				var id = inp2[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
		}
		totalcount++;
	}

  

--></script>
</head>
<body>
<?php
	$judge = false;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if($isexist)
	{
		$errorinfo = existCheck($_SESSION['edit'],$main_table,2);
		if(count($errorinfo) == 2 && $errorinfo[0] == "" && $errorinfo[1] == "")
		{
			$judge = true;
			$_SESSION['edit']['true'] = true;
			$_SESSION['pre_post'] = $_SESSION['post'];
		}
		if(isset($_SESSION['data']))
		{
			$data = $_SESSION['data'];
		}
		else
		{
			$data = "";
		}
		
                if($filename == 'ZAIKOINFO_2')
                {
                    $form = makeformZAIKOEdit_set($_SESSION['edit'],$errorinfo[0],$isReadOnly,"edit",$data );  
                }    
		else
                {
                    $form = makeformEdit_set($_SESSION['edit'],$errorinfo[0],$isReadOnly,"edit",$data );
                }
                
		$checkList = $_SESSION['check_column'];
		$notnullcolumns = $_SESSION['notnullcolumns'];
		$notnulltype = $_SESSION['notnulltype'];
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "</form>";
		echo "<form action='listJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
		echo "<input type ='submit' value = '戻る' name = 'cancel' class = 'free'>";
		echo "</div></form>";
		echo "<div style='clear:both;'></div>";
		echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data" 
					onsubmit = "return check(\''.$checkList.
					'\',\''.$notnullcolumns.
					'\',\''.$notnulltype.'\');">';
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div><br><br>";
		if($errorinfo[1] != "")
		{
			echo "<a class = 'error'>".$errorinfo[1]."</a><br>";
		}
		for($i = 2 ; $i < count($errorinfo) ; $i++)
		{
			echo "<a class = 'error'>".$errorinfo[$i]."</a><br>";
		}
		echo $form;
		echo "</tr></table>";
		echo "<div class = 'center'>";
		
		
		if($filename == "SAIINFO_2")
		{
			echo '<input type="submit" name = "kousinn" value = "登録" 
					class="free" ';
		}
		else
		{
			echo '<input type="submit" name = "kousinn" value = "更新" 
					class = "free" ';
		}
		
		if($errorinfo[1] != "")
		{
			echo 'disabled>';
		}
		else
		{
			echo '>';
		}
		echo '<input type="submit" name = "clear" value = "クリア" 
				class = "free" onClick = "iscansel = false;">';
		echo "</form>";
		echo "</div>";
	}
	else
	{
		$judge = false;
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "<br><br><div = class='center'>";
		echo "<a class = 'title'>".$title1.$title2."不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、".$title2."できません。</a>
				</div>";
		echo '<form action="listJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
<script language="JavaScript"><!--
$(function()
	{
		total();
	});
        
     function total() {
   
     var countnum = document.getElementById("zaikonumber").value;
     countnum = parseInt(countnum);
     
     var num = new Array();
     var syukka = new Array();
     var henkyaku = new Array();
     var dobazaiko = new Array();
     var total = 0;
     var dobatotal = 0;
     
     for ( var i = 0 ; i < countnum; i++ ){
        //在庫入力
          num[i] = $('[id=zaikonum]')[i].value;
          syukka[i] = $('[id=syukkanum]')[i].value;
          henkyaku[i] = $('[id=henkyakunum]')[i].value;
          if(num[i] == '')
          {
              num[i] = 0;
          }    
          num[i] = parseInt(num[i]);
          syukka[i] = parseInt(syukka[i]);
          henkyaku[i] = parseInt(henkyaku[i]);
          }

        for(i = 0; i < num.length; i++){
         
              total = total + num[i];
              dobazaiko[i] = num[i] - syukka[i] + henkyaku[i];
              dobazaiko[i] = parseInt(dobazaiko[i]);
              $('[id=dobazaiko]')[i].value = dobazaiko[i];
              dobatotal = dobatotal + dobazaiko[i];
            }

         if(!total){    
         
            //計算結果表示のinput内を削除
            $('[id=zaikototal]').val('');
            return false;
         }

            $('input[id=zaikototal]').val(total);
             $('input[id=dobatotal]').val(dobatotal);
    };

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("入力内容正常確認。\n情報更新しますがよろしいですか？\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./editComp.php";
			}
		}
	}
--></script>
</body>
</html>


