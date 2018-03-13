<?php

class Office extends Controller {

    public function __construct() {
        parent::__construct();

        $this->view ->js("jquery/jquery-selector.min")
                    ->css('jquery-selector');
    }

    public function index(){
    	header("location:".URL."office/settings");
    }
    public function settings($section='my',$tap=''){

    	$this->view->setPage('on', 'settings' );
        $this->view->setPage('title', 'ตั้งค่า');
        $this->view->setData('section', $section);
        if( !empty($tap) ) $this->view->setData('tap', $tap);

        if( $section == "my" ){
        	if( empty($tap) ) $tap = 'basic';

            $this->view->setData('section', 'my');
            $this->view->setData('tap', 'display');
            $this->view->setData('_tap', $tap);

    		// if( $tap=='basic' ){

    		// 	$this->view
    		// 	->js(  VIEW .'Themes/'.$this->view->getPage('theme').'/assets/js/bootstrap-colorpicker.min.js', true)
    		// 	->css( VIEW .'Themes/'.$this->view->getPage('theme').'/assets/css/bootstrap-colorpicker.min.css', true);

    		// 	$this->view->setData('prefixName', $this->model->query('system')->prefixName());
    		// }
        }
        elseif( $section == "users" ){

            if( empty($tap) ) $tap = 'users';
            if( $tap == 'users' ){
                if( $this->format=='json' ){
                    $results = $this->model->query('user')->lists();
                    $this->view->setData('results', $results);
                    $render = "settings/sections/users/users/json";
                }
                else{
                    $this->view->setData('status', $this->model->query('user')->status());
                    $this->view->setData('group', $this->model->query('user')->group());
                    $this->view->setData('teams', $this->model->query('teams')->lists());
                }
            }
            elseif( $tap == 'group' ){
                $this->view->setData('data', $this->model->query('user')->group());
            }
            elseif( $tap == 'teams' ){
                $this->view->setData('data', $this->model->query('teams')->lists());
            }
        }
        // elseif( $section == 'agency' ){
        //     if( empty($tap) ) $tap = 'company';
        //     if( $tap == 'company' ){
        //         if( $this->format=='json' ){
        //             $results = $this->model->query('agency_company')->lists();
        //             $this->view->setData('results', $results);
        //             $render = "settings/sections/agency/company/json";
        //         }
        //         else{
        //             $this->view->setData('status', $this->model->query('agency_company')->status());
        //         }
        //     }
        //     else{
        //         $this->error();
        //     }
        // }
        elseif( $section == 'products' ){
            if( empty($tap) ) $tap = 'promotions';
            if( $tap == 'promotions' ){
                if( $this->format=='json' ){
                    $results = $this->model->query('promotions')->lists();
                    $this->view->setData('results', $results);
                    $render = "settings/sections/products/promotions/json";
                }
                else{
                    $this->view->setData('status', $this->model->query('promotions')->status());
                }
            }
        }
        else{
            $this->error();
        }
        $this->view->render( !empty($render) ? $render : "settings/display");
    }

    public function reports($section="booking", $tap=""){
        $this->view->setPage('on', 'reports');
        $this->view->setPage('title', 'Reports - '.ucfirst($section));
        $this->view->setData('section', $section);
        if( !empty($tap) ) $this->view->setData('tap', $tap);

        if( $section == "booking" ){
            if( empty($tap) ) $tap = "daily";
            $this->view->setData('tap', $tap);
            if( $tap == "daily" ){

                // $this->view->js('jquery/jquery-selector.min')
                //            ->css('jquery-selector');

                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'status'=>1,'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('booking')->status());
            }else if($tap =="monthy"){
               
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'status'=>1,'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('booking')->status());
            }else{
                $this->error();
            }
        }
        elseif( $section == "recevied" ){
            if( empty($tap) ) $tap = "daily";
            $this->view->setData('tap', "recevied_".$tap);
            if( $tap == "daily" ){
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('bank', $this->model->query("bankbook")->lists());
            }
        }
        elseif( $section == "period" ){
            if( empty($tap) ) $tap = "monthy";
            $this->view->setData('tap', "period_".$tap);
            if( $tap == "monthy" ){
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'status'=>1,'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('booking')->status());
            }
        }
        elseif( $section == "monitor" ){
            $this->view->setData('tap', "monitor");
            $this->view->setData('status', $this->model->query('products')->periodStatus());
            $this->view->setData('country', $this->model->query('products')->categoryList());
        }
        elseif( $section == "sales" ){
            if( empty($tap) ) $tap = "teams";
            $this->view->setData('tap', $tap);
            if( $tap == "teams" ){
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('teams', $this->model->query('teams')->lists( array('sort'=>'name', 'dir'=>'ASC') ));
            }
        }
        else{
            $this->error();
        }
        $this->view->render( !empty($render) ? $render : "reports/display" );
    }

