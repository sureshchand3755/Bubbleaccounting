
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" width="270" height="62" style="width:225px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
            <div style="width:100%; margin-bottom:15px;">
                <p>Hello <?php echo $firstname; ?>, </p>
                <p>We have received your forgot password request. Please Reset your Password by clicking on the below link.</p>
                <p>Reset Link: <a href="<?php echo $reset_link; ?>"><?php echo $reset_link; ?></a></p>
                <p>OTP: <?php echo $otp; ?></p>
                <p>This Link will be expired with in 10 Mins.</p>
                <p>Please don’t share with anyone.</p><br/><br/>
                <p>Regards,<br/> Team BubbleAccount</p>
            </div>
        </div>
    </div>
</div>