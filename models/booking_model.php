<?php

class Booking_Model extends Model {


	public function __construct()
	{
		parent::__construct();
	}

    private $_objName = "booking";
    private $_table = "booking b 
                       LEFT JOIN agency ag ON b.agen_id=ag.agen_id
                       LEFT JOIN period per ON b.per_id=per.per_id
                       LEFT JOIN series ser ON per.ser_id=ser.ser_id
                       LEFT JOIN agency_company ac ON ag.agency_company_id=ac.agen_com_id
                       LEFT JOIN user u ON b.user_id=u.user_id";
    private $_field = "b.*
                       , ag.agen_fname
                       , ag.agen_lname
                       , ag.agen_position
                       , ag.agen_email
                       , ag.agen_tel
                       , ag.agen_nickname
                       , per.per_url_pdf 
                       , per.per_date_start
                       , per.per_date_end

                       , ac.agen_com_name
                       , ac.agen_com_id

                       , u.user_fname
                       , u.user_lname
                       , u.user_nickname
                       , u.user_name
                    
                       , ser.ser_id
                       , ser.ser_name
                       , ser.ser_code";
    private $_cutNamefield = "book_";

    public function lists($options=array()){
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'create_date',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($_REQUEST["status"]) ){
            $options["status"] = $_REQUEST["status"];
        }