    public function agency($id=null){

        $this->view->setPage('title', 'เอเจนซี่');
        $this->view->setPage('on', 'agency');

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( !empty($id) ){

        }
        else{
            if( $this->format=='json' ){
                $results = $this->model->query('agency')->lists();
                $this->view->setData('results', $results);
                $render = "agency/lists/json";
            }
            else{
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('agency')->status());
                $render = "agency/lists/display";
            }
        }
        $this->view->render( $render );
    }
    public function agency_company($id=null){
        $this->view->setPage('title', 'บริษัทเอเจนซี่');
        $this->view->setPage('on', 'agency_company');

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( !empty($id) ){

        }
        else{
            if( $this->format=='json' ){
                $results = $this->model->query('agency_company')->lists();
                $this->view->setData('results', $results);
                $render = "agency_company/lists/json";
            }
            else{
                $this->view->setData('status', $this->model->query('agency_company')->status());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('province', $this->model->query('system')->province());
                $render = "agency_company/lists/display";
            }
        }
        $this->view->render($render);
    }

    public function series($id=null, $bus=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        $bus = isset($_REQUEST["bus"]) ? $_REQUEST["bus"] : $bus;

        $this->view->setPage('title', 'ซีรีย์ทัวร์');
        $this->view->setPage('on', 'series');

        if( !empty($id) && !empty($bus) ){
            $this->view->setData('tab', 'booking'); 

            $item = $this->model->query('products')->period($id ,array('office'=>true, 'bus'=>$bus));
            if( empty($item) ) $this->error();

            $this->view->setData('item', $item);

            $options = array(
                'period'=>$item['per_id'],
                'bus'=>$item['bus_no'],
                'unlimit'=>true,
                'dir'=>'ASC'
                // 'q'=> !empty($_REQUEST["q"]) ? $_REQUEST["q"] : '' 
            );
            $this->view->setData('booking', $this->model->query('booking')->lists( $options ));
            $render = "series/profile/display"; 
        }
        else{
            if( $this->format=='json' ){
                $results = $this->model->query('products')->lists( array('office'=>true, 'period'=>true) );
            // print_r($results);die;
                $this->view->setData('results', $results);
                $render = "series/lists/json";
            }
            else{
                $this->view->setData("category", $this->model->query("products")->categoryList());
                $render = "series/lists/display";
            }
        }
        $this->view->render( $render );
    }

    public function booking($sections=null, $id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        $this->view->setPage('on', 'booking');
        $this->view->setPage('title', 'จัดการการจองทัวร์');

        if( !empty($sections) ){
            /* SET ITEM */
            if( !empty($id) ){
                $item = $this->model->query('booking')->get($id, array('payment'=>true, 'room'=>true));
                if( empty($item) ) $this->error();
                $this->view->setData("item", $item);

                $period = $item["per_id"];
                $bus = $item["bus_no"];
            }
            else{
                $period = isset($_REQUEST["period"]) ? $_REQUEST["period"] : null;
                $bus = isset($_REQUEST["bus"]) ? $_REQUEST["bus"] : null;
            }

            /* SET PERIOD */
            if( empty($period) || empty($bus) ) $this->error();
            $per = $this->model->query('products')->period( $period, array('bus'=>$bus) );
            if( empty($per) ) $this->error();
            $this->view->setData('period', $per);

            /* SET SECTIONS */
            if( empty($sections) ) $sections = "basic";
            $this->view->setData("sections", $sections);

            if( $sections == "basic" ){
                $this->view->setPage('title', 'รายละเอียดการจองทัวร์');
                $this->view->setData('busList', $this->model->query("products")->busList( $period ));

                // $promotion = $this->model->query("booking")->getPromotion( date("Y-m-d") );

                // จำนวน ที่นั่ง ที่จองไปแล้ว
                $seatBooked = $this->model->query('products')->seatBooked( $period, $bus );
                $availableSeat = $per['bus_qty']-$seatBooked['booking'];

                $settings = array(
                    'trave' => array(
                        'date' => date('Y-m-d', strtotime($per['per_date_start']))
                    ),
                    'deposit' => array(
                        'date' => date('Y-m-d'),
                        'price' => $per['ser_deposit'],
                    ),
                );

                $DayOfGo = $this->fn->q('time')->DateDiff( date("Y-m-d"), $per['per_date_start'] );
                if( $DayOfGo > 31 ){ // 32+
                    $settings['deposit']['date'] = date("Y-m-d 18:00", strtotime("+2 day"));
                    $settings['fullPayment']['date'] = date('Y-m-d 18:00', strtotime("-30 day", strtotime($settings['trave']['date'])));
                }elseif ( $DayOfGo > 13 ){ // 14-31
                    $settings['fullPayment']['date'] = date("Y-m-d 18:00", strtotime("+2 day"));
                    $settings['deposit']['date'] = '';
                    $settings['deposit']['price'] = 0;
                }elseif($DayOfGo >7){ // 8-13
                    $settings['fullPayment']['date'] = date("Y-m-d 18:00", strtotime("+1 day"));
                    $settings['deposit']['price'] = 0;
                    $settings['deposit']['date'] = '';
                }elseif($DayOfGo >3){ // 4-7
                    $settings['fullPayment']['date'] = date("Y-m-d H:i:s", strtotime("+12 hour"));
                    $settings['deposit']['price'] = 0;
                    $settings['deposit']['date'] = '';
                }
                else{ //defualt
                    $settings['fullPayment']['date'] = date("Y-m-d H:i:s", strtotime("+3 hour"));
                    $settings['deposit']['price'] = 0;
                    $settings['deposit']['date'] = '';
                }

                $settings['trave']['date'] = date('Y-m-d', strtotime("-1 day", strtotime($settings['trave']['date'])));

                if( !empty($_POST) ){

                    $_items = array();
                    if( !empty($item) ){
                        $availableSeat += $item["book_qty"];
                        $status = $item["status"];

                        if( !empty($item['items']) ){
                            foreach ($item['items'] as $key => $value) {
                                $_items[] = $value["book_list_id"];
                            }
                        }
                    }
                    else{
                        $status = $availableSeat<=0 ? '05': '00'; // 00 = จอง, 05=รอ
                    }

                    $totalQty = 0;
                    $totalDis = 0;
                    $_SUM = array('subtotal'=>0, 'discount'=>0, 'total'=>0); $seats = array(); $n = 0;
                    foreach ($_POST['seat'] as $key => $value) {
                        $n ++;
                        if( empty($value) ) $value = 0;
                    // if( empty($value) ) continue;

                        switch ($key) {
                            case 'adult': $name='Adult'; $price=$per['per_price_1']; break;
                            case 'child': $name='Child'; $price=$per['per_price_2']; break;
                            case 'child_bed': $name='Child No bed'; $price=$per['per_price_3']; break;
                            case 'infant': $name='Infant'; $price=$per['per_price_4']; break;
                            case 'joinland': $name='Joinland'; $price=$per['per_price_5']; break;

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

                    $ex_count = count($_POST["book_list"]["name"]);
                    for($i=0;$i<=$ex_count;$i++){
                        $n++;
                        if( empty($_POST["book_list"]["name"][$i]) || 
                            empty($_POST["book_list"]["price"][$i]) || 
                            empty($_POST["book_list"]["qty"][$i]) ) continue;

                        $seats[] = array(
                            'book_list_code' => $n,
                            'book_list_name' => $_POST["book_list"]["name"][$i],
                            'book_list_price' => $_POST["book_list"]["price"][$i],
                            'book_list_qty' => $_POST["book_list"]["qty"][$i],
                            'book_list_total' => $_POST["book_list"]["total"][$i],
                        );
                        $_SUM['subtotal'] += $_POST["book_list"]["total"][$i];
                    }

                    if( !empty($item) ){
                        if( $totalDis>$availableSeat ){
                            $arr['error'] = 1;
                            $arr['message'] = array('text'=>'ใส่จำนวนคนไม่ถูกต้อง !', 'auto'=>1, 'load'=>1, 'bg'=>'red') ;
                        }
                    }
                    else{
                        if( $totalDis>$availableSeat && $status=='00' ){
                            $arr['error'] = 1;
                            $arr['message'] = array('text'=>'ใส่จำนวนคนไม่ถูกต้อง !', 'auto'=>1, 'load'=>1, 'bg'=>'red') ;
                        }
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
                    else if( empty($seats) ){
                        $arr['error'] = 1;
                        $arr['message'] = array('text'=>'กรุณากรอกข้อมูลที่นั่ง !', 'auto'=>1, 'load'=>1, 'bg'=>'red') ;
                    }
                    /*else if( empty($room_total) ){
                        $arr['error'] = 1;
                        $arr['message'] = array('text'=>'กรุณาเลือกห้อง', 'auto'=>1, 'load'=>1, 'bg'=>'red');
                    }*/
                    else if( empty($_POST["customername"]) || empty($_POST["customertel"]) ){
                        $arr['error']['customername'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
                        $arr['error']['customertel'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
                        $arr['message'] = array('text'=>'กรุณากรอกชื่อ-นามสกุล และเบอร์โทรศัพท์ของลูกค้า', 'auto'=>1, 'load'=>1, 'bg'=>'red');
                    }
                    else if( empty($arr['error']) ){

                        if( !empty($_POST['room']['single']) ){
                            $_SUM['subtotal'] += $_POST['room']['single']*$per['single_charge'];
                        }

                        $comOffice = $per['per_com_company_agency']*$totalQty;
                        $comAgency = $per['per_com_agency']*$totalQty;

                        $extra_discount = $_POST["book_discount"];
                        if( $per["per_discount"] > 0 ){
                            $extra_discount = $per["per_discount"] * $totalDis;
                        }
                        // if( $promotion > 0 ){
                        //     $extra_discount += $promotion * $totalDis;
                        // }

                        $_SUM['discount'] = $comOffice + $comAgency + $extra_discount;
                        $_SUM['total'] = $_SUM['subtotal'] - $_SUM['discount'];

                        $settings['deposit']['price'] *= $totalQty;

                        /*-- setData: booking --*/
                        $book = array(
                            "agen_id"=>$_POST['sale'],
                            "user_id"=>$_POST['sale_id'],
                            "per_id"=>$period, // period: id
                            "bus_no"=> $bus,  // POST: bus

                            "book_total"=>$_SUM['subtotal'], // book_total // ยอดรวมรายการทั้งหมด

                            "book_master_deposit"=>$settings['deposit']['price'], // จำนวนเงินที่ต้องมัดจำ Master
                            "book_due_date_deposit"=>$settings['deposit']['date'], // กำหนดจ่ายเงินมัดจำ
                            "book_master_full_payment"=>$_SUM['total']-$settings['deposit']['price'], // จำนวนเงินที่ต้องจ่ายเต็ม Master
                            "book_due_date_full_payment"=>$settings['fullPayment']['date'], // กำหนดจ่ายเงิน Full payment

                            "status"=> !empty($item["status"]) ? $item["status"] : $status,
                            "book_discount"=> $extra_discount , // หากมีส่วนลดเพิ่มเติมจาก Period
                            "book_amountgrandtotal"=> $_SUM['total'], // book_amountgrandtotal ยอดรวมสุทธิ
                            "book_comment"=>$_POST['comment'], // POST: comment

                            "book_com_agency_company"=>$comOffice,  // period: per_com_company_agency
                            "book_com_agency"=>$comAgency, // period: per_com_agency

                            "book_room_twin"=>$_POST['room']['twin'], 
                            "book_room_double"=>$_POST['room']['double'], 
                            "book_room_triple"=>$_POST['room']['triple'], 
                            "book_room_single"=>$_POST['room']['single'], 

                            "book_cus_name"=>$_POST['customername'],
                            "book_cus_tel"=>$_POST['customertel'],
                        );

                        if( !empty($id) ){
                            $book["inv_rev_no"] = $item["inv_rev_no"]+1;
                            $book["update_date"] = date("c");
                            $book["update_user_id"] = $this->me["id"];
                            $invoice_code = str_replace("B", "", $item["book_code"]);
                            $book["invoice_code"] = "I{$invoice_code}({$book["inv_rev_no"]})";
                            $this->model->query('booking')->update($id, $book);
                            $bookCode = $item["book_code"];
                        }
                        else{
                            /*-- get: prefixnumber --*/
                            $prefixNumber = $this->model->query('booking')->prefixNumber();

                            $booking = !empty($prefixNumber['pre_booking'])? intval($prefixNumber['pre_booking']): 1;
                            $invoice = !empty($prefixNumber['pre_invoice'])? intval($prefixNumber['pre_invoice']): 1;
                            $year = !empty($prefixNumber['pre_year'])? intval($prefixNumber['pre_year']): date('Y');
                            $month = !empty($prefixNumber['pre_month'])? intval($prefixNumber['pre_month']): date('m');


                            $running_booking = sprintf("%04s", $booking);
                            $running_invoice = sprintf("%04s", $invoice);
                            $month = sprintf("%02d", $month);
                            $bookCode = "B{$year}/{$month}{$running_booking}";

                            $book["book_date"] = date('c');
                            $book["book_code"] = $bookCode;
                            $book["invoice_code"] = "I{$year}/{$month}{$running_invoice}";
                            $book["invoice_date"] = date("c");
                            $book["create_date"] = date("c");
                            $book["create_user_id"] = $this->me["id"];

                            $this->model->query('booking')->insert( $book );

                            /* -- update: prefixnumber -- */
                            $this->model->query('booking')->prefixNumberUpdate( 1, array(
                                'pre_booking' => $booking+1,
                                'pre_invoice' => $invoice+1
                            ) );
                        }

                        /* SET DETAIL */
                        foreach ($seats as $key => $value) {
                            if( !empty($_items[$key]) ){
                                $value['update_date'] = date('c');
                                $this->model->query('booking')->detailUpdate($_items[$key], $value);
                                unset($_items[$key]);
                            }
                            else{
                                $value['book_code'] = $bookCode;
                                $value['create_date'] = date('c');
                                $this->model->query('booking')->detailInsert($value);
                            }
                        }

                        /* DEL DETAIL */
                        foreach ($_items as $key => $value) {
                            $this->model->query('booking')->detailDelete($value);
                        }

                        $arr['message'] = 'บันทึกข้อมูลการจองเรียบร้อย !';
                        $arr['url'] = URL.'office/booking';
                    }
                    echo json_encode($arr);
                    exit;
                }
                else{
                    // $this->view->setData('promotion', $promotion);
                    $this->view->setData('seatBooked', $seatBooked );
                    $this->view->setData('settings', $settings );
                    $this->view->setData('salesList', $this->model->query('products')->salesList( $period ) );
                    $this->view->setData('company', $this->model->query("booking")->companyLists());
                }
            }
            elseif( $sections == "payment" ){
                $this->view->setPage('title', 'การชำระเงิน');
                if( empty($item) ) header("location:".URL."office/booking/basic");

            }
            elseif( $sections == "room" ){
                $this->view->setPage('title', 'ข้อมูลผู้เดินทาง');
                if( empty($item) ) header("location:".URL."office/booking/basic");

            }
            else{
                $this->error();
            }

            $render = "booking/forms/create/display";
        }
        else{
            if( $this->format=='json' ){
                $results = $this->model->query('booking')->lists();
                $this->view->setData('results', $results);
                $render = "booking/lists/json";
            }
            else{
                $this->view->setData('status', $this->model->query('booking')->status());
                $this->view->setData('sales', $this->model->query('booking')->salesLists());
                $render = "booking/lists/display";
            }
        }
        $this->view->render( $render );
    }

    /* JSON ZONE */
    public function room_detail(){
        $period = isset($_REQUEST["period"]) ? $_REQUEST["period"] : null;
        $bus = isset($_REQUEST["bus"]) ? $_REQUEST["bus"] : null;

        if( empty($period) || empty($bus) ) $this->error();

        $booking = $this->model->query("booking")->lists( array("period"=>$period, "bus"=>$bus, "room"=>true) );
        $this->view->setData("booking", $booking);
        $this->view->render( "booking/forms/room_detail" );
    }
}