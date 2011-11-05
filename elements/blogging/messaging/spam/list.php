<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }
?>
<table width="100%">
    <tr>
        <td><b>Title</b></td>
        <td><b>Submission Date</b></td>
    </tr>
    <?php
        $allspams = New Spams();
        while ($curspamid = $allspams->NextSpam()) {
            $curspam = New Spam($curspamid);
            $curmsg = New Message($curspam->GetMsgID());
            ?>
            <tr>
                <td><a href="javascript:dm('messaging/spam/view&spamid=<?php 
                echo $curspamid; 
                ?>');"><?php 
                echo $curmsg->MessageSubject(); 
                ?></a></td>
                <td><?php 
                echo BCDate($curspam->GetDate()); 
                ?></td>
            </tr>
            <?php
        }
    ?>
</table>