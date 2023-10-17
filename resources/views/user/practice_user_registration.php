
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" width="270" height="62" style="width:225px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
            <div style="width:100%; margin-bottom:15px;">
                <p>Hello <?php echo $firstname; ?>, </p>
                <p>Thank You for Registering your Practice with us. Please find the Login Details below. </p>

                <p>Your Practice Name: <?php echo $practice_name; ?></p>
                <p>Your Practice Code: <?php echo $practice; ?></p>
                <p>Your Admin Username: <?php echo $email; ?></p>
                <p>Your Admin Password: <?php echo $decrypted_password; ?></p>
                <p>Please don’t share this information with anyone.</p><br/><br/>
                <p>Regards,<br/> Team Bubble.ie</p>
            </div>
        </div>
    </div>
</div>
