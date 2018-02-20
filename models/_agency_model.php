<?php

class Agency_Model extends Model {


	public function __construct()
	{
		parent::__construct();
	}

	private $_objType = "agency";
    private $_table = "agency LEFT JOIN agency_company ON agency_company.agen_com_id=agency.agency_company_id";
    private $_field = "
          agency.agen_id
        , agency.agen_user_name
        , agency.status
        , agency.agen_fname
        , agency.agen_lname
        , agency.agen_nickname
        , agency.agen_email
        , agency.agen_tel
        , agency.agen_line_id

        , agency_company.agen_com_id as company_id
        , agency_company.agen_com_name as company_name
    ";
    private $_cutNamefield = "agen_";

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


        $data['fullname'] = $data['fname'];
        $data['lname'] = str_replace('-', '', $data['lname']);
        if( !empty($data['lname']) ){
            $data['fullname'] .= " {$data['lname']}";
        }

        return $data;
    }
    public function insert(&$data){
    	$data["create_date"] = date("c");
    	$data["update_date"] = date("c");

    	$this->db->insert($this->_objType, $data);
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
    	$data["create_date"] = date("c");
    	$this->db->update($this->_objType, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

	/* public function insert($data)
	{
		if( empty($data['create_date']) ) $data['create_date'] = date('c');
		$data['status'] = 1;
		$data['agen_show'] = 1;

		$this->db->insert('agency', $data);
		return $this->db->lastInsertId();
	} */

	public function login($user, $pass){

		$sth = $this->db->prepare("SELECT agen_id as id FROM agency WHERE (agen_user_name=:login AND agen_password=:pass AND status=:display)");

		$sth->execute( array(
			':login' => $user,
			':pass' => substr(md5(trim($pass)),0,20),
			':display' => '1'
		) );

		$fdata = $sth->fetch( PDO::FETCH_ASSOC );
		return $sth->rowCount()==1 ? $fdata['id']: false;
	}

	public function getUser($id)
	{
		$sth = $this->db->prepare("SELECT * FROM agency WHERE (agen_id=:id)");
		$sth->execute( array(
			':id' => $id
		) );

		$fdata = $sth->fetch( PDO::FETCH_ASSOC );
		return $sth->rowCount()==1 ? $fdata: false;

	}
	public function is_username($text){
		return $this->db->count($this->_objType, 'agen_user_name=:text', array(':text'=>$text));
	}
	public function is_email($text){
		return $this->db->count($this->_objType, 'agen_emil=:text', array(':text'=>$text));
	}
}