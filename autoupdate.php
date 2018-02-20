<?php

function DateDiff($strDate1,$strDate2){
    return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );
}

date_default_timezone_set("Asia/Bangkok");

// conn
$servername = "localhost";
$username = "jitwilaitour_dbo";
$password = "ObservationCampaignDoctor8Shift";
$dbname = "jitwilaitour_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET character_set_results = utf8"); 
$conn->query("SET character_set_client = utf8"); 
$conn->query("SET character_set_connection = utf8");

$_data = array();
$today = date('Y-m-d');
$now = date('Y-m-d H:i:s');
$todayTimeStamp = strtotime($today);

$sql = "UPDATE booking SET 
	  status=40
	, book_log='update by system (Auto Cancel) {$now}'
	, cancel_date='{$now}'
	WHERE 
	(
		(
			`book_due_date_deposit` != '0000-00-00 00:00:00' && 
			`book_due_date_deposit` <= '{$now}'
		) 
		OR `book_due_date_full_payment` <= '{$now}'

	) 	AND status IN (0, 10) 

		AND book_receipt = 0 
		AND book_is_guarantee != 1
		AND book_on_wl != 1
		AND book_guarantee_file = ''
";
// if ($conn->query($sql) === TRUE) {
// 	echo "Record updated successfully";
// } else {
// 	echo "Error updating record: " . $conn->error;
// }


/* UPDATE W/L ATFER UPDATE STATUS COMPLETE */

