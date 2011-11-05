<?php
    include "elements/element_developer.php";

$bfc->start();
echo "cacheable = false;";
$bfc->end();

    $i = $_POST[ "i" ];
    if( $i == 0 ) {
        $folder = New DocFolder();
    } else {
        $folder = New DocFolder( $i );
    }
?>

<div id="helpnewsubfolder">
    <?php h4( "Create Subfolder" ); ?>
    <table class="formtable">
        <tr><td class="ffield"><b>Panrent Folder Name:</b> </td><td class="ffield"><span id="helpnewsubfolder/foldername"><?php echo $folder->FolderName(); ?></span></td></tr>
        <tr><td class="ffield"><b>Parent Folder Id:</b> </td><td class="ffield"><span id="helpnewsubfolder/folderid"><?php echo $folder->FolderID(); ?></span></td></tr>
        <tr><td class="ffield"><b>Folder Key:</b> </td><td class="ffield"><input id="helpnewsubfolder/key" type="text" class="inbt" /></td></tr>
        <tr><td class="ffield"><b>Folder Name:</b> </td><td class="ffield"><input id="helpnewsubfolder/name" type="text" class="inbt" /></td></tr>
    </table>
    <a href="javascript:HelpNewSubfolder()">Create Subfolder</a>
</div>

<div id="helpnewsubfolderresult">
</div>