<?php
 
 class Booking extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($book=null) {

        if( empty($this->me) || empty($book) ) $this->error();
        // print_r($this->me); die;
        // $this->error();

        $book = $this->model->get( $book );
        if( empty($book) ) $this->error();
        // print_r($book); die;

        $period = $book['per_id'];
        $item = $this->model->query('products')->period( $period );
        if( empty($item) ) $this->error();
        // print_r($item); die;
        $this->view->setData( 'busList', $this->model->query('products')->busList( $period ) );
        $this->view->setData( 'salesList', $this->model->query('products')->salesList( $period ) );

        // จำนวน ที่นั่ง ที่จองไปแล้ว
        $seatBooked = $this->model->query('products')->seatBooked( $period );
        $this->view->setData( 'seatBooked', $seatBooked );

        $this->view->setData( 'item', $item );
        $this->view->setData( 'book', $book );
        $this->view->render("booking/display");
    }

    public function register($period=null, $bus_no=null) {

        $period = isset($_REQUEST['period'])? $_REQUEST['period']: $period;
        $bus_no = isset($_REQUEST['bus_no'])? $_REQUEST['bus_no']: $bus_no;
        if( empty($this->me) || empty($period) || empty($bus_no) ) $this->error();

        $item = $this->model->query('products')->period( $period, array('bus'=>$bus_no) );
        if( empty($item) ) $this->error();
        // print_r($item); die;

        $promotion = $this->model->getPromotion( date("Y-m-d") );
        // print_r($promotion);die;

        // จำนวน ที่นั่ง ที่จองไปแล้ว
        $seatBooked = $this->model->query('products')->seatBooked( $period, $bus_no );
        $availableSeat = $item['bus_qty']-$seatBooked['booking'];

        $settings = array(
            'trave' => array(
                'date' => date('Y-m-d', strtotime($item['per_date_start']))
            ),
            'deposit' => array(
                'date' => date('Y-m-d'),
                'price' => $item['ser_deposit'],
            ),
            
        );

        $DayOfGo = $this->fn->q('time')->DateDiff( date("Y-m-d"), $item['per_date_start'] );
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
  
        /* CODE คำนวนวันเดินทาง 3 เงื่อนไขของ ใบเฟิร์น (มากกว่า 30 วัน || 8 - 30 วัน || ต่ำกว่า 8 วัน)
        $DayOfGo = $this->fn->q('time')->DateDiff( date("Y-m-d"), $item['per_date_start'] );
        if( $DayOfGo > 30 ){
            $settings['deposit']['date'] = date("Y-m-d", strtotime("+2 day"));
            
        }else if ($DayOfGo >8 && $DayOfGo <=30){
            $settings['fullPayment']['date'] = date("Y-m-d 18:00:00", strtotime("tomorrow"));
            $settings['deposit']['date'] = '-';
            $settings['deposit']['price'] = 0;

        }else if ($DayOfGo >=1 || $DayOfGo <=1  && $DayOfGo <=8){
            $settings['fullPayment']['date'] = date("Y-m-d H:i:s", strtotime("+1 min"));
            $settings['deposit']['date'] = '-';
            $settings['deposit']['price'] = 0;
        }
        */

        $settings['trave']['date'] = date('Y-m-d', strtotime("-1 day", strtotime($settings['trave']['date'])));

        // $settings['fullPayment']['date'] = date('Y-m-d', strtotime("-21 day", strtotime($settings['trave']['date'])));

        // if( strtotime($settings['fullPayment']['date']) < strtotime(date('Y-m-d')) ){
        //     $settings['fullPayment']['date'] = date("Y-m-d", strtotime('tomorrow'));
        //     $settings['deposit']['date'] = '-';
        //     $settings['deposit']['price'] = 0;
        // }

        if( !empty($_POST) ){

            $totalQty = 0;
            $totalDis = 0;
            $status = $availableSeat<=0 ? '05': '00'; // 00 = จอง, 05=รอ
            $_SUM = array('subtotal'=>0, 'discount'=>0, 'total'=>0); $seats = array(); $n = 0;
            foreach ($_POST['seat'] as $key => $value) {
                $n ++;
                if( empty($value) ) $value = 0;
                // if( empty($value) ) continue;

                switch ($key) {
                    case 'adult': $name='Adult'; $price=$item['per_price_1']; break;
                    case 'child': $name='Child'; $price=$item['per_price_2']; break;
                    case 'child_bed': $name='Child No bed'; $price=$item['per_price_3']; break;
                    case 'infant': $name='Infant'; $price=$item['per_price_4']; break;
                    case 'joinland': $name='Joinland'; $price=$item['per_price_5']; break;
                    
                    default: $name=''; $price=0; break;
                }
                $total = $value * $price;
                $seats[] = array(
                    'book_list_code' => $n,
                    'book_list_name' => $name,
                    'book_list_price' => $price,
                    'book_list_qty' => $value,
                    'book_list_total' => $total,
                );

                if( in_array($key, array('adult', 'child', 'child_bed', 'joinland')) ){
                    $totalQty += $value;
                }

                if( in_array($key, array('adult', 'child', 'child_bed')) ){
                    $totalDis += $value;
                }

                $_SUM['subtotal'] += $total;
            }

            $room_total = 0;
            foreach ($_POST["room"] as $key => $value) {
                if( empty($value) ) continue;
                $room_total += $value;
            }

            if( empty($_POST["sale_id"]) ){
                $arr["error"]["sale_id"] = "กรุณาเลือก Sale Contact";
                $arr['message'] = array('text'=>'กรุณาเลือก Sale Contact', 'auto'=>1, 'load'=>1, 'bg'=>'red');
            }
            else if( $totalDis>$availableSeat && $status=='00' ){
                $arr['error'] = 1;
                $arr['message'] = array('text'=>'ใส่จำนวนคนไม่ถูกต้อง!', 'auto'=>1, 'load'=>1, 'bg'=>'red') ;
            }
            else if( empty($seats) ){
                $arr['error'] = 1;
                $arr['message'] = array('text'=>'Please, Input seat!', 'auto'=>1, 'load'=>1, 'bg'=>'red') ;
            }
            else if( empty($room_total) ){
                $arr['error'] = 1;
                $arr['message'] = array('text'=>'กรุณาเลือกห้อง', 'auto'=>1, 'load'=>1, 'bg'=>'red');
            }
            else if( empty($_POST["customername"]) || empty($_POST["customertel"]) ){
                $arr['error']['customername'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
                $arr['error']['customertel'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';

                $arr['message'] = array('text'=>'กรุณากรอกชื่อ-นามสกุล และเบอร์โทรศัพท์ของลูกค้า', 'auto'=>1, 'load'=>1, 'bg'=>'red');
            }
            else{

                /*-- get: prefixnumber --*/
                $prefixNumber = $this->model->prefixNumber();

                $booking = !empty($prefixNumber['pre_booking'])? intval($prefixNumber['pre_booking']): 1;
                $invoice = !empty($prefixNumber['pre_invoice'])? intval($prefixNumber['pre_invoice']): 1;
                $year = !empty($prefixNumber['pre_year'])? intval($prefixNumber['pre_year']): date('Y');
                $month = !empty($prefixNumber['pre_month'])? intval($prefixNumber['pre_month']): date('m');
                
               
                $running_booking = sprintf("%04s", $booking);
                $running_invoice = sprintf("%04s", $invoice);
                $month = sprintf("%02d", $month);
                $bookCode = "B{$year}/{$month}{$running_booking}";

                
                if( !empty($_POST['room']['single']) ){
                    $_SUM['subtotal'] += $_POST['room']['single']*$item['single_charge'];
                }

                $comOffice = $item['per_com_company_agency']*$totalQty;
                $comAgency = $item['per_com_agency']*$totalQty;

                $extra_discount = 0;
                if( $item["per_discount"] > 0 ){
                    $extra_discount = $item["per_discount"] * $totalDis;
                }
                if( $promotion > 0 ){
                    $extra_discount += $promotion * $totalDis;
                }

                $_SUM['discount'] = $comOffice + $comAgency + $extra_discount;
                $_SUM['total'] = $_SUM['subtotal'] - $_SUM['discount'];

                $settings['deposit']['price'] *= $totalQty;

                /*-- insert: booking --*/
                $book = array(
                    "book_code"=>$bookCode, // running_booking
                    "book_date"=>date('c'), // date now
                    "invoice_code"=>"I{$year}/{$month}{$running_invoice}", // running_invoice
                    "invoice_date"=>date('c'), // date now
                    "agen_id"=>$this->me['id'], // login: id
                    "user_id"=>$_POST['sale_id'], // POST: sale_id
                    "per_id"=>$period, // period: id
                    "bus_no"=> $bus_no,  // POST: bus

                    "book_total"=>$_SUM['subtotal'], // book_total // ยอดรวมรายการทั้งหมด

                    "book_master_deposit"=>$settings['deposit']['price'], // จำนวนเงินที่ต้องมัดจำ Master
                    "book_due_date_deposit"=>$settings['deposit']['date'], // กำหนดจ่ายเงินมัดจำ
                    "book_master_full_payment"=>$_SUM['total']-$settings['deposit']['price'], // จำนวนเงินที่ต้องจ่ายเต็ม Master
                    "book_due_date_full_payment"=>$settings['fullPayment']['date'], // กำหนดจ่ายเงิน Full payment

                    "status"=> $status,
                    "book_discount"=> $extra_discount , // หากมีส่วนลดเพิ่มเติมจาก Period
                    "book_amountgrandtotal"=> $_SUM['total'], // book_amountgrandtotal ยอดรวมสุทธิ
                    "book_comment"=>$_POST['comment'], // POST: comment

                    "book_com_agency_company"=>$comOffice,  // period: per_com_company_agency
                    "book_com_agency"=>$comAgency, // period: per_com_agency

                    "book_room_twin"=>$_POST['room']['twin'], 
                    "book_room_double"=>$_POST['room']['double'], 
                    "book_room_triple"=>$_POST['room']['triple'], 
                    "book_room_single"=>$_POST['room']['single'], 

                    "create_date"=>date('c'),
                    "book_cus_name"=>$_POST['customername'],
                    "book_cus_tel"=>$_POST['customertel'],
                );
                // print_r($book); die;
                $this->model->insert($book);


                /*-- insert: booking_list --*/
                foreach ($seats as $key => $value) {
                    $value['book_code'] = $bookCode;
                    $value['create_date'] = date('c');

                    $this->model->detailInsert($value);
                }

                /* -- update: prefixnumber -- */
                $this->model->prefixNumberUpdate( 1, array(
                    'pre_booking' => $booking+1,
                    'pre_invoice' => $invoice+1
                ) );


                $arr['message'] = 'Thank You.';
                $arr['url'] = URL.'booking/'.$book['id'];
                // $arr['url'] = URL;
            }

           echo json_encode( $arr );
           die;
        }
        else{
            $this->view->setData('promotion', $promotion);

            $this->view->setData( 'busList', $this->model->query('products')->busList( $period ) );
            $this->view->setData( 'salesList', $this->model->query('products')->salesList( $period ) );

            
            $this->view->setData( 'seatBooked', $seatBooked );
            
            // print_r($seatBooked); die;

            $this->view->setData( 'item', $item );
            $this->view->setData( 'settings', $settings );


            $this->view->setPage('title', 'จองทัวร์ - ' .  $item['name']);
            $this->view->render("booking/register");
        }

    }


    public function save()
    {
        $arr['message'] = 'Saved.';
        $arr['url'] = 'refresh';
        echo json_encode($arr);
    }

    public function booking_cancel($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();
        
        if( !empty($_POST) ){
            
            $this->model->update($id, array('status'=>40, 'status_cancel'=>3 , 'cancel_by'=>'Agency ID : '.$this->me["id"], 'cancel_date'=>date("c")));
            $this->model->updateWaitingList( $item['per_id'], $item["bus_no"] );

            if( $item['permit']['cancel'] ){
                $arr['message'] = 'ยกเลิกการจองเรียบร้อย';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถยกเลิกได้ กรุณาติดต่อทาง ProbookingCenter';
            }
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->render('forms/booking/cancel');
        }
    }

    public function payment($id=null){
        $this->view->setPage('title', 'แจ้งโอนเงิน');

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get($id, array('payment'=>true));
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->render("booking/payment");
    }

    public function profile($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $book = $this->model->get( $id );
        if( empty($book) ) $this->error();
        // print_r($book); die;

        $period = $book['per_id'];
        $item = $this->model->query('products')->period( $period );
        if( empty($item) ) $this->error();
        // print_r($item); die;
        $this->view->setData( 'busList', $this->model->query('products')->busList( $period ) );
        $this->view->setData( 'salesList', $this->model->query('products')->salesList( $period ) );

        // จำนวน ที่นั่ง ที่จองไปแล้ว
        $seatBooked = $this->model->query('products')->seatBooked( $period );
        $this->view->setData( 'seatBooked', $seatBooked );

        $this->view->setData( 'item', $item );
        $this->view->setData( 'book', $book );
        $this->view->render("forms/booking/profile");
    }

    public function guarantee($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($id) || $this->format != 'json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( !empty($id) && !empty($_FILES["book_guarantee_file"]) ){

                if( !empty($item["book_guarantee_file"]) ){
                    $file = substr(strrchr($item['book_guarantee_file'],"/"),1);
                    if( file_exists(PATH_GUARANTEE.$file) ){
                        @unlink(PATH_GUARANTEE.$file);
                    }
                }

                $i = mt_rand(10, 99);
                $type = strrchr($_FILES["book_guarantee_file"]['name'],".");
                $name = 'gua_'.$i.'_'.date('Y_m_d_H_i_s').$type;
                if( move_uploaded_file($_FILES["book_guarantee_file"]["tmp_name"], PATH_GUARANTEE.$name) ){
                    $this->model->update($id, array("book_guarantee_file"=>"../upload/guarantee/{$name}"));
                    $arr['message'] = 'อัพโหลดเรียบร้อย';
                }
                else{
                    $arr['message'] = 'อัพโหลดไฟล์ไม่สำเร็จ กรุณาลองอีกครั้ง';
                }
            }
            else{
                $arr['message'] = 'เกิดข้อผิดพลาด กรุณาลองอีกครั้ง...';
            }
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->render('forms/booking/guarantee');
        }
    }
    public function passport_view($id=null){
        
        if(empty($id) ) $this->error();    
        $item = $this->model->get($id, array("passport"=>true));
        $booking = $this->model->get($id);
        // authentication page !!
        //print_r($this->me['role']);die;
        if($this->me['id']!= $booking['agen_id'] && $this->me['role']!='admin') $this->error();
       // print_r($this->me);die;
        if( empty($item) ) $this->error();
        $this->view->setData('item', $item);
        $this->view->setData('booking', $booking);
        $this->view->render('booking/passport');
        
    }
    public function passport_insert($id=null){  
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;   
        
        if(empty($id) || $this->format != 'json') $this->error();
        $item = $this->model->get($id, array("passport"=>true));
        if(!empty($_POST)){
        if(!empty($_FILES["book_passport_file"])){
            for($i=0;$i<count($_FILES["book_passport_file"]["name"]);$i++){     
                $type = strrchr($_FILES["book_passport_file"]['name'][$i],".");
                $ii = mt_rand(10, 999989);
                $type = strrchr($_FILES["book_passport_file"]['name'][$i],".");
                $name = 'pass_'.$ii.'_'.date('Y_m_d_H_i_s').$type;
                move_uploaded_file($_FILES["book_passport_file"]["tmp_name"][$i], PATH_PASSPORT.$name);   
                $passport = array(
                    "pass_url"=>"../upload/passport/{$name}",
                    "pass_book_id"=>$id
                    );
                
                $this->model->setPassport($passport);    
            }
                 $arr['message'] = 'อัพโหลดเรียบร้อย';  
                 $arr['url']='refresh';

        }else{
            $arr['message'] = 'เกิดข้อผิดพลาด กรุณาลองอีกครั้ง';    
        }
        echo json_encode($arr);  
     }else{
                $this->view->setData('item', $item);
                $this->view->render('forms/booking/passportinsert');
            }
         
    }
    // manage passpart insert / delete /update / 1 times
    public function passport($id=null){
        header('Access-Control-Allow-Origin:http://admin.probookingcenter.com'); 
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;   
        if( empty($id) ) $this->error();
        $item = $this->model->get($id, array("passport"=>true));
        if( empty($item) ) $this->error();
        if( !empty($_POST) ){
            $_passport = array();
            if( !empty($item["passport"]) ){
                foreach($item["passport"] AS $pass){
                    $_passport[] = $pass["pass_id"];
                    unlink(PATH_PASSPORT.$pass["pass_file_url"]);

                }
            }
            
            if( !empty($_FILES["book_passport_file"]) ){
                for($i=0;$i<count($_FILES["book_passport_file"]["name"]);$i++){     
                    $type = strrchr($_FILES["book_passport_file"]['name'][$i],".");
                    $ii = mt_rand(10, 999989);
                    $type = strrchr($_FILES["book_passport_file"]['name'][$i],".");
                    $name = 'pass_'.$ii.'_'.date('Y_m_d_H_i_s').$type;
                    move_uploaded_file($_FILES["book_passport_file"]["tmp_name"][$i], PATH_PASSPORT.$name);
                    $passport = array(
                                     "pass_url"=>"../upload/passport/{$name}",
                                     "pass_book_id"=>$id
                    );

                    if( !empty($_passport[$i]) ){
                        $passport["id"] = $_passport[$i];
                        unset($_passport[$i]);
                    }

                    $this->model->setPassport($passport);    
                }
                $arr['message'] = 'อัพโหลดเรียบร้อย';   
                $arr['url'] ='refresh';

                if( !empty($_passport) ){
                    foreach($_passport AS $id){
                        $this->model->unsetPassport($id);
                    }
                }
                $arr['message'] = 'อัพโหลดเรียบร้อย';   
                $arr['url'] =URL.'booking/passport_view/'.$item["book_id"];
        		// if( !empty($item["book_passport_file"][]) ){
        		// 	$file = substr(strrchr($item['book_passport_file'],"/"),1);
        		// 	if( file_exists(PATH_PASSPORT.$file) ){
        		// 		@unlink(PATH_PASSPROT.$file);
        		// 	}
                // }
                
        }
            else{
                $arr['message'] = 'เกิดข้อผิดพลาด กรุณาลองอีกครั้ง...';
            }
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->render('forms/booking/passport');
        }
    }
    public function delete_passport($id=null){
        header('Access-Control-Allow-Origin:http://admin.probookingcenter.com'); 
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if(empty($id)) $this->error();
        $item = $this->model->getPassport($id);
            if(!empty($_POST)){          
                $file = substr(strrchr($item['pass_url'],"/"),1);
                unlink(PATH_PASSPORT.$file);
                $this->model->unsetPassport($_POST['id']);
                $arr['message'] = 'ลบไฟล์เรียบร้อยแล้ว';
                $arr['url'] = 'refresh';
                echo json_encode($arr);
            }else{  
                $this->view->setData('item', $item);
                $this->view->render('forms/booking/passport_del');
            }
           
      //  $this->model->unsetPassport($id);
    }
    public function zip_passport($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['$ID'] : $id;
       // if(empty($_POST['Authentication'])) $this->error();
        $item = $this->model->get($id, array("passport"=>true));
        $i = mt_rand(10, 999989);
        $zipname = 'zip_passport_'.$i.'.zip';
        $zip = new ZipArchive;
        if($zip->open($zipname, ZipArchive::CREATE) ===TRUE){
             foreach($item['passport'] as $key => $value){
                 $zip->addFile(PATH_PASSPORT.$value['pass_file_url'], $value['pass_file_url']);      
             }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$zipname);
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);
            @unlink(PATH_ROOT.$zipname);
        }
     }
     public function passport_update($id=null){
         header('Access-Control-Allow-Origin:http://admin.probookingcenter.com'); // Allow only origin refernce admin.probookingcenter

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        
        if(empty($id)) $this->error();
        if(!empty($_POST)){

            $this->model->update($_POST['id'], array('booking_passport'=>1));
            $arr['message'] = 'บันทึกเรียบร้อยแล้ว';
            $arr['url'] = 'refresh';
            echo json_encode($arr); 
        }else{
            $this->view->setData('id', $id);
            $this->view->render('forms/booking/updatepassport');
        }
    }
    public function payRejected($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        if( empty($this->me) || empty($id) || $this->format != 'json' ) $this->error();
        $item = $this->model->query('payment')->get($id);

        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->render('forms/booking/payrejected');
    }

    /* JSON ZONE */
    public function listsAgency( $com_id=null ){
        if( empty($com_id) ) $this->error();
        echo json_encode( $this->model->query("agency")->lists( array("company"=>$com_id, "status"=>1) ));
    }
}