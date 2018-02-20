<?php

class Media_Model extends Model
{
	public function __construct()
    {
        parent::__construct();
    }

    private function insert($data)
    {
        if( empty($data['media_created']) ) $data['media_created'] = date('c');
       
        $this->db->insert('media', $data);
        return $this->db->lastInsertId();
    }
    public function update($id, $data)
    {
        $this->db->update('media', $data, "`media_id`={$id}");
    }
    public function delete($id)
    {
        if( is_array($id) ){
            $item = $id;
            $id = $item['id'];
        }
        else{
            $item = $this->get($id);
        }

        // print_r($item); die;
        $aid =  Hash::create('crc32b', $item['obj_type'], 'album');
        $mid =  Hash::create('md5', $id, 'media');

        $type = $item['type'];
        $filename = "{$aid}_{$mid}.{$type}";

        $source = WWW_UPLOADS.$filename;
        $path = UPLOADS.$filename;
        if( file_exists($source) ){
            unlink($source);
        }

        $this->del( $id );
    }
    public function del($id)
    {
        $this->db->delete('media', "`media_id`={$id}");
    }

    public function set($userfile, $options=array()) {

        $options = array_merge(array(
            'obj' => 'my', // obj_type
            'obj_id' => 0, // obj_id
            'type' => 'jpg',
            'minimize' => array(950, 950),
            'caption' => '',
            'primalink' => ''

            // 'width'
        ), $options);
    	
        $source = $userfile['tmp_name'];
        $filename = $userfile['name'];


        $media = array(
            'media_obj_type' => $options['obj'],
            'media_obj_id' => $options['obj_id'],
            'media_name' => $filename,
            'media_caption' => $options['caption'],
            'media_type' => $options['type'],
            'media_primalink' => $options['primalink'],
        );

        if( !empty($options['id']) ){
            $media_id = $options['id'];
            $this->update( $media_id, $media );
        }
        else{
            // media
            $media_id = $this->insert( $media );
        }

        $aid =  Hash::create('crc32b', $options['obj'], 'album');
        $mid =  Hash::create('md5', $media_id, 'media');

        $u = new Upload();
        $u->current = $userfile;

        
        $extension = strtolower(strrchr($filename, '.'));
        $type = strtolower(substr(strrchr($filename,"."),1));

        $name = "{$aid}_{$mid}";
        $dest = WWW_UPLOADS.$name.$extension;

        if( $u->copies($source, $dest) ){
            if($type!='jpg'){
                $dest_new = WWW_UPLOADS.$name.".jpg";
                $u->convertImage( $dest, $dest_new );

                if( file_exists($dest_new) ){
                    $u->minimize( $dest_new, $options['minimize'] );                        
                }
                else{
                    $arr['error'] = 'ไม่สามารถใช้รูปนี้ได้';
                    $this->del( $media_id );
                }

                unlink($dest);
                $dest = $dest_new;

            }
            else{
                // $u->minimize( $dest, $options['minimize'] );
            }

            if( !empty($_POST['cropimage']) ){

                $u->cropimage($_POST['cropimage'], $dest);
                // print_r($_POST['cropimage']); die;
            }


            $arr = array_merge(array('id'=>$media_id), $options);
        }
        else{
            $arr['error'] = 'ไม่สามารถใช้รูปนี้ได้';
        }


        return $arr;
    }
    public function change($id, $userfile, $options=array())
    {
        $item = $this->get($id);
        // print_r($item); die;

        $options = array_merge(array(
            'id' => $id,
            'obj_id' => $item['obj_id'],
            'obj' => $item['obj_type']
        ), $options);

        $this->set($userfile, $options);
    }


    private $_objType = "media";
    private $_table = "media";
    private $_select = "*";
    private $_firstFieldName = "media_";


    public function get($id)
    {
        $sth = $this->db->prepare("SELECT {$this->_select} FROM {$this->_table} WHERE {$this->_firstFieldName}id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) )
            : array();
    }
    public function lists($options=array())
    {
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);
        
        $table = $this->_table;
        $condition = '';
        $params = array();

        if( !empty($options['obj']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`media_obj_type`=:obj";

            $params[':obj'] = $options['obj'];
        }

        if( !empty($options['obj_id']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`media_obj_id`=:obj_id";

            $params[':obj_id'] = $options['obj_id'];
        }

        $arr['total'] = $this->db->count($table, $condition, $params);

        $condition = !empty($condition) ? "WHERE {$condition}":'';

        $orderby = $this->orderby( $this->_firstFieldName.$options['sort'], $options['dir'] );
        $limit = !empty($options['unlimit']) ? '' : $this->limited( $options['limit'], $options['pager'] );

        // echo "SELECT {$this->_select} FROM {$this->_table} {$condition} {$orderby} {$limit}"; die;
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_select} FROM {$table} {$condition} {$orderby} {$limit}", $params ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }

    /* convert */
    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function convert($data)
    {
        $data = $this->_cutFirstFieldName($this->_firstFieldName, $data);

        if( empty($data['type']) ) $data['type'] = 'jpg';

        $data['url'] = URL."media/{$data['obj_type']}-{$data['id']}.{$data['type']}";
        $data['permit']['del'] = true;
        return $data;
    }
}