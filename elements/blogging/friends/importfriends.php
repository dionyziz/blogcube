<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    bfc_start();
    ?>et( "Import Friends" );
    g("user").focus();<?php
    bfc_end();

    h3( "Import Friends" );
    
    ?><h4>Warning: Still expiremental! Try it out, though ;-)</h4><br /> <br /><?php

    echo $arrow;
    ?><a href="javascript:toggle_wind_gmail_easy()">Gmail easy import</a><br />
<div id="wind_gmail_easy" style="display:">
    <br />
    <form id="geasy_form" onSubmit="void(wind_geasy_submit());return false;">
    <table class="formtable">
        <tr>
            <td class="ffield"><strong>Gmail username:</strong></td>
            <td class="ffield"><input type="text" class="inbt" id="user" onKeyPress="if(event.keyCode==13){g('pass').select();g('pass').focus;};" /></td>
        </tr>
        <tr>
            <td class="nfield"><strong>Gmail password:</strong></td>
            <td class="nfield"><input type="password" class="inbt" id="pass" onKeyPress="if(event.keyCode==13){wind_geasy_submit();}" /></td>
            <td>&nbsp;<?php echo $arrow; ?><a href="javascript:wind_geasy_submit();">Import</a></td>
        </tr>
    </table>
    </form>
</div>
<br />
<?php
    echo $arrow;
    ?><a href="javascript:toggle_wind_gmail_csv()">Gmail CSV import</a><br />
<div id="wind_gmail_csv" style="display:none; position:relative;">
    <br />
    <table class="formtable">
        <tr>
            <td class="ffield"><strong>Copy the contents of the gmail-type CSV:</strong><br />
                <div style="position:absolute; bottom:0em; left:10em;"><?php echo $arrow; ?><a id="gcsv" href="javascript:de('wind','blogging/friends/import_do&csv='+safeencode(g('csv').value)+'&type=gcsv','');">Import</a></div>
            </td>
            <td class="nfield"><textarea class="inbt" name="csv" id="csv" cols=60 rows=8 onBlur="javascript:g('gcsv').focus();"></textarea></td>
        </tr>
    </table>
</div>
<br /><br />
<div id="wind"></div>