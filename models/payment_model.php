<?php

class Payment_Model extends Model{

    public function __construct() {
        parent::__construct();
    }
    private $_objType = "payment";
    private $_table = "payment";
    private $_field = "*";
    private $_cutNamefield = "pay_";

    public function insert(&$data){
    	$data["create_date"] = date("c");
    	$this->db->insert($this->_objType, $data);
    	$data["id"] = $this->db->lastInsertId();
    }
    public function update($id, $data){
    	$data["update_date"] = date("c");
    	$this->db->update($this->_objType, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

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

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
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

    public function status(){
        $a[] = array('id'=>0, 'name'=>'รอการตรวจสอบ');
        $a[] = array('id'=>1, 'name'=>'ผ่านการตรวจสอบ');
        $a[] = array('id'=>9, 'name'=>'รอการตรวจสอบ');

        return $a;
    }
    public function getStatus($id){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $id == $value["id"] ){
                $data = $value;
                break;
            }
        }
        return $data;
    }
    public function getUser($text){
        $sth = $this->db->prepare("SELECT user_nickname, user_fname, user_lname FROM user WHERE user_name=:username LIMIT 1");
        //return($sth);die;
        $sth->execute( array(
            ':username' => $text
        ) );
        
        $fdata = $sth->fetch( PDO::FETCH_ASSOC ) ;

        if( $sth->rowCount()==1 ){
            return "{$fdata["user_fname"]} {$fdata["user_lname"]}";
        } return "";
    }
    public function getAgency($text){
        $sth = $this->db->prepare("SELECT agen_fname,agen_nickname, agen_lname FROM agency WHERE agen_user_name=:username LIMIT 1");
        $sth->execute( array(
            ':username' => $text
        ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC ) ;

        if( $sth->rowCount()==1 ){
            return "{$fdata["agen_fname"]} {$fdata["agen_lname"]}";
        } return "";
    }
    public function getAgencyCompany($text){
        $sth = $this->db->prepare("SELECT agen_com_name FROM agency_company WHERE agen_com_id = :id LIMIT 1");
        $sth->execute(
            array(
                ':id'=>$text
            )
        );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        if( $sth->rowCount()==1 ){
            return $fdata["agen_com_name"];
        } return "";
    }
}