        if( !empty($options["company"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "ag.agency_company_id=:company";
            $where_arr[":company"] = $options["company"];
        }

        if( !empty($options["agency"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "b.agen_id=:agency";
            $where_arr[":agency"] = $options["agency"];
        }

        if( !empty($options["period"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "b.per_id=:period";
            $where_arr[":period"] = $options["period"];
        }

        if( !empty($options["bus"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "b.bus_no=:bus";
            $where_arr[":bus"] = $options["bus"];
        }

        if( isset($options["status"]) && $options["status"] != null ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "b.status=:status";
            $where_arr[":status"] = $options["status"];
        }

        if( !empty($options["q"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "(b.book_code LIKE :q
                           OR ag.agen_fname LIKE :q
                           OR ser.ser_code LIKE :q
                           OR ser.ser_name LIKE :q)";
            $where_arr[":q"] = "%{$options["q"]}%";
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        if( !empty($options["unlimit"]) ) $limit = '';
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }

	public function prefixNumber()
	{
		$sth = $this->db->prepare("SELECT * FROM prefixnumber LIMIT 1");
        $sth->execute();

        return $sth->fetch( PDO::FETCH_ASSOC );
	}
	public function prefixNumberUpdate($id, $data) {
		$this->db->update('prefixnumber', $data, "`prefix_id`={$id}");
	}


	public function get($id, $options=array())
	{
		$sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE book_id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options );
        } return array();
	}
	public function insert(&$data){
    	$this->db->insert('booking', $data);
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
        $this->db->update($this->_objName, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value, $options );
        }

        return $data;
    }
    public function convert($data, $options=array()){

    	// $data = $this->_cutFirstFieldName($this->_cutNamefield, $data);
        
        $total_qty = 0;
        $booking_list = $this->db->select("SELECT * FROM booking_list WHERE `book_code`=:code ORDER BY book_list_code ASC", array(':code'=>$data['book_code']));
        $items = array();
        $data['book_inf'] = 0;
        $data['book_jl'] = 0;
        foreach ($booking_list as $key => $value) {
        	$items[$value['book_list_code']] = $value;
            if( $value['book_list_code'] == 4 ){
                $data['book_inf'] += $value['book_list_qty'];
                continue;
            }
            if( $value['book_list_code'] == 5 ){
                $data['book_jl'] += $value['book_list_qty'];
                continue;
            }
            $total_qty += $value["book_list_qty"];
        }
        $data['book_qty'] = $total_qty;
        $data['book_status'] = $this->getStatus($data['status']);
        $data['items'] = $items;

        if( !empty($options["payment"]) ){
            $data["payment"] = $this->listsPayment($data["book_id"]);
        }
        if( !empty($options["passport"]) ){
            $data["passport"] = $this->listsPassport($data["book_id"]);
        }
        if( !empty($options["room"]) ){
            $data["room"] = $this->listsRoom($data["book_code"]);
        }

        if( !empty($data['per_url_pdf']) && $data['per_url_pdf'] != 'undefined' ){
            $file = substr(strrchr($data['per_url_pdf'],"/"),1);
            if( file_exists(PATH_TRAVEL.$file) ){
                $data['per_url_pdf'] = 'http://admin.probookingcenter.com/admin/upload/travel/'.$file;
            }
            else{
                $data['per_url_pdf'] = '';
            }
        }else{
            $data['per_url_pdf'] = '';
        }
        $data["total_passport"] = $this->db->count("passport", "pass_book_id={$data["book_id"]}");
        $data["permit"]["cancel"] = false;
        if( $data["status"] == 0 || $data["status"] == 5 || $data['status'] == 10 ){
            $data["permit"]["cancel"] = true;
        }

        return $data;
    }


    public function detailInsert(&$data){
    	$this->db->insert('booking_list', $data);
    	$data['id'] = $this->db->lastInsertId();
    }

    /* STATUS */
    public function status(){
        $a[] = array('id'=>0, 'name'=>'จอง', 'detail'=>"จอง");
        $a[] = array('id'=>10, 'name'=>'แจ้ง Quotation', 'detail'=>"แจ้ง quatation");
        $a[] = array('id'=>20, 'name'=>'มัดจำ(บางส่วน)', 'detail'=>"มัดจำบางส่วน");
        $a[] = array('id'=>25, 'name'=>'มัดจำ', 'detail'=>"มัดจำต็มจำนวน");
        $a[] = array('id'=>30, 'name'=>'ชำระเต็มจำนวน (บางส่วน)', 'detail'=>"ชำระเต็มจำนวน บางส่วน");
        $a[] = array('id'=>35, 'name'=>'ชำระเต็มจำนวน', 'detail'=> "ชำระเต็มจำนวน แบบเต็มจำนวน");
        $a[] = array('id'=>40, 'name'=>'ยกเลิก', "detail"=> "Cancel");
        $a[] = array('id'=>50, 'name'=>'จอง/WL', "detail"=> "จอง/Waiting");
        $a[] = array('id'=>5, 'name'=>'Waiting List', 'detail'=>"Waiting List");
        $a[] = array('id'=>55, 'name'=>'แจ้งชำระเงิน', 'detail'=>"แจ้งชำระเงิน");
        $a[] = array('id'=>60, 'name'=>'รอการตรวจสอบ');
        return $a;
    }
    public function getStatus($id){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $id == $value['id'] ){
                $data = $value;
                break;
            }
        }
        return $data;
    }

    public function updateWaitingList($per_id, $bus_no){
        /* GET Waiting List */
        $waiting = $this->db->select("SELECT book_id,user_id,COALESCE(SUM(booking_list.book_list_qty)) AS qty FROM booking LEFT JOIN booking_list ON booking.book_code=booking_list.book_code WHERE booking.bus_no = {$bus_no} booking.per_id={$per_id} AND  (status=5 OR status=50) AND booking_list.book_list_code IN ('1','2','3') GROUP BY booking.book_id ORDER BY booking.create_date ASC");
      
        if( !empty($waiting) ){
            /* จำนวนทีนั่งทั้งหมด */
            $seats = $this->db->select("SELECT bus_list.bus_qty  period.per_qty_seats, period.per_date_start FROM period LEFT JOIN bus_list ON period.period.per_id = bus_list.per_id WHERE per_id={$per_id} LIMIT 1");

            /* SET DAY OF GO */
            $DayOfGo = $this->fn->q('time')->DateDiff(date("Y-m-d"), date("Y-m-d", strtotime($seats[0]["per_date_start"])));
            if( $DayOfGo > 31 ){ //32 day
                $settings['deposit']['date'] = date("Y-m-d 18:00", strtotime("+2 day"));
                $settings['fullPayment']['date'] = date('Y-m-d 18:00', strtotime("-30 day", strtotime($settings['trave']['date'])));
            }elseif ( $DayOfGo > 13 ){ //14 - 31 day
                $settings['fullPayment']['date'] = date("Y-m-d 18:00", strtotime("+2 day"));
                $settings['deposit']['date'] = '';
                $settings['deposit']['price'] = 0;
            }elseif($DayOfGo >7){ //13 - 8 day
                $settings['fullPayment']['date'] = date("Y-m-d 18:00", strtotime("+1 day"));
                $settings['deposit']['price'] = 0;
                $settings['deposit']['date'] = '';
            }elseif($DayOfGo >3){ // 4 -7 day
                $settings['fullPayment']['date'] = date("Y-m-d H:i:s", strtotime("+12 hour"));
                $settings['deposit']['price'] = 0;
                $settings['deposit']['date'] = '';
            }
            else{
                $settings['fullPayment']['date'] = date("Y-m-d H:i:s", strtotime("+3 hour"));
                $settings['deposit']['price'] = 0;
                $settings['deposit']['date'] = '';
            }
            /* จำนวนคนจองทั้งหมด (ตัด Waiting กับ ยกเลิกแล้ว) (นับ จอง / WL) */
            $book = $this->db->select("SELECT COALESCE(SUM(booking_list.book_list_qty),0) as qty FROM booking_list
                    LEFT JOIN booking ON booking_list.book_code=booking.book_code
                  WHERE booking.bus_no = {$bus_no} AND booking.per_id={$per_id} AND booking.status!=5 AND booking.status!=40 AND booking_list.book_list_code IN ('1','2','3')");
            $BalanceSeats = $seats[0]["bus_qty"] - $book[0]["qty"];
            if( $BalanceSeats > 0 ){
                foreach ($waiting as $key => $value) {
                    $datenow = date("d/m/Y H:i:s");
                    if( !empty($BalanceSeats) ){
                        if( $value["qty"] <= $BalanceSeats ){
                            /* SET STATUS BOOKING */

                            $postData = array();
                            $postData["status"] = 0;
                            $postData["book_log"] = "update by cancel booking (WL System FrontEnd)";
                            $postData["book_on_wl"] = 1;
                            $postData["book_due_date_full_payment"] = $full_date;
                            if( !empty($deposit_date) ){
                                $postData["book_due_date_deposit"] = $deposit_date;
                            }
                            if( isset($deposit_price) ){
                                $postData["book_master_deposit"] = $deposit_price;
                            }

                            $this->db->update("booking", $postData, "book_id={$value["book_id"]}");
                            $BalanceSeats -= $value["qty"];

                            /* SET ALERT FOR SALE */
                            $alert = array(
                                "user_id"=>$value["user_id"],
                                "book_id"=>$value["book_id"],
                                "detail"=>"ระบบปรับ (W/L) เป็น (จอง) ให้แล้ว",
                                "source"=>"100booking",
                                "log_date"=>date("c")
                            );
                            $this->db->insert("alert_msg", $alert);
                        }
                    }
                    else{
                        if( $BalanceSeats > 0 ){
                            /* SET STATUS BOOKING */

                            $postData = array();
                            $postData["status"] = 50;
                            $postData["book_log"] = "update by cancel booking (WL System FrontEnd)";
                            $postData["book_on_wl"] = 1;
                            $postData["book_due_date_full_payment"] = $full_date;
                            if( !empty($deposit_date) ){
                                $postData["book_due_date_deposit"] = $deposit_date;
                            }
                            if( isset($deposit_price) ){
                                $postData["book_master_deposit"] = $deposit_price;
                            }

                            $this->db->update("booking", $postData, "book_id={$value["book_id"]}");

                            /* SET ALERT FOR SALE */
                            $alert = array(
                                "user_id"=>$value["user_id"],
                                "book_id"=>$value["book_id"],
                                "detail"=>"ที่นั่งไม่เพียงพอ",
                                "source"=>"150booking",
                                "log_date"=>date("c")
                            );
                            $this->db->insert("alert_msg", $alert);

                            /* EXIT LOOP */
                            $BalanceSeats = 0;
                            break;
                        }
                    }
                }
            }
        }
    }

    /* PAYMENT */
    public function listsPayment($id){
        $data = array();
        $data["pay_total"] = 0 ;
        $results = $this->db->select("SELECT p.*
                                            , b.bank_name
                                            , b.bankbook_code
                                            , b.bankbook_name
                                            , b.bankbook_branch 
                                    FROM payment p LEFT JOIN bankbook b 
                                    ON p.bankbook_id=b.bankbook_id WHERE book_id={$id}");
        foreach ($results as $key => $value) {
            $data['lists'][$key] = $value;
            $data['lists'][$key]["book_status"] = $this->getStatus( $value["book_status"] );
            $data['lists'][$key]["status"] = $this->query('payment')->getStatus( $value["status"] );
            if( !empty($value['pay_url_file']) ){
                $file = substr(strrchr($value['pay_url_file'],"/"),1);
                // $data[$key]['pay_url_file'] = 'http://admin.probookingcenter.com/admin/upload/payment/'.$file;
                if( file_exists(PATH_PAYMENT.$file) ){
                    $data['lists'][$key]['pay_url_file'] = 'http://admin.probookingcenter.com/admin/upload/payment/'.$file;
                }
                else{
                    $data['lists'][$key]['pay_url_file'] = '';
                }
            }

            if( $value["status"] == 1 ){
                $data["pay_total"] += $value["pay_received"];
            }
        }
        return $data;
    }

    public function getPassport($id){
        $sth = $this->db->prepare("SELECT * FROM passport WHERE pass_id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        if( $sth->rowCount()==1 ){
            return $sth->fetch( PDO::FETCH_ASSOC );
        } return array();
    }
    public function listsPassport($id){
        $data = array();
        $results = $this->db->select("SELECT * from passport WHERE pass_book_id={$id}");
        foreach ($results as $key => $value){
            $data[$key] =$value;
            $file = substr(strrchr($value['pass_url'],"/"),1);
            $data[$key]['pass_file_url']= $file;   
        }
        return$data;
    }
    public function setPassport($data){
        if( !empty($data["id"]) ){
            $id = $data["id"];
            unset($data["id"]);
            $this->db->update("passport", $data, "pass_id={$id}");
        }
        else{
            $this->db->insert("passport", $data);
        }
    }
     public function unsetPassport($id){
        $this->db->delete("passport", "pass_id={$id}");
    }
    public function getPromotion( $date ){
        $sth = $this->db->prepare("SELECT COALESCE(SUM(pro_discount),0) AS discount FROM promotions WHERE (pro_start_date <= :datenow AND pro_end_date >= :datenow) AND pro_status='enabled' LIMIT 1");
        $sth->execute( array( ':datenow' => $date ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );

        if( $sth->rowCount()==1 ){
            return $fdata["discount"];
        } return 0;
    }

    /* ROOM DETAIL */
    private $_roomSelect = "*";
    private $_roomTable = "room_detail";
    public function listsRoom( $code, $options=array() ){

        $where_str = "";
        $where_arr = array();

        /* options zone */

        /* condition */
        $where_str .= !empty($where_str) ? " AND " : "";
        $where_str .= "book_code=:code";
        $where_arr[":code"] = $code;

        $where_str = !empty($where_str) ? "WHERE {$where_str}" : "";

        $sql = "SELECT {$this->_roomSelect} FROM {$this->_roomTable} {$where_str}";
        return $this->buildFragRoom( $this->db->select($sql, $where_arr) );
    }
    public function buildFragRoom( $results, $options=array() ){
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convertRoom( $value, $options );
        }
        return $data;
    }
    public function convertRoom( $data, $options=array() ){
        $data = $this->_cutFirstFieldName("room_", $value);
        return $data;
    }

    /* AGENCY */
    public function companyLists(){
        return $this->db->select("SELECT agen_com_id AS id, agen_com_name AS name FROM agency_company WHERE status=1 ORDER BY agen_com_name ASC");
    }
}