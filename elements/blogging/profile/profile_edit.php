<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $publ_icons = Array( "everyone" , "friendsoffriends" , "friends" , "meonly" );

    bfc_start()
?>
    et( "User configuration" );
<?php
    bfc_end();

    h3( "User configuration" , "profile64" );
    $user_firstname = $user->Firstname();
    $user_lastname = $user->Lastname();
    $user_gender = $user->Gender();
    $user_birthday = $user->Dob();
    $user_public_birday = $user->PublicBirthday();
    $user_public_age = $user->PublicAge();
    $user_website = $user->Website();
    $user_email = $user->Email();
    $user_msn = $user->Msn();
    $user_yahoo = $user->Yahoo();
    $user_icq = $user->Icq();
    $user_jabber = $user->Jabber();
    $user_gtalk = $user->Gtalk();
    $user_aim = $user->Aim();
    $user_public_email = $user->PublicEmail();
    $user_public_msn = $user->PublicMsn();
    $user_public_yahoo = $user->PublicYahoo();
    $user_public_icq = $user->PublicIcq();
    $user_public_jabber = $user->PublicJabber();
    $user_public_gtalk = $user->PublicGtalk();
    $user_public_aim = $user->PublicAim();
    $user_movie = $user->FavoriteMovie();
    $user_band = $user->FavoriteBand();
    $user_musicgenre = $user->FavoriteMusicGenre();
    $user_game = $user->FavoriteGame();
    $user_quote = $user->FavoriteQuote();
    $user_hair = $user->Hair();
    $user_eyecolor = $user->Eyes();
    $user_aboutme = $user->AboutMe();
    ParseSolDate( $user_birthday , $year , $month , $day );
            
    function PublicityStatus( $id , $value ) {
        global $publ_icons;
        $publ_stat = array ( "public" => "Everyone",
                            "ffriends" => "Friends of Friends",
                            "friends" => "Friends",
                            "private" => "Me only"
                            );
        $kp = array_keys( $publ_stat );
        
        $alloptions = "";
        $selectedopt = 0;
        for ( $i=0; $i<count( $publ_stat ); $i++ ) {
            $alloptions .= "<option value=\"" . $kp[ $i ] . "\"";
            if ( $value == $kp[ $i ] ) {
                $alloptions .= " selected=\"selected\"";
                $selectedopt = $i;
            }
            $alloptions .= " style=\"background-image:url('images/nuvoe/publicity_"
                        . $publ_icons[ $i ]
                        . ".png');background-position:left;background-repeat:no-repeat;padding-left:18px;\">" 
                        . $publ_stat[ $kp[ $i ] ] 
                        . "</option>";
        }
        ?>
        <select id="publicprofsave_<?php echo $id; ?>" onchange="UpdateField( '<?php echo $id; ?>' , '2' );" style="padding-left:18px;background-repeat:no-repeat;background-position:left;background-image:url('images/nuvoe/publicity_<?php
        echo $publ_icons[ $selectedopt ];
        ?>.png');">
        <?php
        echo $alloptions;
        ?></select><?php
    }
    echo $arrow; 
