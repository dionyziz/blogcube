<?php
    include "modules/module.php";

    //import_blog("http://spaces.msn.com/members/jnfoot/feed.rss");
    //import_blog("http://jnfootrocks.blogspot.com/atom.xml");
    //import_blog("http://dionyziz.blogspot.com/atom.xml");
    //import_blog("http://www.livejournal.com/users/jnfoot/data/rss");
    
    function import_blog($feedurl) {
        switch(true) {
            case (strpos($feedurl, "http://spaces.msn.com/members/") !== false):
                //this is a msn spaces feed
                return parse_rss_feed($feedurl);
                break;
            case (strpos($feedurl, ".blogspot.com/atom.xml") !== false):
                //this is a blogger.com, blogspot.com feed
                return parse_feed_feed($feedurl); 
                break;
            case (strpos($feedurl, "http://www.livejournal.com/users/") !== false):
                //this is a livejournal feed
                return parse_rss_feed($feedurl);
                break;
            default:
                return false;
        }
    }
    
    function parse_rss_feed($feedurl) {
        $import_to_db = array();
        $dom = xmldoc(file_get_contents($feedurl));
        $root = $dom->document_element();
        if ($root->tagname == "rss") {
            foreach ($root->child_nodes() as $blogpart) { 
                if ($blogpart->tagname == "channel") {
                    $i = 0;
                    foreach ($blogpart->child_nodes() as $item) {
                        switch($item->tagname) {
                            case "title":
                                $import_to_db["blog_title"] = $item->get_content();
                                break;
                            case "item":
                                $typechild = $item->last_child();
                                if ($typechild->get_content() == "blogentry") {
                                    foreach ($item->child_nodes() as $postparts) {
                                        switch($postparts->tagname) {
                                            case "title":
                                            case "description":
                                            case "pubDate":
                                                echo "got something in item";
                                                $import_to_db["blog_posts"][$i][$postparts->tagname] = $postparts->get_content();
                                                break;
                                            default:
                                        }
                                    }
                                    $i++;
                                }
                                break;
                            default:
                        }
                    } 
                }
            }
            return true;
        }
        else {
            return false;
        }
    }
    
    function parse_feed_feed($feedurl) {
        $import_to_db = array();
        $dom = xmldoc(file_get_contents($feedurl));
        $root = $dom->document_element();
        if ($root->tagname == "feed") {
            $imports_to_db = array();
            $i = 0;
            foreach ($root->child_nodes() as $blogpart) {
                switch($blogpart->tagname) {
                    case "tagline":
                        $imports_to_db["blog_title"] = $blogpart->get_content();
                        break;
                    case "entry":
                        foreach ($blogpart->child_nodes() as $postparts) {
                            switch($postparts->tagname) {
                                case "created":
                                case "content":
                                    $imports_to_db["blog_posts"][$i][$postparts->tagname] = $dom->dump_node($postparts);
                                    break;
                                case "title":
                                    $imports_to_db["blog_posts"][$i][$postparts->tagname] = $postparts->get_content();
                                    break;
                                default:
                            }
                        }
                        $i++;
                        break;
                    default:
                }
            }
            return true;
        }
        else {
            return false;
        }
    }
?>