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
	for ($i =1 ; $i < 11 ; $i++)
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

		$ID  = $i;
//		$ID = str_pad($i, 4, "0", STR_PAD_LEFT);


//--------------------------------------------------------------------//
//                                                                    //
//                              現場作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO genbainfo (GENBAID,GENBANAME,2CODE,GENBASTATUS,ENDDATE)VALUES('".$ID."','現場".$i."',1,'2','2013-6-27')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功 :".$i;
//		}


//--------------------------------------------------------------------//
//                                                                    //
//                              差異作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO saiinfo (4CODE,2CODE,1CODE,SAITYPE,REASON,SAICREATEDATE,SAIUPDATE,SAISTATUS,SAINUM)VALUES(".$ID.",3,1,'2','考え中','2013-6-27 00:00:00','2013-6-27 00:00:00','1',100)";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功 :".$i;
//		}



//--------------------------------------------------------------------//
//                                                                    //
//                              履歴作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功1 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功2 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功3 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功4 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功5 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功6 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功7 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功8 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功9 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功10 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功11 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功12 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功13 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功14 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功15 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功16 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功17 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功18 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'100','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功19 :".$i;
//		}
//		$sql = "INSERT INTO rirekiinfo (CREATEDATE,SAGYOUDATE,4CODE,2CODE,1CODE,IONUM,IOTYPE)VALUES('2013-6-27','2013-6-20',".$ID.",3,1,'200','2')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功20 :".$i;
//		}



//--------------------------------------------------------------------//
//                                                                    //
//                              資材作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO sizaiinfo (SIZAIID,SIZAINAME,SAIKB)VALUES('".$ID."','資材".$ID."','1')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功 :".$i;
//		}



//--------------------------------------------------------------------//
//                                                                    //
//                              在庫作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO zaikoinfo (1CODE,ZAIKONUM)VALUES('".$ID."','10000')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功 :".$i;
//		}



//--------------------------------------------------------------------//
//                                                                    //
//                              出荷作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO syukkainfo (4CODE,1CODE,SYUKKANUM)VALUES(9997,".$ID.",'5000')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功 :".$i;
//		}



//--------------------------------------------------------------------//
//                                                                    //
//                              資材作成                              //
//                                                                    //
//--------------------------------------------------------------------//
//		$sql = "INSERT INTO kokyakuinfo (KOKYAKUID,KOKYAKUNAME)VALUES('".$ID."','顧客".$i."')";
//		$result = $con->query($sql) or ($judge = true);
//		if($judge)
//		{
//			echo $con->error;
//		}
//		else
//		{
//			echo "成功 :".$i;
//		}

	}
?>