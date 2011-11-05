<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

    //bug name
    if ( isset($_POST['bug_name']) ) {
        $bug_search_name = safedecode($_POST['bug_name']);
    }
    else {
        $bug_search_name = "";
    }
    //bug page
    if ( isset($_POST['bug_page']) ) {
        $bug_page = safedecode($_POST['bug_page']);
        if ( $bug_page <= 0  ) { $bug_page = 1; }
    }
    else {
        $bug_page = 1;
    }
    //bug highlight
    if ( isset($_POST['bug_highlight']) ) {
        $bug_highlight = $_POST['bug_highlight'];
    }
    //bug fixedby
    if ( isset($_POST['bug_fixedby']) ) {
        $bug_search_fixedby = $_POST['bug_fixedby'];
    }
    else {
        if ( isset($_POST['bug_fixedby_n']) ) {
            $bfb = GetUserByUsername(safedecode($_POST['bug_fixedby_n']));
            if ( !$bfb ) $bug_search_fixedby = -1;
            else $bug_search_fixedby = $bfb->Id();
        }
        else {
            $bug_search_fixedby = -1;
        }
    }
    //bug datefrom
    if ( isset($_POST['bug_datefrom']) ) {
        $bug_search_datefrom = $_POST['bug_datefrom'];
    }
    else {
        $bug_search_datefrom = "";
    }
    //bug dateuntil
    if ( isset($_POST['bug_dateuntil']) ) {
        $bug_search_dateuntil = $_POST['bug_dateuntil'];
    }
    else {
        $bug_search_dateuntil = "";
    }
    //$bug_search_datefrom = "2006-03-20 00:00:00";

    echo CountBugs(); ?> bugs posted so far!
<br /><br />
Search for bugs concerning: 
<input type="text" onblur="bug_search();" class="inbt" id="bug_search_name" <?php
    if ($bug_search_name != "" ) {
        ?>value="<?php 
        echo $bug_search_name; 
        ?>" <?php
    }
?>/>&nbsp;<a href="javascript:bug_search();">Search</a> <a href="javascript:de('tempbuglist','blogging/bugreporting/advsearch');">Advanced Search</a>

<table width="100%">
<tr>
    <td><b>Title</b></td>
    <td align="center"><b>Fixed</b></td>
</tr>
<?php
    $search_result = BugSearch(0,$bug_search_name,"","","","",0,"","","",$bug_search_datefrom,$bug_search_dateuntil,$bug_search_fixedby,($bug_page-1)*20,20);
    while ($curbug = bug_retrieve($search_result)) {
        if (( isset($bug_highlight) ) && ( $curbug->Id() == $bug_highlight )) {
            $bugcolor = "#F5F3B4"; // yellow (highlight)
        }
        else if ( $curbug->FixedBy() == 0 ) {
            $bugcolor = "#DE9F96"; //red
        }
        else {
            $bugcolor = "#D8F0A6"; //green
        }
        ?>
        <tr style="background-color:<?php 
            echo $bugcolor; 
            ?>;">
            <td>
                <a href="javascript:dm('bugreporting/bug/view&bugid=<?php 
                echo $curbug->Id(); 
                ?>');"><?php 
                echo $curbug->Name(); 
                ?></a>
            </td>
            <td align="center">
                <?php
                    if ( $curbug->FixedBy() == 0 ) {
                        echo "No";
                    }
                    else {
                        $curuser = New User($curbug->FixedBy());
                        $curusername = $curuser->Username();
                        ?><a href="javascript:dm('profile/profile_view&user=<?php 
                        echo $curusername; 
                        ?>');"><?php 
                        echo $curusername; 
                        ?></a><?php
                    }
                ?>
            </td>
        </tr>
        <?php
    }
?>
</table>
<?php
    if ( ceil($bugcount/20) > 1 ) {
        ?>Jump to page:<?php
    }
?>
<?php
    $search_result = BugSearch(0,$bug_search_name,"","","","",0,"","","",$bug_search_datefrom,$bug_search_dateuntil,$bug_search_fixedby);
    $bugcount = CountRetrievedBugs($search_result);
    if ( ceil($bugcount/20) > 1 ) {
        for ($i=1;$i<=ceil($bugcount/20);$i++) {
            if ( $bug_page != $i ) {
                ?><a href="javascript:de('tempbuglist','blogging/bugreporting/buglist&bug_name=<?php
                echo $bug_search_name;
                ?>&bug_page=<?php
                echo $i;
                ?>&bug_fixedby=<?php
                echo $bug_search_fixedby;
                ?>&bug_datefrom=<?php
                echo $bug_search_datefrom;
                ?>&bug_dateuntil=<?php
                echo $bug_search_dateuntil;
                ?>');"><?php
            }
            img( "img.png.bc?id=pages&n=" . $i , "Page " . $i , "Jump to Page " . $i , 16 , 16 );
            if ( $bugpage != $i ) echo "</a>";
            echo " ";
        }
    }
?>
<br />
<?php 
echo $arrow; 
?><a href="javascript:de('tempbuglist','blogging/bugreporting/buglist&bug_fixedby=<?php 
echo $user->Id(); 
?>')">Views bugs that I have fixed</a><br /><?php 
echo $arrow; 
?><a href="javascript:de('tempbuglist','blogging/bugreporting/buglist&bug_fixedby=0')">View all unfixed bugs</a><br /><?php 
echo $arrow; 
?><a href="javascript:de('tempbuglist','blogging/bugreporting/buglist&bug_datefrom=<?php 
echo NowSolDate() . " 00:00"; 
?>&bug_dateuntil=<?php 
echo NowSolDate() . " 23:59"; 
?>');">View bugs that are submitted today</a><br /><?php
    bfc_start();
?>
    cacheable = false;
    fade0("buglist");
    g("buglist").innerHTML = g("tempbuglist").innerHTML;
    fadein("buglist");
<?php
    bfc_end();
?>