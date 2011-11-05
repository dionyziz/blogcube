<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

$bfc->start();
echo "cacheable = false;";
$bfc->end();
    
    h3( "Documentation" );
    
    function TravelFolder( $folder ) {
        // global $popup;
        $popup = PopupMenu( Array(
            Array( "Create Item" , "javascript:de('helphidden','blogging/help/item/new&i=" . $folder->FolderID() . "');" ) ,
            Array( "Create Subfolder" , "javascript:de('helphidden','blogging/help/subfolder/new&i=" . $folder->FolderID() . "');" ) ,
            Array( "Delete Folder" , "#" )
            ) );
        ?><br /><table><tr><td><?php
        img( "images/nuvola/dfolder.png" );
        ?></td><td><a href="#" <?php
        PopupAnchor( $popup );
        ?>><?php
        echo $folder->FolderName();
        ?></a></td></tr></table><blockquote><?php
        while( $newkey = $folder->GetFolder() ) {
            TravelFolder( New DocFolder( $newkey ) );
        }
        ?></blockquote><?php
    }

    // $popup = PopupMenu( Array(
    //    Array( "Create Item" , "javascript:alert('bla');" ) , //void(g('helpnewitem').style.display='')
    //    Array( "Create Subfolder" , "#" ) ,
    //    Array( "Delete Folder" , "#" )
    //    ) );
        
    TravelFolder( New DocFolder() );
    
    bfc_start();
    ?>et( "Documentation Management" );<?php
    bfc_end();
?>
<div id="helphidden">
</div>
