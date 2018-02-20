<?php

class Products_Model extends Model{

    public function __construct() {
        parent::__construct();
    }
    private $_objType = "series";
    private $_field = "s.*, c.country_name, a.air_name";
    private $_table = "series s 
        LEFT JOIN country c ON s.country_id=c.country_id
        LEFT JOIN airline a ON s.air_id=a.air_id";

    private $_cutNamefield = "ser_";

    public function get($id, $options=array()){
        
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options );
        } return array();
    }

    public function lists($options=array()){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:12,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'ser_code',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'ASC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            // 'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $condition = "";
        $params = array();

        if( isset($_REQUEST["country"]) ){
        	$options["country"] = $_REQUEST["country"];
        }
        if( !empty($options["country"]) ){
        	$condition .= !empty($condition) ? " AND " : "";
        	$condition .= "s.country_id=:country";
        	$params[":country"] = $options["country"];
        }

        if( isset($_REQUEST["status"]) ){
        	$options["status"] = $_REQUEST["status"];
        }
        if( !empty($options["status"]) ){
            $condition .= !empty($condition) ? " AND " : "";
            $condition .= "s.status=:status";
            $params[":status"] = $options["status"];
        }

        if( !empty($options["show"]) ){
        	$condition .= !empty($condition) ? " AND " : "";
        	$condition .= "s.ser_show=:show";
        	$params[":show"] = $options["show"];
        }

        if( !empty($options["promote"]) ){
            $condition .= !empty($condition) ? " AND " : "";
            $condition .= "s.ser_is_promote=:promote";
            $params[":promote"] = 1;
        }
        if( !empty($options["recommend"]) ){
            $condition .= !empty($condition) ? " AND " : "";
            $condition .= "s.ser_is_recommend=:recommend";
            $params[":recommend"] = 1;
        }
        /*$condition .= !empty($condition) ? " AND " : "";
        $condition .= "s.ser_url_img_1!=:img";
        $params[":img"] = '';*/

        /*$condition .= !empty($condition) ? " AND " : "";
        $condition .= "s.ser_deposit>:deposit";
        $params[":deposit"] = 0;*/


        $arr['total'] = $this->db->count($this->_table, $condition, $params);

        $_field = "
              {$this->_field}
            , period.per_date_start AS periodStartDate
            , period.per_date_end AS periodEndDate
            , COUNT(*) AS periodCount
        ";
        $_table = "({$this->_table}) LEFT OUTER JOIN period ON s.ser_id=period.ser_id";
        $groupby = ' GROUP BY s.ser_id HAVING periodCount>0';

        $condition .= !empty($condition) ? " AND " : "";
        $condition .= "period.per_date_start>=:periodStartDate";
        $params[":periodStartDate"] = date('Y-m-d');


        if( !empty($options['unlimited']) ){
            $options['limit'] = $arr['total'];
        }

        $limit = $this->limited( $options['limit'], $options['pager'] );

        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $condition = !empty($condition) ? "WHERE {$condition}":'';

        $sql = "SELECT {$_field} FROM {$_table} {$condition} {$groupby} {$orderby} {$limit}";
        if( isset($_GET['chong_debug']) ){ if( $_GET['chong_debug']=='sql' ){ echo $sql; die; } }
        $arr['lists'] = $this->buildFrag( $this->db->select($sql, $params ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
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

    	$data = $this->_cutFirstFieldName($this->_cutNamefield, $data);

    	$data['primarylink'] = str_replace(' ', '-', $data['name']);
    	$data['url'] = URL.'tour/'.$data['id'];
        // $data['url'] = URL.'products/'.$data['id'];
    	$data['airline'] = '';
    	$data['category_str'] = $data['country_name'];

        $firstPeriod = $this->getFirstPeriod($data['id']);
        $data['first_start_date'] = $firstPeriod["per_date_start"];
        $data['first_end_date'] = $firstPeriod["per_date_end"];

        $data['days_str'] = '';
    	$data['price_str'] = number_format($firstPeriod['per_price_1']).'.-';

    	$url_image = '';
    	for($i=1;$i<=5;$i++){
    		if( !empty($data['url_img_'.$i]) ){
    			$url_image = $data['url_img_'.$i];
    			break;
    		}
    	}

    	$image = str_replace('../', '', $url_image);
    	$data['image_cover_url'] = !empty($image) ? UPLOADS_URL.$image : "";

        if( !empty($options["period"]) ){
            $data['period'] = $this->periodList($data['id']);
        }

        $data['permit']['del'] = true;



        if( !empty($data['url_word']) ){
            $file = substr(strrchr($data['url_word'],"/"),1);

            if( file_exists(PATH_TRAVEL.$file) ){
                $data['url_word'] = 'http://admin.probookingcenter.com/admin/upload/travel/'.$file;
            }
            else{
                $data['url_word'] = '';
            }
        }
        //print_r($data['url_word']);die;


        if( !empty($data['url_pdf']) ){
            $file = substr(strrchr($data['url_pdf'],"/"),1);

            if( file_exists(PATH_TRAVEL.$file) ){
                $data['url_pdf'] = 'http://admin.probookingcenter.com/admin/upload/travel/'.$file;
            }
            else{
                $data['url_pdf'] = '';
            }
        }

        return $data;
    }


    #recommend
    public function recommendList( $limit=9 ){

        $results = $this->lists( array(
            // 'show' => 1,
            'limit' => $limit,
            'status' => 1,
            'recommend' => true
        ) );

        return $results['lists'];

    	// return $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} WHERE s.status=:status AND ser_url_img_1!='' ORDER BY RAND() LIMIT {$limit}", array(':status' => 1) ) );
    }

    #Slide
    public function slideList( $limit=9 ){
        $results = $this->lists( array(
            'limit' => $limit,
            'status' => 1,
            'promote' => true
        ) );

        return $results['lists'];
    }


    #Popular
    public function popularList( $limit = 4){

        $results = $this->lists( array(
            'show' => 3,
            'limit' => $limit,
            'status' => 1
        ) );

        return $results['lists'];

    	// return $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} WHERE s.status=:status AND ser_url_img_1!='' AND ser_deposit > 0 ORDER BY RAND() LIMIT 4", array(':status' => 1) ) );
    }



    /* -- category -- */
    public function categoryList() 
    {
        return $this->db->select("SELECT country_id as id, country_name as name FROM country");
    }
    public function category($id)
    {
        $sth = $this->db->prepare("SELECT country_id as id, country_name as name FROM country WHERE country_id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->categoryConvert( $sth->fetch( PDO::FETCH_ASSOC ) );
        } return array();
    }
    public function categoryConvert($data)
    {


        return $data;
    }


    /* -- Period -- */
    private $_periodSelect = "
          p.per_id
        , p.per_date_start
        , p.per_date_end
        , p.per_price_1
        , p.per_price_2
        , p.per_price_3
        , p.per_price_4
        , p.per_price_5
        , p.single_charge
        , p.per_discount
        
        , p.per_qty_seats

        , p.per_com_agency
        , p.per_com_company_agency

        , p.per_url_word
        , p.per_url_pdf

        , p.status
    ";
    private $_periodTable = "period p";
    public function period($id)
    {
        $sth = $this->db->prepare("

            SELECT 
                  {$this->_periodSelect}
                , ser_code as code
                , ser_name as name
                , ser_route
                , series.ser_deposit
                , series.ser_id

                , airline.air_name

            FROM period p LEFT JOIN (series LEFT JOIN airline ON series.air_id=airline.air_id) ON series.ser_id=p.ser_id 
            WHERE p.per_id=:id 
            LIMIT 1

        ");
        $sth->execute( array( ':id' => $id) );
        return  $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ): array();
    }
    public function busList($id)
    {
        $busList = $this->db->select("SELECT bus_id, bus_no, bus_qty FROM bus_list WHERE per_id=:id ORDER BY bus_no", array(':id'=>$id ));

        foreach ($busList as $key => $value) {
            
            $sth = $this->db->prepare("
                SELECT COALESCE(SUM(booking_list.book_list_qty),0) as qty
                FROM booking_list LEFT JOIN booking ON booking.book_code=booking_list.book_code 
                WHERE 
                        booking.per_id=:id
                    AND booking.bus_no=:bus_no
                    AND booking_list.book_list_code IN ('1','2','3','4','5')
                    AND booking.status != 40
            ");
            $sth->execute( array( ':id'=> $id, ':bus_no'=> $value['bus_no']) );

            $fdata = $sth->fetch( PDO::FETCH_ASSOC );
            $busList[$key]['booked'] = !empty($fdata['qty']) ? $fdata['qty']: 0;
        }

        return $busList;
    }
    public function salesList($id)
    {
        return $this->db->select("SELECT user_id as id, CONCAT(`user_fname`, ' ', `user_lname`, '(', `user_tel`, ')') AS 'name' FROM user WHERE status=:status AND group_id IN (3,5,7) ORDER BY user_fname", array(':status'=>1 ));
    }

    public function periodList($id){

        $results = $this->db->select("SELECT {$this->_periodSelect} FROM {$this->_periodTable} WHERE p.ser_id=:id AND per_date_start>=:d AND status != 9 ORDER BY per_date_start ASC", array(':id'=>$id, ':d'=>date('Y-m-d')));

        $data = array();
        foreach ($results as $key => $value) {
            $data[$key] = $this->_cutFirstFieldName('per_', $value);

            // $bus_list = 
            // per_id

            // 
            // $booking = 0;
            $data[$key]['booking'] = $booking = $this->seatBooked( $value['per_id'] );
            $data[$key]['seats'] = $value['per_qty_seats'];

            $data[$key]['balance'] = $value['per_qty_seats'] - $booking['booking'];
            // $data[$key]['bb'] = $rr;

            if( !empty($data[$key]['url_pdf']) ){
                $file = substr(strrchr($data[$key]['url_pdf'],"/"),1);

                if( file_exists(PATH_TRAVEL.$file) ){
                    $data[$key]['url_pdf'] = 'http://admin.probookingcenter.com/admin/upload/travel/'.$file;
                }
                else{
                    $data[$key]['url_pdf'] = '';
                }
            }
            if( !empty($data[$key]['url_word']) ){
                $file = substr(strrchr($data[$key]['url_word'],"/"),1);

                if( file_exists(PATH_TRAVEL.$file) ){
                    $data[$key]['url_word'] = 'http://admin.probookingcenter.com/admin/upload/travel/'.$file;
                }
                else{
                    $data[$key]['url_word'] = '';
                }
            }
           // print_r($data);die;

            $data[$key]['booking'] = $booking;

        }
        // print_r($data); die;
        return $data;
    }
    public function seatBooked($id)
    {
        $sth = $this->db->prepare("
            SELECT 
                  COALESCE(SUM(booking_list.book_list_qty),0) as booking
                , SUM(IF(booking.status=35, booking_list.book_list_qty, 0)) AS payed
                , SUM(IF(booking.status=10, booking_list.book_list_qty, 0)) AS invoice
            FROM booking_list LEFT JOIN booking ON booking.book_code=booking_list.book_code 
            WHERE 
                    booking.per_id=:id
                AND booking_list.book_list_code IN ('1','2','3','4','5')
                AND booking.status != 40
        ");
        $sth->execute( array( ':id'=> $id) );

        return $sth->fetch( PDO::FETCH_ASSOC );
        // print_r($fdata); die;
        // return !empty($fdata['booking']) ? $fdata['booking']: 0;
    }
    public function getFirstPeriod($id){

        $sth = $this->db->prepare("SELECT per_price_1, per_date_start, per_date_end FROM period WHERE ser_id=:id ORDER BY per_price_1 ASC LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $sth->fetch( PDO::FETCH_ASSOC );
        } return array();
    }

    /* Hot Sale */
    public function hotsaleList(){
        $data = array();
        //,bl.`bus_qty` AS 'per_qty_seats'
        $results = $this->db->select("SELECT  
                a.`per_id`
                ,c.`ser_id`
                ,c.`ser_name`
                ,c.`ser_code`
                ,d.`country_name`
                ,a.`per_date_start`
                ,a.`per_date_end`
                ,a.`per_price_1`
                ,a.`per_price_2`
                ,a.`per_price_3`
                ,a.`per_price_4`
                ,a.`per_price_5`
                ,a.`per_qty_seats`
                ,a.`per_com_agency`
                ,a.`per_com_company_agency`
                ,a.`single_charge`
                ,a.`per_discount`
                ,(SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` e on b.`book_code` = e.`book_code` WHERE e.`per_id` =  a.`per_id` 
                AND e.`bus_no` = bl.`bus_no` 
                AND b.`book_list_code` IN ('1','2','3')
                AND e.`status` != 40 )  AS 'qty_book'
                ,bl.`bus_qty` - (SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` e on b.`book_code` = e.`book_code` 
                WHERE e.`per_id` =  a.`per_id` AND e.`bus_no` = bl.`bus_no` 
                AND b.`book_list_code` IN ('1','2','3')
                AND e.`status` != 40)  AS 'qty_receipt'
                ,a.`status`
                ,bl.`bus_no`
                ,e.`air_name`
                FROM `period` a
                LEFT OUTER JOIN `series` c
                on a.`ser_id` = c.`ser_id`
                LEFT OUTER JOIN `bus_list` bl
                on a.`per_id` = bl.`per_id`
                LEFT OUTER JOIN `country` d
                on c.`country_id` = d.`country_id`
                 LEFT OUTER JOIN `airline` e
                on c.`air_id` = e.`air_id`
                WHERE  a.`status` NOT IN ('2','3','9','10') 
                AND CURDATE() BETWEEN DATE_ADD(a.per_date_start, INTERVAL -22 DAY) AND a.per_date_start
                ORDER BY a.`per_date_start`, c.`ser_id`, bl.`bus_no`");

        foreach ($results as $key => $value) {
            $data[$key] = $this->_cutFirstFieldName("per_", $value);
            $data[$key]['booking'] = $booking = $this->seatBooked( $value['per_id'] );
            $data[$key]['seats'] = $value['per_qty_seats'];
            $data[$key]['balance'] = $value['per_qty_seats'] - $booking['booking'];
        }

        return $data;
    }
}