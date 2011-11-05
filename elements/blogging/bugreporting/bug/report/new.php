<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "Report a Bug" , "bug64" );
    $userinfo = UserAgentParse( $_SERVER[ 'HTTP_USER_AGENT' ] );
    $useros = $userinfo[ "os" ];
    $userplatform = $userinfo[ "platform" ];
    $userbrowser = $userinfo[ "browser" ];
    $userbrowser_ver = $userinfo [ "browser_ver" ];
    $bug_title = safedecode( $_POST[ "bug_title" ] );
    $bug_desc = safedecode( $_POST[ "bug_desc" ] );
?>
<table width="100%">
    <tr>
        <td class="ffield">A short, but descriptive <b>title</b>:</td>
        <td class="ffield"><input type="text" value="<?php 
        if ( isset( $_POST[ "bug_title" ] ) ) {
            echo $bug_title; 
        } 
        ?>" id ="bug_title" size="65" class="inpt"></td>
    </tr>
    <tr>
        <td class="nfield">Your <b>Web Browser</b>:</td>
        <td class="nfield">
            <select id="bug_browser" onchange="bug_osbox();" style="padding-left:18px;background-repeat:no-repeat;background-position:left;background-position:left;background-image:url('images/silk/browser_<?php
                echo strtolower($userbrowser);
            ?>.png');">
                <?php
                    $browser_list = array(
                        "aol" => "AOL",
                        "camino" => "Camino",
                        "firefox" => "FireFox",
                        "galeon" => "Galeon",
                        "internetexplorer" => "Internet Explorer",
                        "konqueror" => "Konqueror",
                        "mozilla" => "Mozilla",
                        "msnexplorer" => "MSN Explorer",
                        "netscape" => "Netscape",
                        "omniweb" => "Omniweb",
                        "opera" => "Opera",
                        "safari" => "Safari",
                        "shiira" => "Shiira",
                        "sunrise" => "Sunrise",
                        "other" => "Other..."
                    );
                    $browser_ids = array_keys($browser_list);
                    for ($i=0;$i<count($browser_list);$i++) {
                        ?><option value="<?php
                            echo $browser_ids[$i];
                        ?>" style="font-weight:<?php
                            if ( $i == count($browser_list)-1 ) {
                                ?>bold;<?php
                            }
                            else {
                                ?>normal;<?php
                            }
                        ?>background-image:url('images/silk/browser_<?php
                            echo $browser_ids[$i];
                        ?>.png');background-position:left;background-repeat:no-repeat;padding-left:18px;" <?php
                        if ( strtolower($userbrowser) == $browser_ids[$i] ) {
                            ?>selected="selected" <?php
                        }
                        ?>><b><?php
                            echo $browser_list[$browser_ids[$i]];
                        ?></b></option>
                        <?php
                    }
                ?>
            </select>
            <div id="bug_browser_version" class="inline">
                Version: <input id="bug_browser_ver" value="<?php
                    echo $userbrowser_ver;
                ?>" size="10" class="inpt" />
            </div>
            <div id="bug_browser_specify" style="display: none;" class="inline">
                Specify: <input id="bug_browser_spec" size="37" class="inpt" />
            </div
    </tr>
    <tr>
        <td class="nfield">Your <b>Operating System</b>:</td>
        <td class="nfield">
            <select id="bug_os" onchange="bug_os_check();" >
                <option value="WIN2003" style="background-image:url('images/os/win.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;">Windows 2003</option>
                <option value="WINXP" style="background-image:url('images/os/win.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;" <?php 
                    if ( $useros == "Windows XP" ) { 
                        ?>selected<?php 
                    } 
                ?>>Windows XP</option>
                <option style="background-image:url('images/os/win.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;" value="WIN2000" <?php 
                    if ( $useros == "Windows 2000" ) { 
                        ?>selected<?php 
                    } 
                ?>>Windows 2000</option>
                <option style="background-image:url('images/os/win.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;" value="WINME" <?php 
                    if ( $useros == "Windows ME" ) { 
                        ?>selected<?php 
                    } 
                ?>>Windows Me</option>
                <option value="WIN98" style="background-image:url('images/os/win.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;" <?php 
                    if ( $useros == "Windows 98" ) { 
                        ?>selected<?php 
                    } 
                ?>>Windows 98</option>
                <option value="LINUX" style="background-image:url('images/os/tux.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;" <?php 
                    if ( $userplatform == "Linux" ) { 
                        ?>selected<?php 
                    }
                ?>>Linux</option>
                <option value="BSD" style="background-image:url('images/os/freebsd.png'); background-position:left; background-repeat:no-repeat;padding-left:18px;">BSD</option>
                <option value="UNIX">Unix</option>
                <option value="MACOS" <?php 
                    if ( $userplatform == "Macintosh" ) { 
                        ?>selected<?php
                    } 
                ?>>MacOS</option>
            </select>
            <div id="bug_linux_prop" style="display:none">
                <br /><?php
                    img( "images/nuvoe/tux22.png" , "Linux" , "Tux, the Linux Penguin" );
                ?> Please specify your distribution and kernel version, if you know it.<br /><br />
                <table class="intracted">
                    <tr>
                        <td class="ffield"><b>Distribution</b> (e.g. "Mandriva 2006"):</td><td class="ffield">
                            <input type="text" id="bug_linux_distro" class="inpt" />
                        </td>
                    </tr>
                    <tr>
                        <td class="nfield"><b>Kernel Version</b> (e.g. "2.6"):</td><td class="nfield">
                            <input type="text" id="bug_linux_kernel_version" class="inpt" />
                        </td>
                    </tr>
                </table>
            </div>
            <div id="bug_unix_prop" style="display:none">
                <br />Please specify the type of your Unix system and your kernel version, if applicable.<br /><br />
                <table class="intracted">
                    <tr>
                        <td class="ffield fintracted"><b>Unix Type</b> (e.g. "Solaris 10"):</td><td class="ffield">
                            <input type="text" id="bug_unix_type" class="inpt" />
                        </td>
                    </tr>
                    <tr>
                        <td class="nfield fintracted"><b>Kernel Version</b> (e.g. "2.6"):</td><td class="nfield">
                            <input type="text" id="bug_unix_kernel_version" class="inpt" />
                        </td>
                    </tr>
                </table>
            </div>
            <div id="bug_bsd_prop" style="display:none">
                <br /><?php
                    img( "images/nuvoe/freebsd22.png" , "FreeBSD" , "BSD Daemon" );
                ?> Please specify the type and version of your BSD system and your kernel version, if applicable.<br /><br />
                <table class="intracted">
                    <tr>
                        <td class="ffield fintracted"><b>BSD Type</b> and <b>Version</b> (e.g. FreeBSD 5.3):</td><td class="ffield">
                            <input type="text" id="bug_bsd_type" class="inpt" />
                        </td>
                    </tr>
                        <td class="nfield fintracted"><b>Kernel Version</b> (e.g. "2.6"):</td><td class="nfield">
                            <input type="text" id="bug_bsd_kernel_version" class="inpt" />
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td class="nfield">Detailed <b>Description</b>:</td>
        <td class="nfield"><textarea id="bug_desc" cols="63" rows="10"><?php 
            if ( isset( $_POST[ "bug_desc" ] ) ) 
                echo safedecode( $_POST[ "bug_desc" ] ); 
        ?></textarea></td>
    </tr>
</table>
<table width="100%">
    <td style="width:58%;">
    <div id="bug_status">
    </td>
    <td align="right">
        <?php
            img( "images/nuvola/bug.png" );
        ?> <a href="javascript:bug_report();">Report</a>&nbsp;&nbsp;
        <?php
            img( "images/nuvola/discard.png" );
        ?> <a href="javascript:dm('hola');">Cancel</a>
    </td>
</table>
<?php
    bfc_start();
?>
et( "Report a Bug" );
<?php
    bfc_end();
?>