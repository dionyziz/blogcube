<?php
    /*
        Developer: dionyziz
    */

    // replacement as an icon for things that we don't have an icon for
    $arrow = "<span class=\"arrow\">&raquo;</span>&nbsp;";

    function PopupMenu( $items ) {
        $popupid = uid();
        $pid = "popup_" . $popupid;
        
        ?><div id="<?php
        echo $pid;
        ?>" class="popupmnu" style="display:none"><table><?php
        for( $i = 0 ; $i < count( $items ) ; $i++ ) {
            $thisitem = $items[ $i ];
            $title = $thisitem[ 0 ];
            $url = $thisitem[ 1 ];
            ?><tr><td class="popupitem"><a href="<?php // moved here by ch-world: onclick="a('popupclicked');
            echo $url; // comment removed by ch-world
            ?>"><?php
            echo $title;
            ?></a></td></tr><?php
        }
        ?></table></div><?php
        
        return $pid;
    }
    
    function PopupAnchor( $targetpopup ) {
        ?>oncontextmenu="return PopupMenu('<?php
        echo $targetpopup;
        ?>', event);" <?php // moved to here by ch-world: onblur="PopupUnmenu()"
    }
    
    $guiwindow_z = 500;
    
    function gui_window2( $windowtitle , $windowcontents , $id = 0 , $left = -1 , $top = -1 , $right = -1 , $bottom = -1 , $modal = false ) {
        global $guiwindow_z, $bfc;
        
        if ( $id == 0 ) {
            $id = 'bcwin_' . rand(0, 1234567890);
        }
        ?><div id="<?php
        echo $id;
        ?>" class="bcwindow" style="<?php
        if ( $left != -1 || $top != -1 || $right != -1 || $bottom != -1 ) {
            if ( $left != -1 ) {
                ?>left:<?php
                echo $left;
                ?>px;<?php
            }
            else if ( $right != -1 ) {
                ?>right:<?php
                echo $right;
                ?>px;<?php
            }
            if ( $top != -1 ) {
                ?>top:<?php
                echo $top;
                ?>px;<?php
            }
            else if ( $bottom != -1 ) {
                ?>bottom:<?php
                echo $bottom;
                ?>px;<?php
            }
        }
        ?>z-index:<?php
        echo $guiwindow_z++;
        ?>;">
        <div onmousedown="grab('<?php
        echo $id;
        ?>',event)" class="bcwintitle"><div style="display:inline;"><?php
        $js = 'g(\'' . $id . '\').style.display=\'none\';';
        if ( $modal ) {
            $js .= 'Modalize(false)';
        }
        DropDownMenu( array(
            array( 'caption' => 'Close' , 'js' => $js )
        ) );
        ?></div><div style="display:inline;text-align:left"><?php
        echo $windowtitle;
        ?></div></div><div class="bcwincontent"><?php
        echo $windowcontents;
        ?></div>
        </div><?php
        if ( $modal ) {
            $bfc->start();
            ?>Modalize(true);<?php
            $bfc->end();
        }
        return $id;
    }

    function DropDownMenu( $options , $totheright = false ) {
        $id = 'dropdown_' . rand(0, 9876543210);
        ?><div class="dropdownparent"><a href="" onclick="DropDown.toggle('<?php
        echo $id;
        ?>');return false;" class="dropdownlink"><?php
        img('images/silk/dropdown.png', 'v', 'Click to show drop-down menu');
        ?></a><div class="dropdownbox dropdownbox<?php
        if ( $totheright ) {
            ?>right<?php
        }
        else {
            ?>left<?php
        }
        ?>" style="display:none" id="<?php
        echo $id;
        ?>"><?php
        $imagesexist = false;
        foreach( $options as $option ) {
            if ( isset( $option['image'] ) ) {
                $imagesexist = true;
            }
        }
        foreach( $options as $option ) {
            ?><div class="dropdownitem"><a href="" onclick="DropDown.off('<?php
            echo $id;
            ?>');<?php
            echo $option['js'];
            ?>;return false;" style="text-decoration:none;<?php
                if (( !$option['image'] ) && ( $imagesexist )) {
                    ?>padding-left:20px;<?php
                }
            ?>"><?php
            if ( isset( $option['image'] ) ) {
                img( $option['image'] );
            }
            ?> <?php
            echo $option['caption'];
            ?></a></div><?php
        }
        ?></div></div><?php
    }

    function gui_hide( $message = "" , $escapesinglequote = false , $extracode = "" ) {
        // deprecated
        global $target;
        
        if ( $message == "" )
            $message = "&times;";
            
        $quot = $escapesinglequote ? "\\" : "";
        
        ?>&nbsp;<a href="javascript:de(<?php echo $quot; ?>'<?php 
        echo $target; 
        echo $quot;
        ?>',<?php echo $quot; ?>'nothing<?php echo $quot; ?>');<?php str_replace( "'" , "$quot'" , $extracode ); ?>"><?php echo $message; ?></a><?php
    }
    
    function LinkList( $linksarray ) {
        global $arrow;
        
        $links = Array();
        $k = array_keys( $linksarray );
        for( $i = 0 ; $i < count( $k ) ; $i++ ) {
            $links[] = Array( $k[ $i ] , $linksarray[ $k[ $i ] ] , "" );
        }
        IconLL( $links );
    }
    
    function IconLL( $iconlinksarray ) {
        global $arrow;
        
        ?><table><?php
        for( $i = 0 ; $i < count( $iconlinksarray ) ; $i++ ) {
            $thislink = $iconlinksarray[ $i ];
            $text = $thislink[ 0 ];
            $href = $thislink[ 1 ];
            $icon = $thislink[ 2 ];
            $id   = $thislink[ 3 ];
            ?><tr><td class="hpitm"><?php
            if( $icon ) {
                img( "images/nuvola/" . $icon . ".png" );
            }
            else {
                echo $arrow;
            }
            ?> <a href="<?php
            echo $href;
            ?>"<?php
            if( $id ) {
                ?> id="<?php
                echo $id;
                ?>"<?php
            }
            ?>><?php
            echo $text;
            ?></a></td></tr><?php
        }
        ?></table><?php
    }
    
    function h3( $title , $icon = "" ) {
        if( $icon ) {
            ?><table><tr><td><?php
            img( "images/nuvola/" . $icon . ".png" );
            ?></td><td><?php
        }
        ?><h3><?php
        echo $title;
        ?></h3><?php
        if( $icon ) {
            ?></td></tr></table><?php
        }
        ?><br /><?php
    }
    
    function h4( $title , $icon = "" ) {
        if( $icon ) {
            ?><table><tr><td><?php
            img( "images/nuvola/" . $icon . ".png" );
            ?></td><td><?php
        }
        ?><h4><?php
        echo $title;
        ?></h4><?php
        if( $icon ) {
            ?></td></tr></table><?php
        }
        ?><br /><?php
    }
    
    function hr() {
        ?><hr noshade="noshade" size="1" /><?php
    }
    
    function img( $src , $alt = "" , $title = "" , $width = 0 , $height = 0 , $xstyle = "" , $ietrasparency = true ) {
        $actfl = strpos( $src , "?" );
        if( $actfl !== false ) {
            $imagefh = true;
        }
        else {
            $imagefh = @fopen( $src , "r" );
        }
        if( $imagefh ) {
            if( $actfl !== false ) {
                $imgbin = true;
            }
            else {
                $imgbin = fread( $imagefh , filesize( $src ) );
            }
            if( $imgbin ) {
                if( $actfl === false ) {
                    $img = imagecreatefromstring( $imgbin );
                }
                if( $width ) {
                    $w = $width;
                }
                else {
                    if( $actfl === false ) {
                        $w = imagesx( $img );
                    }
                    else {
                        bc_die( "You *have* to pass image size to the img() function if the image is a php-generated image" );
                    }
                }
                if( $height ) {
                    $h = $height;
                }
                else {
                    if( $actfl === false ) {
                        $h = imagesy( $img );
                    }
                    else {
                        bc_die( "You *have* to pass image size to the img() function if the image is a php-generated image" );
                    }
                }
                ?><img src="<?php
                if( UserBrowser() == "MSIE" ) {
                    echo "images/blank.gif";
                }
                else {
                    echo $src;
                }
                ?>"<?php
                if( $alt ) {
                    ?> alt="<?php
                    echo $alt;
                    ?>"<?php
                }
                if( $title ) {
                    ?> title="<?php
                    echo $title;
                    ?>"<?php
                }
                ?> style="width:<?php
                echo $w;
                ?>px;height:<?php
                echo $h;
                ?>px;<?php
                if( UserBrowser() == "MSIE" ) {
                    ?>filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php
                        echo $src;
                    ?>',sizingMethod='scale');<?php
                }
                if( $xstyle ) {
                    echo $xstyle;
                }
                ?>" /><?php
                return;
            }
        }
        ?>(error loading image "<?php
        echo $src;
        ?>")<?php
    }
    
    function BCTip( $tip ) {
        if( !$tip ) {
            $tip = "&nbsp;";
        }

        img('images/nuvola/tip.png');
        ?> <?php
        echo $tip;
        return;
    }
    
    function BCTipNew( $tip ) {
        ?><table cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top"><img src="images/tiptl.png" /></td><td class="tiptop"></td><td><img src="images/tiptr.png" /></td></tr>
        <tr><td class="tipleft"></td><td class="tiptip"><?php
        img('images/nuvola/tip.png');
        ?> <?php
        echo $tip;
        ?></td><td class="tipright">&nbsp;</td></tr>
        <tr><td style="vertical-align:bottom"><img src="images/tipbl.png" /></td><td class="tipbottom">&nbsp;</td><td style="vertical-align:bottom"><img src="images/tipbr.png" /></td></tr></table><?php
    }
    

    function TextFadeOut( $string , $startcolor = "" , $endcolor = "" , $maxlen = 6 , $fadechars = 6 ) {
        if ( $startcolor == "" ) {
            $startcolor = New RGBColor( 0 , 0 , 0 );
        }
        if ( $endcolor == "" ) {
            $endcolor = New RGBColor( 255 , 255 , 255 );
        }
        if ( strlen( $string ) > $maxlen ) {
            $s = "";
            $ist = $st = $maxlen - $fadechars + 1;
            $colormix = New RGBColor( $endcolor->R() - $startcolor->R() , $endcolor->G() - $startcolor->G() , $endcolor->B() - $startcolor->B() );
            for( $i = 0; $i < $fadechars + 1; $i++ ) {
                $colorpart = $i / ( $fadechars + 1 );
                $resultcolor = New RGBColor( 
                    $colormix->R() * $colorpart + $startcolor->R() , 
                    $colormix->G() * $colorpart + $startcolor->G() , 
                    $colormix->B() * $colorpart + $startcolor->B() 
                );
                $rgb = $resultcolor->CSS();
                $s .= "<span style=\"color:$rgb;\">" . utf8_substr( $string , $st , 1 ) . "</span>";
                $st++;
            }
            $res = substr( $string, 0 , $ist ) . $s;
            return $res;
        }
        else {
            return $string;
        }
    }
    
    class RGBColor {
        protected $mR;
        protected $mG;
        protected $mB;
        protected $mCSS;
        
        public function R() {
            return $this->mR;
        }
        public function G() {
            return $this->mG;
        }
        public function B() {
            return $this->mB;
        }
        public function CSS() {
            return $this->mCSS;
        }
        public function Set( $r , $g , $b ) {
            $this->mR = $r;
            $this->mG = $g;
            $this->mB = $b;
            $this->mCSS = "rgb(" . intval( $this->mR ) . "," . intval( $this->mG ) . "," . intval( $this->mB ) . ")";
        }
        public function RGBColor( $r , $g , $b ) {
            $this->Set( $r , $g , $b );
        }
    }

    class RGBColorTransformation extends RGBColor {
        private function mLighten( $value , $offset ) {
            return 255 - ( 255 - $value ) * $offset;
        }
        public function Lighten( $offset = 0.25 ) {
            $this->mR = $this->mLighten( $this->mR , $offset );
            $this->mG = $this->mLighten( $this->mG , $offset );
            $this->mB = $this->mLighten( $this->mB , $offset );
            $this->Set( $this->mR , $this->mG , $this->mB );
        }
        public function RGBColorTransformation( $r , $g , $b ) {
            $this->RGBColor( $r , $g , $b );
        }
    }
    
    function EarthGo( $postsubmit = "" , $postupload = "" , $backgroundcolor = "#ccd6ee" , $foregroundcolor = "black" ) {
        global $allow;
        
        $earthcall = true;
        $earthpostsubmit = $postsubmit;
        $earthpostupload = $postupload;
        $earthbackgroundcolor = $backgroundcolor;
        $earthforegroundcolor = $foregroundcolor;
        include "elements/blogging/earth/earthform.php";
    }
?>