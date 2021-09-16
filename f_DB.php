<?php


/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////




/***************************************************************************
function dbconect()


引数			なし

戻り値	$con	mysql接続済みobjectT
***************************************************************************/

function dbconect(){


//-----------------------------------------------------------//
//                                                           //
//                     DBアクセス処理                        //
//                                                           //
//-----------------------------------------------------------//

	
	//-----------------------------//
	//   iniファイル読み取り準備   //
	//-----------------------------//
	$db_ini_array = parse_ini_file("./ini/DB.ini",true);																// DB基本情報格納.iniファイル
	
	//-------------------------------//
	//   iniファイル内情報取得処理   //
	//-------------------------------//
	$host = $db_ini_array["database"]["host"];																			// DBサーバーホスト
	$user = $db_ini_array["database"]["user"];																			// DBサーバーユーザー
	$password = $db_ini_array["database"]["userpass"];																	// DBサーバーパスワード
	$database = $db_ini_array["database"]["database"];																	// DB名
	
	
	//------------------------//
	//     DBアクセス処理     //
	//------------------------//
	$con = new mysqli($host,$user,$password, $database, "3306") or die('1'.$con->error);					// DB接続
	
	$con->set_charset("cp932") or die('2'.$con->error);												// cp932を使用する
	return ($con);
}


/************************************************************************************************************
function login($userName,$usserPass)


引数1	$userName				ユーザー名
引数2	$userPass				ユーザーパスワード

戻り値	$result					ログイン結果
************************************************************************************************************/
	
function login($userName,$userPass){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$Loginsql = "select * from loginuserinfo where LUSERNAME = '".$userName."' AND LUSERPASS = '".$userPass."' ;";		// ログインSQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$log_result = false;																								// ログイン判断
	$rownums = 0;																										// 検索結果件数
	
	//------------------------//
	//    ログイン検索処理    //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($Loginsql);																					// クエリ発行
	$rownums = $result->num_rows;																						// 検索結果件数取得
	
	//------------------------//
	//    ログイン判断処理    //
	//------------------------//
	if ($rownums == 1)
	{
		$log_result = true;																								// ログイン結果true
	}
	return ($log_result);
	
}


/************************************************************************************************************
function limit_date()


引数	なし					ユーザー名

戻り値	$result					有効期限結果
************************************************************************************************************/
	
function limit_date(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_format($date, "Y-m-d");
	$Loginsql = "select * from systeminfo;";																		// 有効期限SQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$limit_result = 0;																								// 有効期限判断
	$rownums = 0;																									// 検索結果件数
	$startdate = "";
	$enddate = "";
	$befor_month = "";
	$message = "";
	$result_limit = array();
	
	//------------------------//
	//    ログイン検索処理    //
	//------------------------//
	$con = dbconect();																								// db接続関数実行
	$result = $con->query($Loginsql) or die($con-> error);														// クエリ発行
	$rownums = $result->num_rows;																					// 検索結果件数取得
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$startdate = $result_row['STARTDATE'];
	}
	
	//------------------------//
	//    ログイン判断処理    //
	//------------------------//
	$enddate = date_create($startdate);
	$enddate = date_add($enddate, date_interval_create_from_date_string('1 year'));
	$enddate = date_sub($enddate, date_interval_create_from_date_string('1 days'));
	$enddate = date_format($enddate, 'Y-m-d');
	$befor_month = date_create($enddate);
	$befor_month = date_format($befor_month, 'Y-m-01');
	$befor_month = date_create($befor_month);
	$befor_month = date_sub($befor_month, date_interval_create_from_date_string('1 month'));
	$befor_month = date_format($befor_month, 'Y-m-d');
	if($enddate >= $date)
	{
		$limit_result = 1;
		if($befor_month <= $date)
		{
			$enddate2 = date_create($enddate);
			$date2 = date_create($date);
			$limit_result = 2;
			$interval = date_diff($date2, $enddate2);
			$message = $interval->format('%a');
		}
	}
	else
	{
		$limit_result = 0;
	}
	$result_limit[0] = $limit_result;
	$result_limit[1] = $message;
	return ($result_limit);
	
}
/************************************************************************************************************
function UserCheck($userID,$userPass)


引数1	$userID						ユーザー名
引数2	$userPass					ユーザーパス

戻り値	$columnName					既に登録されているカラム名
************************************************************************************************************/
	
function UserCheck($userID,$userPass){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$checksql1 = "select * from loginuserinfo where LUSERNAME ='".$userID."' OR LUSERPASS ='".$userPass."' ;";			// 既登録確認SQL文1
	$checksql2 = "select * from loginuserinfo where LUSERNAME ='".$userID."' ;";										// 既登録確認SQL文2
	$checksql3 = "select * from loginuserinfo where LUSERPASS ='".$userPass."' ;";										// 既登録確認SQL文3
	
	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = ""		;																							// 既に登録されているカラム名宣言
	$rownums = 0;																										// 検索結果件数
	
	//------------------------//
	//      チェック処理      //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($checksql1);																					// クエリ発行
	$rownums = $result->num_rows;																						// 検索結果件数取得
	if($rownums == 0)
	{
		return($columnName);
	}
	else
	{
		$result = $con->query($checksql2);																				// クエリ発行
		$rownums = $result->num_rows;																					// 検索結果件数取得
		if($rownums != 0)
		{
			$columnName .= 'LUSERNAME';
		}
		return($columnName);
	}
	
	
	
}


/************************************************************************************************************
function insertUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function insertUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$userID = $_SESSION['insertUser']['uid'];
	$userPass = $_SESSION['insertUser']['pass'];
	$insertsql = "insert into loginuserinfo (LUSERNAME,LUSERPASS) value ('".$userID."','".$userPass."') ;";				// 既登録確認SQL文

	//------------------------//
	//        登録処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$con->query($insertsql);																							// クエリ発行
}


/************************************************************************************************************
function selectUser()


引数	なし

戻り値	list			listhtml
************************************************************************************************************/
	
function selectUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	if(!isset($_SESSION['listUser']))
	{
		$_SESSION['listUser']['limit'] = ' limit 0,10';
		$_SESSION['listUser']['limitstart'] =0;
		$_SESSION['listUser']['where'] ='';
		$_SESSION['listUser']['orderby'] ='';
	}
	
	//------------------------//
	//          定数          //
	//------------------------//
	$limit = $_SESSION['listUser']['limit'];																			// limit
	$limitstart = $_SESSION['listUser']['limitstart'];																	// limit開始位置
	$where = $_SESSION['listUser']['where'];																			// 条件
	$orderby = $_SESSION['listUser']['orderby'];																		// order by 条件
	$totalSelectsql = "SELECT * from loginuserinfo ".$where." ;";														// 管理者全件取得SQL
	$selectsql = "SELECT * from loginuserinfo ".$where.$orderby.$limit." ;";											// 管理者リスト分取得SQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$totalcount = 0;
	$listcount = 0;
	$list_str = "";
	$counter = 1;
	$id ="";
	
	//------------------------//
	//        登録処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($totalSelectsql);																				// クエリ発行
	$totalcount = $result->num_rows;																					// 検索結果件数取得
	$result = $con->query($selectsql);																					// クエリ発行
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_str .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_str .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_str .= "<table class = 'list' ><thead><tr>";
	$list_str .= "<th>No.</th>";
	$list_str .= "<th>管理者ID</th>";
	$list_str .= "<th>編集</th>";
	$list_str .= "</tr></thead>";
	$list_str .= "<tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if(($counter%2) == 1)
		{
        // 2018/08 変更 ↓
			$id = "id = 'stripe_none'";
        // 2018/08 変更 ↑
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_str .= "<tr><td ".$id." class = 'td1' >".($limitstart + $counter)."</td>";
		$list_str .= "<td ".$id."class = 'td2' >".$result_row['LUSERNAME']."</td>";
		$list_str .= "<td ".$id." class = 'td3'><input type='submit' name='"
					.$result_row['LUSERID']."_edit' value = '編集'></td></tr>";
		$counter++;
	}
	$list_str .= "</tbody>";
	$list_str .= "</table>";
        $list_str .= "<div class='addcenter'>";
	$list_str .= "<div class ='listcenter'>";
	$list_str .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></div><div class ='listcenter'>";
	$list_str .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></div>";
	return($list_str);
}
  
