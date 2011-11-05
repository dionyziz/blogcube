<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if( avatar_set_inactive_by_mediaid( $_POST[ "mediaid" ] ) ) {
        ?>Deleted<?php
    }
    else {
        ?>Failed<?php
    }
?>