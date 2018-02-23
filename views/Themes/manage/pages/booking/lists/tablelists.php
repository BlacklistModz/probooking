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

        $expired = $this->fn->q('time')->DateTH( $item['book_due_date_full_payment'] );
        $expired .= ' '.date("H:i:s", strtotime( $item['book_due_date_full_payment'] ));

        if( $item["book_due_date_deposit"] != "0000-00-00 00:00:00" && $item['status'] != 30 && $item['status'] != 35 ){
            $expired = $this->fn->q('time')->DateTH( $item['book_due_date_deposit'] );
            $expired .= ' '.date("H:i:s", strtotime( $item['book_due_date_deposit'] ));
        }

        $cls = $i%2 ? 'even' : "odd";

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['book_id'].'">'.

            '<td class="status"><span class="status-label status_'.$item['status'].'">'.$item['book_status']['name'].'</span></td>'.
            '<td class="status">'.$item['book_code'].'</td>'.
            '<td class="status">'.$item['ser_code'].'</td>'.
            '<td class="type">'.$this->fn->q('time')->str_event_date($item['per_date_start'], $item['per_date_end']).'</td>'.
            '<td class="status">'.$item['book_qty'].'</td>'.
            '<td class="price">'.number_format($item['book_total']).'</td>'.
            '<td class="price" style="background-color:#43d967; color:#fff;">'.number_format($item['book_amountgrandtotal']).'</td>'.
            '<td class="type">'.$this->fn->q('time')->DateTH( $item['book_date'] ).'</td>'.
            '<td class="type" style="background-color:#f47f7f; color:#fff;">'.$expired.' น.</td>'.
            '<td class="contact">'.$item['agen_com_name'].'</td>'.
            '<td class="contact">'.$item['agen_fname'].' '.$item['agen_lname'].'</td>'.
            '<td class="contact">'.$item['user_fname'].' '.$item['user_lname'].'</td>'.
            '<td class="actions">'.
                '<span class="gbtn"><a href="'.URL.'office/booking/basic/'.$item['book_id'].'" class="btn btn-blue"><i class="icon-pencil"></i> จัดการ</a></span>'.
            '</td>'.

        '</tr>';
        
    }
  
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';