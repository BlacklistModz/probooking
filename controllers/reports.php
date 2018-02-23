<?php 

class Reports extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function booking_daily(){
        $date = isset($_REQUEST["date"]) ? $_REQUEST["date"] : date("Y-m-d");
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : null;
        $series = isset($_REQUEST["series"]) ? $_REQUEST["series"] : null;
        $sale = isset($_REQUEST["sale"]) ? $_REQUEST["sale"] : null;
        $company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : null;
        $agency = isset($_REQUEST["agency"]) ? $_REQUEST["agency"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;

        $start = date("Y-m-d 00:00:00", strtotime($date));
        $end = date("Y-m-d 23:59:59", strtotime($date));

        $options = array(
            // "date"=>$date,
            "start"=>$start,
            "end"=>$end,
            "country"=>$country,
            "series"=>$series,
            "sale"=>$sale,
            "company"=>$company,
            "agency"=>$agency,
            "status"=>$status
        );

        $book = $this->model->listsBooking( $options );
        $this->view->setData('book', $book);
        $this->view->setPage('path', 'Themes/manage/pages/reports/sections/booking/json');
        $this->view->render("daily-main");
    }
    public function booking_monthy(){
        // $date = isset($_REQUEST["date"]) ? $_REQUEST["date"] : date("Y-m-d");

        //$date = isset()
        $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date("m");
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : null;
        $series = isset($_REQUEST["series"]) ? $_REQUEST["series"] : null;
        $sale = isset($_REQUEST["sale"]) ? $_REQUEST["sale"] : null;
        $company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : null;
        $agency = isset($_REQUEST["agency"]) ? $_REQUEST["agency"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;

        $start = date("Y-m-d 00:00:00", strtotime("{$year}-{$month}-01"));
        $end = date("Y-m-t 23:59:59", strtotime("{$year}-{$month}-01"));

        $options = array(
            "start"=>$start,
            "end"=>$end,
            "country"=>$country,
            "series"=>$series,
            "sale"=>$sale,
            "company"=>$company,
            "agency"=>$agency,
            "status"=>$status
        );

        $book = $this->model->listsBooking( $options );
        $this->view->setData('book', $book);
        $this->view->setPage('path', 'Themes/manage/pages/reports/sections/booking/json');
        $this->view->render("daily-main");
    }

    public function recevied_daily(){
        $date = isset($_REQUEST["date"]) ? $_REQUEST["date"] : date("Y-m-d");
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : null;
        $series = isset($_REQUEST["series"]) ? $_REQUEST["series"] : null;
        $bankbook = isset($_REQUEST["bankbook"]) ? $_REQUEST["bankbook"] : null;

        $start = date("Y-m-d 00:00:00", strtotime($date));
        $end = date("Y-m-d 23:59:59", strtotime($date));

        $options = array(
            "start"=>$start,
            "end"=>$end,
            "country"=>$country,
            "series"=>$series,
            "bankbook"=>$bankbook
        );

        $results = $this->model->listsReceivedDaily( $options );
        $this->view->setData('results', $results);
        $this->view->setData('bank', $bankbook);
        $this->view->setData('bankbook', $this->model->query('bankbook')->lists());
        $this->view->setPage('path', 'Themes/manage/pages/reports/sections/recevied/json');
        $this->view->render("daily-main");
    }

    public function period_monthy(){
        $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date("m");
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : null;
        $series = isset($_REQUEST["series"]) ? $_REQUEST["series"] : null;
        $sale = isset($_REQUEST["sale"]) ? $_REQUEST["sale"] : null;
        $company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : null;
        $agency = isset($_REQUEST["agency"]) ? $_REQUEST["agency"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;

        $start = date("Y-m-d 00:00:00", strtotime("{$year}-{$month}-01"));
        $end = date("Y-m-t 23:59:59", strtotime("{$year}-{$month}-01"));
        
        $options = array(
            "start"=>$start,
            "end"=>$end,
            "country"=>$country,
            "series"=>$series,
            "sale"=>$sale,
            "company"=>$company,
            "agency"=>$agency,
            "status"=>$status
        );
        $results = $this->model->listsPeriodMonthy( $options );
        $this->view->setData('results', $results);
        $this->view->setPage('path', 'Themes/manage/pages/reports/sections/period/json');
        $this->view->render("monthy-main");
    }
    public function monitor(){
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : null;
        $series = isset($_REQUEST["series"]) ? $_REQUEST["series"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;

        $start = date("Y-m-d", strtotime("{$year}-01-01"));
        $end = date("Y-m-t", strtotime("{$year}-12-01"));

        $options = array(
            'start' => $start,
            'end' => $end,
            'country' => $country,
            'series' => $series,
            'status' => $status
        );

        $results = $this->model->listsMonitor( $options );
        $this->view->setData('results', $results);
        $this->view->setPage('path', 'Themes/manage/pages/reports/sections/monitor/json');
        $this->view->render('json');
    }

    /* GET DATA JSON */
    public function getProducts($country_id=null){
    	if( $this->format!='json' ) $this->error();
    	echo json_encode($this->model->listsSeries( $country_id ));
    }
    public function getAgency($com_id=null){
        $com_id = isset($_REQUEST["com_id"]) ? $_REQUEST["com_id"] : $com_id;
        if( empty($com_id) || $this->format!='json' ) $this->error();
        echo json_encode($this->model->listsAgency( $com_id ));
    }
}