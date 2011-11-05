<?php
    $n = $_POST[ "n" ];
    
    $theuser = GetUserByUserName( $n );
    $uservalid = ValidUname( $n );
    if( $uservalid === -1 ) {
        // this should never happen, js check takes place before this
        img( "images/nuvola/error.png" );
        ?> Invalid<?php
    }
    else if( $uservalid === -2 ) {
        img( "images/nuvola/error.png" );
        ?> Reserved<?php
    }
    else if( $theuser === false ) {
        img( "images/nuvola/done.png" );
        ?> Available<?php
    }
    else {
        img( "images/nuvola/error.png" );
        ?> Taken<br /><?php
        $suggestion = GetUnameSuggestion( $n );
        if ( $suggestion !== false ) {
            ?><br />What do you think about <?php
            ?><a href="javascript:setValue('reg_uname','<?php 
            echo $suggestion; 
            ?>'); de('ureg_ca','blogging/accounts/unamecheck&n=' + g('reg_uname').value);"><?php 
            echo $suggestion; 
            ?></a>?<?php
        }
    }
?>