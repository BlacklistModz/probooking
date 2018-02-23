<div class="profile-left" role="left" data-width="340">

	<div role="leftHeader">
		
        <div class="clearfix profile-left_header">

            <div class="clearfix profile-left_actions">
                <!-- <div class="lfloat">
                    <a class="btn-icon" href="<?=URL?>customers"><i class="icon-arrow-left"></i></a>
                </div> -->
                <div class="rfloat">
                </div>
            </div>

            <div class="anchor clearfix">
                <div class="content"><div class="spacer"></div><div class="massages">
                    <h3 class="fullname">รายละเอียดพีเรียด</h3>
                    <div class="subname fsm">Code : <?=$this->item['code']?></div>
                </div></div>
            </div>

            <div class="mvs clearfix">
                <!-- <div class="fsm fcg"><i class="icon-clock-o mrs"></i>แก้ข้อมูลล่าสุด:</div> -->
            </div>

        </div>
	</div>
	<!-- end: .profile-left-header -->

	<div class="phl" role="leftContent">

        <div class="profile-resume mtm"><?php 
            include 'sections/basic.php';
            include 'sections/period.php';
            include 'sections/payment.php';
            include 'sections/hotel.php';

        ?></div>

        <div class="profile-resume mtm tac">
            <div>
                <a href="" class="btn btn-green"><i class="icon-print"></i> พิมพ์ข้อมูลผู้เดินทาง</a>
            </div>
            <div class="mts">
                <a href="" class="btn btn-blue"><i class="icon-print"></i> พิมพ์ Tag กระเป๋า</a>
            </div>
            <div class="mts">
                <span class="gbtn">
                    <a href="<?=URL?>office/series" class="btn btn-red"><i class="icon-arrow-circle-o-left mrs"></i> ย้อนกลับ</a>
                </span>
            </div>
        </div>
    </div>
    <!-- end: .profile-left-details -->
</div>