?><a href="javascript:profpersonal();">Personal information</a><br /><br />
<div id="personal_info" style="display:none">
<br />
<table class="formtable">
    <tr>
        <td class="ffield"><strong>Firstname:</strong></td>
        <td class="ffield"><input id="profsave_firstname" type="text" class="inbt" onfocus="prof_check( 'firstname' );" value="<?php echo $user_firstname; ?>" onblur="UpdateField( 'firstname' , '1' );" /></td>
        <td><div id="getelem_firstname"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>Lastname:</strong></td>
        <td class="nfield"><input id="profsave_lastname" type="text" class="inbt" onfocus="prof_check( 'lastname' );" value="<?php echo $user_lastname; ?>" onblur="UpdateField( 'lastname' , '1' );" /></td>
        <td><div id="getelem_lastname"></div></td>
    </tr>
    <!--Countries (under discussion) //-->
    <tr>
        <td class="nfield"><strong>Gender:</strong></td>
        <td class="nfield">
            <select id="profsave_gender" onchange="UpdateField( 'gender' , '1' );">
                <?php
                    $gender = array ( 1=>"male",
                                    2=>"female"
                                    );
                    for ( $i=1; $i<3; $i++ ) {
                    ?><option value="<?php echo $gender[ $i ]; ?>"
                    <?php 
                        if ( $user_gender == $gender[ $i ] ) {
                    ?> selected="selected"<?php } ?>>
                    <?php echo ucfirst( $gender[ $i ] ); ?></option>
                    <?php 
                        }
                    ?>
            </select>
        </td>
        <td><div id="getelem_gender"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>Date of birth:</strong></td>
        <td class="nfield">
            <table>
                <tr>
                    <td>
                        <select id="profsave_dob1" onchange="UpdateField( 'dob1' , '1' );">
                            <?php 
                                for( $i=1; $i<32; $i++ ) {
                            ?><option value="<?php 
                                                if ( $i<10 ) {
                                                    echo "0".$i;
                                                }
                                                else {
                                                    echo $i;
                                                }
                                            ?>" 
                            <?php if( $day == $i ) {
                            ?> selected="selected"<?php }
                            ?>>
                            <?php echo $i; ?></option>
                            <?php
                            }
                            ?>    
                        </select>
                    </td>
                    <td>
                        <select id="profsave_dob2" onchange="UpdateField( 'dob2' , '1' );">
                            <?php
                                $months = array( 1=>"January",
                                                2=>"February",
                                                3=>"March",
                                                4=>"April",
                                                5=>"May",
                                                6=>"June",
                                                7=>"July",
                                                8=>"August",
                                                9=>"September",
                                                10=>"October",
                                                11=>"November",
                                                12=>"December"
                                            ); 
                                for( $i=1; $i<13; $i++ ) {
                                    
                            ?>
                            <option value="<?php if ( $i<10 ) {
                                                    echo "0".$i;
                                                }
                                                else {
                                                    echo $i;
                                                }
                                            ?>" 
                            <?php 
                            /*if ( $month<10 ) {
                                $month = substr( $month , 1 , 2 );
                            }
                            */
                            if ( $month == $i ) {
                            ?> selected="selected"
                            <?php    
                            }
                            ?>>
                            <?php echo $months[ $i ]; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="profsave_dob3" onchange="UpdateField( 'dob3' , '1');"> 
                            <?php
                                for ( $i=1925; $i<=2005; $i++ ) {
                                ?>    
                            <option value="<?php echo $i; ?>"
                            <?php if( $year == $i ) {
                            ?> selected="selected"
                            <?php 
                                }
                            ?>>
                            <?php echo $i; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
            </table><br />
            <table>
                <tr><td><?php
                    $pub_img = "images/nuvola/publicity.png";
                    $pub_alt = "Publicity";
                    $pub_tit = "Publicity Status - Choose who you want to be able to view this info";
                    img( $pub_img , $pub_alt , $pub_tit );
                ?> Birthday</td><td><?php
                    img( $pub_img , $pub_alt , $pub_tit );
                ?> Age</td></tr>
                <tr><td><?php 
                    PublicityStatus ( "birthday" , $user_public_birday );
                ?></td><td><?php 
                    PublicityStatus ( "age" , $user_public_age ); 
                ?></td></tr>
            </table>
        </td>
        <td><div id="getelem_dob"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>About me:</strong></td>
        <td class="nfield"><textarea id="profsave_aboutme" wrap="hard" rows="7" cols="25" onfocus="prof_check( 'aboutme' );" onblur="UpdateField( 'aboutme' );" style="width:100%"><?php echo $user_aboutme; ?></textarea></td>
        <td><div id="getelem_aboutme"></div></td>
    </tr>
</table>
<br />
</div>    
<?php echo $arrow; ?><a href="javascript:profcontacts();">Contact information</a><br /><br />
<div id="contacts" style="display:none">
<br />
<table class="formtable">
    <tr>
        <td class="ffield"><?php
        img( "images/nuvola/email.png" , "E-mail" , "E-mail" );
        ?></td>
        <td class="ffield"><input id="profsave_email" onfocus="prof_check( 'email' );" onblur="UpdateField( 'email' , '1' );" type="text" class="inbt" value="<?php echo $user_email; ?>" /></td>
        <td class="ffield">
            <?php 
            img( $pub_img , $pub_alt , $pub_tit );
            ?> <?php
            PublicityStatus ( "email" , $user_public_email );
            ?>
        </td>    
        <td><div id="getelem_email"></div></td>
    </tr><?php
    
    function ProfileIM( $imagealt , $imagetitle, $imvar, $pubvar, $pub_img , $pub_alt , $pub_tit ) {
        // construct and send the necessary HTML code for instant messaging selection
        // for the profile, to the user
        
        $id = strtolower( $imagealt );
        ?><tr><td class="nfield"><?php
        img( "images/messenger/" . $id . ".png" , $imagealt , $imagetitle );
        ?></td><td class="nfield"><input type="text" id="profsave_<?php
        echo $id;
        ?>" onfocus="prof_check( '<?php 
        echo $id;
        ?>' );" onblur="UpdateField( '<?php
        echo $id;
        ?>' , '1' );" class="inbt" value="<?php
        //$imvar = "user_" . $id;
        //global $$imvar;
        echo $imvar;
        ?>"    /></td><td class="nfield"><?php
        //$pubvar = "user_public_" . $id;
        //global $$pubvar;
        img( $pub_img , $pub_alt , $pub_tit );
        ?> <?php
        PublicityStatus( $id , $pubvar );
        ?></td><td><div id="getelem_<?php
        echo $id;
        ?>"></div></td></tr><?php
    }
    
    ProfileIM( "MSN" , "MSN Messenger Passport", $user_msn, $user_publicmsn, $pub_img , $pub_alt , $pub_tit );
    ProfileIM( "Yahoo" , "Yahoo! Messenger ID", $user_yahoo, $user_publicyahoo, $pub_img , $pub_alt , $pub_tit );
    ProfileIM( "ICQ" , "ICQ Number", $user_icq, $user_publicicq, $pub_img , $pub_alt , $pub_tit );
    ProfileIM( "Jabber" , "Jabber Username", $user_jabber, $user_publicjabber, $pub_img , $pub_alt , $pub_tit );
    ProfileIM( "GTalk" , "Google Talk ID", $user_gtalk, $user_publicgtalk, $pub_img , $pub_alt , $pub_tit );
    ProfileIM( "AIM" , "AOL Instant Messenger ID", $user_aim, $user_publicaim, $pub_img , $pub_alt , $pub_tit );

    ?><tr>
        <td class="nfield"><strong>Website:</strong></td>
        <td class="nfield"><input id="profsave_website" onfocus="prof_check( 'website' );" type="text" class="inbt" value="<?php echo $user_website; ?>" onblur="UpdateField( 'website' , '1' );" /></td>
        <td class="nfield" />
        <td><div id="getelem_website"></div></td>
    </tr>    
</table>
<br />
</div>
<?php echo $arrow; ?><a href="javascript:proffavor();">Favourites & hobbies</a><br /><br />
<div id="favourites" style="display:none">    
<br />
<table class="formtable">
    <tr>    
        <td class="nfield"><strong>Favourite band:</strong></td>
        <td class="nfield"><input onfocus="prof_check( 'band' );" id="profsave_band" type="text" class="inbt" value="<?php echo $user_band; ?>" onblur="UpdateField( 'band' , '1' );" /></td>
        <td><div id="getelem_band"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>Favourite music genre:</strong></td>
        <td class="nfield"><input id="profsave_music" onfocus="prof_check( 'music' );" type="text" class="inbt" value="<?php echo $user_musicgenre; ?>" onblur="UpdateField( 'music' , '1' );" /></td>
        <td><div id="getelem_music"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>Favourite game:</strong></td>
        <td class="nfield"><input id="profsave_game" onfocus="prof_check( 'game' );" onblur="UpdateField( 'game' , '1' );" type="text" class="inbt" value="<?php echo $user_game; ?>"  /></td>
        <td><div id="getelem_game"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>Favourite quote:</strong></td>
        <td class="nfield"><input id="profsave_quote" onfocus="prof_check( 'quote' );" onblur="UpdateField( 'quote' , '1' );" type="text" class="inbt" value="<?php echo $user_quote; ?>"  /></td>
        <td><div id="getelem_quote"></div></td>
    </tr>
    <tr>
        <td class="nfield"><strong>Hair color:</strong></td>
        <td class="nfield">
            <select id="profsave_haircolor" onchange="UpdateField( 'haircolor' , '1' );">
                <?php 
                    $hair_color = array ( 1=>"blonde",
                                        2=>"brown",
                                        3=>"black",
                                        4=>"red",
                                        5=>"ginger",
                                        6=>"auburn",
                                        7=>"gray"
                                    );
                    for ( $i=1; $i<8; $i++ ) {
                ?>
                <option value="<?php echo $hair_color[ $i ]; ?>"
                <?php if ( $user_hair == $hair_color[ $i ] ) {
                ?> selected="selected"
                <?php     
                    }
                ?>>
                <?php echo ucfirst( $hair_color[ $i ] ); ?>
                </option>
                <?php 
                    }
                ?>
            </select>
        </td>    
        <td><div id="getelem_haircolor"></div></td>
    <tr>
        <td class="nfield"><strong>Eye color:</strong></td>
        <td class="nfield">
            <select id="profsave_eyecolor" onchange="UpdateField( 'eyecolor' , '1' );">
                <?php
                    $eye_color = array ( 1=>"blue",
                                        2=>"brown",
                                        3=>"green",
                                        4=>"hazel",
                                        5=>"gray"
                                    );
                    for ( $i=1; $i<6; $i++ ) {
                        ?>
                        <option value="<?php echo $eye_color[ $i ]; ?>"<?php 
                        if ( $user_eyecolor == $eye_color[ $i ] ) {
                            ?> selected="selected"<?php
                        }
                        ?>><?php 
                        echo ucfirst( $eye_color[ $i ] ); 
                        ?></option><?php 
                    }
                ?>
            </select>
        </td>
        <td><div id="getelem_eyecolor"></div></td>
    </tr>
</table>
<br />
</div>
<?php echo $arrow; ?><a href="javascript:proffiles();">My Avatars</a><br /><br />
<div id="prof_files" <?php 
    if ( !isset( $_POST[ "avatstat" ] ) ) {
        ?>style="display:none;"
        <?php 
    } 
?>>
<br />
<?php
    //showing all avatars of a user 
    $showingavatars = true;
    $avatars = retrieve_user_avatars( $user->Id() ); // only retrieves active avatars
    $num_rows = count( $avatars );
    bfc_start(); ?>
        defaultavatar = <?php echo $user->Avatar(); ?>;
    <?php bfc_end();
    for ( $i=0; $i<$num_rows; $i++ ) {
        $this_avatar = $avatars[ $i ];
        include "elements/blogging/profile/avatar.php";
    }
    $showingavatars = false;
    ?><div id="newavatars" class="inline"></div>
    <div class="avatar_upload_td">
        <div style="float:left;margin-right:4px"><?php    
            img( "images/nuvola/upload64.png" , "Upload" , "Upload Avatar" , 64 , 64 ); 
        ?></div>
        <div><?php
                h4( "New Avatar" );
            ?><br />
            <div id="fafavuperror" style="display:none"></div>
            <iframe src="cubism.bc?g=media/uploadformavatars" frameborder="no" style="height:30px" id="fifavup"></iframe>
            <div id="fifavupanime" style="display:none">
            Uploading...<br />
            <img src="images/uploading.gif" alt="Please wait..." title="Uploading..." />
            </div>
        </div>
    </div>
    <div id="avatarstatus" style="display:none;"></div>
    <br /><?php
    bfc_start();
    ?>cur_num_avatars=<?php 
    echo $num_rows;
    ?>;allowdesignmode();<?php
    bfc_end();
    ?></div><?php 
    echo $arrow; 
?><a href="javascript:dm('accounts/account_options');">Account Options</a><br /><br />