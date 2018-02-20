<?php

class Members_model extends Model{

    public function __construct() {
        parent::__construct();
    }
    private $_objType = "members";
    private $_table = "members";
    private $_field = "*";
    private $_cutNamefield = "mem_";

    public function lists($options=array()){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
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
    public function insert(&$data){
    	$data["{$this->_cutNamefield}created"] = date("c");
    	$data["{$this->_cutNamefield}updated"] = date("c");

    	$this->db->insert($this->_objType, $data);
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
    	$data["{$this->_cutNamefield}updated"] = date("c");
    	$this->db->update($this->_objType, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }
    public function is_username($text){
    	return $this->db->count($this->_objType, "{$this->_cutNamefield}username=:text", array(":text"=>$text));
    }
    public function is_email($text){
        return $this->db->count($this->_objType, "{$this->_cutNamefield}email=:text", array(":text"=>$text));
    }
}