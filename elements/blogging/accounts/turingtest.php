<?php 
    // if ( isset( $_SESSION[ "turingenabled" ] ) && $_SESSION[ "turingenabled" ] ) {
?>
<tr><td class="nfield"><b>Security</b></td><td class="nfield">
Please enter the number you see in the textbox below.<br />
<a href="" onclick="help('turing');return false;">Why?</a>
</td></tr>
<tr><td class="efield">&nbsp;</td><td class="efield">
<?php
    img( "cubism.bc?g=turingimage&" .  UniqueTimeStamp()  , "Security check" , "Enter this number in the textbox below" , 110 , 20 );
?>
</td></tr>
<tr><td class="efield"></td><td class="efield">
    <input autocomplete="off" type="text" id="login_turing" class="logintrng" style="width: 120px;" />
    <a href="cubism.bc?g=turingsound&" .  UniqueTimeStamp()>
<?php
    img( "images/nuvola/sound22.png"  , "Audible security check" , "Enter the number from this audio file in the textbox on the left" , 22 , 22 );
?>
</a>
</td></tr>
<?php 
    // }
?>