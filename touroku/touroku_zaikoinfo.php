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
	
	
	$name_ini = parse_ini_file('./dummyname.ini');													// form.ini呼び出し
	
	$name = $name_ini['name'];
//	echo $name;
	
	$name_array = explode(',',$name);
//	print_r ($name_array);

	$PLATENO = "";
	$FIRSTDATE = "";
	$BODYNO = "";
	$TYPEDIV = "";
	$KINDDIV = "";
	$EXPIRYDATE = "";
	$CODE4 = 1;
	$CODE3 = 1;
	$judge = false;
	
	$plate_array = array('あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く', 'け', 'こ','　','０','１','２','３','４','５','６','７','８','９');
	$year_array1 = array('26', '25', '24', '23', '22', '21', '20', '19', '18', '17','16','15','14','13','12','11','10','9','8','7','6');
	$year_array2 = array('2014', '2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005','2004','2003','2002','2001','2000','1999','1998','1997','1996','1995','1994');
	$month_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12');
	$day_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');
	$num_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
	
//	$con = new mysqli('127.0.0.1','root','hirano', 'LAA0494870-leadplan') or die('データベースとの接続に失敗しました');										//DB接続
	$con = new mysqli('127.0.0.1','root','hirano', 'kani') or die('データベースとの接続に失敗しました');										//DB接続
	$con->set_charset("cp932") or die('データベースとの接続に失敗しました');																	//文字コード設定
	for ($i =0 ; $i < 10000 ; $i++)
	{
//		$BUYPRICE = array_to_random_string2($num_array, 9 ,count($num_array));
//		$BUYTAX = array_to_random_string2($num_array, 8 ,count($num_array));
//		$CARRECYCLE = array_to_random_string2($num_array, 8 ,count($num_array));
//		$BUYCOST = array_to_random_string2($num_array, 8 ,count($num_array));
//		$CARTAX = array_to_random_string2($num_array, 8 ,count($num_array));
//		$date_year2 = array_to_random_string($year_array2, 1 ,count($year_array2));
//		$date_month = array_to_random_string($month_array, 1 ,count($month_array));
//		$date_day = array_to_random_string($day_array, 1 ,count($day_array));
//		$BUYDATE = $date_year2.'-'.$date_month.'-'.$date_day;
//		$date_year1 = array_to_random_string($year_array1, 1,count($year_array1));
//		$MAKEDATE = "平成 ".$date_year1."年";
//		$sql = "INSERT INTO ZAIKOINFO (BUYPRICE ,BUYTAX ,CARRECYCLE ,BUYCOST ,CARTAX ,BUYDATE ,MAKEDATE ,4CODE )VALUES('".$BUYPRICE."','".$BUYTAX."','".$CARRECYCLE."','".$BUYCOST."','".$CARTAX."','".$BUYDATE."','".$MAKEDATE."',".$CODE4.");";
		$ID = str_pad($i, 5, "0", STR_PAD_LEFT);
		$sql = "INSERT INTO genbainfo (GENBAID,GENBANAME,2CODE)VALUES('".$ID."','現場".$i."',1)";
		$result = $con->query($sql) or ($judge = true);
		if($judge)
		{
			echo $con->error;
		}
		else
		{
			echo "成功 :".$i;
		}
	}
?>