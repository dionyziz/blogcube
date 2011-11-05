<?php
    $unsupportedbrowser = $unsupportedversion = false;
    $userbrowser = UserBrowser();
    $userbrowserversion = UserBrowserVersion();
    if( isset( $_GET[ "browsercheck" ] ) ) {
        $browsercheck = $_GET[ "browsercheck" ];
    }
    if( $browsercheck != "none" ) {
        if( $userbrowser == "MSIE" ) {
            if ( $userbrowserversion < "6.0" ) {
                // Internet Explorer 6.0
                $unsupportedversion = true;
            }
        }
        elseif( $userbrowser == "FIREFOX" ) { 
            // FireFox any version
        }
        elseif ( $userbrowser == "OPERA" ) {
            if ( $userbrowserversion < "9.00" ) {
                // Opera 9.0
                $unsupportedversion = true;
            }
        }
        else {
            $unsupportedbrowser = true;
        }
        if ( $unsupportedversion === true || $unsupportedbrowser === true ) {
            ?><div id="globalwarning">
                <div style="float:right">
                    <a href="" onclick="g('globalwarning').style.display='none';return false;" style="text-decoration:none">&times;</a>
                </div>
                <img src="images/warning_solid.jpg" alt="Warning" />
                Your version of browser is not supported. <small style="font-size: 80%;"><?php
                echo $userbrowser;
                ?> <?php
                    echo $userbrowserversion;
                ?></small>
            </div>
            <div>
                <br />
            </div><?php
        }
        if ( $unsupportedversion === true ) {
            ?><div id="nobrowser" style="width:100%">
                <br /><?php
                h3( "Your version of browser is not supported" );
                ?><br />
                We're sorry, but the version of the browser that you are currently using is not supported.<br />
                Please go to <a href="<?php
                    switch ($userbrowser) {
                        case "MSIE":
                            ?>http://www.microsoft.com/windows/ie/">Internet Explorer<?php
                            break;
                        case "OPERA":
                            ?>http://www.opera.com/download/">Opera<?php
                    }
                ?>download center</a> and download the latest version.
            </div><?php
        }
        else if ( $unsupportedbrowser === true ) {
            ?><div id="nobrowser" style="width:100%">
                <br /><?php
                    h3( "Your browser is not supported" );
                ?><br />
                We're sorry, but your browser is unfortunately not yet supported by BlogCube.<br />
                Please use one of the supported browsers, <a href="http://www.mozilla.org/">FireFox</a>, <a href="http://www.microsoft.com/windows/ie/">Internet Explorer 6.0</a> or <a href="http://www.opera.com/download">Opera 9.0</a>.<br />
                Or you can <a href="javascript:void(g('nobrowser').style.display='none'+(g('browser_tryanyway').style.display=''));">try anyway</a>.
              </div><?php
        }
    }
    if (( $unsupportedbrowser === true ) || ( $unsupportedversion == true )) {
        ?><div id="browser_tryanyway" style="display:none" class="inline"><?php
        // </div> in browsercheck_end.php
    }
?>