/************************************************************************************************************
function selectID($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function selectID($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$selectidsql = "SELECT * FROM loginuserinfo where LUSERID = ".$id." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($selectidsql);																				// クエリ発行
	if($result->num_rows == 1)
	{
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
	}
	return($result_array);
}

/************************************************************************************************************
function updateUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function updateUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$userID = $_SESSION['editUser']['uid'];
	$userPass = $_SESSION['editUser']['newpass'];
	$id = $_SESSION['listUser']['id'];
	$updatesql = "UPDATE loginuserinfo SET LUSERNAME ='"
				.$userID."', LUSERPASS = '".$userPass."' where LUSERID = ".$id." ;";									// 更新SQL文

	//------------------------//
	//        更新処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$con->query($updatesql);																							// クエリ発行
}
/************************************************************************************************************
function deleteUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function deleteUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$id = $_SESSION['result_array']['LUSERID'];
	$deletesql = "DELETE FROM loginuserinfo where LUSERID = ".$id." ;";													// 更新SQL文

	//------------------------//
	//        更新処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$con->query($deletesql);																							// クエリ発行
}



/************************************************************************************************************
function makeList($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																									// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";                           // 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";			// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>編集</a></th></tr>";
	}
	$list_html .="</thead><tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$disabled = "";
                // 2018/06/29 追加対応 ↓
                if(isset( $result_row['GENBASTATUS'] ))
                {
                        if( $result_row['GENBASTATUS'] != '0' ) 
                        {
                                $disabled = "disabled";
                        }
                }
                // 2018/06/29 追加対応 ↑
		$list_html .="<tr class='orange'>";
		if(($counter%2) == 1)
		{
        // 2018/08 変更 ↓
			$id = "id = 'stripe_none'";
        // 2018/08 変更 ↑
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$columns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 3)
			{
				$class = "class = 'right' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".
			$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
//			$list_html .= "<td ".$id."><input type='submit' name='edit_".
//							$result_row[$main_table.'CODE']."' value = '編集' ".$disabled."></td>";
			$list_html .= "<td ".$id."  valign='top'><input type='submit' name='edit_".
							$result_row[$main_table.'CODE']."' value = '編集' ".$disabled."></td>";
		}
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
        if($filename != "SIZAIINFO_2" && $filename != "KOKYAKUINFO_2" && $filename != "DOBAINFO_2")
        {
            if($filename == "SAILIST_2")
            {
                $list_html .= "<div class='saicenter'>";
            }
            else
            {
                $list_html .= "<div class='addcenter'>";
                 //$list_html .= "<div class='box'>";
            }    
            
        }
       
        
	$list_html .= "<div class = 'listcenter'>";
	$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div><div class = 'listcenter'>";
	$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	return ($list_html);
}



/************************************************************************************************************
function makeList_Modal($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function makeList_Modal($sql,$post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit = $_SESSION['Modal']['limit'];																				// limit
	$limitstart = $_SESSION['Modal']['limitstart'];																		// limit開始位置
	$resultcolumns = $form_ini[$tablenum]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	//------------------------//
	//          振分          //
	//------------------------//
	
	$filename = $_SESSION['filename'];
	
	
	
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
            //-------------------------↓2018/09/18 土場在庫追加対応-------------------------------//
		$columns = '402,403,202,203,1003';
		$columns_array = explode(',',$columns);
            //-------------------------↑2018/09/18 土場在庫追加対応-------------------------------//    
	}
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																								// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>選択</a></th>";
	for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
	{
		$title_name = $form_ini[$resultcolumns_array[$i]]['link_num'];
                if($title_name != "土場名")
                {    
                    $list_html .="<th><a class ='head'>".$title_name."</a></th>";
                }
	}
	$list_html .="<tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
        // 2018/08 変更 ↓
			$id = "id = 'stripe_none'";
        // 2018/08 変更 ↑
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_html .= "<td ".$id." class = 'center'>";
		$column_value = $result_row[$tablenum.'CODE'].'#$';
		$form_name = $tablenum.'CODE,';
		$form_type .= '9,';
		for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
		{
			$field_name = $form_ini[$resultcolumns_array[$i]]['column'];
			$format = $form_ini[$resultcolumns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$resultcolumns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 4)
			{
				$class = "class = 'right'";
			}
			else
			{
				$class = "";
			}
            //---------------------↓2018/09/20 土場在庫追加対応-----------------------------------------------------------//            
                        if($field_name != "DOBANAME")
                        {    
                            $row .="<td ".$id." ".$class." ><a class ='body'>"
						.$value."</a></td>";
                        }    
            //---------------------↑2018/09/20 土場在庫追加対応-----------------------------------------------------------//            
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$columns_array[$i]]['form_type'];
			$form_value = formvalue_return($columns_array[$i],$value,$type);
			$form_name .= $form_value[0];
			$column_value .= $form_value[1];
			$form_type .=  $form_value[2];
		}
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
		$list_html .= '<input type ="radio" name = "radio" onClick="select_value(\''
						.$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';
		$list_html .= "</td>";
		$list_html .= $row;
		$list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<table><tr><td>";
	$list_html .= "<input type='submit' class = 'button' name ='back' value ='戻る'";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td><td>";
	$list_html .= "<input type='submit' class = 'button'  name ='next' value ='進む'";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	return ($list_html);
}

/************************************************************************************************************
function existCheck($post,$tablenum,$type)

引数1		$post							登録フォーム入力値
引数2		$tablenum						テーブル番号
引数3		$type							1:insert 2:edit 3:delete

戻り値		$errorinfo						既登録確認結果
************************************************************************************************************/
function existCheck($post,$tablenum,$type){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// SQL関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$uniquecolumn = $form_ini[$filename]['uniquecheck'];
	$uniquecolumn_array = explode(',',$uniquecolumn);
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$master_tablenum_array = explode(',',$master_tablenum);
	//------------------------//
	//          変数          //
	//------------------------//
	$errorinfo = array();
	$errorinfo[0] = "";
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$table_title = "";
	$counter = 1;
	$syorimei = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	switch($type)
	{
	case 1 :
		$syorimei = "登録";
		break;
	case 2 :
		$syorimei = "編集";
		break;
	case 3 :
		$syorimei = "削除";
		break;
	default :
		break;
	}
	$con = dbconect();																									// db接続関数実行
	if($type == 2)
	{
		$table_title = $form_ini[$tablenum]['table_title'];
		$code = $tablenum.'CODE';
		$codeValue = $post[$code];
		$sql = idSelectSQL($codeValue,$tablenum,$code);
		$result = $con->query($sql) or ($judge = true);																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		if($result->num_rows == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."情報が削除されているため".
									$syorimei."できません。</a></div><br>";
			$counter++;
		}
		else
		{
			$errorinfo[$counter] = "";
			$counter++;
		}
	}
	for( $j = 0 ; $j < count($uniquecolumn_array) ; $j++)
	{
		if($uniquecolumn_array[$j] == "")
		{
			break;
		}
		$sql = uniqeSelectSQL($post,$tablenum,$uniquecolumn_array[$j]);
		if($sql != '')
		{
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			if($result->num_rows != 0 )
			{
				$errorinfo[0] .= $uniquecolumn_array[$j].",";
			}
		}
	}
	for($k = 0 ; $k < count($master_tablenum_array) ; $k++ )
	{
		if($master_tablenum == '')
		{
			break;
		}
		$table_title = $form_ini[$master_tablenum_array[$k]]['table_title'];
		$code = $master_tablenum_array[$k].'CODE';
		$codeValue = $post[$code];
		$sql = idSelectSQL($codeValue,$master_tablenum_array[$k],$code);
		$result = $con->query($sql) or ($judge = true);																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		if($result->num_rows == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."情報が削除されているため".
									$syorimei."できません。</a></div><br>";
			$counter++;
		}
	}
	return ($errorinfo);
}

/************************************************************************************************************
function insert($post)

引数		$post						入力内容

戻り値		なし
************************************************************************************************************/
function insert($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = InsertSQL($post,$tablenum,"");
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge =false;
	}
	if($main_table_type == 0)
	{
		$main_CODE = $con->insert_id;
		$post[$tablenum.'CODE'] = $main_CODE;
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if($list_tablenum_array[$i] == "" )
			{
				break;
			}
			$over =getover($post,$list_tablenum_array[$i]);
			for( $j = 0; $j < count($over) ; $j++ )
			{
				$sql = InsertSQL($post,$list_tablenum_array[$i],$over[$j]);
				$result = $con->query($sql) or ($judge = true);																// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
				}
			}
		}
	}

        if($filename == 'SIZAIINFO_1')
	{
            //-----------------------2018/10/02 土場追加対応--------------------------------------------------------//
		$main_CODE = $con->insert_id;
                $doba = "SELECT 10CODE FROM dobainfo;";
                $doba_sql = $con->query($doba);
                while($doba_row = $doba_sql->fetch_array(MYSQLI_ASSOC))
                {
                    $dobacode[] = $doba_row['10CODE']; 
                }
                for($i = 0; $i < count($dobacode); $i++)
                {
                    $sql = "INSERT INTO zaikoinfo (1CODE,ZAIKONUM,10CODE) VALUES (".$main_CODE.",0,$dobacode[$i])";
                    $result = $con->query($sql) or ($judge = true);																// クエリ発行
                    
                }
            //-----------------------2018/10/02 土場追加対応--------------------------------------------------------//
		if($judge)
                {
                    error_log($con->error,0);
                }
	}   //-----------------------↓2018/10/02 土場追加対応--------------------------------------------------------//
        else if($filename == 'DOBAINFO_1')
        {
            $i = 0;
            $main_CODE = $con->insert_id;
            $dobasql = "SELECT 1CODE FROM zaikoinfo WHERE zaikoinfo.10CODE <> ".$main_CODE." GROUP BY zaikoinfo.1CODE;";
            $doba_result = $con->query($dobasql);
            while($doba_row = $doba_result->fetch_array(MYSQLI_ASSOC))
            {
                  $sizaicode[$i] = $doba_row['1CODE']; 
                  $i++;
            }
            for($i = 0; $i < count($sizaicode); $i++)
            {
                  $sql = "INSERT INTO zaikoinfo (1CODE,ZAIKONUM,10CODE) VALUES (".$sizaicode[$i].",0,$main_CODE)";
                  $result = $con->query($sql) or ($judge = true);																// クエリ発行
                    
            }
      //-----------------------↑2018/10/02 土場追加対応--------------------------------------------------------//
            if($judge)
            {
                error_log($con->error,0);
            }
            
            
        }    
	
}

