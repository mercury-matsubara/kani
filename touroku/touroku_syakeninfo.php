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
//	$year_array2 = array('2014', '2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005','2004','2003','2002','2001','2000','1999','1998','1997','2017','2015','2016');
	$year_array2 = array('2014');
//	$month_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12');
	$month_array = array('5');
	$day_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');
	$num_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9','0');
	
	$con = new mysqli('127.0.0.1','root','hirano', 'LAA0494870-leadplan') or die('データベースとの接続に失敗しました');										//DB接続
	$con->set_charset("cp932") or die('データベースとの接続に失敗しました');																	//文字コード設定
	for ($i =0 ; $i < 1000 ; $i++)
	{
		$CODE4 = $i + 1;
		$CODE3 = $i + 1;
		$PLATENO = array_to_random_string($plate_array, 12 ,count($plate_array));
		$date_year1 = array_to_random_string($year_array1, 1,count($year_array1));
		$date_month = array_to_random_string($month_array, 1,count($month_array));
		$FIRSTDATE = "平成 ".$date_year1."年".$date_month."月";
		$date_day = array_to_random_string($day_array, 1,count($day_array));
		$BODYNO = array_to_random_string($num_array, 10 ,count($num_array));
		$TYPEDIV = array_to_random_string($num_array, 10 ,count($num_array));
		$KINDDIV = array_to_random_string($num_array, 10 ,count($num_array));
		$date_year2 = array_to_random_string($year_array2, 1 ,count($year_array2));
		$date_month = array_to_random_string($month_array, 1 ,count($month_array));
		$date_day = array_to_random_string($day_array, 1 ,count($day_array));
		$EXPIRYDATE = $date_year2.'-'.$date_month.'-'.$date_day;
		$sql = "INSERT INTO syakeninfo (PLATENO ,FIRSTDATE ,BODYNO ,TYPEDIV ,KINDDIV,EXPIRYDATE,4CODE,3CODE)VALUES('".$PLATENO."','".$FIRSTDATE."','".$BODYNO."','".$TYPEDIV."','".$KINDDIV."','".$EXPIRYDATE."',".$CODE4.",".$CODE3.");";
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