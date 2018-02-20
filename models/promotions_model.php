<?php

class Promotions_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objName = "promotions";
    private $_table = "promotions";
    private $_field = "*";
    private $_cutNamefield = "pro_";

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

        if( isset($_REQUEST["status"]) ){
            $options["status"] = $_REQUEST["status"];
        }
        if( !empty($options["status"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}status=:status";
            $where_arr[":status"] = $options["status"];
        }

        if( !empty($options["q"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}name LIKE :q";
            $where_str[":q"] = "%{$options["q"]}%";
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
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
    public function buildFrag($results, $options=array()){
    	$data = array();
    	foreach ($results as $key => $value) {
    		if( empty($value) ) continue;
    		$data[] = $this->convert( $value, $options );
    	}
    	return $data;
    }
    public function convert($data, $options=array()){
    	$data = $this->_cutFirstFieldName($this->_cutNamefield, $data);

        $data['status_arr'] = $this->getStatus($data["status"]);

    	$data["permit"]["del"] = true;
    	return $data;
    }
    public function insert(&$data){
    	$data["{$this->_cutNamefield}created"] = date("c");
    	$data["{$this->_cutNamefield}updated"] = date("c");
    	$this->db->insert($this->_objName, $data);
    }
    public function update($id, $data){
    	$data["{$this->_cutNamefield}updated"] = date("c");
    	$this->db->update($this->_objName, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objName, "{$this->_cutNamefield}id={$id}");
    }
    public function is_name($name){
        return $this->db->count($this->_objName, "{$this->_cutNamefield}name=:name", array(":name"=>$name));
    }

    /*STATUS*/
    public function status(){
    	$a[] = array('id'=>'enabled', 'name'=>'เปิดใช้งาน');
    	$a[] = array('id'=>'disabled', 'name'=>'ปิดใช้งาน');
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
}