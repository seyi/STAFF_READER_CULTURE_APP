<?php
error_reporting(E_ERROR | E_PARSE);

require_once '../web_services/system/library/functions/general_functions.php';
require_once '../web_services/system/config/constants.php';
require_once '../web_services/system/config/config.php';
set_time_limit(300000000000000);
//connect to the database
//$connect = mysql_connect("10.2.1.92", "rhapsody", "rhapsody");
//mysql_select_db("rhapsody", $connect); //select the table
//

/**
 * Database Classes 
 */
require_once '../web_services/system/library/classes/NicDatabase.php';
$dbobj =  new NicDatabase();
$orderdbobj = new NicDatabase();
$ordersubdbobj = new NicDatabase();
//var_dump($dbobj);

$file = "staffemails2.csv";


//$data = fgetcsv($handle);



$i=0;
$row = 1;
$emailarr = array();
ini_set('auto_detect_line_endings',TRUE);

$datasplit = array();
for($i=0; $i < count($emailarr); $i++){
	
		$datasplit[] = explode(",",$emailarr[$i][0]);
		
		
		
	//echo $emailarr[$i][$i];
	echo "\n";
	}
	
	$i = 0;
	$count2 = 0;
	$strarr = array();
	for($b=0;$b<count($emailarr);$b++){
		$i++;
		$strarr[] = $emailarr[$b];
		
		}
		$dbtemp =array();
		
		for($c=0;$c<count($strarr);$c++){
			$count2++;
			$firstn = $strarr[$c][0];
			$lastn = $strarr[$c][1];
			$email = trim($strarr[$c][2]);
			$rmn = 'RMN';
			$subscrpt = $strarr[$c][3];
			//echo $count2 . " ".$firstn. " " .$lastn." ". $email." ".$subscrpt;
			//echo '<br />';
			//$user_select = 'SELECT u.id ,u.first_name,u.last_name,u.last_login_date from users as u where u.email = "'. $value .'"';
			$query = 'select * from users as u where u.first_name = "'.$rmn.'"' ;
			$userid = $dbobj->getRow($query,true);
			
			if($userid){
				$id = $userid->id;
				$orderquery = 'select o.id,o.created from orders as o where o.user_id = "'.$id.'"';
				$orderobj = $orderdbobj->fetchAll($orderquery,true);
			//	$dbtemp[] = $orderobj;
				
				
				if($orderobj){
					
					
					if((count($orderobj) == 1))
					{
						$orderid = $orderobj[0]->id;
						$orderdate = $orderobj[0]->created;
						if($orderdate > '2013-12-31'){
							$ordersubquery = 'select * from order_subscriptions as os where os.order_id = "'.$orderid.'"';
							$ordersubobj = $ordersubdbobj->getRow($ordersubquery,true);
							if($ordersubobj){
								if($ordersubobj->subscription_date == '2014-12-31' ){
										echo 'User with id : '.$id. ' and subscription with id : '.$ordersubobj->id.'has
										 no subcription';
								}
								echo "Subscription id for order with id : ".$orderid. " is :" .$ordersubobj->id;
								echo "<br />";
							}
							
						}
						
						
					}else{
						
						foreach($orderobj as $key=>$value){
							$orderid = $value->id;	
							
							$orderdate = $value->created;
							if($orderdate > '2013-12-31'){
								$ordersubquery = 'select * from order_subscriptions as os where os.order_id = "'.$orderid.'"';
								$ordersubobj = $ordersubdbobj->getRow($ordersubquery,true);
								if($ordersubobj){
									if($ordersubobj->subscription_date == '2015-12-31'){
											echo 'User with id : '.$id. ' and subscription with id : '.$ordersubobj->id.'has
										 no subcription';
									}
									echo "User with first name:".$userid->first_name."**** has Subscription id for order with
									 id : ".$orderid. " is :" .$ordersubobj->id;
									echo "<br />";
								}
								
							}
							
						}
					}
					
					//echo $orderid. 'for user id'.$id.' and email '.$email;
					//echo "<br />";
				}
				
			}else {
				
			}
			
			
		}
		
		//var_dump($dbtemp);
		
		
/*	//	var_dump($strarr);
 while (($data = fgetcsv($handle)) !== FALSE) {
     //print_r($data);
     $i++;
        $query="INSERT INTO orders (order_number,created,user_id,description,plan_id,amount,book_id,payment_type,
                        transaction_status,payment_status,transaction_id,receipt) VALUES
                        (
                            '" . addslashes($data[0]) . "',
                            '" . addslashes($data[1]) . "',
                            '" . addslashes($data[2]) . "',
                            '" . addslashes($data[3]) . "',
                            '" . addslashes($data[4]) . "',
                            '" . addslashes($data[5]) . "',
                            '" . addslashes($data[6]) . "',
                            '" . (addslashes($data[4])==5 ? "Purchase" : "Subscription")."',
                            '" . (addslashes($data[6])=="Completed" ? 'Completed' : 'Pending')."',
                            '" . (addslashes($data[6])=="Completed" ? 'Yes' : 'No')."',
                            '" . addslashes($data[7]) . "',
                            '" . addslashes($data[8]) . "',
                            ) ";
							
		
        //mysql_query($query);
    }
*/	
	//fclose($handle);
	
/*	ini_set('auto_detect_line_endings',TRUE);
	$fp = fopen('BATCH1.csv','r');
	//var_dump($fp);
	
	$str = '';
	$srch = '';
	$i = 1;
	
	
	while(false !== ($char = fgetc($fp))){
		$str .=  $char;
		$srch .= $char;
		
		if(strlen($srch) > 2){
			$srch = substr($srch,1);
			}
			
			if($i > 1 && $srch[1] == chr(10) && $srch[0] != '\\'){
				break;
				}
		$i++;
		}
		
		echo $str;
	*/
	
