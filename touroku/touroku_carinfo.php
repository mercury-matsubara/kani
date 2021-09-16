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
	
	
	function array_to_random_string(array $array, $length ,$count)
	{
		$result ="";
		for($i = 0 ; $i < $length ; $i++ )
		{
			$result.= $array[rand(0, ($count-1))];
		}
		return $result;
	}
	function array_to_random_string2(array $array, $length ,$count)
	{
		$result ="";
		$end = rand(($length -5), $length);
		for($i = 0 ; $i < $end ; $i++ )
		{
			$result.= $array[rand(0, ($count-1))];
		}
		return $result;
	}
	function array_to_random_string3(array $array, $length ,$count)
	{
		return rand(0, ($count-1));
	}
	
	$num = 0;
	$judge = false;
	
	$dummycar_ini = parse_ini_file('./dummycar.ini');													// form.ini呼び出し
	$carname = $dummycar_ini['carname'];
	$carname_array = explode(',',$carname);
	$cargrade = $dummycar_ini['cargrade'];
	$cargrade_array = explode(',',$cargrade);
	
	$mail_array = array('m.d.sendadd1@gmail.com','m.d.sendadd2@gmail.com','m.d.sendadd3@gmail.com','m.d.sendadd4@gmail.com','m.d.sendadd5@gmail.com','m.d.sendadd6@gmail.com','m.d.sendadd7@gmail.com','m.d.sendadd8@gmail.com','m.d.sendadd9@gmail.com','m.d.sendadd10@gmail.com');
	$plate_array = array('あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く', 'け', 'こ','　','０','１','２','３','４','５','６','７','８','９');
	$year_array1 = array('26', '25', '24', '23', '22', '21', '20', '19', '18', '17','16','15','14','13','12','11','10','9','8','7','6');
	$year_array2 = array('2014', '2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005','2004','2003','2002','2001','2000','1999','1998','1997','1996','1995','1994');
	$month_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12');
	$day_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');
	$num_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
	
	$con = new mysqli('127.0.0.1','root','hirano', 'LAA0494870-leadplan') or die('データベースとの接続に失敗しました');										//DB接続
	$con->set_charset("cp932") or die('データベースとの接続に失敗しました');																	//文字コード設定
	for ($i =0 ; $i < 10000 ; $i++)
	{
		$CARNAME = array_to_random_string($carname_array, 1 ,count($carname_array));
		$CARGRADE = array_to_random_string($cargrade_array, 1 ,count($cargrade_array));
		$sql = "INSERT INTO carinfo (CARNAME ,CARGRADE )VALUES('".$CARNAME."','".$CARGRADE."');";
		$result = $con->query($sql) or ($judge = true);
		if($judge)
		{
			echo $con->error;
			$judge = false;
		}
		else
		{
			echo "成功 :".$i;
		}
	}
?>

</body>
</html>

