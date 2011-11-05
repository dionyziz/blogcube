<?php
    // this is included at the end of an element
    // make final checks that everything is okay
    // before sending stuff to the client

    // we should make sure the bfc element
    // has been closed (see bfc_end() function for more info)
    if ( $inbfc ) {
        die( "<b>Warning</b>: In " . $id . ": Finishing without leaving BFC!" );
    }
?>