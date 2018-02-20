<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<header class="page-title">
		<!-- <h2><?=$this->item['name']?></h2> -->
		<?php include("sections/header.php"); ?>
		<!-- <h2>MYANMAR</h2> -->
	</header>
	<div class=" clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac" style="color:red;"><i class="icon-fire"></i> โปรดันขาย <i class="icon-fire"></i></h1>
				</header>
				<div class="clearfix">
					<table class="table-bordered mtl" width="100%" style="overflow-x: auto; display: block; width: 100%; -webkit-overflow-scrolling: touch;  -ms-overflow-style: -ms-autohiding-scrollbar;">
						<thead>
							<tr style="background-color: #003;color:#fff;">
								<th width="5%">CODE</th>
								<th width="30%">PROGRAM</th>
								<th width="10%">Period</th>
								<th width="5%">Adult</th>
								<th width="5%">Child</th>
								<th width="5%">Child NB</th>
								<th width="5%">Infant</th>
								<th width="5%">Joinland</th>
								<th width="5%">Sing Charge</th>
								<th width="5%">Com</th>
								<th width="3%">ที่นั่ง</th>
								<th width="3%">จอง</th>
								<th width="3%">รับได้</th>
								<th width="3%">Book</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->results as $key => $value) { 
								$dateStr = $this->fn->q('time')->str_event_date($value["date_start"], $value["date_end"]);

								$price_1 = $value["price_1"];
								$price_2 = $value["price_2"];
								$price_3 = $value["price_3"];

								if( $value["discount"] != 0.00 ){
									$price_1 = $value["price_1"]!=0.00 
											   ? '<strike style="color:red;">'.number_format($value["price_1"]).'</strike> <label class="fwb">'.number_format($value["price_1"] - $value["discount"]).'.-</label>' 
											   : '-';
									$price_2 = $value["price_2"]!=0.00 
											   ? '<strike style="color:red;">'.number_format($value["price_2"]).'</strike> <label class="fwb">'.number_format($value["price_2"] - $value["discount"]).'.-</label>' 
											   : '-';
									$price_3 = $value["price_3"]!=0.00 
											   ? '<strike style="color:red;">'.number_format($value["price_3"]).'</strike> <label class="fwb">'.number_format($value["price_3"] - $value["discount"]).'.-</label>' 
											   : '-';
								}
								else{
									$price_1 = number_format($value["price_1"]).'.-';
									$price_2 = number_format($value["price_2"]).'.-';
									$price_3 = number_format($value["price_3"]).'.-';
								}
							?>
							<tr>
								<td class="tac fwb"><?=$value["ser_code"]?></td>
								<td><span class="mls"><?=$value["ser_name"]?></span></td>
								<td class="tac"><?=$dateStr?></td>
								<td class="tar"><span class="mrs"><?= $price_1 ?></span></td>
								<td class="tar"><span class="mrs"><?= $price_2 ?></span></td>
								<td class="tar"><span class="mrs"><?= $price_3 ?></span></td>
								<td class="tar"><span class="mrs"><?= $value["price_4"]!=0.00 ? number_format($value["price_4"]).'.-' : '-' ?></span></td>
								<td class="tar"><span class="mrs"><?= $value["price_5"]!=0.00 ? number_format($value["price_5"]).'.-' : '-' ?></span></td>
								<td class="tar"><span class="mrs"><?=number_format($value["single_charge"])?>.-</span></td>
								<td class="tac">
									<?=number_format($value["com_agency"])?> + <?=number_format($value["com_company_agency"])?>
								</td>
								<td class="tac"><?=number_format($value["qty_seats"])?></td>
								<td class="tac"><?=number_format($value["booking"]["booking"])?></td>
								<td class="tac" style="background-color: #43d967;">
										<?php 
										if ($value['balance']<=0){ 
                                			if( $value['booking']['payed'] < $value['seats'] ){
                                				echo '<span class="fwb fcw">'.($value['balance']<=0  ? 'W/L': number_format($value['balance'])).'</span>';
                                			}
                                			else{
                                				echo '<span class="fcr fwb">เต็ม</span>';
                                			}
                                        } else {
                                   		if( $value['status'] == 1  ){
                                   			echo '<span class="fwb fcw">'.($value['balance']<=0  ? 'W/L': number_format($value['balance'])).'</span>';
                                   		}
                                  		else{
                                   			if( $value['status'] == 2 ){
                                   				echo '<span class="fcr fwb">เต็ม</span>';
                                   			}
                                   			else{
                                   				echo '<span class="fcr fwb">ปิดทัวร์</span>';
                                   			}
                                   			// elseif( $value['status'] == 3 ){
                                   			// 	echo '<span class="fcr">ปิดทัวร์</span>';
                                   			// }
                                   			// elseif( $value['status'] == 9 ){
                                   			// 	echo '<span class="fcr">ระงับ</span>';
                                   			// }
                                    		// elseif( $value['status'] == 10 ){
                                    		// 	echo '<span class="fcr">ตัดตั๋ว</span>';
                                    		// }
                                    	}
                                	}
									?>
								</td>
								<td class="tac">
									<?php if ($value['balance']<=0){ 
                                		if( $value['booking']['payed'] < $value['seats'] ){
                                			echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-orange btn-submit">W/L</a>';
                                		}
                                		else{
                                			echo '<span class="btn btn-danger disabled">เต็ม</span>';
                                		}
                                    } else {
                                    	if( $value['status'] == 1  ){
                                    		echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-success btn-submit">จอง</a>';
                                    	}
                                    		else{
                                    		echo '<span class="btn btn-danger disabled"><i class="icon-lock"></i></span>';
                                    	}
                                	} ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
var c = document.getElementById('c'),
    ctx = c.getContext('2d'),
    cw = c.width = 300,
    ch = c.height = 300,
    parts = [],
    partCount = 200,   
    partsFull = false,    
    hueRange = 50,
    globalTick = 0,
    rand = function(min, max){
        return Math.floor( (Math.random() * (max - min + 1) ) + min);
    };

var Part = function(){
  this.reset();
};

Part.prototype.reset = function(){
  this.startRadius = rand(1, 25);
  this.radius = this.startRadius;
  this.x = cw/2 + (rand(0, 6) - 3);
  this.y = 200;      
  this.vx = 0;
  this.vy = 0;
  this.hue = rand(globalTick - hueRange, globalTick + hueRange);
  this.saturation = rand(50, 100);
  this.lightness = rand(20, 70);
  this.startAlpha = rand(1, 10) / 100;
  this.alpha = this.startAlpha;
  this.decayRate = .1;  
  this.startLife = 7;
  this.life = this.startLife;
  this.lineWidth = rand(1, 3);
}
    
Part.prototype.update = function(){  
  this.vx += (rand(0, 200) - 100) / 1500;
  this.vy -= this.life/50;  
  this.x += this.vx;
  this.y += this.vy;  
  this.alpha = this.startAlpha * (this.life / this.startLife);
  this.radius = this.startRadius * (this.life / this.startLife);
  this.life -= this.decayRate;  
  if(
    this.x > cw + this.radius || 
    this.x < -this.radius ||
    this.y > ch + this.radius ||
    this.y < -this.radius ||
    this.life <= this.decayRate
  ){
    this.reset();  
  }  
};
  
Part.prototype.render = function(){
  ctx.beginPath();
  ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
  ctx.fillStyle = ctx.strokeStyle = 'hsla('+this.hue+', '+this.saturation+'%, '+this.lightness+'%, '+this.alpha+')';
  ctx.lineWidth = this.lineWidth;
  ctx.fill();
  ctx.stroke();
};

var createParts = function(){
  if(!partsFull){
    if(parts.length > partCount){
      partsFull = true;
    } else {
      parts.push(new Part()); 
    }
  }
};
  
var updateParts = function(){
  var i = parts.length;
  while(i--){
    parts[i].update();
  }
};

var renderParts = function(){
  var i = parts.length;
  while(i--){
    parts[i].render();
  }   
};
    
var clear = function(){
  ctx.globalCompositeOperation = 'destination-out';
  ctx.fillStyle = 'hsla(0, 0%, 0%, .3)';
  ctx.fillRect(0, 0, cw, ch);
  ctx.globalCompositeOperation = 'lighter';
};
     
var loop = function(){
  window.requestAnimFrame(loop, c);
  clear();
  createParts();
  updateParts();
  renderParts();
  globalTick++;
};

window.requestAnimFrame=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(a){window.setTimeout(a,1E3/60)}}();

loop();
</script>