/************************************************************************************************************
function make_post($main_codeValue)

引数		$main_codeValue						メインテーブルのプライマリー番号

戻り値		なし
************************************************************************************************************/
function make_post($main_codeValue){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$table_type = $form_ini[$tablenum]['table_type'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$master_tablenum_array = explode(',',$master_tablenum);
	$uniqecolumns = $form_ini[$filename]['uniquecheck'];
	$uniqecolumns_array = explode(',',$uniqecolumns);
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$over = array();
	$form_name = '';
	$form_type = '';
	$form_param = array();
	$names_array = array();
	$valus_array = array();
	$counter = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$_SESSION['edit'][$code] = $main_codeValue;
	$sql = idSelectSQL($main_codeValue,$tablenum,$code);
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		foreach($result_row as $key => $value)
		{
                        // 2018/06/29 追加対応 ↓
                        if( $key == "DELETED" )
                        {
                                // DELETEDは表示しない
                                continue;
                        }
                        // 2018/06/29 追加対応 ↑
			$form_name = $param_ini[$key]['column_num'];
			foreach($uniqecolumns_array as $uniqevalue)
			{
				if(strstr($uniqevalue, $form_name) == true)
				{
					$_SESSION['edit']['uniqe'][$form_name] = $value;
				}
			}
			$form_type = $form_ini[$form_name]['form_type'];
			$form_param = formvalue_return($form_name,$value,$form_type);
			$names_array = explode(',',$form_param[0]);
			$valus_array = explode('#$',$form_param[1]);
			for($i = 0 ; $i < count($valus_array) ; $i++ )
			{
				$_SESSION['edit'][$names_array[$i]] = $valus_array[$i];
			}
		}
	}
//	if($master_tablenum != '' && $table_type != 1)
	if($master_tablenum != '')
	{
		for($i = 0 ; $i < count($master_tablenum_array) ; $i++ )
		{
			$code = $master_tablenum_array[$i].'CODE';
			$sql = idSelectSQL($_SESSION['edit'][$code],$master_tablenum_array[$i],$code);
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				foreach($result_row as $key => $value)
				{
                                        // 2018/06/29 追加対応 ↓
                                        if( $key == "DELETED" )
                                        {
                                                // DELETEDは表示しない
                                                continue;
                                        }
					$form_name = $param_ini[$key]['column_num'];
					foreach($uniqecolumns_array as $uniqevalue)
					{
						if(strpos($uniqevalue, $form_name) !== false)
						{
							$_SESSION['edit']['uniqe'][$form_name] = $value;
						}
					}
					$form_type = $form_ini[$form_name]['form_type'];
					$form_param = formvalue_return($form_name,$value,$form_type);
					$names_array = explode(',',$form_param[0]);
					$valus_array = explode('#$',$form_param[1]);
					for($j = 0 ; $j < count($valus_array) ; $j++ )
					{
						$_SESSION['edit'][$names_array[$j]] = $valus_array[$j];
					}
				}
			}
		}
	}
	
	if($list_tablenum != '' && $table_type != 1)
//	if($list_tablenum != '')
	{
		for($i = 0 ; $i < count($list_tablenum_array) ; $i++ )
		{
			$code = $tablenum.'CODE';
			$sql = idSelectSQL($main_codeValue,$list_tablenum_array[$i],$code);
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				foreach($result_row as $key => $value)
				{
					$form_name = $param_ini[$key]['column_num'];
					foreach($uniqecolumns_array as $uniqevalue)
					{
						if(strpos($uniqevalue, $form_name) !== false)
						{
							$_SESSION['edit']['uniqe'][$form_name] = $value;
						}
					}
					$form_type = $form_ini[$form_name]['form_type'];
					$form_param = formvalue_return($form_name,$value,$form_type);
					$names_array = explode(',',$form_param[0]);
					$valus_array = explode('#$',$form_param[1]);
					for($j = 0 ; $j < count($valus_array) ; $j++ )
					{
						$_SESSION['data'][$list_tablenum_array[$i]][$counter][$names_array[$j]] = $valus_array[$j];
					}
				}
				$counter++;
			}
			$counter = 0;
		}
	}
}


/************************************************************************************************************
function update($post)

引数		$post								入力内容

戻り値		なし
************************************************************************************************************/
function update($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = UpdateSQL($post,$tablenum,"");
        
        //-----------------------------↓2018/10/02 土場追加対応----------------------------//
        if($filename == "ZAIKOINFO_2")
        {
            for($i = 0; $i < count($sql); $i++ )
            {
                $result = $con->query($sql[$i]) or ($judge = true);    // クエリ発行
            } 
        }    
        else
        {
            $result = $con->query($sql) or ($judge = true);    // クエリ発行
        }    
        //-----------------------------↑2018/10/02 土場追加対応----------------------------//
	if($judge)
	{
		error_log($con->error,0);
	}
	if($main_table_type == 0)
	{
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if(isset($post['delete'.$list_tablenum_array[$i]]))
			{
				$delete = $post['delete'.$list_tablenum_array[$i]];
				for($j = 0 ; $j < count($delete) ; $j++)
				{
					$delete_param = explode(':',$delete[$j]);
					$delete_path = $delete_param[0];
					$delete_CODE = $delete_param[1];
					$tablenum = $list_tablenum_array[$i];
					$code = $tablenum.'CODE';
					if(file_exists($delete_path))
					{
						unlink($delete_path);
					}
					$sql = DeleteSQL($delete_CODE,$tablenum,$code);
					$result = $con->query($sql) or ($judge = true);																// クエリ発行
					if($judge)
					{
						error_log($con->error,0);
					}
				}
			}
		}
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if($list_tablenum_array[$i] == "" )
			{
				break;
			}
			$over =getover($post,$list_tablenum_array[$i]);
			for( $j = 0; $j < count($over) ; $j++ )
			{
				$sql = InsertSQL($post,$list_tablenum_array[$i],$over[$j]);
				$result = $con->query($sql) or ($judge = true);																// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
				}
			}
		}
	}
	
}




/************************************************************************************************************
function make_csv($post)

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_csv($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = array();
	$isonce = true;
	$csv = "";
	$where_csv = "";
	$header_csv = "";
	$value_csv = "";
	$header = "";
	$where = "";
	$path = "";
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	
	
	
	if($filename == 'HENKYAKUINFO_2')
	{
		$post['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuSQL($post);
		$sql = SQLsetOrderby($post,$filename,$sql);
	}
	else if($filename == 'SYUKKAINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($post,$filename,$sql);
	}
	else if($filename == 'GENBALIST_2' || $filename == 'SIZAILIST_2' || $filename == 'ZAIKOINFO_2')
	{
		$sql = itemListSQL($post);
		$sql = SQLsetOrderby($post,$filename,$sql);
	}
	else
	{
		$sql = joinSelectSQL($post,$tablenum);
		$sql = SQLsetOrderby($post,$filename,$sql);
	}
	//-------------------------↓2018/10/04 土場追加対応------------------------------------------//
	//csv作成　資材コード順
        if($filename == 'ZAIKOINFO_2')
        {
            
            
            $result = $con->query($sql[2]) or ($judge = true);
        }
        else
        {    
            $result = $con->query($sql[0]) or ($judge = true);
           
        }
        
	//-------------------------↑2018/10/04 土場追加対応------------------------------------------//																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		foreach($result_row as $key => $value)
		{
                        // 2018/06/29 追加対応 ↓
                        if($key == 'DELETED')
                        {
                                continue;
                        }
                        // 2018/06/29 追加対応 ↑
			if($isonce == true)
			{
				if($key != 'SYUKKASUM' && $key != 'HENKYAKUSUM' && $key != 'ZAIKO')
				{
					$header = $param_ini[$key]['link_name'];
					$header_csv .= $header.",";
					$where = key_value($key,$post);
				}
				else
				{
					if($key == 'SYUKKASUM')
					{
						$header = "出荷数";
					}
					if($key == 'HENKYAKUSUM')
					{
						$header = "返却数";
					}
					if($key == 'ZAIKO')
					{
						$header = "土場在庫数";
					}
					$header_csv .= $header.",";
					$where = "";
				}
				$where_csv .= $header." = ".$where.",";
			}
			$columnnum = 0;
			if(isset($param_ini[$key]['column_num']))
			{
				$columnnum = $param_ini[$key]['column_num'];
			}
			if($columnnum != 0 )
			{
				$type = $form_ini[$columnnum]['form_type'];
				$format = $form_ini[$columnnum]['format'];
				$value = format_change($format,$value,$type);
			}
			$value = mb_convert_encoding($value, "sjis-win", "cp932");
			$value_csv .= $value.",";
		}
		$value_csv = substr($value_csv,0,-1);
		if($isonce == true)
		{
			$header_csv = substr($header_csv,0,-1);
			$where_csv = substr($where_csv,0,-1);
			$csv .= $where_csv."\r\n".$header_csv."\r\n".$value_csv."\r\n";
		}
		else
		{
			$csv .= $value_csv."\r\n";
		}
		$value_csv = "";
		$header_csv = "";
		$isonce = false;
		
	}
	$path = csv_write($csv);
	return($path);
}

/************************************************************************************************************
function delete($post,$data)

引数1		$post								入力内容
引数2		$data								登録ファイル内容

戻り値	なし
************************************************************************************************************/
function delete($post,$data){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	$list_insert ="";
	$list_insert_array = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$delete_CODE = $post[$code];
	$sql = DeleteSQL($delete_CODE,$tablenum,$code);
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$delete_path = "";
	$delete_CODE = "";
	if($main_table_type == 0 && $list_tablenum != '')
	{
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			$list_insert = $form_ini[$list_tablenum_array[$i]]['insert_form_num'];
			$list_insert_array = explode(',',$list_insert);
			$code = $list_tablenum_array[$i].'CODE';
			for($j = 0; $j < count($list_insert_array) ; $j++)
			{
				if(isset($data[$list_tablenum_array[$i]]))
				{
					for($k = 0 ; $k < count($data[$list_tablenum_array[$i]]) ; $k++)
					{
						foreach($data[$list_tablenum_array[$i]][$k] as $key => $value)
						{
							if($key == '')
							{
								// 空アレイの場合
							}
							else if(strstr($key,$list_insert_array[$j]) == true )
							{
								$delete_path = $value;
								$delete_CODE = $data[$list_tablenum_array[$i]][$k][$code];
								break;
							}
						}
						if($delete_path != '' && $delete_CODE != '')
						{
							if(file_exists($delete_path))
							{ 
								unlink($delete_path );
							}
							$sql = DeleteSQL($delete_CODE,$list_tablenum_array[$i],$code);
							$result = $con->query($sql) or ($judge = true);												// クエリ発行
							if($judge)
							{
								error_log($con->error,0);
							}
							$delete_path = "";
							$delete_CODE = "";
						}
					}
				}
			}
		}
	}
	
}


