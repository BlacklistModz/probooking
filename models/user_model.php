<?php

class User_Model extends Model{

    public function __construct() {
        parent::__construct();
    }
    private $_objType = "`user`";
    private $_table = "`user` u LEFT JOIN `group` g ON u.group_id=g.group_id";
    private $_field = "u.*, g.group_name";
    private $_cutNamefield = "user_";

    public function insert(&$data){
        $data["create_date"] = date("c");
        $data["update_date"] = date("c");
        $this->db->insert($this->_objType, $data);
    }
    public function update($id, $data){
        $data["update_date"] = date("c");
        $this->db->update($this->_objType, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
        $this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

    public function lists( $options=array() ) {
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

        if( isset($_REQUEST["group"]) ){
            $options["group"] = $_REQUEST["group"];
        }
        if( isset($_REQUEST["status"]) ){
            $options["status"] = $_REQUEST["status"];
        }
        if( isset($_REQUEST["team"]) ){
            $options["team"] = $_REQUEST["team"];
        }
        if( !empty($options["group"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "u.group_id=:group";
            $where_arr[":group"] = $options["group"];
        }
        if( !empty($options["status"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "status=:status";
            $where_arr[":status"] = $options["status"];
        }
        if( !empty($options["team"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "u.user_team_id=:team";
            $where_arr[":team"] = $options["team"];
        }
        if( !empty($options["q"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}name LIKE :q
                           OR {$this->_cutNamefield}fname LIKE :q
                           OR {$this->_cutNamefield}lname LIKE :q
                           OR {$this->_cutNamefield}nickname LIKE :q
                           OR {$this->_cutNamefield}email LIKE :q
                           OR {$this->_cutNamefield}tel LIKE :q";
            $where_arr[":q"] = "%{$options["q"]}%";
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        if( !empty($options["unlimit"]) ) $limit = "";
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function get($id){
        
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) );
        } return array();
    }
    public function convert($data){

        $data = $this->_cutFirstFieldName($this->_cutNamefield, $data);
        $data['fullname'] = $data['fname'].' '.$data['lname'];

        $data['status_arr'] = $this->getStatus($data['status']);

        $data['initials'] = $this->fn->q('text')->initials( $data['name'] );

        $data['permit']['del'] = false;
        if( $data["group_id"] == 1 || $data["group_id"] == 4 ){
            $data['permit']['del'] = true;
        }

        // print_r($data);die;

        return $data;
    }
    public function login($user, $pass){

        $sth = $this->db->prepare("SELECT user_id as id FROM {$this->_objType} WHERE ((user_name=:login AND user_password=:pass) OR (user_email=:login AND user_password=:pass)) AND status=1");

        $sth->execute( array(
            ':login' => $user,
            ':pass' => substr(md5(trim($pass)),0,20),
        ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        return $sth->rowCount()==1 ? $fdata['id']: false;
    }
    public function is_user($user){
        return $this->db->count($this->_objType, "{$this->_cutNamefield}name=:user", array(":user"=>$user));
    }
    public function is_email($email){
        return $this->db->count($this->_objType, "{$this->_cutNamefield}email=:email", array(":email"=>$email));
    }
    public function status(){
        $a[] = array('id'=>1, 'name'=>'ใช้งาน', 'color'=>'#27c24c');
        $a[] = array('id'=>2, 'name'=>'ระงับการใช้งาน', 'color'=>'#ff902b');

        return $a;
    }
    public function getStatus($id=null){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $id == $value['id'] ){
                $data = $value;
                break;
            }
        }
        return $data;
    }

    /* GROUP */
    private $_objGroup = "`group`";
    private $_tableGroup = "`group`";
    private $_fieldGroup = "*";
    private $_cutNamefieldGroup = "group_";

    public function group($id=null){
        if( !empty($id) ){
            $sth = $this->db->prepare("SELECT {$this->_fieldGroup} FROM {$this->_tableGroup} WHERE {$this->_cutNamefieldGroup}id=:id LIMIT 1");
            $sth->execute( array(
                ':id' => $id
            ) );

            $fdata = $this->_cutFirstFieldName($this->_cutNamefieldGroup, $sth->fetch( PDO::FETCH_ASSOC ));
            $total_user = $this->db->count($this->_objType, "group_id=:id", array(":id"=>$fdata['id']));
            $fdata['permit']['del'] = empty($total_user) ? true : false;

            if( $sth->rowCount()==1 ){
                return $fdata;
            } return array();
        }
        else{
            $data = array();
            $results = $this->db->select("SELECT {$this->_fieldGroup} FROM {$this->_tableGroup} ORDER BY {$this->_cutNamefieldGroup}id ASC");
            foreach ($results as $key => $value) {
                $data[$key] = $this->_cutFirstFieldName($this->_cutNamefieldGroup, $value);
            }
            return $data;
        }
    }
    public function insertGroup(&$data){
        $data["create_date"] = date("c");
        $data["update_date"] = date("c");
        $this->db->insert($this->_objGroup , $data);
    }
    public function updateGroup($id, $data){
        $data["update_date"] = date("c");
        $this->db->update($this->_objGroup, $data, "{$this->_cutNamefieldGroup}id={$id}");
    }
    public function deleteGroup($id){
        $this->db->delete($this->_objGroup, "{$this->_cutNamefieldGroup}id={$id}");
    }
    public function is_groupname($text){
        return $this->db->count($this->_objGroup, "{$this->_cutNamefieldGroup}name:text", array(":text"=>$text));
    }
}