<?php
//print_r($this->results['lists']); die;
$tr = "";
$tr_total = "";

if( !empty($this->results['lists']) ){ 
    //print_r($this->results); die;

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 
        // print_r($item);die;
        // $item = $item;

        $guarantee = '';
        if( !empty($item['com_guarantee']) ){
            $guarantee = '<i class="icon-thumbs-o-up"></i>';
        }

        $cls = $i%2 ? 'even' : "odd";

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['com_id'].'">'.

            '<td class="status">'.$guarantee.'</td>'.

            '<td class="name">'.$item["com_name"].'</td>'.

            '<td class="contact">'.$item['sale_name'].'</td>'.

            '<td class="status_str">
                <span class="agen_status_'.$item['status'].'">'.$item['status_arr']['name'].'</span>
            </td>'.

            '<td class="actions whitespace">'.
                '<span class="gbtn">
                    <a data-plugins="dialog" href="'.URL.'agency_company/edit/'.$item['com_id'].'" class="btn btn-no-padding btn-blue"><i class="icon-pencil"></i></a>
                </span>'.
                '<span class="gbtn">
                    <a data-plugins="dialog" href="'.URL.'agency_company/del/'.$item['com_id'].'" class="btn btn-no-padding btn-red"><i class="icon-trash"></i></a>
                </span>'.
            '</td>'.

        '</tr>';
        
    }
  
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';