/************************************************************************************************************
function make_zaikokei()

引数	なし

戻り値	なし
************************************************************************************************************/
function make_zaikokei(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "SELECT * FROM zaikoinfo;";
	$judge = false;
	$total = 0;
	$all_price = 0;
	$all_tax = 0;
	$all_recycle = 0;
	$all_cost = 0;
	$all_car_tax = 0;
	$old_buy_day = "";
	$old_make_date = "";
	$year = 99;
	$pre_year=0;
	$year_type = 0;
	$zaiko_param = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$total++;
		$all_price += $result_row['BUYPRICE'];
		$all_tax += $result_row['BUYTAX'];
		$all_recycle += $result_row['CARRECYCLE'];
		$all_cost += $result_row['BUYCOST'];
		$all_car_tax += $result_row['CARTAX'];
		if($old_buy_day == '')
		{
			$old_buy_day = $result_row['BUYDATE'];
		}
		if(strtotime($old_buy_day ) >= strtotime($result_row['BUYDATE']))
		{
			$old_buy_day = $result_row['BUYDATE'];
		}
		if(strstr($result_row['MAKEDATE'],'昭和') == true)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 2;
			}
		}
		else if(strstr($result_row['MAKEDATE'],'平成') == true && $year_type != 2)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 1;
			}
		}
		else if($year_type == 0)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 0;
			}
		}
	}
	
	$zaiko_param[0] = $total;
	$zaiko_param[1] = $old_buy_day;
	$zaiko_param[2] = $old_make_date;
	$zaiko_param[3] = $all_price;
	$zaiko_param[4] = $all_tax;
	$zaiko_param[5] = $all_recycle;
	$zaiko_param[6] = $all_cost;
	$zaiko_param[7] = $all_car_tax;
	return($zaiko_param);
	
}


/************************************************************************************************************
function make_kensaku($post,$tablenum)

引数1		$post										選択年月
引数2		$tablenum									メインテーブル番号

戻り値		$syakentable								年月選択リンクテーブル
************************************************************************************************************/
function make_kensaku($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$befor_year = ($year - 2);
	$after_year = ($year + 3);
	$filename = $_SESSION['filename'];
	$formnum = $form_ini[$filename]['sech_form_num'];
	$columnname = $form_ini[$formnum]['column'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$syakenbi = array();
	$syaken_year ="";
	$syaken_month ="";
	$syakentable = "";
	$counter = 1;
	$wareki = "";
	$wareki1 = "";
	$wareki2 = "";
	$syakendate =array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = kensakuSelectSQL($post,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$syakendate = explode('-',$result_row[$columnname]);
		$syaken_year = $syakendate[0];
		$syaken_month = $syakendate[1];
		$syaken_month = ltrim($syaken_month,'0');
		if(isset($syakenbi[$syaken_year][$syaken_month]) == true)
		{
			$syakenbi[$syaken_year][$syaken_month]++;
		}
		else
		{
			$syakenbi[$syaken_year][$syaken_month] = 1;
		}
	}
	$syakentable = "<table id = 'syaken'><tr><th>有効期限満了月</th></tr>";
	for($yearcount = $befor_year ; $yearcount < ($after_year+1) ; $yearcount++)
	{
		$syakentable .= "<tr><td class='year".$counter."'><a class ='kensakuyear'>";
		$counter++;
		$wareki1 = wareki_year($yearcount);
		$wareki2 = wareki_year_befor($yearcount);
		if($wareki1 != $wareki2)
		{
			$wareki = $wareki1."年 - ".$wareki2."年度 [".$yearcount."]";
		}
		else
		{
			$wareki = $wareki1."年度 [".$yearcount."]";
		}
		$syakentable .= $wareki."</a></td>";
		for($monthcount = 1 ;$monthcount < (12 + 1); $monthcount++)
		{
			if(isset($syakenbi[$yearcount][$monthcount]))
			{
				$syakentable .= "<td><a href='./kensakuJump.php?year="
								.$yearcount."&month=".$monthcount."'> ";
				$syakentable .= $monthcount."月[".$syakenbi[$yearcount][$monthcount]."] </a></td>";
			}
			else
			{
				$syakentable .= "<td><a class='itemname'> ";
				$syakentable .= $monthcount."月[0] </a></td>";
			}
		}
		$syakentable .="</tr>";
	}
	$syakentable .="</table>";
	return($syakentable);
}

/************************************************************************************************************
function make_mail($code,$tablenum)

引数1		$code								
引数2		$tablenum							

戻り値		$mail_param							
************************************************************************************************************/
function make_mail($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_Form.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$adress_column = $mail_ini['param']['adress_column'];
	$title_text = $mail_ini['param']['title'];
	$header_text = $mail_ini['param']['header'];
	$header_text_array = explode('~',$header_text);
	$fotter_text = $mail_ini['param']['fotter'];
	$fotter_text_array = explode('~',$fotter_text);
	$user_column = $mail_ini['param']['user_column'];
	$template = $mail_ini['param']['template'];
	$template_array = explode('~',$template);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$adress = array();
	$title = array();
	$subject = array();
	$user = array();
	$count = 0;
	$mail_param = array();
	$count_code = 0;
	$count_rows = 0;
	$count_gap = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$code_array = explode(',',$code);
	$count_code = count($code_array);
	$count_rows = $result->num_rows;
	$count_gap = ($count_code - $count_rows);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$adress[$count] = $result_row[$adress_column];
		$title[$count] = $title_text;
		$subject[$count] = "";
		for($i = 0 ; $i < count($header_text_array) ; $i++)
		{
			if(isset($result_row[$header_text_array[$i]]))
			{
				$column_num = $param_ini[$header_text_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$header_text_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($header_text_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $header_text_array[$i];
				}
			}
		}
		for($i = 0 ; $i < count($template_array) ; $i++)
		{
			if(isset($result_row[$template_array[$i]]))
			{
				$column_num = $param_ini[$template_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$template_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($template_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $template_array[$i];
				}
			}
		}
		for($i = 0 ; $i < count($fotter_text_array) ; $i++)
		{
			if(isset($result_row[$fotter_text_array[$i]]))
			{
				$column_num = $param_ini[$fotter_text_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$fotter_text_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($fotter_text_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $fotter_text_array[$i];
				}
			}
		}
		$user[$count] = $result_row[$user_column];
		$count++;
	}
	$mail_param[0] = $adress;
	$mail_param[1] = $title;
	$mail_param[2] = $subject;
	$mail_param[3] = $user;
	$mail_param[4] = $count_gap;
	return($mail_param);
}

/************************************************************************************************************
function pdf_select($code_value,$tablenum,$maintablenum)

引数	なし

戻り値	なし
************************************************************************************************************/
function pdf_select($code_value,$tablenum,$maintablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$column = $form_ini[$tablenum]['insert_form_num'];
	$columnname = $form_ini[$column]['column'];
	$link_num = $form_ini[$column]['link_num'];
	$code = $maintablenum."CODE";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$pdf_table = "";
	$pdf_path = '';
	$isonece = true ;
	$pdf_result = array();
	$judge = false;
	$count=0;
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = idSelectSQL($code_value,$tablenum,$code);
	$sql = substr($sql,0,-1);
	$sql .=" order by ".$columnname." desc ;";
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$pdf_table = "<table id = 'link'><tr><td class = 'center'>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$pdf_table .= "<a href = './pdf.php?path=".
						$result_row[$columnname]."&code=".
						$code_value."&tablenum=".
						$tablenum."' target='Modal' >".
						$link_num.($count+1)."</a>&nbsp;";
		$count++;
		if($isonece)
		{
			$pdf_path = $result_row[$columnname];
			$isonece = false;
		}
	}
	$pdf_table .= "</td></tr></table>";
	if($pdf_path =='')
	{
		$pdf_table = '<a class = "error">対象ファイルなし</a>';
	}
	
	$pdf_result[0] = $pdf_table;
	$pdf_result[1] = $pdf_path;
	return($pdf_result);
}


/************************************************************************************************************
function syaken_mail_select()

引数	なし

戻り値	なし
************************************************************************************************************/
function syaken_mail_select(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_mail.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM syakeninfo LEFT JOIN userinfo ON (syakeninfo.3CODE = userinfo.3CODE)";
	$sql .= " LEFT JOIN carinfo ON (syakeninfo.4CODE = carinfo.4CODE)";
	$after_month = $mail_ini['syaken']['after_month'];
	$adress = $mail_ini['syaken']['send_add'];
	$title = $mail_ini['syaken']['title'];
	$header1 = $mail_ini['syaken']['header1'];
	$header2 = $mail_ini['syaken']['header2'];
	$template = $mail_ini['syaken']['template'];
	$title_array = explode('~',$title);
	$header1_array = explode('~',$header1);
	$header2_array = explode('~',$header2);
	$template_array = explode('~',$template);
	$month = date_create('NOW');
	$month = date_format($month, "m");
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	
	//------------------------//
	//          変数          //
	//------------------------//
	$predate ="";
	$date ="";
	$judge = false;
	$title_text = "";
	$body_text = "";
	$head_text = "";
	$sentence_text = "";
	$total = 0;
	$syaken_array =array();
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$predate = $year.'-'.$month.'-01';
	$date = date_create($predate);
	$date = date_add($date, date_interval_create_from_date_string($after_month.' month'));
	$date = date_format($date,'Yn');
	$syaken_date = date_create($predate);
	$syaken_date = date_add($syaken_date, date_interval_create_from_date_string($after_month.' month'));
	$syaken_year = date_format($syaken_date,'Y');
	$syaken_month = date_format($syaken_date,'n');
	$syaken_date = date_format($syaken_date,'Y-m-d');
	$syaken_array['YEAR'] = $syaken_year;
	$syaken_array['MONTH'] = $syaken_month;
	$sql .=" WHERE DATE_FORMAT(EXPIRYDATE,'%Y%c') = '".$date."'";
	$sql .=" ORDER BY EXPIRYDATE ASC ;";
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		for($i = 0 ; $i < count($template_array) ; $i++ )
		{
			if(isset($result_row[$template_array[$i]]))
			{
				$body_text .= $result_row[$template_array[$i]];
			}
			else if($template_array[$i] == "<br>")
			{
				$body_text .= "\r\n";
			}
			else
			{
				$body_text .= $template_array[$i];
			}
		}
		$total++;
	}
	$syaken_array['TOTAL'] =$total;
	for($i = 0 ; $i < count($title_array) ; $i++)
	{
		if(isset($syaken_array[$title_array[$i]]))
		{
			$title_text .= $syaken_array[$title_array[$i]];
		}
		else if($title_array[$i] == "<br>")
		{
			$title_text .= "\r\n";
		}
		else
		{
			$title_text .= $title_array[$i];
		}
	}
	for($i = 0 ; $i < count($header1_array) ; $i++)
	{
		if(isset($syaken_array[$header1_array[$i]]))
		{
			$head_text .= $syaken_array[$header1_array[$i]];
		}
		else if($header1_array[$i] == "<br>")
		{
			$head_text .= "\r\n";
		}
		else
		{
			$head_text .= $header1_array[$i];
		}
	}
	for($i = 0 ; $i < count($header2_array) ; $i++)
	{
		if(isset($syaken_array[$header2_array[$i]]))
		{
			$head_text .= $syaken_array[$header2_array[$i]];
		}
		else if($header2_array[$i] == "<br>")
		{
			$head_text .= "\r\n";
		}
		else
		{
			$head_text .= $header2_array[$i];
		}
	}
	$sentence_text .= $head_text.$body_text;
	sendmail($adress,$title_text,$sentence_text);
}

/************************************************************************************************************
function make_check_array($post,$main_table)

引数	なし

戻り値	なし
************************************************************************************************************/
function make_check_array($post,$main_table){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$check_array = array();
	$judge = false;
	$count = 0;
	$check_str = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = joinSelectSQL($post,$main_table);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$check_str = "check_".$result_row[$main_table.'CODE'];
		$check_array[$count] = $check_str;
		$count++;
	}
	return $check_array;
}

