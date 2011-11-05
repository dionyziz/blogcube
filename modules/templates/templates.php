<?php
    /*
        Module:Templates
        File: /modules/templates/templates.php
        Developer: Izual
        Basic Include File -- this file is included by BlogCube core elements
    */

    include "modules/module.php";
    
    if ( $debug_version ) {
        $templatesdir = "/home/templates/beta";
    }
    else {
        $templatesdir = "/home/templates/stable";
    }    
    
    function create_templatename( $name , $privid ) {
        //the privid is a sign of whether a user can use a template or not, he can use it if it is his (privid=1) or when it is generally available (privid=0)
        //the privid field might be useless, it can be done through the userid field

        if ( $privid == "0" || $privid == "1" ) {
            $name = bcsql_escape( $name );
            $userid = $user->Id();
            $userip = UserIp();
            $today = NowDate();
            $privid = bcsql_escape( $privid );
            $query = "INSERT INTO `$templatenames` ( `tmpln_id` , `tmpln_name` , `tmpln_userid` , `tmpln_crdate` , `tmpln_mddate` , `tmpln_ip` , `tmpln_privid` )
                    VALUES ( '' , '$name' , '$userid' , '$today' , '$today' , '$userip' , '$privid' );";
            bcsql_query( $query );
            $last_id = bcsql_insert_id();
        }
        else {
            bc_die( "The privid not valid:".$privid );
        }
        
    }
    function create_templatestyle( $tmplid , $sidebarcontainerleft_width , $sidebarcontainerleft_bgcolor , $sidebarcontainerright_width , $sidebarcontainerright_bgcolor , $aielposts_width , $aielposts_bgcolor , $aielposts_color , $aielposts_fontsize , $aielposts_fontfamily , $blogtitle_color , $blogtitle_fontsize , $blogtitle_fontfamily , $blogdate_color , $blogdate_fontsize , $blogdate_fontfamily , $blogposttitle_color , $blogposttitle_fontsize , $blogposttitle_fontfamily , $bloglinks_color , $bloglinks_fontsize , $bloglinks_fontfamily , $postedby_color , $postedby_fontsize , $postedby_fontfamily , $arrow_color , $arrow_fontsize ) {
        //this function takes a lot of parametrs as it creates the style for a template
        $userid = $user->Id();
        write_tocss( $userid , $tmplid , $sidebarcontainerleft_width , $sidebarcontainerleft_bgcolor , $sidebarcontainerright_width , $sidebarcontainerright_bgcolor , $aielposts_width , $aielposts_bgcolor , $aielposts_color , $aielposts_fontsize , $aielposts_fontfamily , $blogtitle_color , $blogtitle_fontsize , $blogtitle_fontfamily , $blogdate_color , $blogdate_fontsize , $blogdate_fontfamily , $blogposttitle_color , $blogposttitle_fontsize , $blogposttitle_fontfamily , $bloglinks_color , $bloglinks_fontsize , $bloglinks_fontfamily , $postedby_color , $postedby_fontsize , $postedby_fontfamily , $arrow_color , $arrow_fontsize );
        $tmplid = bcsql_escape( $tmplid );
        $sidebarcontainerleft_width = bcsql_escape( $sidebarcontainerleft_width );
        $sidebarcontainerleft_bgcolor = bcsql_escape( $sidebarcontainerleft_bgcolor );
        $sidebarcontainerright_width = bcsql_escape( $sidebarcontainerright_width );
        $sidebarcontainerright_bgcolor = bcsql_escape( $sidebarcontainerright_bgcolor );
        $aielposts_width = bcsql_escape( $aielposts_width );
        $aielposts_bgcolor = bcsql_escape( $aielposts_bgcolor );
        $aielposts_color = bcsqql_escape( $aielposts_color );
        $aielposts_fontsize = bcsql_escape( $aielposts_fontsize );
        $aielposts_fontfamily = bcsql_escape( $aielposts_fontfamily );
        $blogtitle_color =  bcsql_escape( $blogtitle_color );
        $blogtitle_fontsize = bcsql_escape( $blogtitle_fontsize );
        $blogtitle_fontfamily = bcsql_escape( $blogtitle_fontfamily );
        $blogdate_color = bcsql_escape( $blogdate_color );
        $blogdate_fontsize = bcsql_escape( $blogdate_fontsize );
        $blogdate_fontfamily = bcsql_escape( $blogdate_fontfamily );
        $blogposttitle_color = bcsql_escape( $blogposttitle_color );
        $blogposttitle_fontsize = bcsql_escape( $blogposttitle_fontsize );
        $blogposttitle_fontfamily = bcsql_escape( $blogposttitle_fontfamily );
        $bloglinks_color = bcsql_escape( $bloglinks_color );
        $bloglinks_fontsize = bcsql_escape( $bloglinks_fontsize );
        $bloglinks_fontfamily = bcsql_escape( $bloglinks_fontfamily );
        $postedby_color = bcsql_escape( $postedby_color );
        $postedby_fontsize = bcsql_escape( $postedby_fontsize );
        $postedby_fontfamily = bcsql_escape( $postedby_fontfamily );
        $arrow_color = bcsql_escape( $arrow_color );
        $arrow_fontsize = bcsql_escape( $arrow_fontsize );
        $query ="INSERT INTO `$templatestyles` ( `tmpls_id` , `tmpls_tmplid` , `tmpls_sidebarcontainerleft_width` , `tmpls_sidebarcontainerleft_background-color` , `tmpls_sidebarcontainerright_width` , `tmpls_sidebarcontainerright_background-color` , `tmpls_aielposts_width` , `tmpls_aielposts_background-color` , `tmpls_aielposts_color` , `tmpls_aielposts_font-size` , `tmpls_aielposts_font-family` , `tmpls_blogtitle_color` , `tmpls_blogtitle_font-size` , `tmpls_blogtitle_font-family` , `tmpls_blogdate_color` , `tmpls_blogdate_font-size` , `tmpls_blogdate_font-family` , `tmpls_blogposttitle_color` , `tmpls_blogposttitle_font-size` , `tmpls_blogposttitle_font-family` , `tmpls_bloglinks_color` , `tmpls_bloglinks_font-size` , `tmpls_bloglinks_font-family` , `tmpls_postedby_color` , `tmpls_postedby_size` , `tmpls_postedby_font-family` , `tmpls_arrow_color` , `tmpls_arrow_font-size` )
                VALUES ( '' , '$tmplid' , '$sidebarcontainerleft_width' , '$sidebarcontainerleft_bgcolor' , '$sidebarcontainerright_width' , '$sidebarcontainerright_bgcolor' , '$aielposts_width' , '$aielposts_bgcolor' , '$aielposts_color' , '$aielposts_fontsize' , '$aielposts_fontfamily' , '$blogtitle_color' , '$blogtitle_fontsize' , '$blogtitle_fontfamily' , '$blogposttitle_color' , '$blogposttitle_fontsize' , '$blogposttitle_fontfamily' , '$bloglinks_color' , '$bloglinks_fontsize' , '$bloglinks_fontfamily' , '$postedby_color' , '$postedby_fontsize' , '$postedby_fontfamily' , '$arrow_color' , '$arrow_fontsize' );";
        bcsql_query( $query );
    }
    function write_tocss( $userid , $tmplid , $sidebarcontainerleft_width , $sidebarcontainerleft_bgcolor , $sidebarcontainerright_width , $sidebarcontainerright_bgcolor , $aielposts_width , $aielposts_bgcolor , $aielposts_color , $aielposts_fontsize , $aielposts_fontfamily , $blogtitle_color , $blogtitle_fontsize , $blogtitle_fontfamily , $blogdate_color , $blogdate_fontsize , $blogdate_fontfamily , $blogposttitle_color , $blogposttitle_fontsize , $blogposttitle_fontfamily , $bloglinks_color , $bloglinks_fontsize , $bloglinks_fontfamily , $postedby_color , $postedby_fontsize , $postedby_fontfamily , $arrow_color , $arrow_fontsize ) {
        // *.css files are written in /home/templates/(beta or stable)/userid/tmpid.css, the directory has to be created
        
        $userfolder = $templatesdir."/".$userid;
        $userfile = $userfolder."/".$tmplid;
        //writing the string that contains all styling
        $px = "px;";
        $percent = "%;";
        $semcolon = ";";
        $fonts = "pt;";
        $styling = "
        td.sidebarcontainerleft {
            width: $sidebarcontainerleft_width.$percent
            background-color: $sidebarcontainerleft_bgcolor.$semcolon
        }
        td.sidebarcontainerright {
            width: $sidebarcontainerright_width.$percent
            background-color: $sidebarcontainerright_bgcolor.$semcolon
        }
        td.aielposts {
            width: $aielposts_width.$percent
            background-color: $aielposts_bgcolor.$semcolon
            color: $aielposts_color.$semcolon
            font-size: $aielposts_fontsize.$fonts
            font-family: $aielposts_fontfamily.$semcolon
        }
        span.blogtitle {
            color: $blogtitle_color.$semcolon
            font-size: $blogtitle_fontsize.$fonts;
            font-family: $blogtitle_fontfamily.$semcolon
            font-weight: bold;
            padding-top: 70px;
            vertical-align: middle;
            cursor: default;
        }
        span.blogdate {
            display: block;
            font-weight: bold;
            cursor: default;
            padding-bottom: 13px;
            color: $blogdate_color.$semcolon
            font-size: $blogdate_fontsize.$fonts;
            //font-family: $blogdate_fontfamily.$semcolon
        }
        span.blogpostttitle {
            color: $blogposttitle_color.$semcolon
            font-size: $blogposttitle_fontsize.$fonts
            font-family: $blogposttitle_fontfamily.$semcolon
            letter-spacing: 0;
            display: inline;
            cursor: default;
            margin-bottom: 4px;
            font-weight: bold;
        }
        a.bloglinks:link, a.bloglinks:active, a.bloglinks:visited {
            color: $bloglinks_color.$semcolon
            font-size: $bloglinks_fontsize.$fonts
            font-family: $bloglinks_fontfamily.$semcolon
            border-style: none;
            border-bottom: 1px solid #3366cc;
            padding: 0 0 0 0;
            margin: 0 0 0 0;
            text-decoration: none;
            border-bottom-style: none;
        }
        
        a.bloglinks:hover {
            color: $bloglinks_hover_color.$semcolon
            background-color: $bloglinks_hover_background_color.$semcolon
            border-bottom: 1px solid #55ad61;
            border-bottom-style: none;
        }
        
        span.postedby {
            color: $postedby_color.$semcolon
            font-size: $postedby_fontsize.$fonts
            font-family: $postedby_fontfamily.$semcolon
        }
        
        span.arrow {
            color: $arrow_color.$semcolon
            font-size: $arrow_fontsize.$fonts
        }
        ";
        //end of string
        //remove span.arrow
        if ( file_exists( $userfile ) ) {
            unlink( $userfile );
        }
        if ( !file_exists( $userfolder ) ) {
            mkdir( $userfolder );
        }
        $fp = fopen( $userfile , "w" );
        fwrite( $fp , $styling );
        fclose( $fp );
    }
    class template {
        var $t_id;
        var $t_name;
        var $t_userid;
        var $t_crdate;
        var $t_moddate;
        var $t_ip;
        var $t_privid;
        var $t_sidebarcontainerleft_width;
        var $t_sidebarcontainterleft_bgcolor;
        var $t_sidebarcontainerright_width;
        var $t_sidebarcontainerright_bgcolor;
        var $t_aielposts_width;
        var $t_aielposts_bgcolor;
        var $t_aielposts_color;
        var $t_aielposts_fontsize;
        var $t_aielposts_fontfamily;
        var $t_blogtitle_color;
        var $t_blogtitle_fontsize;
        var $t_blogtitle_fontfamily;
        var $t_blogdate_color;
        var $t_blogdate_fontsize;
        var $t_blogdate_fontfamily;
        var $t_blogposttitle_color;
        var $t_blogposttitle_fontsize;
        var $t_blogposttitle_fontfamily;
        var $t_bloglinks_color;
        var $t_bloglinks_fontsize;
        var $t_bloglinks_fontfamily;
        var $t_postedby_color;
        var $t_postedby_fontsize;
        var $t_postedby_fontfamily;
        var $t_arrow_color;
        var $t_arrow_fontsize;
    
        function id() {
            return $this->t_id;
        }
        function name() {
            return $this->t_name;
        }
        function userid() {
            return $this->t_userid;
        }
        function crdate() {
            return $this->t_crdate;
        }
        function mod_date() {
            return $this->t_moddate;
        }
        function ip() {
            return $this->t_ip;
        }
        function privid() {
            return $this->t_privid;
        }
        function sidebarcontainerleft_width() {
            return $this->t_sidebarcontainerleft_width;
        }
        function sidebarcontainterleft_bgcolor() {
            return $this->t_sidebarcontainterleft_bgcolor;
        }
        function sidebarcontainerright_width() {
            return $this->t_sidebarcontainerright_width;
        }
        function sidebarcontainerright_bgcolor() {
            return $this->t_sidebarcontainerright_bgcolor;
        }
        function aielposts_width() {
            return $this->t_aielposts_width;
        }
        function aielposts_bgcolor() {
            return $this->t_aielposts_bgcolor;
        }
        function aielposts_color() {
            return $this->t_aielposts_color;
        }
        function aielposts_fontsize() {
            return $this->t_aielposts_fontsize;
        }
        function aielposts_fontfamily() {
            return $this->t_aielposts_fontfamily;
        }
        function blogtitle_color() {
            return $this->t_blogtitle_color;
        }
        function blogtitle_fontsize() {
            return $this->t_blogtitle_fontsize;
        }
        function blogtitle_fontfamily() {
            return $this->t_blogtitle_fontfamily;
        }
        function blogdate_color() {
            return $this->t_blogdate_color;
        }
        function blogdate_fontsize() {
            return $this->t_blogdate_fontsize;
        }
        function blogdate_fontfamily() {
            return $this->t_blogdate_fontfamily;
        }
        function blogposttitle_color() {
            return $this->t_blogposttitle_color;
        }
        function blogposttitle_fontsize() {
            return $this->t_blogposttitle_fontsize;
        }
        function blogposttitle_fontfamily() {
            return $this->t_blogposttitle_fontfamily;
        }
        function bloglinks_color() {
            return $this->t_bloglinks_color;
        }
        function bloglinks_fontsize() {
            return $this->t_bloglinks_fontsize;
        }
        function bloglinks_fontfamily() {
            return $this->t_bloglinks_fontfamily;
        }
        function postedby_color() {
            return $this->t_postedby_color;
        }
        function postedby_fontsize() {
            return $this->t_postedby_fontsize;
        }
        function postedby_fontfamily() {
            return $this->t_postedby_fontfamily;
        }
        function arrow_color() {
            return $this->t_arrow_color;
        }
        function arrow_fontsize() {
            return $this->t_arrow_fontsize;
        }

        function update_style( $styling_attr , $newvalue ) {
            switch ( $styling_attr ) {
                case "tmpls_sidebarcontainerleft_width":
                    $this->t_sidebarcontainerleft_width = $newvalue;
                    break;
                case "tmpls_sidebarcontainerleft_background-color":
                    $this->t_sidebarcontainterleft_bgcolor = $newvalue;
                    break;
                case "tmpls_sidebarcontainerright_width":
                    $this->t_sidebarcontainerright_width = $newvalue;
                    break;
                case "tmpls_sidebarcontainerright_background-color":
                    $this->t_sidebarcontainerright_bgcolor = $newvalue;
                    break;
                case "tmpls_aielposts_width":
                    $this->t_aielposts_width = $newvalue;
                    break;
                case "tmpls_aielposts_background-color":
                    $this->t_aielposts_bgcolor = $newvalue;
                    break;
                case "tmpls_aielposts_color":
                    $this->t_aielposts_color = $newvalue;
                    break;
                case "tmpls_aielposts_font-size":
                    $this->t_aielposts_fontsize = $newvalue;
                    break;
                case "tmpls_aielposts_font-family":
                    $this->t_aielposts_fontfamily = $newvalue;
                    break;
                case "tmpls_blogtitle_color":
                    $this->t_blogtitle_color = $newvalue;
                    break;
                case "tmpls_blogtitle_font-size":
                    $this->t_blogtitle_fontsize = $newvalue;
                    break;
                case "tmpls_blogtitle_font-family":
                    $this->t_blogtitle_fontfamily = $newvalue;
                    break;
                case "tmpls_blogdate_color":
                    $this->t_blogdate_color = $newvalue;
                    break;
                case "tmpls_blogdate_font-size":
                    $this->t_blogdate_fontsize = $newvalue;
                    break;
                case "tmpls_blogdate_font-family":
                    $this->t_blogdate_fontfamily = $newvalue;
                    break;
                case "tmpls_blogposttitle_color":
                    $this->t_blogposttitle_color = $newvalue;
                    break;
                case "tmpls_blogposttitle_font-size":
                    $this->t_blogposttitle_fontsize = $newvalue;
                    break;
                case "tmpls_blogposttitle_font-family":
                    $this->t_blogposttitle_fontfamily = $newvalue;
                    break;
                case "tmpls_bloglinks_color":
                    $this->t_bloglinks_color = $newvalue;
                    break;
                case "tmpls_bloglinks_font-size":
                    $this->t_bloglinks_fontsize = $newvalue;
                    break;
                case "tmpls_bloglinks_font-family":
                    $this->t_bloglinks_fontfamily = $newvalue;
                    break;
                case "tmpls_postedby_color":
                    $this->t_postedby_color = $newvalue;
                    break;
                case "tmpls_postedby_size":
                    $this->t_postedby_fontsize = $newvalue;
                    break;
                case "tmpls_postedby_font-family":
                    $this->t_postedby_fontfamily = $newvalue;
                    break;
                case "tmpls_arrow_color":
                    $this->t_arrow_color = $newvalue;
                    break;
                case "tmpls_arrow_font-size":
                    $this->t_arrow_fontsize = $newvalue;
                    break;
                default:
                    bc_die( "The styling attribute you provided is incorrect. Styling attribute: ".$styling_attr );
            }
            //updating the database
            $query  = "UPDATE `$templatestyles` SET `$styling_attr` = '$newvalue' WHERE `tmpls_id` = '$this->t_id';";
            bcsql_query( $query );
            $today = NowDate();
            $query = "UPDATE `$templatenames` SET `tmpln_mddate` = '$today' WHERE `tmpln_id` = '$this->t_id';";
            bcsql_query( $query );
            //writing to the css file
            write_tocss( $this->t_userid , $this->t_id , $this->t_sidebarcontainerleft_width , $this->t_sidebarcontainterleft_bgcolor , $this->t_sidebarcontainerright_width , $this->t_sidebarcontainerright_bgcolor , $this->t_aielposts_width , $this->t_aielposts_bgcolor , $this->t_aielposts_color , $this->t_aielposts_fontsize , $this->t_aielposts_fontfamily , $this->t_blogtitle_color , $this->t_blogtitle_fontsize , $this->t_blogtitle_fontfamily , $this->t_blogdate_color , $this->t_blogdate_fontsize , $this->t_blogdate_fontfamily , $this->t_blogposttitle_color , $this->t_blogposttitle_fontsize , $this->t_blogposttitle_fontfamily , $this->t_bloglinks_color , $this->t_bloglinks_fontsize , $this->t_bloglinks_fontfamily , $this->t_postedby_color , $this->t_postedby_fontsize , $this->t_postedby_fontfamily , $this->t_arrow_color , $this->t_arrow_fontsize );
        }
        
        function template( $tmplid ) {
            if ( ValidId( $tmplid ) ) {
                $query = "SELECT * FROM `$templatenames` WHERE `tmpln_id`='$tmplid' LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $num_rows = bcsql_num_rows( $sqlr );
                if ( $num_rows == 1 ) {
                    $templates = bcsql_fetch_array( $sqlr );
                    $this->t_id = $templates[ "tmpln_id" ];
                    $this->t_name = $templates[ "tmpln_name" ];
                    $this->t_userid = $templates[ "tmpln_userid" ];
                    $this->t_crdate = $templates[ "tmpln_crdate" ];
                    $this->t_moddate = $templates[ "tmpln_mddate" ];
                    $this->t_ip = $templates[ "tmpln_ip" ];
                    $this->t_privid = $templates[ "tmpln_privid" ];
                    $this->t_sidebarcontainerleft_width = $templates[ "tmpls_sidebarcontainerleft_width" ];
                    $this->t_sidebarcontainterleft_bgcolor = $templates[ "tmpls_sidebarcontainerleft_background-color" ];
                    $this->t_sidebarcontainerright_width = $templates[ "tmpls_sidebarcontainerright_width" ];
                    $this->t_sidebarcontainerright_bgcolor = $templates[ "tmpls_sidebarcontainerright_background-color" ];
                    $this->t_aielposts_width = $templates[ "tmpls_aielposts_width" ];
                    $this->t_aielposts_bgcolor = $templates[ "tmpls_aielposts_background-color" ];
                    $this->t_aielposts_color = $templates[ "tmpls_aielposts_color" ];
                    $this->t_aielposts_fontsize = $templates[ "tmpls_aielposts_font-size" ];
                    $this->t_blogtitle_color = $templates[ "tmpls_blogtitle_color" ];
                    $this->t_blogtitle_fontsize = $templates[ "tmpls_blogtitle_font-size" ];
                    $this->t_blogtitle_fontfamily = $templates[ "tmpls_blogtitle_font-family" ];
                    $this->t_blogdate_color = $templates[ "tmpls_blogdate_color" ];
                    $this->t_blogdate_fontsize = $templates[ "tmpls_blogdate_font-size" ];
                    $this->t_blogdate_fontfamily = $templates[ "tmpls_blogdate_font-family" ];
                    $this->t_blogposttitle_color = $templates[ "tmpls_blogposttitle_color" ];
                    $this->t_blogposttitle_fontsize = $templates[ "tmpls_blogposttitle_font-size" ];
                    $this->t_blogposttitle_fontfamily = $templates[ "tmpls_aielposts_font-family" ];
                    $this->t_bloglinks_color = $templates[ "tmpls_blogtitle_color" ];
                    $this->t_bloglinks_fontsize = $templates[ "tmpls_blogtitle_font-size" ];
                    $this->t_bloglinks_fontfamily = $templates[ "tmpls_blogtitle_font-family" ];
                    $this->t_postedby_color = $templates[ "tmpls_blogdate_color" ];
                    $this->t_postedby_fontsize = $templates[ "tmpls_blogdate_font-size" ];
                    $this->t_postedby_fontfamily = $templates[ "tmpls_postedby_font-family" ];
                    $this->t_arrow_color = $templates[ "tmpls_arrow_color" ];
                    $this->t_arrow_fontsize = $templates[ "tmpls_arrow_font-size" ];
                }
                else {
                        bc_die( "Template constructor returns more than one result" );
                }
            }
            else {
                bc_die( "Not valid template id. Id: ".$tmplid );
            }
            
        }
    
    }
    
    function delete_templates( $tmplid ) {
        if ( ValidId( $tmplid ) ) {
            $query = "DELETE FROM `$templatenames` WHERE `tmpln_id`='$tmplid';";
            bcsql_query( $query );
            $query = "DELETE FROM `$templatestyles` WHERE `tmpls_tmplid`='$tmplid';";
            bcsql_query( $query );
        }
        else {
            bc_die( "Not valid template id. Template id: ".$tmplid );
        }
    }
    
    
    
    
    
    
    
    
    
    
    
?>