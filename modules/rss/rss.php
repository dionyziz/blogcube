<?php

if( isset( $_GET['feed'] ) ) {
    //header('Content-type: application/rss+xml; charset=UTF-8', true);
    die ( MakeBlogPostsRSS( $_GET['feed'] ));
}
    function MakeBlogPostsRSS( $blogid ) {
        // RSS for all blogposts on $blogid
        
        // get blog
        $blog = GetBlogByName( $blogid );
        // create rss feed
        $dom = new DOMDocument('1.0');
        // set encoding
        //$dom->encoding = "utf-8";
        $temp = $dom->appendChild( $dom->createComment( 'generator="BlogCube"' ) );
        
        // <rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/">
        $rss = $dom->appendChild( $dom->createElement( 'rss' ) );
        $rss->setAttribute( "version", "2.0" );
        // let us deactivate them. we'll see later if we really need them
        //$rss->set_attribute( "xmlns:content", "http://purl.org/rss/1.0/modules/content/" );
        //$rss->set_attribute( "xmlns:wfw", "http://wellformedweb.org/CommentAPI/" );
        //$rss->set_attribute( "xmlns:dc", "http://purl.org/dc/elements/1.1/" );
        
        // <channel>
        $channel = $rss->appendChild( $dom->createElement( 'channel' ) );
        
        // <title>$blog->Title()</title>
        $temp = $channel->appendChild( $dom->createElement( 'title' ) );
        $temp->appendChild( $dom->createTextNode( $blog->Title() ) );
        
        // <link>$blog->URL()</link>
        $temp = $channel->appendChild( $dom->createElement( 'link' ) );
        $temp->appendChild( $dom->createTextNode( $blog->URL() ) );
        
        // <description>$blog->Description()</description>
        $temp = $channel->appendChild( $dom->createElement( 'description' ) );
        $temp->appendChild( $dom->createTextNode( $blog->Description() ) );
        
        // <pubDate>Fri, 03 Feb 2006 12:03:49 +0000</pubDate>
        //$temp = $channel->appendChild( $dom->createElement( 'title' ) );
        //$temp->appendChild( $dom->createTextNode( $blog->Title() ) );
        
        // <generator>http://www.blogcube.net</generator>
        $temp = $channel->appendChild( $dom->createElement( 'generator' ) );
        $temp->appendChild( $dom->createTextNode( "http://www.blogcube.net" ) );
        
        while( $post2 = $blog->Post() ) {
            // some bug workaround ? --ch-world
            $post = New Post( $post2->Id() );
            
            // <item>
            $item = $channel->appendChild( $dom->createElement( 'item' ) );
            // <title>$post->Title()</title>
            $temp = $item->appendChild( $dom->createElement( 'title' ) );
            $temp->appendChild( $dom->createTextNode( $post->Title() ) );
            
            // <link>$blog->URL() #post</link>
            // Thunderbird uses this as unique id to see if there is new blogpost
            $temp = $item->appendChild( $dom->createElement( 'link' ) );
            $temp->appendChild( $dom->createTextNode( 'http://www.google.com/' . $post->Id() ) );
            
            // <comments>$blog->URL() #comments</comments>
            //$temp = $item->appendChild( $dom->createElement( 'comments' ) );
            //$temp->appendChild( $dom->createTextNode( 'link here' ) );
            
            // <guid>$blog->URL() #post</guid>
            $temp = $item->appendChild( $dom->createElement( 'guid' ) );
            $temp->appendChild( $dom->createTextNode( $post->Id() ) );
            
            // <description>$post->Text()</description>
            $temp = $item->appendChild( $dom->createElement( 'description' ) );
            $temp->appendChild( $dom->createCDATASection( $post->Text() ) );

            $temp = $item->appendChild( $dom->createElement( 'pubDate' ) );
            ParseDate( $post->Date() , $year , $month , $day , $hour , $minute , $second );
            $temptime = mktime( $hour , $minute , $second , $month , $day , $year );
            $temp->appendChild( $dom->createTextNode( strftime( '%a, %d %b %Y %H:%M:%S +0000' , $temptime ) ) );
        }
        return $dom->saveXML( );
    }
    
    function MakeBlogCommentsRSS( $blogid ) {
        // RSS for all comments on $blogid
    }
    
    function MakeBlogPostCommentsRSS( $postid ) {
        // RSS for comments on a certain blogpost only, $postid
    }
    
    function MakeUserPhotosRSS( $userid ) {
        // RSS for public uploaded photographs for user $userid
    }
    
    function MakeFriendsPhotosRSS() {
        // RSS for public and friendly uploaded photographs of friends of mine ($user->Id())
    }
    
    function MakeFriendsCommentsRSS() {
        // RSS for comments made by my friends
    }
    
    function MakeFriendsPostsRSS() {
        // RSS for posts made by my friends
    }

    // Friends of Friends RSS?
?>