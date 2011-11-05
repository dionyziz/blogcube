<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    ?><div id="invform"><?php
    h3( "Invite a Friend" , "invite64" );
    ?>
    Please type in your friend's full name and e-mail address.
<br /><br />
<form onsubmit="return invitefriend();">
<table>
<tr><td class="ffield">Friend's First Name:</td><td class="ffield"><input type="text" id="invitation_fname" class="inbt" /></td></tr>
<tr><td class="nfield">Friend's Last Name:</td><td class="nfield"><input type="text" id="invitation_lname" class="inbt" /></td></tr>
<tr><td class="nfield">Friend's Email:</td><td class="nfield"><input type="text" id="invitation_email" class="inbt" /></td></tr>
</table>
<?php
    $links = Array();
    $links[ "Preview Invitation" ] = "javascript:de('invitefriend','blogging/invitations/invite/preview');";
    $links[ "Invite Friend" ] = "javascript:void(invitefriend());";
    $links[ "Cancel" ] = "javascript:de('main','/blogging/invitations/invitations');";
    LinkList( $links );
?><br />
<input type="submit" style="display:none" />
</form>
</div>
<div id="invitefriend"></div>
<?php
$bfc->start();
?>
g("invitation_fname").focus();
cacheable = true;
et("Invite a friend");
<?php
$bfc->end();
?>
