<?php

$tr = "";
$tr_total = "";
$url = URL .'promotions/';
if( !empty($this->results['lists']) ){ 

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 

        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        // set Name

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            // '<td class="check-box"><label class="checkbox"><input id="toggle_checkbox" type="checkbox" value="'.$item['id'].'"></label></td>'.

            // '<td class="bookmark"><a class="ui-bookmark js-bookmark'.( $item['bookmark']==1 ? ' is-bookmark':'' ).'" data-value="" data-id="'.$item['id'].'" stringify="'.URL.'customers/bookmark/'.$item['id']. (!empty($this->hasMasterHost) ? '?company='.$this->company['id']:'') .'"><i class="icon-star yes"></i><i class="icon-star-o no"></i></a></td>'.
        	'<td class="status_th"><span class="status_'.$item['status'].'">'.$item['status_arr']['name'].'</span></td>'.
            '<td class="name">'.$item["name"].'</td>'.
            '<td class="event_date">'.$this->fn->q('time')->str_event_date( $item["start_date"], $item["end_date"] ).'</td>'.
            '<td class="status">'.number_format($item["discount"]).'</td>'.
            '<td class="actions whitespace">'.
            	'<span class="gbtn">
            		<a href="'.URL.'promotions/edit/'.$item['id'].'" class="btn btn-green btn-no-padding" data-plugins="dialog"><i class="icon-pencil"></i></a>
            	</span>'.
            	'<span class="gbtn">
            		<a href="'.URL.'promotions/del/'.$item['id'].'" class="btn btn-red btn-no-padding" data-plugins="dialog"><i class="icon-trash"></i></a>
            	</span>'.
            '</td>'.
              
        '</tr>';
        
    }
}

$table = '<table class="settings-table"><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';