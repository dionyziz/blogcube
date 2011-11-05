<?php
    $invitationid = $_POST[ "iid" ];
    $invitationcode = $_POST[ "icd" ];
    
    $thisinvitation = New Invitation( $invitationid );
    if( $thisinvitation->CheckValidy( $invitationcode ) || ( $user->IsDeveloper() && $debug_version ) ) {
        if ( $user->IsDeveloper() ) {
            debug_notice('Invalid invitation: developer exemption');
        }
        // we need to check if the invitation has been used or not
        if( $thisinvitation->RegisteredUserId() ) {
            // used invitation
            ?><table><tr><td><img src="images/nuvola/error64.png" /></td>
            <td><h3>Invitation already used</h3></td></tr></table><br />
            We're sorry, but the invitation you are trying to use has already been used.<br />
            Perhaps you should ask the friend who invited you to reinvite you.
            <?php
        }
        else {
            $email = $thisinvitation->InvitedEmail();
            $namepart = substr( $email , 0 , strpos( $email , "@" ) );
            ?><div id="iid" style="display:none"><?php
            echo $thisinvitation->Id();
            ?></div><div id="icd" style="display:none"><?php
            echo $thisinvitation->Code();
            ?></div><table><tr><td><img src="images/nuvola/account64.png" /></td>
            <td><h3>Create a BlogCube Account</h3></td></tr></table>
            <br />
            <table class="formtable">
            <tr><td class="ffield">Choose a <b>user name</b> </td><td class="ffield"><input type="text" value="<?php
            $u = GetUserByUsername( $namepart );
            if( $u === false ) {
                echo $namepart;
            }
            ?>" onkeyup="Users.checkRegister(this)" class="inbt" id="reg_uname" tabindex="1" /></td><td class="ffield"><div id="reg_checkavail" class="inline"><a href="" onclick="de('ureg_ca','blogging/accounts/unamecheck&n=' + g('reg_uname').value);return false;" tabindex="9">Check Availability</a></div><div id="reg_uinvalid" style="display:none"><?php 
            img( 'images/nuvola/error.png' , 'Error' , 'Invalid Username Provided' );
            ?> <b>Invalid</b></div><div id="reg_uinvalidshort" style="display:none"><?php
            img( 'images/nuvola/error.png' , 'Error' , 'Invalid Username Provided' );
            ?> <b>Too short</b></div></td>
            <td id="ureg_ca" rowspan="3" style="vertical-align:top" style="width:300px"></td></tr>
            <tr><td class="nfield">Enter a <b>password</b> </td><td class="nfield"><input type="password" class="inbt" id="reg_pwd" tabindex="2" onkeyup="Strength.strength('reg_pwd','reg_too_short','reg_pwd2','reg_strength')" /></td><td class="nfield" style="width:180px"><div id="reg_too_short" style="display: none;">Password too short</div><div id="reg_strength"></div></td></tr>
            <tr><td class="nfield"><b>Retype</b> password </td><td class="nfield"><input type="password" class="inbt" id="reg_pwd2" tabindex="3" disabled="true" onkeyup="reg_pwd_check()" /></td><td class="nfield" ><div id="reg_pwd_correct" style="display: none;"><?php img('images/nuvola/done.png'); ?></div></tr>
            <tr><td class="nfield">E-mail address </td><td class="nfield"><input type="text" value="<?php
            echo $email;
            ?>" class="inbt" id="reg_mail" tabindex="4" /></td><td class="nfield" /><td id="ureg_ce" /></tr>
            <tr><td class="nfield">Acceptance of Terms </td><td colspan="2" class="nfield"><input type="checkbox" name="tos" id="tos" tabindex="5" /> <label for="tos">I accept the</label>
            <a href="" onclick="help('tos');return false;" tabindex="7">Terms of Service</a><label for="tos"> and the </label><a href=""  onclick="help('pp');return false;" tabindex="8">Privacy Policy</a></td><tr><tr>
            <td colspan="2"><div id="oreg"></div></td><td class="ssfield"><?php
            echo $arrow;
            ?><a href="" onclick="Users.register();return false;" tabindex="6">Create Account</a></td></tr></table>
            <?php
            $validinv = true;
        }
    }
    else {
        ?><table><tr><td><img src="images/nuvola/error.png" /></td>
        <td><h3>Invalid Invitation</h3></td></tr></table><br />
        We're sorry, but the invitation you are trying to use is invalid.<br />
        Perhaps you should ask the friend who invited you to reinvite you.
        <?php
    }
    ?><br /><br />
    <h4>Already have an account?</h4><br /><br /><?php
    LinkList( Array( "I've already registered" => "javascript:dm('accounts/login' );" ) );
    $bfc->start();
    ?>
    et( "Create an Account" );
    <?php
    if( $validinv === true ) {
        ?>g("reg_uname").focus();<?php
    }
    $bfc->end();
?>