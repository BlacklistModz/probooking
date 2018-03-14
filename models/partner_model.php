<?php

class Partner_Model extends Model{

    public function __construct() {
        parent::__construct();
    }
    private $_objType = "agency";
    private $_table = "agency";
    private $_field = "*";
    private $_cutNamefield = "agen_";

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
        if( !empty($options["status"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "status=:status";
            $where_arr[":status"] = $options["status"];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        if( !empty($options["unlimit"]) ) $limit = '';
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

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
    public function get($id, $options=array()){
        
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options );
        } return array();
    }
    public function convert($data, $options=array()){

        $data = $this->_cutFirstFieldName($this->_cutNamefield, $data);
        $data['permit']['del'] = true;

        return $data;
    }

    #COMPANY AGENCY
    private $c_objType = "agency_company";
    private $c_table = "agency_company";
    private $c_field = "*";
    private $c_cutNamefield = "agen_com_";
    public function listsCompany($options=array()){
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
        if( !empty($options["status"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "status=:status";
            $where_arr[":status"] = $options["status"];
        }

        if( isset($_REQUEST["geo"]) ){
            $options["geo"] = $_REQUEST["geo"];
        }
        if( !empty($options["geo"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "agen_com_geo=:geo";
            $where_arr[":geo"] = $options["geo"];
        }

        $arr['total'] = $this->db->count($this->c_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        if( !empty($options["unlimit"]) ) $limit = '';
        $arr['lists'] = $this->buildFragCompany( $this->db->select("SELECT {$this->c_field} FROM {$this->c_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function buildFragCompany($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convertCompany( $value, $options );
        }

        return $data;
    }
    public function getCompany($id, $options=array()){
        
        $sth = $this->db->prepare("SELECT {$this->c_field} FROM {$this->c_table} WHERE {$this->c_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convertCompany( $sth->fetch( PDO::FETCH_ASSOC ), $options );
        } return array();
    }
    public function convertCompany($data, $options=array()){

        $data = $this->_cutFirstFieldName($this->c_cutNamefield, $data);
        $data['permit']['del'] = true;

        return $data;
    }
}
