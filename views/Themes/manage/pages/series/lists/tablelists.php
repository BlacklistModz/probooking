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

        $period = '';
        if( !empty($item['period']) ){
            $num = 0;
            $fullPaymentTotal = 0;
            foreach ($item['period'] as $per) {
                $booking = '';
                $waiting = '';
                if( !empty($per["booklist"]["booking"]) ){
                    foreach ($per["booklist"]["booking"] as $key => $value) {

                        // if( $value["status"] == 35 ){
                        //     $fullPaymentTotal += $value["qty"];
                        // }

                        $cls = " s-{$value['status']}";
                        $guarantee = '';
                        if( !empty($value["book_is_guarantee"]) ){
                            $guarantee = '<i class="fa fa-thumbs-up"></i>';
                        }

                        $booking .= !empty($booking) ? " | " : "";
                        $booking .= '<a class="list-status'.$cls.'">'.$guarantee.' '.$value['sale_nickname'].' '.$value['qty'].' ('.  ucwords($value['company_name']). ')</a>';
                    }
                }

                if( !empty($per["booklist"]["waiting"]) ){
                    foreach ($per["booklist"]["waiting"] as $key => $value) {

                        $cls = " s-{$value['status']}";
                        $guarantee = '';
                        if( !empty($value["book_is_guarantee"]) ){
                            $guarantee = '<i class="fa fa-thumbs-up"></i>';
                        }

                        $waiting .= !empty($waiting) ? " | " : "";
                        $waiting .= '<a class="list-status'.$cls.'">'.$guarantee.' '.$value['sale_nickname'].' '.$value['qty'].' ('.  ucwords($value['company_name']). ')</a>';
                    }
                }

                $btn = '';
                if( $per['booking']["payed"] == intval($per["bus_qty"]) ){
                    $txt = '<span class="gbtn"><a class="btn btn-red btn-small"><i class="icon-lock"></i> เต็ม</a></span>';
                }
                else{
                    $txt = '<span class="gbtn"><a class="btn btn-small btn-orange" href="'.URL.'office/booking/basic?period='.$per['id'].'&bus='.$per['bus_no'].'" target="_blank"><em class="icon-book"></em> W/L</a></span>';
                }

                if( intval($per['balance'])<=0 ){
                    $btn = $txt;
                }
                else{
                    $btn = '<span class="gbtn"><a class="btn btn-small btn-blue" href="'.URL.'office/booking/basic?period='.$per['id'].'&bus='.$per['bus_no'].'" target="_blank"><em class="icon-book"></em> จอง</a></span>';
                }

                if( $per["status"] == 3 ){
                    $btn = '';
                }

                $num++;
                $period .= '<tr class="no-hover">
                                <td style="text-align:center;padding: 0px;">'.$num.'</td>
                                <td style="text-align:center;"><div class="status-label '.$per['status_arr']['cls'].'">'.$per['status_arr']['name'].'</div></td>
                                <td style="text-align:center;">'.$this->fn->q('time')->str_event_date($per["date_start"], $per["date_end"]).'</td>
                                <td style="text-align:center; background-color:#51c6ea; color:#fff;" class="fwb">'.number_format($per['price_1']).'</td>
                                <td style="text-align:center;">'.number_format($per["bus_qty"]).'</td>
                                <td style="text-align:center;">'.number_format($per["booking"]["booking"]).'</td>
                                <td style="text-align:center; background-color:#43d967; color:#fff;" class="fwb">'.number_format($per['balance']).'</td>
                                <td style="text-align:center;">'.number_format($per['booking']["payed"]).'</td>
                                <td class="fwb">'.$booking.'</td>
                                <td class="fwb">'.$waiting.'</td>
                                <td style="text-align:center;">
                                    <div class="whitespace">
                                            '.$btn.'
                                        <span class="gbtn">
                                            <a href="'.URL.'office/series/'.$per["id"].'/'.$per['bus_no'].'" class="btn btn-green btn-small"><i class="icon-pencil-square-o"></i> รายละเอียด</a>
                                        </span>
                                    </div>
                                </td>
                            </tr>';
            }
        }

        $cls = $i%2 ? 'even' : "odd";

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="name">
                <div style="color:#f532e5;" class="fwb">'.$item['code'].' - '.$item['name'].'</div>'.
                '<div class="mts uiBoxWhite pas">
                    <table class="table-bordered">
                        <thead>
                            <tr style="color:#fff; background-color:#003;">
                                <th style="width:1%;">#</th>
                                <th style="width:2%;">สถานะ</th>
                                <th style="width:10%;">เดินทาง</th>
                                <th style="width:2%;">ราคา</th>
                                <th style="width:2%;">ที่นั่ง</th>
                                <th style="width:2%;">จอง</th>
                                <th style="width:2%;">รับได้</th>
                                <th style="width:2%;">FP</th>
                                <th style="width:40%;">Booking</th>
                                <th style="width:20%;">W/L</th>
                                <th style="width:8%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            '.$period.'
                        </tbody>
                    </table>
                </div>'.
            '</td>'.

            // '<td class="email" style="text-align:center;"><span class="agen_status_'.$item['status'].'">'.$item['agen_status']['name'].'</span></td>'.
            // '<td class="username" style="text-align:center;">'.$item['user_name'].'</td>'.
            // '<td class="name">'.$item['fullname'].'</td>'.
            // '<td class="contact">'.$item['company_name'].'</td>'.
            // '<td class="email">'.$item['email'].'</td>'.
            // '<td class="phone" style="text-align:center;">'.$item['tel'].'</td>'.
            // '<td class="status fwb">'.strtoupper($item['role']).'</td>'.
        '</tr>';
        
    }
  
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';