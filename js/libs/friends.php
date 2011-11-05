Friends = {
    friendcardover: function ( id ) {
        var fcard;

        for ( i = 0 ; i < allfriendids.length ; i++ )
            g( 'friendcard_' + allfriendids[ i ] ).style.display = 'none';
        if( ( fcard = g( fcid = 'friendcard_' + id ) ).style.display == "none" ) {
            fcard.style.left = findPosX( fscard = g( 'friendsmallcard_' + id ) ) + 5 + "px";
            fcard.style.top = findPosY( fscard ) + 18 + "px";
            fade0( fcid );
            fcard.style.display = 'inline';
            fadein( fcid , false , 50 , 0.9 , 0.1 );
        }
    }
    
    ,friendcardout: function ( id ) {
        var fcard;

        fcard = g( 'friendcard_' + id );
        fcard.style.display = 'none';
    }
    
    ,removefriend: function ( id , username ) {
        if ( confirm( "Are you sure that you want to remove " + username + " from your friends?" ) ) {
            de('friend_killer','blogging/friends/removefriend&frid=' + id);
            g('friendsmallcard_' + id).style.display = "none";
        }
    }
};
