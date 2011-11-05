<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

$bfc->start();
echo "cacheable = false;";
$bfc->end();

    //$k = gui_window( "New Item" , "This is my first BlogCube window!" , "helphidden" );
    //bfc_start();
    //echo $k;
    //bfc_end();
    $i = $_POST[ "i" ];
    if( $i == 0 ) {
        $folder = New DocFolder();
    } else {
        $folder = New DocFolder( $i );
    }
?>

<div id="helpnewitem">
    <?php h4( "Create Item" ); ?>
    <table class="formtable">
        <tr><td class="ffield"><b>Folder Name:</b> </td><td class="ffield"><span id="helpnewitem/foldername"><?php echo $folder->FolderName(); ?></span></td></tr>
        <tr><td class="ffield"><b>Folder Id:</b> </td><td class="ffield"><span id="helpnewitem/folderid"><?php echo $folder->FolderID(); ?></span></td></tr>
        <tr><td class="ffield"><b>Itemkey:</b> </td><td class="ffield"><input id="helpnewitem/key" type="text" class="inbt" /></td></tr>
        <tr><td class="ffield"><b>Itemtitle:</b> </td><td class="ffield"><input id="helpnewitem/title" type="text" class="inbt" /></td></tr>
        <tr><td class="ffield"><b>Itemtext:</b> </td><td class="ffield"><input id="helpnewitem/text" type="text" class="inbt" /></td></tr>
    </table>
    <a href="javascript:HelpNewItem()">Create Item</a>
</div>

<div id="helpnewitemresult">
</div>