//
	function readCSV($csvfile){
		$buffer = Array();
		ini_set('auto_detect_line_endings',TRUE);
		if(($handle = fopen($csvfile,"r")) != FALSE){
				while(($data = fgetcsv($handle,1000,",")) != FALSE){
					$buffer[] = $data;			
				}
		}
		
		 return $buffer;
		 
	}
	
	$data_store = Array();
	$data_store = readCSV($file);
	$staffemailarray = Array();
	function split_csv_data($csv_buffer) {
		$data_split = Array();
		$email_array = Array();
		foreach($csv_buffer as $key=>$value){
			$data_split[] = $value;
			
		}
		foreach($data_split as $key=>$emailvalue){
			$email_array[] = $emailvalue[0];

		}

		return $email_array;
	}
	
	function comma_separated_email($data_store){

		$staffemailarray = split_csv_data($data_store);

		$emailstrarr = Array();
		$emailcommaseperated =  "";
		for ($i = 0;$i<count($staffemailarray);$i++){
			$emailcommaseparated .= "'". $staffemailarray[$i]."'";
			$emailcommaseparated .= ",";
			
		}	
		return $emailcommaseparated;
	}
	
	function trim_str_val($strArray) {
		$trimmedEmails = Array();
		foreach($strArray as $key=>$value)
		{
		    $trimmedEmails[] = trim($value["email"]);
	    }	
		return $trimmedEmails; 
	}

	$emailslist = comma_separated_email($data_store);
	if(($lastcomma =strripos($emailslist,",")) != FALSE){
		$emailslist[$lastcomma] = " ";
	}

	$emailsplit = preg_split("[,]",$emailslist);
	$year = date('Y');
	$month = date('m');
	$day = date('d');
	$today = new DateTime($year."-".$month."-".$day." "."00:00:00");
	$today_format = $today->format('Y-m-d H:i:s');
	$staffdb = New NicDatabase();
	$commentdb = New NicDatabase();
	$userdb = New NicDatabase();
	$user = Array();
	$mycounter = 0;
	$staffemailarr2 = Array();
	$staffemailarr2 = split_csv_data($data_store);
	foreach($staffemailarr2  as $key=>$value){
		$user_select = 'select u.id,u.first_name,u.last_name,u.last_login_date from users u where u.email ="'.$value.'"';
		$user_object = $userdb->getRow($user_select,true); 
		if($user_object) {
			$user[$mycounter]['id'] = $user_object->id;
			$user[$mycounter]['firstname'] = $user_object->first_name;
			$user[$mycounter]['lastname'] = $user_object->last_name;
			$user[$mycounter]['lastlogindate'] = $user_object->last_login_date;
			$sql_user_comment = 'select c.book_id,c.chapter_number,c.description,c.created from comments c where c.user_id = "'.$user_object->id.'" AND created >= "'.$today_format.'";';
			$user_comment_object = $commentdb->getRow($sql_user_comment,true);
			if($user_comment_object){
				$user[$mycounter]['bookid'] = $user_comment_object->book_id;
				$user[$mycounter]['chapternumber'] = $user_comment_object->chapter_number;
				$user[$mycounter]['commenttext'] = $user_comment_object->description;
				$comment_date = date($user_comment_object->created);
				$user[$mycounter]['commentdate'] = $comment_date;
			} else {
				$user[$mycounter]['bookid'] = "";
				$user[$mycounter]['chapternumber'] = "";
				$user[$mycounter]['commenttext'] = "";
				
				$user[$mycounter]['commentdate'] = "";
				
			}
			
			$sql_user_department = 'select d.dept from staff_dept_email as d  where d.email = "'.$value.'" or d.alt_email = "'.$value.'";';			
	
			$mycounter++;

		}
	}
	$myselectEmail = 'select email from staff_dept_email;';
	$staffSelectAltEmail = 'select alt_email from staff_dept_email;';
	
	//$myselectemailArr = $userdb->fetchall($myselectEmail);
	$trimmedEmails = Array();
	$trimmedEmails = trim_str_val($userdb->fetchall($myselectEmail));
	
	//(map (fn [coll result] (if (notEpmty?) (conj result s)) result) )	
	function neglectEmptyString($thestring) {
		$buffer = Array();
		foreach($thestring as $key=>$value) {
			if(!empty($value)){
				$buffer[] = $value;
			}
		}
		return $buffer ;
	}
	
	$only_str_with_val = neglectEmptyString($trimmedEmails);
	
	$staff_email_in_db = 'select * from staff_dept_email where email in (select email from users where email not like " ") or alt_email in (select email from users where email not like " ") order by dept desc ;';
	
	$myuserarr = Array();
	$myuserarr[] = $userdb->fetchall($staff_email_in_db,false);
	$testarr = Array();
	$testarr2 = Array();
	foreach($myuserarr[0] as $key=>$value) {
		$testarr[] = $value ;
	}
	
	
	$teastarr3 = Array();
	foreach($testarr as $key=>$value) {
			if ((preg_match('/\s/',$value["email"]))) { 
				$testarr3 []  = $value["email"] ;

			}
			'select id from users where email = "'.$value["email"].'" ';
	}
	
	
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	//echo json_encode($user);
	
	echo json_encode($only_str_with_val);

	unset($staffdb);
	unset($commentdb);
	unset($userdb);	
	
?>
