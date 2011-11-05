<?php
    function CreateInvitation( $fromfirstname , $fromlastname , $invitationid , $invitationcode ) {
        global $domainname;
        
        return "
" . $fromfirstname . " " . $fromlastname . " has invited you to create a free BlogCube account.

To accept this invitation and register for your account, visit
http://invitations.$domainname/?" . $invitationid . "/" . $invitationcode . "

Once you create your account, " . $fromfirstname . " " . $fromlastname . " will be added to your BlogCube friends, so that you can stay in touch!

If you haven't already heard about BlogCube, it's a new simple blogging service that allows you to...

- Create your own personal blog, pick a topic and start posting in a few seconds.
- Share photos with your friends and family.
- Join a community of friends, view their blogs and images.
- Do everything simply and easily, with just a few clicks.

BlogCube is still in an early stage of development. But if you set up an account, you'll be able to keep it even after we make BlogCube more widely available. We might also ask for your comments and suggestions periodically and we appreciate your help in making BlogCube even better.

Thanks,

The BlogCube Team

" /*
To learn more about BlogCube before registering, visit:
http://blogcube.net/
*/ . "
(If clicking the URLs in this message does not work, copy and paste them into the address bar of your browser). 
";
    }
?>