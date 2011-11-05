<?php
    // do NOT include elements/element.php here (in-function element include)
    
    if( $thisisabug !== true ) {
        // do not change to bc_die, this element is included from bc_die anyway
        die( "Element may not be directly included" ); // modules/trivial/others
    }
    $praises = array(
        "All hail to the bugbusters!",
        "You did it, nice work alpha tester!",
        "Oh, it's getting hot in here!",
        "Alpha's rule!",
        "Again the alpha's score!",
        "Gooooooooaaaaaaaaal!",
        "Your momma knows you're good hacker?",
        "Bill Gates is afraid of you!",
        "Another bug shot in the face!",
        "...and on the 8th day, God created Bug testers and he gave all his power to the alpha's!",
        "Gub the Bug says: You killed my mommy, you evil alpha tester!",
        "Welcome to Alpha the Barbarian!",
        "Bug is female, I just know that!",
        "BugCube, blame us, we deserve it!",
        "Warning: Bugs can harm your health.",
        "Bugs can cause overweight.",
        "Bug me baby one more time!",
        "Bugs are my best friends, next to my teddy bears.",
        "I am sick, I got bugeria.",
        "Please stay away from your computer until the explosion is over.",
        "Bush, bush says a bug with a cold.",
        "Bill Gates' newest novel: My life as a bug.",
        "I see dead bugs...",
        "BugCube, for losers like you!",
        "A bug is bisexual, it fucks up everything!"
    );

    ?><div style="margin:auto;width:600px;"><br />
    <div style="border:1px solid #e0e0e0;background-color:#fafafa;margin:5px 5px 1px 5px"><!-- float container -->
        <div style="float:left;margin:5px 5px 5px 5px"><?php
            img('images/others/water/water.jpg', 'water', 'Water, BlogCube\'s Debugging System', 75, 75, 'border:1px solid black');
        ?></div>
        <div style="margin-top: 3px">A BlogCube error has occured.<br />Cookie: <b><?php
            echo $praises[ rand( 0, count($praises) - 1 ) ];
        ?></b><br /><br />Please <a href="http://blogcube.net/#bugreporting/bug/report/new">report this bug</a>.
        </div>
        <div style="clear:both;"></div>
    </div><br />
    When reporting a bug, include your <b>Operating System</b> information, your <b>Browser</b> information and, if possible, a way to reproduce it.<br /><br />
    <h4>Technical Information</h4><br />
    <b>Death Note:</b> <?php
    echo $error;
    ?><br /><b>Username:</b> <?php
    if ( $user->Id() ) {
        echo $user->Username();
    }
    else {
        ?>(anonymous)<?php
    }
    ?><br /><b>User Id:</b> <?php
    if ( $user->Id() ) {
        echo $user->Id();
    }
    else {
        ?>(unspecified)<?php
    }
    ?><br /><b>IP:</b> <?php
    echo UserIp();
    ?><br /><b>Status:</b> <?php
    if ( $logok ) {
        if ( $mailok !== false ) {
            ?>Reported, Logged, Distributed<?php
        }
        else {
            ?>Reported, Logged<?php
        }
    }
    else {
        if ( $logok !== false ) {
            ?>Reported, Distributed<?php
        }
        else {
            ?>Reported<?php
        }
    }
    ?><br /><b>Timestamp:</b> <?php
    echo NowDate();
    ?><br /><b>Death Id:</b> <?php
    if ( $logok !== false ) {
        echo $logok;
    }
    else {
        ?>(unspecified)<?php
    }
    ?><br /><br /><?php
    echo $watertrace;
    ?></div><br /></div>