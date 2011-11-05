<?php
    /*
        Developer: dionyziz
    */
    // 1. water debugging module; this module is not included here;
    // it's included in header.php, to be able to trace everything we need
    // require_once "modules/water/water.php";
    
    // 2: trivial functions, might be used in other modules
    require_once "modules/trivial/trivial.php";

    // 3. codehighlight module; contains code highlighting to be used in case of errors
    require_once "modules/codehighlight/codehighlight.php";

    if( !$readonly ) {
        // 4: database module; other modules require a database connection is already made (like Logs and Authentication)
        require_once "modules/database/database.php";
    }

    // 5. crons (contains code necessary to set up cronjobs by other modules)
    require_once "modules/cron/cron.php";

    // 6. users (contains authentication script and inits $user)
    require_once "modules/users/users.php";

    // all other modules included in no particular order (alphabetically)
    require_once "modules/attila/attila.php";
    require_once "modules/bfc/bfc.php";
    require_once "modules/blogcute/blogcute.php";
    require_once "modules/blogs/blogs.php";
    require_once "modules/bookmarks/bookmarks.php";
    require_once "modules/bugreporting/bugreporting.php";
    require_once "modules/comments/comments.php";
    require_once "modules/documentation/documentation.php";
    require_once "modules/emailblog/emailblog.php";
    require_once "modules/filters/filters.php";
    require_once "modules/friends/friends.php";
    require_once "modules/import/import.php";
    require_once "modules/invitations/invitations.php";
    require_once "modules/irclogs/irclogs.php";
    require_once "modules/logs/logs.php";
    require_once "modules/math/math.php";
    require_once "modules/media/media.php";
    require_once "modules/messaging/messaging.php";
    require_once "modules/polls/polls.php";
    require_once "modules/posts/posts.php";
    require_once "modules/rss/rss.php";
    require_once "modules/spellcheck/bc_spell.php";
    require_once "modules/versioncontrol/versioncontrol.php";
    require_once "modules/wind/wind.php";
    
    // the following module is included only if the user requesting the page is developer (for extra security)
    if ( $user->IsDeveloper() ) {
        require_once "modules/stabilize/stabilize.php";
    }
?>