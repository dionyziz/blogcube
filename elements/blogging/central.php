<div width="760" id="centraltable">
    <table id="topbartbl" style="width:760px">
        <tr>
            <td class="topbar" height="24">
                <div id="topbar">
                </div>
            </td>
            <td class="topbarright">
                <div id="topbarright">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <small>
                    <div id="topbar_info" style="visibility:hidden">Bq1</div>
                </small>
            </td>
        </tr>
    </table>
    
    <br /><br />
    
    <table style="width:760px" id="cubelogo">
        <tr>
            <td style="width:760px;text-align:right">
                <div id="uncertain_position"></div>
                <a href="<?php echo $systemurl; ?>"><img src="images/bcuben.jpg" title="BlogCube" alt="BlogCube" /></a>
            </td>
        </tr>
    </table>
    
    <br />

    <div class="mainblog" id="main"><br />
    </div>
    
    <br />

    <div id="footrd" style="width:760px;margin:auto">
        <span id="footrid">Copyright &#169; 2005 - 2006, BlogCube Development Team. All rights reserved. <?php
            // show the SVN revision
            ?><span style="color:#DFDFDF"><?php
            if ( isset($revision) ) {
                if ( !$debug_version ) {
                    echo 'PARIS/' . $revision;
                }
                else {
                    echo 'SANDBOX/' . $revision;
                }
            }
            else {
                echo 'Paris';
            }
            ?></span><?php
            if( $debug_version ) {
                ?><a id="designmodego" href="javascript:developerwysiwyg()" style="display:none"><?php
                img( "images/coi/linetoolbox.png" , "Design Mode" , "Jump to Design Mode" );
                ?></a>
                <div id="designmodewarn" style="display:none;color:black;">
                    <br />You're now testing layouts in <b>design mode</b>. <br />
                    This does not affect BlogCube in any way. Refresh to go back to normal mode.
                </div><?php
            }
        ?></span>
    </div>
</div>
<?php 
    $bfc->start(); 
?>
donofade = true;
if( !aftermatch2 ) {
    oldhash = '';
    Fire_Handle();
}
else {
    dm( aftermatch2 );
    aftermatch2 = "";
}
de2( "topbar" , "blogging/topbar" , {} , "-" );
de2( "topbarright" , "blogging/topbarright" , {} , "-" );
<?php 
    $bfc->end();
?>