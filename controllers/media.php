<?php

class Media extends Controller  {

    public function __construct() {
        parent::__construct();        
    }
    
    public function index( $options=array() ) {

        if( count($options) < 2 ) $this->error();

        $model = $options[1];
        if( in_array($model, array('blog', 'ebook')) ){

            $guid = explode('_', $options[2]);

            if( count($guid) != 2 ) $this->error();
            $fill = explode('.', $guid[1]);

            if( count($fill) != 2 ) $this->error();
            $type = $fill[1];
            // echo $type; die;

            $id = intval($fill[0]);
            $item = $this->model->query($model)->get( $id );
            if( empty($item) ) $this->error();

            // ค้นหาไฟล์ ต้นฉบับ
            $aid =  Hash::create('crc32b', $model, 'album');
            $mid =  Hash::create('md5', $item['id'], 'media');
            $guid =  Hash::create('crc32b', $guid[0], 'guid');
            $filename = "{$aid}_{$mid}_{$guid}.{$type}";
            // $filename = "0d305741_1c57a242898db02cefad12a5f31099c9_66f0ec09.jpg";

            $title = $options[2];            
        }
        else{

            $guid = explode('-', $model);
            if( empty($guid[1]) ) $this->error();
            $fill = explode('.', $guid[1]);

            if( count($fill) != 2 ) $this->error();
            $type = $fill[1];
            // print_r($fill); die;

            $media = $this->model->get($fill[0]);
            if( empty($media) ) $this->error();

            // ค้นหาไฟล์ ต้นฉบับ
            $aid =  Hash::create('crc32b', $guid[0], 'album');
            $mid =  Hash::create('md5', $media['id'], 'media');

            $filename = "{$aid}_{$mid}.{$type}";

            $title = $model;
        }

        $source = WWW_UPLOADS.$filename;
        $path = UPLOADS.$filename;

        if( !file_exists($source) ) $this->error();

        list($original_width, $original_height) = getimagesize($source);

        $set_width = isset($_REQUEST['w']) ? $_REQUEST['w']: null;
        $set_height = isset($_REQUEST['h']) ? $_REQUEST['h']: null;


        if( $set_width && $set_height ){

            if( $original_width > $original_height && $original_width > $set_width  ){
                
                $width = $set_width;
                $height = round( ( $set_width*$original_height ) / $original_width );

                if( $height < $set_height ){
                    $height = $set_height;
                    $width = round( ( $set_height*$original_width ) / $original_height );
                }

            }
            elseif($original_height > $set_height){

                $height = $set_height;
                $width = round( ( $set_height*$original_width ) / $original_height );

                if( $width < $set_width ){
                    $width = $set_width;
                    $height = round( ( $set_width*$original_height ) / $original_width );
                }

            }
            else{
                $width = $set_width;
                $height = $set_height;
            }

            $dst = array(0,0);
            $dst[0] = 0;
            if( $width > $set_width ){
                $dst[0] = ($width - $set_width)/2;
            }

            $dst[1] = 0;
            if( $height > $set_height ){
                $dst[1] = ($height - $set_height)/2;
            }

            // echo 1; die;
        }
        elseif( $set_width && !$set_height ){
            $width = $set_width;
            $height = ($original_height*$set_width)/$original_width;

            $set_height = $height;

            $dst = array(0,0);
        }
        elseif( !$set_width && $set_height ){

            $height = $set_height;
            $width = ($original_width*$set_height)/$original_height;

            $set_width = $width;
            $dst = array(0,0);
        }
        else{
            $width = $original_width;
            $height = $original_height;

            $set_width = $original_width;
            $set_height = $original_height;
            $dst = array(0,0);
        }

        // echo $width.'__'.$height; die;
        // echo $set_width.'__'.$set_height; die;
        // echo $original_width.'__'.$original_height; die;

        // echo $path; die;
        
        $tmp = imagecreatefromjpeg($path);
        $image = imagecreatetruecolor($set_width, $set_height);

        // $background_color = imagecolorclosest($image, 180, 180, 180);
        // $background_color = imagecolorallocate($image, 233, 14, 91);
        /*imagesize( $image, 0, 0, $background_color );
        imagealphablending( $image, false );
        imagesavealpha( $image, true );*/


        // Demo
        /*$image = imagecreatetruecolor(120, 20);
        $text_color = imagecolorallocate($image, 233, 14, 91);
        imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);*/

        imagecopyresampled($image, $tmp, 0, 0, $dst[0], $dst[1], $width, $height, $original_width, $original_height);

        


        // Set the content type header - in this case image/jpeg
        header("Content-Type: image/jpeg");
        header('Content-Disposition: inline; filename="'.$title.'"');

        // Output the image
        imagejpeg($image);
        


        // Free up memory
        imagedestroy($image);

    }

}