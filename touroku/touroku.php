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


<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS" />
</head>

<body>



<?php
	
	
	$syakenn_ini = parse_ini_file('./syaken.ini');													// form.ini呼び出し
	$temp_ini = parse_ini_file('./temp.ini');															// form.ini呼び出し
	$syaken = "";
	$syaken_array = array();
	$user = "";
	$user_array = array();
	$pdf = "";
	$sql="";
	$judge = false;
	
	$con = new mysqli('127.0.0.1','root','hirano', 'LAA0494870-leadplan') or die('データベースとの接続に失敗しました');										//DB接続
	$con->set_charset("cp932") or die('データベースとの接続に失敗しました');																	//文字コード設定
	for ($i =1 ; $i < 10000 ; $i++)
	{
		if(isset($syakenn_ini['syaken'.$i]))
		{
			$syaken = $syakenn_ini['syaken'.$i];
			$user = $syakenn_ini['user'.$i];
			$syaken_array = explode(',',$syaken);
			$sql = "INSERT INTO syakeninfo (1CODE,PLATENO,FIRSTDATE,BODYNO,TYPEDIV,KINDDIV,EXPIRYDATE,3CODE,4CODE) VALUES (".$i.",'".$syaken_array[0]."','".$syaken_array[1]."','".$syaken_array[2]."','".$syaken_array[3]."','".$syaken_array[4]."','".$syaken_array[5]."','".$syaken_array[6]."',".$syaken_array[7].");";

			$result = $con->query($sql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				echo $con->error."<br>";
				$judge = false;
			}
			else
			{
				echo "成功syaken:".$i."<br>";
			}
			if($user != "")
			{
				$user_array = explode(',',$user);
				$sql = "INSERT INTO userinfo (3CODE,USERNAME,USERADD1,USERPOSTCD,USERTELNO,USERFAXNO,USERMAIL) VALUES (".$i.",'".$user_array[0]."','".$user_array[1]."','".$user_array[2]."','".$user_array[3]."','".$user_array[4]."','".$user_array[5]."');";
				
				$result = $con->query($sql) or ($judge = true);																		// クエリ発行
				if($judge)
				{
					echo $con->error."<br>";
					$judge = false;
				}
				else
				{
					echo "成功user:".$i."<br>";
				}
			}
			else
			{
				echo "なしuser:".$i."<br>";
			}
			for($k = 1 ;$k < 5 ; $k++)
			{
				if(isset($temp_ini['pdf'.$i.'-'.$k]))
				{
					$pdf = $temp_ini['pdf'.$i.'-'.$k].".pdf";
					$sql = "INSERT INTO syakenfileinfo ( SFILEPATH , 1CODE ) VALUES ( '".$pdf."' , ".$i." ) ;";
					$result = $con->query($sql) or ($judge = true);																		// クエリ発行
					if($judge)
					{
						echo $con->error."<br>";
						$judge = false;
					}
					else
					{
						echo "成功pdf:".$i."-".$k."<br>";
					}
				}
				else
				{
					break;
				}
			}
		}
		
		else
		{
			break;
		}
	}
?>

</body>
</html>

