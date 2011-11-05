<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $bfc->start();
    if ( $user->HasPin() ) {
        ?>g( "ao_pin" ).value = "default";<?php
    }
    ?>et( "Account Options" );
    ao_old_password = "<?php echo $user->PasswordHash(); ?>";
    <?php
    $bfc->end();
    
    h3( "Account options" , "kgpg64" );
    echo $arrow;
    ?><a href="javascript:ao_show_password()">Change password</a><br /><br />
<div id="ao_password" style="display:none">
    <br />
    <table class="formtable">
        <tr>
            <td class="ffield"><strong>Old password:</strong></td>
            <td class="ffield"><input type="password" class="inbt" id="ao_old" onkeydown="ao_oldpwd_before()" onkeyup="ao_oldpwd_check()" /></td>
            <td><div id="ao_typing" style="padding-left:5px; display: none;">Typing...</div><div id="ao_validity" style="padding-left:5px;font-weight:bold; display: none;">Invalid Password</div><div id="ao_done1" style="display:none;"><?php img('images/nuvola/done.png'); ?></div></td>
        </tr>
        <tr>
            <td class="nfield"><strong>New password:</strong></td>
            <td class="nfield"><input type="password" class="inbt" id="ao_pwd" onkeyup="Strength.strength('ao_pwd','ao_too_short','ao_rtp','ao_strength')" onblur="" disabled="true" /></td>
            <td><div id="ao_too_short" style="padding-left:5px;display:none">Password too short</div><div id="ao_strength" style="padding-left:5px;"></div></td>
        </tr>
        <tr>
            <td class="nfield"><strong>Retype:</strong></td>
            <td class="nfield"><input type="password" class="inbt" onblur="" id="ao_rtp" disabled="true" onkeyup="ao_newpwd_check()" /></td>
            <td><div id="ao_pwd_changebtn" style="display:none; padding-left:5px;"><a href="javascript:ao_pwd_change();">Change password</a></div><div id="ao_pwd_changed" style="padding-left:5px; display: none;"></div><div id="ao_match" style="font-weight: bold; display: none;padding-left:5px;">Passwords do not match</div></td>
        </tr>
    </table>
    <br />
</div><?php
        echo $arrow;
    ?><a href="javascript:ao_show_distantblogging()">Distant blogging</a><br /><br />
<div id="ao_distantblogging" style="display:none">
    <br />
    <table class="formtable">
        <tr>
            <td class="ffield"><strong>Email blogging:</strong></td>
            <td class="ffield"><input type="radio" name="ao_emailblogging" checked="checked" id="ao_email_enabled" /><label for="ao_email_enabled" /> Enabled</td>
            <td class="ffield"><input type="radio" name="ao_emailblogging" id="ao_email_disabled" /><label for="ao_email_disabled" /> Disabled</td>
        </tr>
        <tr>
            <td class="nfield"><strong>SMS blogging:</strong></td>
            <td class="nfield"><input type="radio" name="ao_smsblogging" checked="checked" id="ao_sms_enabled" /><label for="ao_sms_enabled" /> Enabled</td>
            <td class="nfield"><input type="radio" name="ao_smsblogging" id="ao_sms_disabled" /><label for="ao_sms_disabled" /> Disabled</td>
        </tr>
    </table>
    <br />
    <table class="formtable">
        <tr>
            <td class="ffield"><strong>PIN:</strong></td>
            <td class="ffield"><input type="password" class="inbt" id="ao_pin" onfocus="g( 'ao_pin' ).value = ''" onblur="ao_pin_changing()" /></td>
            <td><div id="ao_pin_changed"></div></td>
        </tr>
    </table>
    <br />
</div>