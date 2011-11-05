<?php
    $curuser = GetUserByUserName( $_POST[ "user" ] );
    if ( $curuser === false ) {
        ?>There is not such user<?php
    }
    else {
        $user_username = $curuser->Username();
        $bfc->start()
    ?>
        et("<?php 
        echo $user_username;
        ?>'s profile" );
    <?php
        $bfc->end();
        
        $user_id = $curuser->Id();
        $user_firstname = $curuser->Firstname();
        $user_lastname = $curuser->Lastname();
        $user_gender = $curuser->Gender();
        $user_birthday = $curuser->Dob();
        $user_public_birday = $curuser->PublicBirthday();
        $user_public_age = $curuser->PublicAge();
        $user_website = $curuser->Website();
        $user_email = $curuser->Email();
        $user_msn = $curuser->Msn();
        $user_yahoo = $curuser->Yahoo();
        $user_icq = $curuser->Icq();
        $user_jabber = $curuser->Jabber();
        $user_gtalk = $curuser->Gtalk();
        $user_aim = $curuser->Aim();
        $user_public_email = $curuser->PublicEmail();
        $user_public_msn = $curuser->PublicMsn();
        $user_public_yahoo = $curuser->PublicYahoo();
        $user_public_icq = $curuser->PublicIcq();
        $user_public_jabber = $curuser->PublicJabber();
        $user_public_gtalk = $curuser->PublicGtalk();
        $user_public_aim = $curuser->PublicAim();
        $user_movie = $curuser->FavoriteMovie();
        $user_band = $curuser->FavoriteBand();
        $user_musicgenre = $curuser->FavoriteMusicGenre();
        $user_game = $curuser->FavoriteGame();
        $user_quote = $curuser->FavoriteQuote();
        $user_hair = $curuser->Hair();
        $user_eyes = $curuser->Eyes();
        $user_aboutme = $curuser->AboutMe();    
        ParseSolDate( $user_birthday , $year , $dobmnth , $day );
        $user_friend = $curuser->IsFriend( $userid );
        $user_ffriend = $curuser->IsFfriend( $userid );
        $user_admin = $curuser->IsAdmin();
        $user_dev = $curuser->IsDeveloper();
        
        function checkfriendship( $contact , $curuser , $user_friend , $user_ffriend ) {
            global $user;
            
            if ( $curuser->Id() == $user->Id() ) {
                return true;
            }
            if ( $contact == "Email" ) {
                switch ( $curuser->PublicEmail() ) {
                    case "private":
                        return false;
                    case "public":
                        return true;
                    case "friends":
                        return $user_friend;
                    case "ffriends":
                        return $user_ffriend;
                }
            }
            else {
                $pfunct = "Public".$contact;
                switch ( $curuser->$pfunct() ) {
                    case "private":
                        return false;
                    case "public":
                        return true;
                    case "friends":
                        return $user_friend;
                    case "ffriends":
                        return $user_ffriend;
                }
            }
        }
    ?>
    <table width="100%">
        <tr>
            <td align="left">
                    <h3>
                    <?php 
                        echo $user_username;
                    ?>
                    </h3>
                    <?php 
                        if ( $user_admin ) {
                            img( "images/nuvola/admin22.png" , "Admin" , "BlogCube Administrator" );
                            ?><br /><?php
                        }
                        elseif ( $user_dev ) {
                            img( "images/nuvola/developer22.png" , "Developer" , "BlogCube Developer" );
                            ?><br /><?php
                        }
            ?><br /><?php
                    echo $user_firstname." ".$user_lastname."<br />";
                    //checking for publicity status missing
                    /*$birthdunix = mktime ( "" , "" , "" , $month , $day , $year );
                    $todayunix = time();
                    $age = $todayunix - $birthdunix;
                    $age = floor( $age / (365*24*60*60));
                    */
                    if ( $user_gender == "male" ) {
                        img( "images/silk/male.png" , "Gender" , "Gender: male" );?>
            <?php 
                    }
                    if ( $user_gender == "female" ) {
                        img( "images/silk/female.png" , "Gender" , "Gender: female" );?>
            <?php 
                    }
                    if ( checkfriendship( "Age" , $curuser , $user_friend , $user_ffriend ) ) {
                        if ( $user_birthday != "" ) {
                            $birthdif = DateDiff($user_birthday . " 00:00:00");
                            echo floor($birthdif / (365*24*60*60));
                            ?> years old<?php
                        }
                    }
                ?><br />
                    <?php
                        if ( checkfriendship( "Birthday" , $curuser , $user_friend , $user_ffriend ) ) {
                            if ( $user_birthday != "" ) {
                                img( "images/nuvola/birthday22.png" , "Birthday" , "My birthday is on" );                
                                $div = intval( $day / 10 );
                                $mod = $day % 10;
                                if ( ( $div == 0 &  $mod == 1 ) || ( $div == 1 & $mod == 1 ) || ( $div == 3 & $mod == 1 ) ) {
                                    $daysup = "st";
                                }
                                elseif ( ( $div == 0 &  $mod == 2 ) || ( $div == 2 & $mod == 2 ) ) {
                                    $daysup = "nd";
                                }
                                elseif ( ($div == 0 &  $mod == 3 ) || ( $div == 3 & $mod == 3 ) ) {
                                    $daysup = "rd";
                                }
                                else {
                                    $daysup = "th";
                                }
                                if ( $day < 10 ) {
                                    $day = substr( $day , 1 , 2 );
                                }
                                echo " ".$day."<sup>".$daysup."</sup> ".$months[ $dobmnth - 1 ];
                            }
                        }
                    ?></b><br /><br />
    <?php
        /*    <tr>
            <td>
                <b>Some words about me:</b><br />
                <i>
                <?php
                    echo $user_aboutme;
                ?>
                </i>
            </td>
        </tr>
    */
    ?>    
    <?php 
        //Display music genre
        if ( $user_musicgenre != "" ) {
            img(  "images/nuvola/music22.png" , "Favourite music genre" , "My favourite music type" );
            echo $user_musicgenre;
    ?><br />
    <?php 
        }
        //End of display
        //Displaying favourite band
        if ( $user_band != "" ) {
            img(  "images/nuvola/piano22.png" , "Favourite band" , "My favourite band" );
            echo " ".$user_band;
        
        ?><br />
    <?php 
        }
        //End of display
    ?>
    <?php 
        //Displaying favourite game
        if ( $user_game != "" ) {
        img( "images/nuvola/play22.png" , "Game" , "My favourite game" );
        echo " ".$user_game;
    ?><br />
    <?php
        }
        //End of diplay
        //Display favourite quote
        if ( $user_quote != "" ) {
    ?>
    The quote I like most is: <b>
    <?php 
        // $user_quote = htmlspecialchars( $user_quote );
        // $user_quote = htmlentities( $user_quote , ENT_QUOTES , 'utf-8' );
        
        /* bfc_start();
        ?>a('<?php
        //echo htmlentities( $user_quote , ENT_QUOTES , 'utf-8' );
        echo $user_quote;
        ?>');<?php
        bfc_end();
        */
        
        /// (above) testing some problems with international entities, please don't pay attention to it --dionyziz.
        
        echo $user_quote;
    ?></b><br />
    <?php 
        }
        //End of display
    ?>
    
    <br />
    <?php 
        //Display hair color
        if ( $user_hair != "" ) {
    ?>
    My hair color is <b>
    <?php 
        echo $user_hair;
    ?></b><br />
    <?php 
        }
        //End of display
    ?>
    
    <?php 
        //Display eyecolor
        if ( $user_eyes != "" ) {
    ?>
    The color of my eyes is <b>
    <?php
        echo $user_eyes;
    ?>
    .</b><br />
    <?php
        }
        //End of display
    ?>
    
    <br />
    <?php 
        if ( $user_aboutme != "" ){
    ?>
    About me: <br />
    <i><?php 
    echo nl2br( htmlspecialchars( $user_aboutme ) ); 
    ?></i>
    <?php 
        }
    ?>
    <br />
    </td>
    <td align="right">    
                <table valign="top" width="350"><tr><td align="right">
                <?php
                    //avatar showing
                    $avatars = retrieve_user_avatars( $user_id ); // only retrieves active avatars
                    $num_rows = count( $avatars );
                    for ( $i=0; $i<$num_rows; $i++ ) {
                        $this_avatar = $avatars[ $i ];
                        $this_avatarmediaid = $this_avatar->Id();
                        img( "download.bc?id=" . $this_avatarmediaid , "Avatar" , $realtext , 64 , 64 );
                    }
                ?>
            </td></tr></table>
                <table id="contactbox" border="1" width="220">
                <tr><td class="contacts_td"><b>Contact Information</b><br /></td></tr>
                <tr><td class="view_contacts">
                <?php
                    $showbox = 0;
                    if (( checkfriendship( "Email" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_email != "" )) {
                        img( "images/nuvola/email.png" , "Email" , "Email adress" );
                        echo " ".$user_email."<br />";
                        $showbox = 1;
                    }    
                    if (( checkfriendship( "Msn" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_msn != "" )) {
                        /*$etposition = strrpos( $user_msn , "@");
                        $pos = substr( $user_msn , 0 , $etposition);    
                        */
                        img( "images/messenger/msn.png" , "MSN" , "MSN Messenger Passport" );
                        echo " ".$user_msn."<br />";
                        $showbox = 1;
                    }
                    if (( checkfriendship( "Yahoo" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_yahoo != "" )) {
                        img( "images/messenger/yahoo.png" , "Yahoo" , "Yahoo! Messenger ID" );
                        ?>
                        <a href="http://profiles.yahoo.com/<?php 
                        echo $user_yahoo; 
                        ?>"><?php 
                        echo $user_yahoo;
                        ?></a><br /><?php
                        $showbox = 1;
                    }
                    if (( checkfriendship( "Icq" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_icq != "" )) {
                        img( "images/messenger/icq.png" , "icq" , "ICQ Number" );
                        ?>
                        <a href="http://www.icq.com/whitepages/about_me.php?uin=<?php 
                        echo $user_icq; 
                        ?>"><?php 
                        echo $user_icq; 
                        ?></a><br /><?php 
                        $showbox = 1;
                    }
                    if (( checkfriendship( "Jabber" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_jabber != "" )) {                
                        img( "images/messenger/jabber.png" , "jabber" , "Jabber Username" );
                        echo " ".$user_jabber."<br />";
                        $showbox = 1;
                    }
                    if (( checkfriendship( "Gtalk" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_gtalk != "" )) {
                        img( "images/messenger/gtalk.png" , "GTalk" , "Google Talk ID" );
                        echo " ".$user_gtalk."<br />";
                        $showbox = 1;
                    }
                    if (( checkfriendship( "Aim" , $curuser , $user_friend , $user_ffriend ) ) && ( $user_aim != "" )) {
                        img( "images/messenger/aim.png" , "AIM" , "AOL Instant Messenger ID" );
                        echo " ".$user_aim."<br />";
                        $showbox = 1;
                    }
                    if ( $showbox == 0 ) {
                        $bfc->start();
                        ?> g('contactbox').style.display = 'none'; <?php
                        $bfc->end();
                    }
                ?></td></table></td></tr>
        </table><?php
        if ( !$anonymous && $user->Id() != $user_id ) {
            echo $arrow;
            ?><a href="javascript:de('adfr','blogging/friends/addfriend&char=<?php 
            echo $user_username 
            ?>','');">Add to friends</a><br /><?php
            echo $arrow;
            ?><a href="javascript:dm('messaging/message/compose&recip=<?php 
            echo $user_username; 
            ?>');">Send message</a>
        <div id="adfr"></div><?php
        }
        if ( $user->Id() == $user_id ) {
            ?><br /><br /><?php
            img( "images/nuvola/profile.png" , "Profile" , "Edit Profile" );
            ?> <a href="javascript:dm('profile/profile_edit');">Edit profile</a><?php 
        }
}
?>