/************************************************************************************************************
function table_code_exist()

引数	なし

戻り値	なし
************************************************************************************************************/
function table_code_exist(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$listtablenum = $form_ini[$tablenum]['see_table_num'];
	$listtablenum_array = explode(',',$listtablenum);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$isexit = false;
	$count = 0;
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	for($i = 0 ; $i < count($listtablenum_array) ; $i++)
	{
		$sql = codeCountSQL($tablenum,$listtablenum_array[$i]);
		$result = $con->query($sql) or ($judge = true);																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$count = $result_row['COUNT(*)'];
		}
		if($count != 0)
		{
			$isexit = true;
		}
		$count = 0;
	}
	return($isexit);
}
/************************************************************************************************************
function make_label($code,$tablenum)

引数	なし

戻り値	なし
************************************************************************************************************/
function make_label($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_Form.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$count = 0;
	$label_param = array();
	$useradress = array();
	$username = array();
	$userpostcd = array();
	$orgadress = array();
	$orgname = array();
	$orgpostcd = array();
	$count_code = 0;
	$count_rows = 0;
	$count_gap = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$code_array = explode(',',$code);
	$count_code = count($code_array);
	$count_rows = $result->num_rows;
	$count_gap = ($count_code - $count_rows);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$useradress[$count] = $result_row['USERADD1'];
		$username[$count] = $result_row['USERNAME'];
		$userpostcd[$count] = $result_row['USERPOSTCD'];
		$count++;
	}
	$label_param[0] = $useradress;
	$label_param[1] = $username;
	$label_param[2] = $userpostcd;
	$label_param[3] = $orgadress;
	$label_param[4] = $orgname;
	$label_param[5] = $orgpostcd;
	$label_param[6] = $count_gap;
	
	return($label_param);
}
/************************************************************************************************************
function existID($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function existID($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$selectidsql = "SELECT * FROM ".$tablename." where ".$tablenum."CODE = ".$id." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($selectidsql);																				// クエリ発行
	if($result->num_rows == 1)
	{
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
	}
	return($result_array);
}

/************************************************************************************************************
function countLoginUser()


引数	

戻り値	
************************************************************************************************************/
	
function countLoginUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM loginuserinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	
	$result = $con->query($sql);																				// クエリ発行
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$countnum = $result_row['COUNT(*)'];
	}
	if($countnum > 1)
	{
		$judge = true;
	}
	return($judge);
}


