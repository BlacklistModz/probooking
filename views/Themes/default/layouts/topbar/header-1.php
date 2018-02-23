<?php

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert');

$form   ->field("user")
        // ->label('หัวข้อ')
        ->addClass('inputtext')
        ->placeholder('Account (Username)')
        ->autocomplete('off');

$form   ->field("pass")
        ->type('password')
        ->addClass('inputtext')
        ->placeholder('Password')
        ->autocomplete('off');


?>
<div id="header-1" class="header-1">
	<div id="topbar" class="container topbar clearfix">

		<!-- <div class="time" id="countDownDate"></div> -->


        <?php if ( !empty($this->me) ) { ?>
        <div id="login-popup" class="login-popup">
            <span class="mrs"><span style="color:#ff0;">Welcome !</span><div class="dropdown-menu mlm"><?php echo $this->me['fullname']; ?>
					<div class="dropdown-content"><ul>
						<li>
							<a class="dropdown-item" href="<?=URL?>profile/sales">Manage Sales</a>
						</li>
						<li>
							<a class="dropdown-item" href="<?=URL?>profile/history">Booking History</a>
						</li>
					</div>
				</div></span>
				
					
            <a class="btn btn-yellow btn-border" href="<?=URL?>logout"><span class="fwn">Logout</span></a>
        </div>
        <?php } else { ?>
	
	
		<ul id="login-popup" class="login-popup">
			<li>
				<a class="btn btn-yellow btn-border" data-action="toggle"><span class="fwn">AGENT LOG IN</span></a>
				<div id="login-popup-layer" class="login-popup-layer">

					<button type="button" class="btn-icon" data-action="toggle" style="position: absolute;top: 0;right: 0"><i class="icon-remove"></i></button>
					<p><strong>AGENT LOG IN</strong> or <a href="<?=URL?>register">Register</a></p>
	                <form class="js-submit-form" action="<?=URL?>login" method="POST">
					<?=$form->html();?>


					<div>
						
					</div>

					<div class="login-popup-layer-button clearfix">
						<div class="lfloat">
							<!-- <label for="save_id" class="checkbox mrm"><input type="checkbox" id="save_id" name="save_id" autocomplete="off"><span>Save ID</span></label> -->
							<!-- <a href="#">Forget Password ?</a> -->
						</div>
						<div class="rfloat">
							
						<button type="submit" class="btn btn-yellow btn-large"><span class="fwn">Log In</span></button>
						</div>
					</div>
	                </form>
				</div>
			</li>
			<li class="register"><a class="btn btn-yellow btn-border" href="<?=URL?>register"><span class="fwn">Register</span></a></li>
			
			<li id="primary-menu-toggle"><a class="btn btn-yellow btn-border"><i class="icon-bars"></i></a></li>

			
		</ul>
        <?php } ?>
	
	</div>

</div>


<!-- end: header-1 -->