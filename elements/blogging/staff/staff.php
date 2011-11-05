<?php
    $bfc->start()
    ?>et('The Team');<?php
    $bfc->end();
    
    h3('The BlogCube Team');
    
    $staff = array(
        array( 
            'key' => 'dio',
            'realname' => 'Dionysis Zindros',
            'nickname' => 'dionyziz',
            'title' => 'BlogCube Lead',
            'description' => "

                Hey, I'm Dionysis, and I'm the guy who's generally responsible for the BlogCube project.
                I'm the one who had the initial idea about BlogCube, and I wrote the first parts of its code.
                Still, most of the code BlogCube runs has been written by me. I brought together all the
                other people that today form the BlogCube Team. I'm usually the right person to 
                contact, if you need anything about BlogCube.

            " ,
            'photo' => 'images/staff/dionysis.jpg'
        ),
        array(
            'key' => 'christian',
            'realname' => 'Christian Herrmann',
            'nickname' => 'ch-world',
            'title' => 'Your Linux Admin',
            'description' => "

                This is your next-door linux admin, Christian, who logs-in as root in his sleep. Consoles, networks, servers,
                domain names are all his responsibility and he can work them on his fingertips. If you ever find that the website is down, he's usually the right guy to talk to ;-)

            ",
            'photo' => 'images/staff/christian.jpg'
        ),
        array(
            'key' => 'feedy',
            'realname' => 'Ioannis Chatzimichos',
            'nickname' => 'feedWARd',
            'title' => 'Coding Wizard',
            'description' => "

                Heya, my name is Ioannis also known as feedWARd. I've been an active BlogCube developer since
                it almost started. My work primarily consists of client-side code. I've done messaging, bug
                reporting, bookmarks and part of account options. I've also made some server-side modules and
                completed other unfinished tasks. My latest piece of art was stabilization script. If you need
                help dionyziz is usually the right person to ask. By the way, I have no social life.

            ",
            'photo' => 'images/staff/ioannis.jpg'
        ),
        array(
            'key' => 'izu',
            'realname' => 'Chris Pappas',
            'nickname' => 'Izual',
            'title' => "Lazy Izy's Busy ",
            'description' => "

                Aloha people, I am the key developer codenamed Izual and I joined the project almost since it began.
                I mostly worked on client-side tasks such as albums, profile viewing and editing
                but also on some server-side things like albums, avatars, the media storing system 
                and the actions logging. We worked hard on it, so we want you to have fun!!!
                
            ",
            'photo' => 'images/staff/chris.jpg'
        ),
        array(
            'key' => 'makis',
            'realname' => 'Makis Kanavidis',
            'nickname' => 'makis',
            'title' => 'The Messiah of Code',
            'description' => "

                Hey guys, this is Makis. Being too busy with BlogCube code, he never got down to
                write his own section in this about screen. He is a quite late addition to the team, 
                but needless to say, he has completed large parts of the system that all of us use 
                for blogging every day, especially things not so trivial, such as mathematic formulas
                rendering, e-mail blogging, and other cool stuff.
                
            ",
            'photo' => 'images/staff/makis.jpg' 
        ),
        array(
            'key' => 'freddy',
            'realname' => 'Frederik Belien',
            'nickname' => 'sfbtkm2',
            'title' => 'Talks Too Much',
            'description' => "

                Frederik is the legal advisor of BlogCube, responsible for all legal matters
                that may arise, such as Terms of Service enforcement, Privacy Policy enforcement,
                inner-BlogCube legal documents and the like. His most important task, however, is 
                leading the Alpha Testing Team in its every-day work of spotting bugs and brainstorming
                ideas for BlogCube.

            ",
            'photo' => 'images/staff/frederik.jpg'
        )

        /* -- */
        ,true,
        /* -- */

        array(
            'key' => 'indy',
            'realname' => 'Aris Micropoulos',
            'nickname' => 'indy',
            'description' => "

                Aris is a former BlogCube key developer who wrote a lot of both server-side and
                client-side code. Parts of his work include the server-side part of the bookmarks system, 
                the polls system, and others.

            "
        ),
        array(
            'key' => 'josh',
            'realname' => 'Josh Nelson',
            'nickname' => 'jnfoot',
            'description' => "

                Josh is a former developer, that has helped us out with a few not-so-trivial
                features, such as RSS, spellchecking, and others.

            "
        ),
        array(
            'key' => 'root',
            'realname' => 'Mark Droog',
            'nickname' => 'ro0t',
            'description' => "

                Mark is a former developer, responsible for BlogCube's chatting system. You can
                now find him as a member of the Alpha Testing Team.

            "
        ),
        array(
            'key' => 'emmerich',
            'realname' => 'Stavros Polimenis',
            'nickname' => 'emmerich',
            'description' => "

                Stavros, a former member of the development team, is now a BlogCube alpha tester.

            "
        )
    );

    $commentcolours = array(
        New RGBColorTransformation( 123 , 222 , 90  ), // green
        New RGBColorTransformation( 255 , 157 , 8   ), // orange
        New RGBColorTransformation( 238 , 36  , 8   ), // red
        New RGBColorTransformation( 115 , 36  , 115 ), // purple
        New RGBColorTransformation( 82  , 129 , 230 ), // blue
        New RGBColorTransformation( 255 , 226 , 115 ), // yellow
        New RGBColorTransformation( 16  , 36  , 139 ), // dark blue
        New RGBColorTransformation( 255 , 255 , 255 ), // white
        New RGBColorTransformation( 246 , 214 , 148 )  // ochra
    );
    // transformation
    foreach( $commentcolours as $i => $colour ) {
        $commentcolours[ $i ]->Lighten();
    }

    ?><br /><?php
    $i = $j = 0;
    $inteam = true;
    foreach ( $staff as $wanker ) {
        $mod2 = true; //( $i % 2 == 0 );
        if ( $wanker !== true ) {
            ?><div id="staff_<?php
            echo $wanker['key'];
            ?>" style="text-align:<?php
            if ( $mod2 ) {
                ?>left<?php
            }
            else {
                ?>right<?php
            }
            ?>;display:block;padding: 2px 5px 5px 2px;background-color:#eeeeee;border:1px solid #cccccc;border-top: 3px solid <?php
            $j++;
            if ( $j >= count( $commentcolours ) ) {
                $j = 0;
            }
            echo $commentcolours[ $j ]->css();
            ?>"><?php
            if ( isset( $wanker[ 'photo' ] ) ) {
                ?><div id="staff_<?php
                echo $wanker[ 'key' ];
                ?>_photo" style="display:table-cell;padding-right:5px;vertical-align:middle"><img src="<?php
                echo $wanker[ 'photo' ];
                ?>" style="border:1px solid #3979bd" /></div><?php
            }
            ?><div style="display:table-cell;vertical-align:top"><?php
            $real = explode( ' ' , $wanker[ 'realname' ] );
            $realname = $real[ 0 ] . ' "<a href="" onclick="dm(\'profile/profile_view&user=' . $wanker[ 'nickname' ] . '\');return false;" style="text-decoration:none">' . $wanker[ 'nickname' ] . '</a>" ' . $real[ 1 ];
            if ( $inteam ) {
                h4( $realname );
                if ( isset( $wanker[ 'title' ] ) ) {
                    ?><b style="font-family:Arial,Helvetica,sans-serif;"><?php
                    echo $wanker[ 'title' ];
                    ?></b><br /><?php
                }
            }
            else {
                ?><b><?php
                echo $realname;
                ?></b><?php
            }
            ?><div style="font-family:Arial,Helvetica,sans-serif;font-size:90%"><?php
            echo $wanker[ 'description' ];
            ?></div></div></div><br /><?php
            $i++;
        }
        else {
            h3("Thanks to...");
            $inteam = false;
            ?><br /><?php
        }
    }
?>