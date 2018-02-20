<?php

class Tour_Fn extends _function
{

    public function item($data, $options=array())
    {
        $options = array_merge(array(
            'cell' => isset($_REQUEST['gcell']) ? $_REQUEST['gcell']: 4,
            'download' => false,
            'cls' => isset($_REQUEST['gcls']) ? $_REQUEST['gcls']: '',
            'min' => isset($_REQUEST['grid_min']) ? $_REQUEST['grid_min']: false,
        ), $options);

        $item = '';
        foreach ($data as $val) {

            $image = '';
            if( !empty($val['image_cover_id']) ){

                $image = '<a href="'.$val['url'].'" class="pic" data-plugins="prograssiveImage2" data-options="'.$this->stringify( array(
                        'id'=>$val['image_cover_id'], 
                        'url'=> URL.'media/',
                        'alt' => $val['name']
                    ) ).'"></a>';
            }
            else{
                $image = !empty($val['image_cover_urls']['s'])
                    ? '<img src="'.$val['image_cover_urls']['s'].'" alt="'.$val['name'].'">'
                    : '';

                if( empty($image) && !empty($val['image_cover_url']) ){
                    $image = '<img class="img" src="'.$val['image_cover_url'].'" alt="'.$val['name'].'">';
                }

                $image = '<a href="'.$val['url'].'" class="pic">'.$image.'</a>';
            }


            $pdf = !empty($val['pdf']['url']) ? $val['pdf']['url']: '#';


            $firstPeriod = '-';
            if( !empty($val['periodStartDate']) && !empty($val['periodEndDate']) ){
                $firstPeriod = $this->periodDate($val['periodStartDate'], $val['periodEndDate']);
            }
            

            $item .= '<li class="item" data-id="'.$val['id'].'"><div class="inner clearfix">'.

                // image
                '<figure class="product-cover">'.$image.'</figure>'.

                '<div class="product-content">'.

                    '<div class="product-header">'.
                        '<div class="product-airline"><i class="ico-airline-'.strtolower($val['airline']).''.($options['min'] ? ' small':'' ).'"></i></div>'.
                        '<h3 class="product-category">'.$val['category_str'].'</h3>'.
                        '<h2 class="product-name"><a href="'.$val['url'].'" title="'.$val['name'].'">'.$val['name'].'</a></h2>'.
                    '</div>'.
                    
                    '<ul class="product-meta">'.

                        '<li class="clearfix duration">'.
                            '<div class="label"><i class="icon-calendar-o"></i><span>ช่วงเวลา:</span></div>'.
                            '<div class="data"><strong>'.$firstPeriod.'</strong></div>'.
                        '</li>'.

                        '<li class="clearfix date">'.
                            '<div class="label"><i class="icon-clock-o"></i><span>ระยะเวลา:</span></div>'.
                            '<div class="data"><strong>'.$val['days_str'].'</strong></div>'.
                        '</li>'.

                        /*'<li class="clearfix price">'.
                            '<div class="label"><i class="icon-money"></i><span>ราคาเริ่มต้น:</span></div>'.
                            '<div class="data"><strong>'.$val['price_str'].'</strong></div>'.
                        '</li>'.*/

                    '</ul>'.

                '</div>'.

                '<div class="product-footer">'.
                    '<label class="product-price-label">ราคาเริ่มต้น / ท่าน</label>'.
                    '<div class="product-price">'.$val['price_str'].'</div>'.
                    '<div class="product-button group-btn">'.
                        '<span class="btn btn-code">'.$val['code'].'</span>'.
                        '<a class="btn btn-more" href="'.$val['url'].'">ดูรายละเอียด</a>'.
                    '</div>'.
                '</div>'.

            '</div></li>';
        }


        $cls = 'products-grid';
        if( !empty( $options['cell'] ) ){
            $cls .= !empty($cls) ? ' ':'';
            $cls .= 'cell-'.$options['cell'];
        }

        if( !empty( $options['min'] ) ){
            $cls .= !empty($cls) ? ' ':'';
            $cls .= 'min';
        }

        if( !empty( $options['cls'] ) ){
            $cls .= !empty($cls) ? ' ':'';
            $cls .= $options['cls'];
        }
       
        $item = '<ul class="'.$cls.'">'.$item.'</ul>';
        return $item;
    }


    public function pagination( $options=array() )
    {

        $total = $options['total'];
        $limit = $options['limit'];
        $page = $options['page'];
        $length = isset($options['length']) ? $options['length']: 1;
        $url = isset($options['url']) ? $options['url']: URL;

        $is_get = !empty($options['is_get']) ? '&':'?';

        $pageTotal = ceil( $total/$limit );
        $min = $page-$length;

        $max = $page+$length;
        if( $pageTotal>5 ){

            if( $max > $pageTotal ){
                $min -= $max-$pageTotal;
            }

            if( $page <= $length ){
                $max += $length-$page;
                $max ++;
            }
        }

        $_min = true; $_max = true;

        $li = '';
        if( $page > 1 ){
            $li .= '<li><a href="'.$url.$is_get.'pager='.($page-1).'" class="page"><i class="icon-angle-left"></i></a></li>';
        }


        for ($i=1; $i <= $pageTotal; $i++) { 

            if( $i < $min || $i > $max ){

                if( $i < $min && $_min ){
                    $li .= '<li><span class="page text">..</span></li>';
                    $_min = false;
                }

                if( $i > $max && $_max ){
                    $li .= '<li><span class="page text">..</span></li>';
                    $_max = false;
                }
                continue;
            }

            if( $page==$i ){ 
                $li .= '<li class="active"><a class="page">'.$i.'</a></li>';
            }
            else{
                $li .= '<li><a href="'.$url.$is_get.'pager='.$i.'" class="page">'.$i.'</a></li>';
            }
        }

        if( $page != $pageTotal ){
            $li .= '<li><a href="'.$url.$is_get.'pager='.($page+1).'" class="page next"><i class="icon-angle-right"></i></a></li>';
        }

        return !empty($li) && $limit < $total
            ? '<nav class="pagination-wrap"><ul class="pagination clearfix">'.$li.'</ul></nav>'
            : '';
    }


    public function periodDate($start, $end)
    {
        $startDate = date("j", strtotime($start));
        $endDate = date("j", strtotime($end));

        $startMonth = $this->q('time')->month(date("n", strtotime($start)));
        $endMonth = $this->q('time')->month(date("n", strtotime($end)));

        $startYear = date("Y", strtotime($start))+543;
        $endYear = date("Y", strtotime($end))+543;

        if( $startMonth == $endMonth ){
            $startMonth = "";
        }

        if( $startYear == $endYear ){
            $startYear = "";
        }

        if( !empty($startYear) ){
            $startYear = substr($startYear, 2);
        }

        if( !empty($endYear) ){
            $endYear = substr($endYear, 2);
        }

        // $star = 
        $text =  trim("{$startMonth} {$startYear}");
        $text .= !empty($text) ? ' - ':'';
        $text .= trim("{$endMonth} {$endYear}");

        return $text;
    }
	
}