if ($conn->query($sql) === TRUE) {

	#UPDATE BOOK/WL TO BOOKING
	/* GET per_id FOR UPDATE BOOK/WL */
	$pw_sql = "SELECT period.per_id, period.per_qty_seats, period.per_date_start,bus_list.bus_no,bus_list.bus_qty FROM period 
	LEFT JOIN bus_list ON period.per_id=bus_list.per_id
	WHERE (SELECT COUNT(*) FROM booking WHERE booking.per_id=period.per_id AND booking.status=50 AND booking.bus_no=bus_list.bus_no) > 0 AND period.status = 1";
	$pw_query = $conn->query($pw_sql);
	/**/
	$pw_numRow = $pw_query->num_rows;
	if( $pw_numRow > 0 ){
		while($pw_rs = $pw_query->fetch_assoc()){
			/* GET BOOK/WL */
			$bw_sql = "SELECT book_id,user_id,COALESCE(SUM(booking_list.book_list_qty)) AS qty FROM booking 
					  LEFT JOIN booking_list ON booking.book_code=booking_list.book_code 
					  WHERE per_id='{$pw_rs["per_id"]}' AND bus_no='{$pw_rs["bus_no"]}' AND status=50 AND booking_list.book_list_code IN ('1','2','3') 
					  GROUP BY booking.book_id 
					  ORDER BY booking.create_date ASC";
			$bw_query = $conn->query($bw_sql);
			$bw_numRow = $bw_query->num_rows;
			if( $bw_numRow > 0 ){
				/* จำนวนคนจองทั้งหมด (ตัด Waiting กับ ยกเลิก) */
				$bs_sql = "SELECT COALESCE(SUM(booking_list.book_list_qty),0) as qty FROM booking_list
						  LEFT JOIN booking ON booking_list.book_code=booking.book_code
                  		  WHERE booking.per_id={$pw_rs["per_id"]} AND booking.bus_no={$pw_rs["bus_no"]} AND booking.status NOT IN ('5','40','50') AND booking_list.book_list_code IN ('1','2','3')";
                $bs_query = $conn->query($bs_sql);
                $bs_rs = $bs_query->fetch_assoc();
                /**/

                $BalanceSeats = $pw_rs["bus_qty"] - $bs_rs["qty"]; // จำนวนคงเหลือ\\
                if( $BalanceSeats > 0 ){
                	while($bw_rs = $bw_query->fetch_assoc()){
                		if( $bw_rs["qty"] <= $BalanceSeats ){
                			$up_book = "UPDATE booking SET status='00',book_on_wl='1',book_log='update by cancel booking' WHERE book_id={$bw_rs["book_id"]}";
                			$conn->query($up_book);

                			$BalanceSeats -= $bw_rs["qty"]; // CALCULATE

                			$alert = "INSERT INTO alert_msg (user_id,book_id,detail,source,log_date) 
                			VALUE ('{$pw_rs["user_id"]}','{$pw_rs["book_id"]}','ระบบปรับ (จอง/WL) เป็น (จอง) เรียบร้อย','100booking',NOW())";
                			$conn->query($alert);
                		}
                		else{
                			break;
                		}
                	}
                }
                else{
                	break;
                }
			}
		}
	}
	/**/

	#UPDATE WL TO BOOK/WL AND BOOKING
	/* GET per_id FOR UPDATE W/L */
	$_sql = "SELECT period.per_id, period.per_qty_seats, period.per_date_start,bus_list.bus_no,bus_list.bus_qty FROM period 
	LEFT JOIN bus_list ON period.per_id=bus_list.per_id
	WHERE (SELECT COUNT(*) FROM booking WHERE booking.per_id=period.per_id AND booking.status=5 AND booking.bus_no=bus_list.bus_no) > 0 AND period.status = 1";
	$q_sql = $conn->query($_sql);
	/* */

	$numRow = $q_sql->num_rows;
	if( $numRow > 0 ){
		while($_rs = $q_sql->fetch_assoc()){

			/* GET Waiting List */
			$w_sql = "SELECT book_id,user_id,COALESCE(SUM(booking_list.book_list_qty)) AS qty FROM booking 
					  LEFT JOIN booking_list ON booking.book_code=booking_list.book_code 
					  WHERE per_id='{$_rs["per_id"]}' AND bus_no='{$_rs["bus_no"]}' AND status=5 AND booking_list.book_list_code IN ('1','2','3') 
					  GROUP BY booking.book_id 
					  ORDER BY booking.create_date ASC";
			$w_query = $conn->query($w_sql);
			$w_numRow = $w_query->num_rows;
			if( $w_numRow > 0 ){
				/* จำนวนที่นั่งทั้งหมด */
				/*$p_sql = "SELECT per_qty_seats, per_date_start FROM period WHERE per_id={$_rs["per_id"]} LIMIT 1";
				$p_query = $conn->query($p_sql);
				$p_rs = $p_query->fetch_assoc();*/

				/* SET DAY OF GO */
				$DayOfGo = DateDiff(date("Y-m-d"), date("Y-m-d", strtotime($_rs["per_date_start"])));
				if( $DayOfGo > 33 ){
					$deposit_date = date("Y-m-d 18:00:00", strtotime("+2 day"));
					$full_date = date("Y-m-d 18:00:00", strtotime("-30 day", strtotime($_rs["per_date_start"])));
				}
				elseif( $DayOfGo > 8 ){
					$deposit_date = "";
					$deposit_price = 0;
					$full_date = date("Y-m-d 18:00:00", strtotime("tomorrow"));
				}
				else{
					if(date('H')>=19){
						$full_date = date("Y-m-d 10:00", strtotime("tomorrow"));
						$deposit_date = "";
						$deposit_price = 0;
					}else if(date('H')<=6){
						$full_date = date("Y-m-d 10:00");
						$deposit_date = "";
						$deposit_price = 0;
					}else{
					$full_date = date("Y-m-d H:i", strtotime("+4 hour"));
					$deposit_date = "";
					$deposit_price = 0;
					}
				}

				/* จำนวนคนจองทั้งหมด (ตัด Waiting กับ ยกเลิก) */
				$s_sql = "SELECT COALESCE(SUM(booking_list.book_list_qty),0) as qty FROM booking_list
						  LEFT JOIN booking ON booking_list.book_code=booking.book_code
                  		  WHERE booking.per_id={$_rs["per_id"]} AND booking.bus_no={$_rs["bus_no"]} AND booking.status NOT IN ('5','40') AND booking_list.book_list_code IN ('1','2','3')";
                $s_query = $conn->query($s_sql);
                $s_rs = $s_query->fetch_assoc();

                $BalanceSeats = $_rs["bus_qty"] - $s_rs["qty"]; // จำนวนคงเหลือ\\
                if( $BalanceSeats > 0 ){
                	while($w_rs = $w_query->fetch_assoc()){
                		$datenow = date("d/m/Y H:i:s");
                		if( $w_rs["qty"] <= $BalanceSeats ){
                			/* SET NEW DUE DATE */
                			$data = "status='00', book_due_date_full_payment='{$full_date}', book_log='update by cancel booking', book_on_wl='1'";
                			if( !empty($deposit_date) ){
                				$data .= !empty($data) ? "," : "";
                				$data .= "book_due_date_deposit='{$deposit_date}'";
                			}
                			if( isset($deposit_price) ){
                				$data .= !empty($data) ? "," : "";
                				$data .= "book_master_deposit='{$deposit_price}'";
                			}
                			/**/

                			$up_book = "UPDATE booking SET {$data} WHERE book_id={$w_rs["book_id"]}";
                			$conn->query($up_book);
                			$BalanceSeats -= $w_rs["qty"];

                			$alert = "INSERT INTO alert_msg (user_id,book_id,detail,source,log_date) 
                			VALUE ('{$w_rs["user_id"]}','{$w_rs["book_id"]}','ระบบปรับ (W/L) เป็น (จอง) เรียบร้อย','100booking',NOW())";
                			$conn->query($alert);
                		}
                		else{
                			if( $BalanceSeats > 0 ){

                				/* SET NEW DUE DATE */
                				$data = "status='50', book_due_date_full_payment='{$full_date}', book_log='update by cancel booking', book_on_wl='1'";
                				if( !empty($deposit_date) ){
                					$data .= !empty($data) ? "," : "";
                					$data .= "book_due_date_deposit='{$deposit_date}'";
                				}
                				if( isset($deposit_price) ){
                					$data .= !empty($data) ? "," : "";
                					$data .= "book_master_deposit='{$deposit_price}'";
                				}
                				/**/

                				$conn->query("UPDATE booking SET {$data} WHERE book_id={$w_rs["book_id"]}");
                				$alert = "INSERT INTO alert_msg (user_id,book_id,detail,source,log_date) 
                                      VALUE ('{$w_rs["user_id"]}','{$w_rs["book_id"]}','ที่นั่งไม่เพียงพอ','150booking',NOW())";
                                $conn->query($alert);
                                $BalanceSeats = 0;
                                break;
                			}
                		}
                	}
                }
			}
		}
	}

	echo "Record updated successfully";
} else {
	echo "Error updating record: " . $conn->error;
}