/************************************************************************************************************
function makeList_item($sql,$post)

引数1	$sql						検索SQL

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $SQL_ini[$filename]['listcolums'];
	$columns_array = explode(',',$columns);
	$columnname = $SQL_ini[$filename]['clumname'];
	$columnname_array = explode(',',$columnname);
	$format = $SQL_ini[$filename]['format'];
	$format_array = explode(',',$format);
	$type = $SQL_ini[$filename]['type'];
	$type_array = explode(',',$type);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];																		// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$value_GENBA = "未選択";
	$value_4CODE = -1;
        $accordion = "";
	$num = 1;
       
                
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
        // 2018/06/29 追加対応 ↓
 	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2'
           || $filename == 'HANSHUTUTEISEI_2' || $filename == 'HANNYUTEISEI_2')
       // 2018/06/29 追加対応 ↑
	{
		if(isset($post['4CODE']))
		{
			if($post['4CODE'] != "")
			{
				$value_4CODE = $post['4CODE'];
				$sql_GENBA = idSelectSQL($value_4CODE,4,'4CODE');
				$result = $con->query($sql_GENBA);
				while($result_row = $result->fetch_array(MYSQLI_ASSOC))
				{
					$value_GENBA = $result_row['GENBANAME'];
				}
			}
		}
		$list_html .= "<br>選択現場 : ".$value_GENBA."<br><br><input type = 'hidden' id = 'check_4CODE' value = '".
						$value_4CODE."'>";
	}
	$result = $con->query($sql[1]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
        // 2018/06/29 変更 ↓
        // 出荷、返却、差異は上限を設けない
	if($filename != 'HENKYAKUINFO_2' && $filename != 'SYUKKAINFO_2'
           && $filename != 'SAIINFO_2' 
           && $filename != 'HANSHUTUTEISEI_2' && $filename != 'HANNYUTEISEI_2')
        // 2018/06/29 変更 ↑
	{
		$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
		$sql[0] .= $limit.";";																									// LIMIT追加
	}
        
        //-----------------------------------------↓2018/09/25 土場追加対応----------------------------------------------//
        /*if($filename == 'ZAIKOINFO_2')
        {
            $result = $con->query($sql[2]) or ($judge = true);
        }
        else
        {
            $result = $con->query($sql[0]) or ($judge = true);
        } */   
	//-----------------------------------------↑2018/09/25 土場追加対応----------------------------------------------//		// クエリ発行
        $result = $con->query($sql[0]) or ($judge = true);
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columnname_array) ; $i++)
	{
		$list_html .="<th><a class ='head'>".$columnname_array[$i]."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>編集</a></th>";
	}
	
	
        //  ↓2018/06/29 追加対応
	if($filename == 'HENKYAKUINFO_2' || $filename == 'HANNYUTEISEI_2')
	{
		//$list_html .="<th><a class ='head'>返却数</a></th>";
		$list_html .="<th><a class ='head'>返却数</a></th><th><a class ='head'>破損数</a></th>";
	}
	if($filename == 'SYUKKAINFO_2' || $filename == 'HANSHUTUTEISEI_2')
	{
                //  ↑2018/06/29 追加対応
		$list_html .="<th><a class ='head'>出荷数</a></th>";
	}
        //  ↓2018/06/29 追加対応
	if($filename == 'SAIINFO_2')
	{
                // 差異の入力項目を出荷・返却と同じ仕組みにする
		$list_html .="<th><a class ='head'>差異理由</a></th><th><a class ='head'>編集</a></th>";
 	}
        //  ↑2018/06/29 追加対応
	
	
	
	
	
	$list_html .="</tr></thead><tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr class = 'mausu'>";
                
		if(($counter%2) == 1)
		{
        // 2018/08 変更 ↓
			$id = "id = 'stripe_none'";
        // 2018/08 変更 ↑
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
                
                
                //-----------------------------------------↓2018/09/25 土場追加対応----------------------------------------------//
                if($filename == 'ZAIKOINFO_2')
                {
                    $list_html .="<tr class = 'hover' onclick=\"show_hide_row('hidden_row$num');\">";
                }
                //-----------------------------------------↑2018/09/25 土場追加対応----------------------------------------------//
                 
                 
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
               
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $columns_array[$i];
			$format1 = $format_array[$i];
			$value = $result_row[$field_name];
			$type1 = $type_array[$i];
			if($format1 != 0)
			{
				$value = format_change($format1,$value,$type1);
			}
			if($format1 == 3)
			{
				$class = "class = 'right' ";
			}
			else
			{
				$class = "";
			}
                        if($field_name == 'ZAIKONUM' || $field_name == 'ZAIKO' || $field_name == 'SYUKKASUM' || $field_name == 'HENKYAKUSUM')
                        {
                            
                            
                            $list_html .="<td ".$id." class = 'zairight' ><a class ='body'>".
                            $value."</a></td>";
                        
                        }    
                        else
                        {
                            $list_html .="<td ".$id." ".$class." ><a class ='body'>".
                            $value."</a></td>";
                        }    
			
         
		}
              
                
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
                    //-----------------------------------------↓2018/09/25 土場追加対応----------------------------------------------//
                    if($filename == 'ZAIKOINFO_2')
                    {
                       // $list_html .= "<td ".$id."><input type='submit' name='edit_3CODE.' value = '編集'></td>";
                        $list_html .= "<td ".$id."><input type='submit' name='edit_".
							$result_row[$main_table.'CODE']."' value = '編集'></td>";
                        
                    }    
                    else
                    {
                        $list_html .= "<td ".$id."><input type='submit' name='edit_".
							$result_row[$main_table.'CODE']."' value = '編集'></td>";
                    }    
                    
                    //-----------------------------------------↑2018/09/25 土場追加対応----------------------------------------------//
		}
		
		//-----------------------------------------↓2018/09/26 土場追加対応----------------------------------------------// 
                 
                if($filename == 'ZAIKOINFO_2')
                {
                     $list_html .="</tr>";
                     if(($counter%2) == 1)
                     {
                         $style = "hidden_row";
                     }
                     else
                     {
                         $style = "hidden_row_color";
                     }    
                     
                     
                     $sql_accordion = $sql[2];
                     $sql_accordion .= " AND SIZAIID = '$result_row[SIZAIID]'";
                     $accordion = $con->query( $sql_accordion);
                     
                     while($accordion_row = $accordion->fetch_array(MYSQLI_ASSOC))
                     {

                        $list_html .="<tr id = 'hidden_row$num' class='$style' >";
                        $list_html .= "<td></td>";
                        $list_html .= "<td colspan=2 class='center' id = 'hidden_color'>$accordion_row[DOBANAME]</td>";
                        $list_html .= "<td id = 'hidden_color' class='zairight' >$accordion_row[ZAIKONUM]</td>";
                        $list_html .= "<td id = 'hidden_color' class='zairight'>$accordion_row[ZAIKO]</td>";
                        $list_html .= "<td id = 'hidden_color' class='zairight'>$accordion_row[SYUKKASUM]</td>";
                        $list_html .= "<td id = 'hidden_color' class='zairight'>$accordion_row[HENKYAKUSUM]</td>";
                        $list_html .= "<td></td>";
                        
                     
                      $list_html .= "</tr>";
                     }
                      
                    $sql_accordion = "";
                     
                }
                //-----------------------------------------↑2018/09/26 土場追加対応----------------------------------------------//
                
                
                
                
                 
                 
                // 2018/06/29 追加対応 ↓
                // 出荷・返却およびその訂正
		if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2'
                || $filename == 'HANSHUTUTEISEI_2' || $filename == 'HANNYUTEISEI_2')
                // 2018/06/29 追加対応 ↑
		{
			$zaiko_num = -1;
			if(isset($result_row['zaiko']))
			{
				$zaiko_num = $result_row['zaiko'];
			}
			$check_js = 'onChange = " return inputcheck(\'syukka_'.$counter.'\',6,4,0)"';
			$syukka_value = "";
                        // 2018/06/29 追加対応 ↓
                        if($filename == 'HANSHUTUTEISEI_2' || $filename == 'HANNYUTEISEI_2')
                        {
        			$syukka_value = $result_row['SUMIO'];;
                        }
                        // 2018/06/29 追加対応 ↑
			if(isset($post['syukka_'.$result_row['1CODE'].'_'.$zaiko_num]))
			{
				$syukka_value = $post['syukka_'.$result_row['1CODE'].'_'.$zaiko_num];
			}
			$list_html .= "<td ".$id."><input type='text' name='syukka_".
							$result_row['1CODE']."_".$zaiko_num."' id = 'syukka_".
							$counter."' value = '"
							.$syukka_value."' ".$check_js.'class = "txtmode2"'." ></td>";
		
                        // ↓2018/06/29 追加対応
                        // 破損を追加
                        if($filename == 'HENKYAKUINFO_2' || $filename == 'HANNYUTEISEI_2' )
                        {
                                $check_js = 'onChange = " return inputcheck(\'hason_'.$counter.'\',6,4,0)"';
                                $hason_value = "";
                                if($filename == 'HANNYUTEISEI_2')
                                {
                                        $hason_value = $result_row['SUMHASON'];
                                        if( $hason_value == "0" )
                                        {
                                                $hason_value = "";
                                        }
                                }
                                if(isset($post['hason_'.$result_row['1CODE'].'_'.$zaiko_num]))
                                {
                                        $hason_value = $post['hason_'.$result_row['1CODE'].'_'.$zaiko_num];
                                }
                                $list_html .= "<td ".$id."><input type='text' name='hason_".
                                                                $result_row['1CODE']."_".$zaiko_num."' id = 'hason_".
                                                                $counter."' value = '"
                                                                .$hason_value."' ".$check_js.'class = "txtmode2"'." ></td>";
                        }
                        else
                        {
                                $list_html .= "<input type='hidden' name='hason_".
                                                                $result_row['1CODE']."_".$zaiko_num."' id = 'hason_".
                                                                $counter."' value = '' >";
                            
                        }
                        // ↑2018/06/29 追加対応
                        
		
		}
                
                //  ↓2018/06/29 追加対応
                if($filename == 'SAIINFO_2')
                {
                        // 差異の入力項目を出荷・返却と同じ仕組みにする
                       $textBoxName = "REASON_".$result_row['8CODE'];
                       $textBoxId = "REASON_".$counter;
                       $sai_value = $result_row['REASON'];
                       $list_html .= "<td ".$id."><input type='text' name='".$textBoxName."' id = '".$textBoxId.
                                                        "' value = '".$sai_value."' class = 'txtmode1' ></td>";
                       $list_html .= "<td ".$id."><input type='button' name='matigai_".$counter."' id = 'matigai_".$counter."' value = '間違い' onclick ='document.getElementById(\"".$textBoxId."\").value=\"数え間違い\";' class = 'button' >";
                       $list_html .= "<input type='button' name='matigai_".$counter."' id = 'matigai_".$counter."' value = '破損' onclick ='document.getElementById(\"".$textBoxId."\").value=\"部材の破損\";' class = 'button' ></td>";
                       
                }
                //  ↑2018/06/29 追加対応
		
                
		$list_html .= "</tr>";
                $num++; 
		$counter++;
	}
	$list_html .="</tbody></table>";
        //  ↓2018/06/29 追加対応
	if($filename != 'HENKYAKUINFO_2' && $filename != 'SYUKKAINFO_2' 
           && $filename != 'SAIINFO_2'
           && $filename != 'HANSHUTUTEISEI_2' && $filename != 'HANNYUTEISEI_2')
        //  ↑2018/06/29 追加対応
	{
                 //$list_html .= "<div class='addcenter'>";
		$list_html .= "<div class = 'listcenter'>";
		$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div><div class = 'listcenter'>";
		$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
		if(($limitstart + $listcount) == $totalcount)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div>";
	}
	return ($list_html);
}



