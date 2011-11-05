<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

    $bugid = $_POST["bugid"];
    $curbug = New Bug($bugid);
    h3("Bug Details");
?>
<br />
<?php echo $arrow; ?> <a href="javascript:bug_view_sh('bug_reported');">Reported Information</a>
<div id="bug_reported">
<table style="width:100%;">
<tr>
    <td class="ffield"><b>Title:</b></td>
    <td class="ffield"><?php echo $curbug->Name(); ?></td>
</tr>
<tr>
    <td class="nfield"><b>Description:</b></td>
    <td class="nfield"><?php echo $curbug->Description(); ?></td>
</tr>
<tr>
    <td class="nfield"><b>Operating System:</b></td>
    <td class="nfield">
    <?php
    switch($curbug->Os()) {
        case "LINUX":
            img('images/nuvoe/tux22.png');
            echo " ";
            break;
        case "BSD":
            img('images/nuvoe/freebsd22.png');
            echo " ";
            break;
    }
    echo $curbug->Os();
    ?>
    </td>
</tr>
<tr>
    <td class="nfield"><b>Browser:</b></td>
    <td class="nfield">
    <?php
    if (file_exists("images/silk/browser_" . strtolower($curbug->UserBrowser()) . ".png")) {
        img('images/silk/browser_' . strtolower($curbug->UserBrowser()) . '.png');
        echo " ";
    }
    echo $curbug->UserBrowser() . " " . $curbug->UserBrowserVersion();    
    ?>
    </td>
</tr>
<tr>
    <td class="nfield"><b>Posted:</b></td>
    <td class="nfield"><?php
        img("images/nuvola/history.png");
        echo " ";
        echo BCDate($curbug->NowDate());
    ?></td>
</tr>
<tr>
    <td class="nfield"><b>Fixed:</b></td>
    <td class="nfield"><?php
        if ( $curbug->FixedBy() == 0 ) {
            echo "Not yet.";
        }
        else {
            $d = New User($curbug->FixedBy());
            ?>
            <a href="javascript:dm('profile/profile_view&user=<?php echo $d->Username(); ?>');"><?php echo $d->Username(); ?></a>
            <?php
        }
        ?>
    </td>
</tr>
</table>
</div>
<br />
<?php echo $arrow; ?> <a href="javascript:bug_view_sh('bug_captured');">Captured Information</a>
<div id="bug_captured" style="display:none">
<table style="width: 100%">
    <tr>
        <td class="nfield"><b>Reported by:</b></td>
        <td class="nfield">
            <?php
                $s = New User($curbug->UserId());
            ?>
            <a href="javascript:dm('profile/profile_view&user=<?php echo $s->Username(); ?>');"><?php echo $s->Username(); ?></a>
        </td>
    </tr>
<?php
    $userstuff = UserAgentParse($curbug->UserAgent());
    foreach ($userstuff as $type => $value) {
        if ( $value != "UNKNOWN" ) {
            switch($type) {
                case "browser":
                    $val = "Browser";
                    break;
                case "browser_ver":
                    $val = "Browser Version";
                    break;
                case "os":
                    $val = "Operating System";
                    break;
                case "language":
                    $val = "Language";
                    $value = ParseLangCode($value);
                    break;
                case "security":
                    $val = "Security";
                    break;
                case "platform":
                    $val = "Platform";
                    break;
            }
            ?>
            <tr>
                <td class="nfield"><b><?php
                    echo $val; 
                ?>:</b></td>
                <td class="nfield"><?php
                    if (file_exists("images/silk/browser_" . strtolower($value) . ".png")) {
                        img('images/silk/browser_' . strtolower($value) . '.png');
                        echo " ";
                    }
                    echo $value;
                ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </table>
</div>
<br /><br />
<?php
    if ( $curbug->FixedBy() == 0 ) {
        ?>Do you think that you've fixed it? <a href="javascript:dm('bugreporting/bug/fix&bugid=<?php echo $bugid; ?>&bugfix=1');">Mark</a> it.<?php
    }
    else {
        ?>This bug is marked as fixed. If you think that it is not fixed, <a href="javascript:dm('bugreporting/bug/fix&bugid=<?php echo $bugid; ?>&bugfix=0');">Unmark</a> it.<?php
    }
?>
<br />
<?php img('images/nuvola/back.png'); ?> <a href="javascript:dm('bugreporting/buglist');">Go back</a>
<?php
    bfc_start();
?>
    et("Bug: <?php echo $curbug->name(); ?>");
<?php
    bfc_end();
?>