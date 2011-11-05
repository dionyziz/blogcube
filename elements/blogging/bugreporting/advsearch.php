<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }
?>
<table>
<tr>
<td class="ffield">Search for bugs concerning:</td>
<td class="ffield"><input id="bug_search_name" class="inbt" size="20" /></td>
</tr>
<tr>
<td class="nfield">Fixed by:</td>
<td class="nfield">
    <input id="bug_search_fixedby" class="inbt" size="20" />
    <br />
    <input id="bug_search_fixedbugs" type="checkbox" onclick="bug_fixedby_de();" />Show unfixed bugs only
</td>
</tr>
<?php
    for ($k=1;$k<=2;$k++) {
        ?><tr><td class="nfield"><?php
        if ( $k == 1 ) {
            echo "From:";
        }
        else {
            echo "To:";
        }
        ?></td>
        <td class="nfield">
        <select id="bug_search_<?php
            if ( $k == 1 ) {
                echo "from";
            }
            else {
                echo "to";
            }
        ?>day"><?php
            for ($i=1;$i<=31;$i++) {
                ?><option value="<?php
                    echo $i;
                ?>"<?php
                    if (( date(j) == $i ) && ( $k == 2 )) echo "selected";
                    else if (( $i == 1 ) && ( $k == 1 )) echo "selected";
                ?>><?php
                    echo $i;
                ?></option><?php
            }
        ?></select>
        <select id="bug_search_<?php
            if ( $k == 1 ) {
                echo "from";
            }
            else {
                echo "to";
            }
        ?>month"><?php
            for ($i=1;$i<=12;$i++) {
                ?><option value="<?php
                    echo $i;
                ?>"<?php
                    if (( date(n) == $i ) && ( $k == 2 )) echo "selected";
                    else if (( $i == 1 ) && ( $k == 1 )) echo "selected";
                ?>><?php
                    switch ($i) {
                        case 1:
                            echo "January"; break;
                        case 2:
                            echo "February"; break;
                        case 3:
                            echo "March"; break;
                        case 4:
                            echo "April"; break;
                        case 5:
                            echo "May"; break;
                        case 6:
                            echo "June"; break;
                        case 7:
                            echo "July"; break;
                        case 8:
                            echo "August"; break;
                        case 9:
                            echo "September"; break;
                        case 10:
                            echo "October"; break;
                        case 11:
                            echo "November"; break;
                        case 12:
                            echo "December";
                    }
                ?></option><?php
            }
        ?></select>
        <input id="bug_search_<?php
            if ( $k == 1 ) {
                echo "from";
            }
            else {
                echo "to";
            }
        ?>year" class="inbt" size="4" maxlength="4" value="<?php
            if ( $k == 1 ) {
                echo "2006";
            }
            else {
                echo date("Y");
            }
        ?>" />
        </td>
        </tr><?php
    }
?>
<br />
<br /><a href="javascript:bug_advsearch();">Search</a>
<?php
    bfc_start();
?>
    cacheable = false;
    fade0("buglist");
    g("buglist").innerHTML = g("tempbuglist").innerHTML;
    fadein("buglist");
    g("tempbuglist").innerHTML = "";
<?php
    bfc_end();
?>