/************************************************************************************************************
function insertnyuusyukka($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function insertnyuusyukka($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$value_1CODE = "";
	$value_2CODE = "";
	$value_4CODE = "";
	$key_array = array();
	$nyuusyukka_num = 0;
	$type = 0;
	$colname = "";

        // 2018/06/29 追加対応 ↓
	if($filename == 'SYUKKAINFO_2' || $filename == 'HANSHUTUTEISEI_2')
        // 2018/06/29 追加対応 ↑
	{
		$type = 1;
		$colname = $form_ini['504']['column'];
	}
       // 2018/06/29 追加対応 ↓
	if($filename == 'HENKYAKUINFO_2' || $filename == 'HANNYUTEISEI_2')
        // 2018/06/29 追加対応 ↑
	{
		$type = 2;
		$colname = $form_ini['604']['column'];
	}
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
	$sagyou_date = $post['form_start_0'];
	$sagyou_date .= "-".$post['form_start_1'];
	$sagyou_date .= "-".$post['form_start_2'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_2CODE = "";
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	$sql_2CODE = idSelectSQL($value_4CODE,4,'4CODE');
	
	
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql_2CODE);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$value_2CODE = $result_row['2CODE'];
	}
        
 	foreach($post as $key  =>  $value)
	{
		
		if(strstr($key,'syukka_') != false)
		{
			$key_array = explode('_',$key);
			$value_1CODE = $key_array[1];
			
                        $keyShukka = "hason_". $key_array[1]."_".$key_array[2];
                        $valueHason = "";
                        if( isset($post[$keyShukka]) )
                        {
                            $valueHason = $post[$keyShukka];
                        }
                        
			if($value != "" || $valueHason != "")
			{
                            // ↓2018/06/07 破損数記録
				$insert_nyuusyukka = "INSERT INTO ".$tablename." (1CODE,4CODE,".$colname.",HASONNUM,SAGYOUDATE) VALUES(";
				$insert_nyuusyukka .= $value_1CODE.",".$value_4CODE.",'".$value."','".$valueHason."','".$sagyou_date."');";
				$result = $con->query($insert_nyuusyukka) or ($judge = true);																	// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$insert_rireki = "INSERT INTO rirekiinfo (1CODE,2CODE,4CODE,IONUM,HASONNUM,IOTYPE,CREATEDATE,SAGYOUDATE) VALUES(";
				$insert_rireki .= $value_1CODE.",".$value_2CODE.",".$value_4CODE.",'".
									$value."','".$valueHason."','".$type."','".$date."','".$sagyou_date."');";
				$result = $con->query($insert_rireki) or ($judge = true);																	// クエリ発行
                            // ↑2018/06/07 破損数記録
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
			}
		}
	}
}





/************************************************************************************************************
function makeList_radio($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function makeList_radio($sql,$post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置
	$resultcolumns = $form_ini[$filename]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																								// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>選択</a></th>";
	for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
	{
		$title_name = $form_ini[$resultcolumns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	$list_html .="<tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
        // 2018/08 変更 ↓
			$id = "id = 'stripe_none'";
        // 2018/08 変更 ↑
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_html .= "<td ".$id." class = 'center'>";
		$column_value = $result_row[$tablenum.'CODE'].'#$';
		$form_name = $tablenum.'CODE,';
		$form_type .= '9,';
		for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
		{
			$field_name = $form_ini[$resultcolumns_array[$i]]['column'];
			$format = $form_ini[$resultcolumns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$resultcolumns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 4)
			{
				$class = "class = 'right'";
			}
			else
			{
				$class = "";
			}
			$row .="<td ".$id." ".$class." ><a class ='body'>"
						.$value."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$columns_array[$i]]['form_type'];
			$form_value = formvalue_return($columns_array[$i],$value,$type);
			$form_name .= $form_value[0];
			$column_value .= $form_value[1];
			$form_type .=  $form_value[2];
		}
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
		$list_html .= '<input type ="radio" name = "radio" onClick="select_value(\''
						.$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';
		$list_html .= "</td>";
		$list_html .= $row;
		$list_html .= "</tr>";
                $list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<table><tr><td>";
	$list_html .= "<input type='submit' class = 'button' name ='back' value ='戻る'";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td><td>";
	$list_html .= "<input type='submit' class = 'button'  name ='next' value ='進む'";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	return ($list_html);
}



/************************************************************************************************************
function genbaend($post)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function genbaend($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	require_once("f_mail.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$value_GENBASTATUS = "";
	$value_1CODE = "";
	$value_2CODE = "";
	$value_4CODE = "";
	$key_array = array();
	$nyuusyukka_num = 0;
	$type = 0;
	$colname = "";
	$saimail = "";
	$saicount = 0;
	$genbaname = "";
	$genbacode = "";
	$sizainame = "";
	$sizaiid = "";
	$title = "";
	$add = "";
	$message = "";
	$out_count = 0;
	 $value_dobacode = "";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_GENBASTATUS = "";
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	$sai_judge = true;
	$sql_GENBASTATUS = idSelectSQL($value_4CODE,4,'4CODE');
	
	
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql_GENBASTATUS);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$value_GENBASTATUS = $result_row['GENBASTATUS'];
                $value_dobacode = $result_row['10CODE'];
	}
	if($value_GENBASTATUS == 1)
	{
		$saiSql = idSelectSQL($value_4CODE,8,'4CODE');
		$result = $con->query($saiSql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$SAISTATUS = $result_row['SAISTATUS'];
			if($SAISTATUS == 0 )
			{
				$sai_judge = false;
				$out_count++;
			}
		}
		if($sai_judge == true)
		{
//			$saiSql = idSelectSQL($value_4CODE,8,'4CODE');
//			$result = $con->query($saiSql);
//			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//			{
//				$value_1CODE = $result_row['SAISTATUS'];
//				$saitype = $result_row['SAITYPE'];
//				$sai = $result_row['SAINUM'];
//				tyousei($value_1CODE,$saitype,$sai);
//			}
//			genba_change($value_4CODE,2);


			$henkyakuSql = henkyakuSQL($post,1,$value_dobacode);
			$result = $con->query($henkyakuSql);
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$SAIKB = $result_row['SAIKB'];
				$syukka = $result_row['yotei'];
				$henkyaku = $result_row['henkyaku'];
				$value_1CODE = $result_row['1CODE'];
				$value_2CODE = $result_row['2CODE'];
				$sai = $syukka - $henkyaku;
				if($sai < 0)
				{
					$sai = abs($sai);
					$saitype = 2;
					tyousei($value_1CODE,$saitype,$sai,$value_dobacode);
				}
				else if($sai > 0)
				{
					$sai = abs($sai);
					$saitype = 1;
					tyousei($value_1CODE,$saitype,$sai,$value_dobacode);
				}
			}
			genba_change($value_4CODE,2);
			$message = "<a class = 'item'>現場終了処理が完了いたしました。</a>";
		}
		else
		{
			$message = "<a class = 'error'>".$out_count."件の差異処理が完了していません。<br>差異処理を完了させてもう一度現場終了処理をしてください。</a>";
		}
	}
	else if($value_GENBASTATUS == 0)
	{
		$henkyakuSql = henkyakuSQL($post,0,$value_dobacode);
		$result = $con->query($henkyakuSql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$SAIKB = $result_row['SAIKB'];
			$syukka = $result_row['yotei'];
			$henkyaku = $result_row['henkyaku'];
			$value_1CODE = $result_row['1CODE'];
			$value_2CODE = $result_row['2CODE'];
			$sai = $syukka - $henkyaku;
			if($sai < 0)
			{
				$sai = abs($sai);
				if($SAIKB == 1)
				{
					$saitype = 2;
					saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$saitype,$sai);
					$sai_judge = false;
					$genbaname =  $result_row['GENBANAME'];
					$genbaid =  $result_row['GENBAID'];
					$sizaiid = $result_row['SIZAIID'];
					$sizainame = $result_row['SIZAINAME'];
					$saicount++;
					$saimail .= $sizainame."(".$sizaiid.") 過剰 ".$sai."個 \r\n";
				}
			}
			else if($sai > 0)
			{
				$sai = abs($sai);
				if($SAIKB == 1)
				{
					$saitype = 1;
					saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$saitype,$sai);
					$sai_judge = false;
					$genbaname =  $result_row['GENBANAME'];
					$genbaid =  $result_row['GENBAID'];
					$sizaiid = $result_row['SIZAIID'];
					$sizainame = $result_row['SIZAINAME'];
					$saicount++;
					$saimail .= $sizainame."(".$sizaiid.") 不足 ".$sai."個 \r\n";
				}
			}
		}
		if($sai_judge == false)
		{
			genba_change($value_4CODE,1);
			if($saimail != "")
			{
				$saimail = $genbaname."(".$genbaid.") にて差異が".$saicount."件 見つかりました。\r\n".$saimail;
				$saimail = rtrim($saimail,'\r\n');
				$title = $mail_ini['sai']['title'];
				$add = $mail_ini['sai']['send_add'];
				sendmail($add,$title,$saimail);
			}
			$message = "<a class = 'error'>".$saicount."件の差異処理がありました。<br>差異処理を完了させてもう一度現場終了処理をしてください。</a>";
		}
		else
		{
			
			$henkyakuSql = henkyakuSQL($post,0,$value_dobacode);
			$result = $con->query($henkyakuSql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$SAIKB = $result_row['SAIKB'];
				$syukka = $result_row['yotei'];
				$henkyaku = $result_row['henkyaku'];
				$value_1CODE = $result_row['1CODE'];
				$value_2CODE = $result_row['2CODE'];
				$sai = $syukka - $henkyaku;
				if($sai < 0)
				{
					$sai = abs($sai);
					$saitype = 2;
					tyousei($value_1CODE,$saitype,$sai);
				}
				else if($sai > 0)
				{
					$sai = abs($sai);
					$saitype = 1;
					tyousei($value_1CODE,$saitype,$sai);
				}
			}
			
			genba_change($value_4CODE,2);
			$message = "<a class = 'item'>現場終了処理が完了いたしました。</a>";
		}
	}
	return($message);
}

/************************************************************************************************************
function tyousei($value_1CODE,$SAITYPE,$SAINUM)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function tyousei($value_1CODE,$SAITYPE,$SAINUM,$value_dobacode){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$SAICREATEDATE = date_format($date, 'Y-m-d H:i:s');
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sainum = ' + 0' ;
	if($SAITYPE == 2)
	{
		$sainum = ' + '.$SAINUM;
	}
	else if($SAITYPE == 1)
	{
		$sainum = ' - '.$SAINUM;
	}
	$sql = "UPDATE zaikoinfo SET ZAIKONUM = (ZAIKONUM ".$sainum.") WHERE 1CODE = ".$value_1CODE." AND 10CODE = ".$value_dobacode.";";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}


/************************************************************************************************************
function saiinsert($value_1CODE,$SAITYPE,$SAINUM)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$SAITYPE,$SAINUM){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$SAICREATEDATE = date_format($date, 'Y-m-d H:i:s');
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sql = "INSERT INTO saiinfo (4CODE,2CODE,1CODE,SAITYPE,SAICREATEDATE,SAISTATUS,SAINUM ) VALUES (";
	$sql .= $value_4CODE.",".$value_2CODE.",".$value_1CODE.",'".$SAITYPE."','".$SAICREATEDATE."','0','".$SAINUM."' ) ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}

/************************************************************************************************************
function genba_change($value_4CODE,$GENBASTATUS)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function genba_change($value_4CODE,$GENBASTATUS){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_format($date, "Y-m-d");
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sainum = ' + 0' ;
	$sql = "UPDATE genbainfo SET GENBASTATUS =  ".$GENBASTATUS;
	if($GENBASTATUS == 2)
	{
		$sql .= " , ENDDATE = '".$date."' ";
	}
        // 2018/06/29 追加対応 ↓
        else
        {
		$sql .= " , ENDDATE = NULL ";
        }
        // 2018/06/29 追加対応 ↑
	$sql .= " WHERE 4CODE = ".$value_4CODE." ;";
	$result = $con->query($sql);
        
        // 現場ステータスの指定が2(完了)の場合
	if($GENBASTATUS == 2)
	{
                // 出荷情報を削除
		$sql = "DELETE FROM syukkainfo WHERE 4CODE = ".$value_4CODE." ;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
                // 返却情報を削除
		$sql = "DELETE FROM henkyakuinfo WHERE 4CODE = ".$value_4CODE." ;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	
}

/************************************************************************************************************
function deleterireki()

引数1		$sql						検索SQL

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function deleterireki(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_sub($date, date_interval_create_from_date_string('1 year'));
	$DATE = date_format($date, "Y-m-d");
//	$DATETIME = date_format($date, 'Y-m-d H:i:s');
	$DATETIME = $DATE." 00:00:00";
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sql = "DELETE FROM genbainfo WHERE ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM saiinfo WHERE SAIUPDATE < '".$DATETIME."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM rirekiinfo WHERE CREATEDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	deletedate_change();
}

// 2018/06/29 追加 ↓

/************************************************************************************************************
function insertnyuusyukka($post)

引数1		$post						ページ移動時post

戻り値	なし
************************************************************************************************************/
	
