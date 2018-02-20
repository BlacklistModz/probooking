<?php

$tr = "";
$tr_total = "";
$url = URL .'user/';
if( !empty($this->results['lists']) ){ 

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 

    	$image = '';
        if( !empty($item['image_url']) ){
            $image = '<div class="avatar lfloat mrm"><img class="img" src="'.$item['image_url'].'" alt="'.$item['fullname'].'"></div>';
        }
        else{
            $image = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
        }

        $guarantee = '';
        if( !empty($item['com_guarantee']) ){
        	$guarantee = '<i class="icon-thumbs-o-up"></i>';
        }

        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        // set Name

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['com_id'].'">'.

            // '<td class="check-box"><label class="checkbox"><input id="toggle_checkbox" type="checkbox" value="'.$item['id'].'"></label></td>'.

            // '<td class="bookmark"><a class="ui-bookmark js-bookmark'.( $item['bookmark']==1 ? ' is-bookmark':'' ).'" data-value="" data-id="'.$item['id'].'" stringify="'.URL.'customers/bookmark/'.$item['id']. (!empty($this->hasMasterHost) ? '?company='.$this->company['id']:'') .'"><i class="icon-star yes"></i><i class="icon-star-o no"></i></a></td>'.
        	'<td class="status">'.$guarantee.'</td>'.

            '<td class="name">'.

                '<div class="anchor clearfix">'.
                    $image.
                    
                    '<div class="content"><div class="spacer"></div><div class="massages">'.

                        '<div class="fullname"><a class="fwb">'. $item['com_name'].'</a></div>'.

                        '<div class="subname fsm fcg meta">'.$item['com_ttt_on'].'</div>'.

                        // '<div class="fss fcg whitespace">Last update: '.$this->fn->q('time')->live( $item['updated'] ).'</div>'.
                    '</div>'.
                '</div></div>'.

            '</td>'.

            // '<td class="email">'.$item['com_email'].'</td>'.

            '<td class="status_th tac fwb">
            		<span class="agen_status_'.$item['status'].'">'.$item['status_arr']['name'].'</span>
            </td>'.

            '<td class="actions whitespace">'.
            	'<span class="gbtn">
            		<a href="'.URL.'agency_company/edit/'.$item['com_id'].'" class="btn btn-green btn-no-padding" data-plugins="dialog"><i class="icon-pencil"></i></a>
            	</span>'.
            	'<span class="gbtn">
            		<a href="'.URL.'agency_company/del/'.$item['com_id'].'" class="btn btn-red btn-no-padding disabled" data-plugins="dialog"><i class="icon-trash"></i></a>
            	</span>'.
            '</td>'.
              
        '</tr>';
        
    }
}

$table = '<table class="settings-table"><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';