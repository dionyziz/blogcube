<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

    $spamid = $_POST["spamid"];
    $curspam = New Spam($spamid);
    $curmsg = New Message($curspam->GetMsgID());
?>
<table width="100%">
<tr><td class="ffield" width="20%"><?php echo $arrow; ?><b>Subject:</b></td><td class="ffield"><?php echo $curmsg->MessageSubject(); ?></td></tr>
<tr><td class="nfield" width="20%"><?php echo $arrow; ?><b>Date:</b></td><td class="nfield"><?php echo BCDate($curmsg->MessageDate()); ?></td></tr>
<tr><td class="nfield" width="20%"><?php echo $arrow; ?><b>Reported on:</b></td><td class="nfield"><?php echo BCDate($curspam->GetDate()); ?></td></tr>
<tr><td class="nfield" width="20%"><?php echo $arrow; ?><b>From*:</b></td><td class="nfield"><?php
    $tempsndr = New User($curmsg->MessageSenderID());
    ?><a href="javascript:dm('profile/profile_view&user=<?php
        echo $tempsndr->Username();
    ?>');"> <?php 
        echo $tempsndr->Username();
    ?></a>
</td></tr>
<tr><td colspan="2"><p><?php echo BlogCute($curmsg->MessageText()); ?></p></td></tr>
<tr><td colspan="2" class="nfield" align="center">
<?php img('images/nuvola/mfilter.png'); ?> <a href="javascript:dm('blogging/messaging/spam/filter/add&msgid=<?php echo $msgid; ?>');">Add a Spam Filter</a>&nbsp;&nbsp; 
</td></tr>
</table>
<br />
*From refers to the person that sent the message to the reporter, not the reporter himself.