<?php
    if ( $anonymous ) {
?>
<img src="images/nuvola/forgot_password.png" /> <h3>Forgot Password</h3><br />
Seems that you're stuck, eh? Not a problem! Just type your username below and hit "Remind Me".<br />We'll send you an e-mail with a new password!<br /><br />
<form onsubmit="return Users.forgot();">
<table>
<tr><td><img src="images/nuvola/personal.png" /> Username:</td><td><input type="text" id="username" />&nbsp;<img src="images/nuvola/remind_password.png" /> <a href="" onclick="forgot();return false;">Remind Me</a></td></tr>
</table><br /><br />
Remembered?<br />
<img src="images/nuvola/back.png" /> <a href="" onclick="de('main','blogging/accounts/login');return false;">Back to Login</a><br /><br />
<input type="submit" style="display:none" />
</form>
<?php
    }
    else {
        $bfc->start(); 
        ?>
        et( "Forgot Password" );
        cacheable = true;
        de( "forgot" , "blogging/accounts/login" );
        <?php
        $bfc->end();
    }
?>