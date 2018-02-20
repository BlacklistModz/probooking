<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class=" container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-users"></i> Sales Management System</h1>
					<h3 class="tac">บริษัท : <?=$this->me['company_name']?></h3>
				</header>

				<div class="clearfix">
					<div class="clearfix">
						<?php if( $this->me["role"] == "admin" ) { ?>
						<a href="<?=URL?>agency/add/" data-plugins="dialog" class="btn btn-blue rfloat"><i class="icon-plus"></i> เพิ่มเซลล์</a>
						<?php } ?>
					</div>
					<div class="mtm">
						<table class="table-bordered" style="color:#000;">
							<thead style="color:#fff; background-color: #003;">
								<tr>
									<th width="5%">ลำดับ</th>
									<th width="15%">Username</th>
									<th width="25%">ชื่อ-นามสกุล</th>
									<th width="25%">Email</th>
									<th width="7%">สิทธิ์</th>
									<th width="13%">สถานะ</th>
									<?php if( $this->me["role"] == "admin" ){ ?>
									<th width="10%">จัดการ</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php 
								if( !empty($this->results['lists']) ) {
									$num=1;
									foreach ($this->results['lists'] as $key => $value) { 
										if( $value['role'] == "admin" ){
											$role = "Admin";
										}
										elseif( $value['role'] == "sales" ){
											$role = "Sale";
										}
										else{
											$role = "-";
										}
										?>
										<tr>
											<td class="tac pam"><?=$num?></td>
											<td class="tac"><?=$value['user_name']?></td>
											<td class="plm"><?=$value['fullname']?></td>
											<td class="plm"><?=$value['email']?></td>
											<td class="tac"><?= $role ?></td>
											<td class="tac">
												<span class="agen_status_<?=$value['status']?>">
													<?=$value['agen_status']['name']?>
												</span>
											</td>
											<?php if( $this->me["role"] == "admin" ){ ?>
											<td class="tac whitespace">

												<a href="<?=URL?>agency/change_password/<?=$value["id"]?>" data-plugins="dialog" class="btn btn-blue"><i class="icon-key"></i></a>

												<a href="<?=URL?>agency/edit/<?=$value['id']?>" data-plugins="dialog" class="btn btn-orange"><i class="icon-pencil"></i></a>
												<!-- <a href="" class="btn btn-red">ลบ</a> -->
											</td>
											<?php } ?>
										</tr>
										<?php 
										$num++;
									} 
								}
								else{
									echo '<tr><td colspan="7" style="color:red;" class="fwb tac">ไม่พบข้อมูล</td></tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>