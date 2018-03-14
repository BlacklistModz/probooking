<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)"> 
    <div class="container div_btn">
        <?php foreach ($this->geo as $key => $value) {
            $cls = "";
            if( $this->currGeo == $value["id"] ){
                $cls .= 'class="active"';
            }
            echo '<a '.$cls.' href="'.URL.'partner/'.$value["id"].'">'.$value["name"].'</a>';
        } ?>
    </div>

    <div class="container"> 
      <?php $i=1; foreach($this->results['lists'] as $key => $item) { ?> 
      <div class="span5 mts mbs" style="width: 550px"> 
         <div class="card"> 

            <?php 
    // เพิ่ม logo 
            if ( 
               (!empty($item['logo_img'])) 
               && ($item['logo_img'] != "undefined") 
           ) { 
     // ถ้าเจอรูปให้โหลดรุปนี้ 
               $image = substr(strrchr($item['logo_img'],"/"),1); 
               $logo_path = "http://admin.probookingcenter.com/admin/upload/company_agency/".$image; 
           } 
           else { 
     //ถ้าไม่ได้กำหนดรูปให้โหลดรูปนี้ 
               $logo_path ="http://probookingcenter.com/public/images/logo/128x128.png"; 
           } 
           ?> 

           <div class="clearfix">
               <table width="100%">
                  <tr>
                     <td width="25%">
                        <img src="<?= $logo_path ?>" style="height: 100px; width: 100px; border-radius: 5px;" />
                    </td>
                    <td valign="top">
                        <h3 class="fwb"> 
                           <i class="icon-handshake-o"></i> <?=$item['name']?> 
                       </h3> 
                   </td>
               </tr>
           </table>
       </div>
   </div> 
</div> 
<?php $i++; } ?> 
</div> 
</section>