<?php
    if ( !Element( "element_admin" ) ) {
        return false;
    }

    if ( !ValidNNI( $_POST['start'] ) ) { $_POST['start'] = 0; } //TODO: use another variable
    $users = GetUsers( $_POST['start'] );
    for( $x = 0; $x < min($users->Length(), 20); $x++ ) {
        $user = $users->User( $x );
        echo "<div>" . $user->Id() . " - " . $user->Username() . "</div>";
    }
?>
<div style="text-align:right">
<?php
if( $users->Length() == "21" ) {
    $newstart = $_POST['start'] + 20;
    ?><a href="javascript:dm( 'admin/userlist&start=<?php
    echo $newstart; 
    ?>' );">Next Users <?php
    img( 'images/silk/forward.png' , 'Forward' , 'Next Users' );
    ?></a>
    <?php
}
?>
</div>
<div style="float:left;">
<?php
if( $_POST['start'] > 0 ) {
    $newstart = $_POST['start'] - 20;
    if ( $newstart < 0 ) { $newstart = 0; }
    ?><a href="javascript:dm( 'admin/userlist&start=<?php
    echo $newstart; ?>' );"><?php
    img( 'images/silk/back.png' , 'Back' , 'Previous Users' );
    ?> Previous Users</a><?php
}
?>
</div>