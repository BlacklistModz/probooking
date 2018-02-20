<?php

class Agency_company_model extends Model  {

	public function __construct()
	{
		parent::__construct();
	}

	private $_objType = "agency_company";
    private $_table = "agency_company ac 
                       LEFT JOIN user u ON ac.agen_com_user_id=u.user_id";
    private $_field = "ac.*, u.user_fname, u.user_lname, u.user_nickname";
    private $_cutNamefield = "agen_";

	public function lists($options=array()){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'com_id',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'ASC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($_REQUEST["status"]) ){
            $options["status"] = $_REQUEST["status"];
        }
        if( isset($_REQUEST["sales"]) ){
            $options["sales"] = $_REQUEST["sales"];
        }
        if( isset($_REQUEST["province"]) ){
            $options["province"] = $_REQUEST["province"];
        }

        if( !empty($options['q']) ){

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "(agen_com_name LIKE :q OR agen_com_tel=:q)";
            $where_arr[':q'] = "%{$options['q']}%";;
            $where_arr[':qfull'] = $options['q'];
        }

        if( isset($options["status"]) && $options["status"] != null ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "ac.status=:status";
            $where_arr[":status"] = $options["status"];
        }
        if( !empty($options["sales"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "agen_com_user_id=:sales";
            $where_arr[":sales"] = $options["sales"];
        }
        if( !empty($options["province"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "agen_com_province=:province";
            $where_arr[":province"] = $options["province"];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        if( !empty($options["unlimit"]) ) $limit = "";
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
        
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}com_id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options );
        } return array();
    }
    public function convert($data, $options=array()){

        $data = $this->_cutFirstFieldName($this->_cutNamefield, $data);
        $data['sale_name'] = $data['user_fname'].' '.$data['user_lname'];
        if( !empty($data['user_nickname']) ){
            $data['sale_name'] .= '('.$data['user_nickname'].')';
        }
        $data['status_arr'] = $this->getStatus($data['status']);
        $data['permit']['del'] = true;

        return $data;
    }

	public function insert($data)
	{
		$data['create_date'] = date('c');
		// $data['status'] = 1;
		// $data['agen_show'] = 1;

		$this->db->insert($this->_objType, $data);
		return $this->db->lastInsertId();
	}
	public function update($id, $data){
		$data['update_date'] = date("c");
		$this->db->update($this->_objType, $data, "{$this->_cutNamefield}com_id={$id}");
	}
	public function delete($id){
		$this->db->delete($this->_objType, "{$this->_cutNamefield}com_id={$id}");
	}

    public function status(){
        $a[] = array('id'=>0, 'name'=>'รอการตรวจสอบ');
        $a[] = array('id'=>1, 'name'=>'เปิดใช้งาน');
        $a[] = array('id'=>2, 'name'=>'ระงับการใช้งาน');

        return $a;
    }
    public function getStatus($id=null){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $value["id"] == $id ){
                $data = $value;
                break;
            }
        }
        return $data;
    }

    public function is_name($text){
        return $this->db->count($this->_objType, "{$this->_cutNamefield}com_name=:text", array(':text'=>$text));
    }
    public function saleLists(){
        return $this->db->select("SELECT user_id AS id, CONCAT(user_fname, ' ', user_lname) AS name FROM user WHERE status=1 ORDER BY user_fname ASC");
    }
}