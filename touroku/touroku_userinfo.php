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
	
	$name_ini = parse_ini_file('./dummyname.ini');													// form.ini�Ăяo��
	$add_ini = parse_ini_file('./dummyadd.ini');													// form.ini�Ăяo��
	$name = $name_ini['name'];
	$name_array = explode(',',$name);
	$add = $add_ini['add'];
	$add_array = explode(',',$add);
	$addcode = $add_ini['addcode'];
	$addcode_array = explode(',',$addcode);
	
	$mail_array = array('m.d.sendadd1@gmail.com','m.d.sendadd2@gmail.com','m.d.sendadd3@gmail.com','m.d.sendadd4@gmail.com','m.d.sendadd5@gmail.com','m.d.sendadd6@gmail.com','m.d.sendadd7@gmail.com','m.d.sendadd8@gmail.com','m.d.sendadd9@gmail.com','m.d.sendadd10@gmail.com');
	$plate_array = array('��', '��', '��', '��', '��', '��', '��', '��', '��', '��','�@','�O','�P','�Q','�R','�S','�T','�U','�V','�W','�X');
	$year_array1 = array('26', '25', '24', '23', '22', '21', '20', '19', '18', '17','16','15','14','13','12','11','10','9','8','7','6');
	$year_array2 = array('2014', '2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005','2004','2003','2002','2001','2000','1999','1998','1997','1996','1995','1994');
	$month_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12');
	$day_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');
	$num_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
	
	$con = new mysqli('127.0.0.1','root','hirano', 'LAA0494870-leadplan') or die('�f�[�^�x�[�X�Ƃ̐ڑ��Ɏ��s���܂���');										//DB�ڑ�
	$con->set_charset("cp932") or die('�f�[�^�x�[�X�Ƃ̐ڑ��Ɏ��s���܂���');																	//�����R�[�h�ݒ�
	for ($i =0 ; $i < 10000 ; $i++)
	{
		$num = array_to_random_string3($addcode_array, 1 ,count($addcode_array));
		$USERNAME = array_to_random_string($name_array, 1 ,count($name_array));
		$USERADD1 = $add_array[$num];
		$USERPOSTCD = $addcode_array[$num];
		$USERTELNO = array_to_random_string($num_array, 10 ,count($num_array));
		$USERFAXNO = array_to_random_string($num_array, 10 ,count($num_array));
		$USERMAIL = array_to_random_string($mail_array, 1 ,count($mail_array));
		$sql = "INSERT INTO USERINFO (USERNAME  ,USERADD1 ,USERPOSTCD ,USERTELNO ,USERFAXNO ,USERMAIL )VALUES('".$USERNAME."','".$USERADD1."','".$USERPOSTCD."','".$USERTELNO."','".$USERFAXNO."','".$USERMAIL."');";
		$result = $con->query($sql) or ($judge = true);
		if($judge)
		{
			echo $con->error;
			$judge = false;
		}
		else
		{
			echo "���� :".$i;
		}
	}
?>