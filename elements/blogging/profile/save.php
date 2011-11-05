<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $fieldname = $_POST[ "fieldname" ];
    $fieldvalue = $_POST[ "fieldvalue" ];
    $fieldvalue = safedecode( $fieldvalue );
    switch ( $fieldname ) {
        case "profsave_firstname":
            $user->SetFirstName( $fieldvalue );
            $check = true;
            break;
        case "profsave_lastname":
            $user->SetLastName( $fieldvalue );
            $check = true;
            break;
        case "profsave_gender":
            $user->SetGender( $fieldvalue );
            $check = true;
            break;
        case "profsave_dob":
            $user->SetDob( $fieldvalue );
            $check = true;
            break;
        case "publicprofsave_birthday":
            $user->SetPublicBirthday( $fieldvalue );
            $check = true;
            break;
        case "publicprofsave_age":
            $user->SetPublicAge( $fieldvalue );
            $check = true;
            break;
        case "profsave_msn":
            //bug-fixed: not saving msn
            if ( ValidEmail( $fieldvalue ) || $fieldvalue == "" ) {
                $check = true;
                $user->SetMsn( $fieldvalue );
            }
            break;
        case "publicprofsave_msn":
            $user->SetPublicMsn( $fieldvalue );
            $check = true;
            break;
        case "profsave_yahoo":
            $pos = strpos( $fieldvalue, "@");
            if ( !$pos || $fieldvalue == "" ) {
                $check = true;
                $user->SetYahoo( $fieldvalue );
            }
            break;
        case "publicprofsave_yahoo":
            $check = true;
            $user->SetPublicYahoo( $fieldvalue );
            break;
        case "profsave_icq":
                $check = true;
                $user->SetIcq( $fieldvalue );
            break;
        case "publicprofsave_icq":
            $user->SetPublicIcq( $fieldvalue );
            $check = true;
            break;
        case "profsave_jabber":
            if ( ValidEmail( $fieldvalue ) || $fieldvalue == "" ) {
                $check = true;
                $user->SetJabber( $fieldvalue );
            }
            break;
        case "publicprofsave_jabber":
            $user->SetPublicJabber( $fieldvalue );
            $check = true;
            break;
        case "profsave_gtalk":
            $etposition = strrpos( $fieldvalue , "@");
            $length = strlen( $fieldvalue );
            $pos = substr( $fieldvalue , $etposition , $length );                
            if ( $pos == "@gmail.com" || $pos == "@googlemail.com" || $fieldvalue == "" ) {
                $check = true;
                $user->SetGtalk( $fieldvalue );
            }
            else {
                $error = "GTalk contacts should end with \"@gmail.com\" or \"@googlemail.com\"";
            }
            break;
        case "publicprofsave_gtalk":
            $user->SetPublicGtalk( $fieldvalue );
            $check = true;
            break;
        case "profsave_aim":
            $check = true;
            $user->SetAim( $fieldvalue );
            break;
        case "publicprofsave_aim":
            $user->SetPublicAim( $fieldvalue );
            $check = true;
            break;
        case "profsave_movie":
            $user->SetFavoriteMovie( $fieldvalue );
            $check = true;
            break;
        case "profsave_band":
            $user->SetFavoriteBand( $fieldvalue );
            $check = true;
            break;
        case "profsave_music":
            $user->SetFavoriteMusicGenre( $fieldvalue );
            $check = true;
            break;
        case "profsave_quote":
            $user->SetFavoriteQuote( $fieldvalue );
            $check = true;
            break;
        case "profsave_game":
            $user->SetFavoriteGame( $fieldvalue );
            $check = true;
            break;
        case "profsave_haircolor":
            $user->SetHair( $fieldvalue );
            $check = true;
            break;
        case "profsave_eyecolor":
            $user->SetEyes( $fieldvalue );
            $check = true;
            break;
        case "profsave_email":
            if ( ValidEmail( $fieldvalue ) ) {
                $check = true;
                $user->SetEmail( $fieldvalue );
            }
            break;
        case "publicprofsave_email":
            $user->SetPublicEmail( $fieldvalue );
            $check = true;
            break;
        case "profsave_website":
            $firstlet = substr( $fieldvalue , 0 , 7 );
            if ( $firstlet != "http://" ) {
                $fieldvalue = "http://".$fieldvalue;
            }
            $user->SetWebsite( $fieldvalue );
            $check = true;            
            break;
        case "profsave_aboutme":
            $check = true;
            $user->SetAboutMe( $fieldvalue );
    }
?>
<?php if ( $check ) {
?>
<div id="profsaved_<?php
    echo $fieldname;
?>"><?php
img( "images/nuvola/done.png" );
?> Saved</div>
<?php
    bfc_start();
?>
setTimeout('fadeout("<?php
echo "profsaved_";
echo $fieldname;
?>");',2000);
<?php
    bfc_end();
?>
<?php 
    }
else {
?>
<div id="profsaved_<?php
    echo $fieldname;
?>"><?php
img( "images/nuvola/error.png" );
?> <?php echo $error;?></div>
<?php
    bfc_start();
?>
setTimeout('fadeout("<?php
echo "profsaved_";
echo $fieldname;
?>");',2000);
<?php
    bfc_end();
?>
<?php 
    }