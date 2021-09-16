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
	startJump($_POST);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<script language="JavaScript"><!--
	history.forward();
--></script>
</head>
<body>
<?php
	session_regenerate_id();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$limit_num = $form_ini[$filename]['limit'];
	$main_table =$form_ini[$filename]['use_maintable_num'];
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if (strstr($key, 'serch'))
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
			exit();
		}
		if(strstr($key, 'edit_'))
		{
			$idarray = explode('_',$key);
			$_SESSION['list']['id'] = $idarray[1];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/edit.php");
			exit();
		}
		if($key == 'next')
		{
			$_SESSION['list']['limitstart'] += $limit_num ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
			exit();
		}
		if($key == 'back')
		{
			$_SESSION['list']['limitstart'] -= $limit_num ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
			exit();
		}
		if($key == 'cancel')
		{
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
			unset($_SESSION['edit']);
			unset($_SESSION['data']);
			unset($_SESSION['upload']);
			unset($_SESSION['list']['id']);
			if(isset($_SESSION['pre_post']['true']))
			{
				unset($_SESSION['pre_post']['true']);
			}
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
			exit();
		}
		if($key == 'kousinn')
		{
			$counter = 0;
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
			foreach($_FILES as $form => $value)
			{
				if($value['size'] != 0)
				{
					$sessionid = session_id();
					$timestamp = date_create('NOW');
					$timestamp = date_format($timestamp, "YmdHis");
					$file_array = explode('.',$value['name']);
					$extention = $file_array[(count($file_array)-1)];
					$filepath = './temp/';
					$filepath .= $timestamp.'_'.session_id().'_'.$counter.'.'.$extention;
					move_uploaded_file( $value['tmp_name'], $filepath );
					$counter++;
					$_POST[$form] = $filepath;
					$_SESSION['upload'][$form] = $filepath;
					$filepath ="";
				}
			}
			$_SESSION['files'] = $_FILES;
			if(isset($_SESSION['edit']['uniqe']))
			{
				$uniqe = $_SESSION['edit']['uniqe'] ;
				$_SESSION['edit'] = $_POST;
				$_SESSION['edit']['uniqe'] = $uniqe;
			}
			else
			{
				$_SESSION['edit'] = $_POST;
			}
			$_SESSION['edit'][$main_table.'CODE'] = $_SESSION['list']['id'];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/editCheck.php");
			exit();
		}
		if($key == 'delete')
		{
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/delete.php");
			exit();
		}
		if ($key == 'label')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listCar.php");
			exit();
		}
		if ($key == 'mail')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listStock.php");
			exit();
		}
		if ($key == 'clear')
		{
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/edit.php");
			exit();
		}
	}
        // 2018/06/29 追加対応 ↓
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2'
           || $filename == 'SAIINFO_2'
           || $filename == 'HANSHUTUTEISEI_2' || $filename == 'HANNYUTEISEI_2')
        // 2018/06/29 追加対応 ↑
	{
		$_SESSION['list'] = $_POST;
		$_SESSION['post'] = null;
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertrireki.php");
		exit();
	}
?>
</body>
</html>



