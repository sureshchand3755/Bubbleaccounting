<!DOCTYPE html>
<html>
<head>
    <title>Notify Bank Account to Clients</title>
</head>
<body>
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" width="270" height="62" style="width:225px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
            <div style="width:100%; margin-bottom:15px;">
                <?php echo $firstname; ?><br/><br/>
                <p>Our bank account details for your information are:</p>
                <p><b>Practice Name: </b> <?php echo $practice_name; ?></p>
                <p><b>IBAN: </b> <?php echo $iban; ?></p>
                <p><b>BIC: </b> <?php echo $bic; ?></p>
            </div>
            
            <div style="width:100%; height:auto; float:left;">
            <b>Regards,</b><br>
            Bubble.ie Team<br/>
            </div>  
        </div>
    </div>
</div>
</body>
</html>