function deleteNyuusyukka($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];

        // 出荷・返却判定
        $type = 0;
	$colname = "";
	if($filename == 'HANSHUTUTEISEI_2')
	{
		$type = 1;                              //出荷
		$colname = $form_ini['504']['column'];
	}
	if($filename == 'HANNYUTEISEI_2')
	{
		$type = 2;                              //返却
		$colname = $form_ini['604']['column'];
	}
        //日付
	$sagyou_date = $post['form_start_0'];
	$sagyou_date .= "-".$post['form_start_1'];
	$sagyou_date .= "-".$post['form_start_2'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	// DB接続
	$con = dbconect();																									// db接続関数実行
        
        // 履歴情報削除
        $sqlRireki = "DELETE FROM rirekiinfo";
        $sqlRireki .= " WHERE 4CODE = ".$value_4CODE;
        $sqlRireki .= " AND SAGYOUDATE = '".$sagyou_date."'";
        $sqlRireki .= " AND IOTYPE = '".$type."'";
        $con->query($sqlRireki) or ($judge = true);																	// クエリ発行
        if($judge)
        {
                error_log($con->error,0);
                $judge = false;
        }
        
        // 出荷or返却情報削除
        $sqlNyuShukka = "DELETE FROM ".$tablename;
        $sqlNyuShukka .= " WHERE 4CODE = ".$value_4CODE;
        $sqlNyuShukka .= " AND SAGYOUDATE = '".$sagyou_date."'";
        $con->query($sqlNyuShukka) or ($judge = true);																	// クエリ発行
        if($judge)
        {
                error_log($con->error,0);
                $judge = false;
        }
}

/************************************************************************************************************
function updateSaiinfo($post)

引数1		$post						ページ移動時post

戻り値		なし					
************************************************************************************************************/
function updateSaiinfo($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
        
	//------------------------//
	//          定数          //
	//------------------------//
	$value_8CODE = "";
	$key_array = array();
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
       
        //送信情報ループ
 	foreach($post as $key  =>  $value)
	{
		//値が設定されており、送信項目名が'REASON_'
		if($value != "" &&
                   strstr($key,'REASON_') != false)
		{
                    
                        //「-」で分割
                        $key_array = explode('_',$key);
                        // コード(キー項目)を取る
                        $value_8CODE = $key_array[1];
                        //SQL文作成
                        $updateSql = "UPDATE saiinfo SET REASON = '".$value."',";
                        $updateSql .= "SAISTATUS = 1, ";
                        $updateSql .= "SAIUPDATE = NOW() ";
                        $updateSql .= "WHERE 8CODE=".$value_8CODE.";";
                        //SQL実行
                        $con->query($updateSql) or ($judge = true);																	// クエリ発行
                        if($judge)
                        {
                                error_log($con->error,0);
                                $judge = false;
                        }
		}
	}
}

/************************************************************************************************************
function deleteLogical($post,$data)

引数1		$post								入力内容
引数2		$data								登録ファイル内容

戻り値	なし
************************************************************************************************************/
function deleteLogical($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
        
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$code = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$delete_CODE = $post[$code];
	$sql = DeleteLogicalSQL($delete_CODE,$tablenum,$code);

	$con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
}

/************************************************************************************************************
function genbaendCancel($post)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function genbaendCancel($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$value_GENBASTATUS = "";
	$value_1CODE = "";
	$value_4CODE = "";
	$message = "";
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql_GENBASTATUS = "";
	$judge = false;
	
        
        // db接続関数実行
	$con = dbconect();
        
	//------------------------//
	//        検索処理        //
	//------------------------//
	
        // まず現場ステータスを取得
	$value_4CODE = $post['4CODE'];
	$sql_GENBASTATUS = idSelectSQL($value_4CODE,4,'4CODE');
	// SQL実行
	$result = $con->query($sql_GENBASTATUS);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
                // 現場ステータス取得
		$value_GENBASTATUS = $result_row['GENBASTATUS'];
                $value_dobacode = $result_row['10CODE'];
	}
                
        // 現場ステータスを見て処理を変える
	if($value_GENBASTATUS == 2)
	{
                /************************************/
                /* 現場ステータス=2:終了の場合 */
                /************************************/
            
                /****** 在庫の復元 ******/
                // 資材に対する出荷・返却集計情報を取得する(差異情報は管理対象のものしかないので×)
                $sql = "SELECT Z.3CODE, Z.1CODE, S.SHUKKA, IFNULL(H.HENKYAKU,0) AS HENKYAKU ";
                $sql .= "FROM zaikoinfo Z ";
                $sql .= "INNER JOIN (SELECT 1CODE,SUM(IONUM) AS SHUKKA,IFNULL(SUM(HASONNUM),0) AS HASONSUM FROM rirekiinfo WHERE IOTYPE = 1 AND 4CODE = ".$value_4CODE." GROUP BY 1CODE ) S ON Z.1CODE = S.1CODE ";
                $sql .= "LEFT JOIN (SELECT 1CODE,SUM(IONUM) AS HENKYAKU,IFNULL(SUM(HASONNUM),0) AS HASONSUM FROM rirekiinfo WHERE IOTYPE = 2 AND 4CODE = ".$value_4CODE." GROUP BY 1CODE ) H ON Z.1CODE = H.1CODE ";
                $sql .= "WHERE 10CODE = ".$value_dobacode."";//在庫の土場を選択
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
                // 取得した出荷・返却情報分ループ
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
                        // 結果取得
                        $value_1CODE = $result_row['1CODE'];        //資材コード
                        $syukka = $result_row['SHUKKA'];            //出荷数
                        $henkyaku = $result_row['HENKYAKU'];        //返却数
                        // 差異を算出
                        $sai = $syukka - $henkyaku;
                        // 差異がないなら次へ
                        if( $sai == 0 )
                        {
                            continue;
                        }
                        // + or - で差異種別を分ける
                        if($sai < 0)    // 過剰
                        {
                                $saitype = 1;   // 終了時は2(加算処理)だが、キャンセルなので逆をする
                        }
                        else if($sai > 0)   // 不足（普通はこっち）
                        {
                                $saitype = 2;   // 終了時は1(減算処理)だが、キャンセルなので逆をする
                        }
                        // 関数渡し用に絶対値に
                        $sai = abs($sai);
                        // 在庫調整（復元）
                        tyousei($value_1CODE,$saitype,$sai,$value_dobacode);
                }
            
                /****** 出荷情報の復元 ******/
                $sql = "INSERT INTO syukkainfo(4CODE,1CODE,SYUKKANUM,HASONNUM,SAGYOUDATE) ";
                $sql .= " SELECT 4CODE,1CODE,IONUM,HASONNUM,SAGYOUDATE FROM rirekiinfo WHERE IOTYPE=1 AND 4CODE=".$value_4CODE;
                $con->query($sql) or ($judge = true);			// クエリ発行
                if($judge)
                {
                        error_log($con->error,0);
                        $judge = false;
                }

                /****** 返却情報の復元 ******/
                $sql = "INSERT INTO henkyakuinfo(4CODE,1CODE,HENKYAKUNUM,HASONNUM,SAGYOUDATE) ";
                $sql .= " SELECT 4CODE,1CODE,IONUM,HASONNUM,SAGYOUDATE FROM rirekiinfo WHERE IOTYPE=2 AND 4CODE=".$value_4CODE;
                $con->query($sql) or ($judge = true);			// クエリ発行
                if($judge)
                {
                        error_log($con->error,0);
                        $judge = false;
                }
	}
        /*********************************************/
        /* 現場ステータス=1:差異処理待ちでも必要な処理 */
        /*********************************************/

        // 差異情報を削除
        $sql = "DELETE FROM saiinfo WHERE 4CODE=".$value_4CODE;
        $con->query($sql) or ($judge = true);			// クエリ発行
        if($judge)
        {
                error_log($con->error,0);
                $judge = false;
        }

        //共通処理として、現場ステータス変更(0に戻す)
        genba_change($value_4CODE, 0);
        $message = "<a class = 'item'>現場終了キャンセル処理が完了いたしました。</a>";

	return($message);
}

// 2018/06/29 追加 ↑

/************************************************************************************************************
function selectDOBA($post)

引数1		$post						ページ移動時post

戻り値	なし
************************************************************************************************************/
function selectDOBA($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          変数          //
	//------------------------//
	$dobaname = $post;
	
        
        // db接続関数実行
	$con = dbconect();
        $sql = "select * from dobainfo where DOBANAME = '$dobaname'";
        // クエリ発行
        $result = $con->query($sql);
        if($result_row = $result->fetch_array(MYSQLI_ASSOC))
        {  
            $dobacode = $result_row['10CODE'];
        }
        
        return($dobacode);
}
?>
