<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    if( $showingavatars !== true ) {
        bc_die( "Element not directly accessible" ); // /elements/blogging/profile/profile_edit
    }

    $defaultavatar = $this_avatar->id() == $user->Avatar();
    $this_avatarmediaid = $this_avatar->Id();
    $realtext = RemoveExtension( $this_avatar->filename() );
    ?><div class="avatar_td<?php
    if( $defaultavatar ) {
        ?>_default<?php
    }
    ?>" id="viewavatar_<?php
    echo $this_avatarmediaid;
    ?>"<?php
    /*if( !$defaultavatar ) {*/
        ?> onmouseover="avatarover(<?php
        echo $this_avatarmediaid;
        ?>)" onmouseout="avatarout(<?php
        echo $this_avatarmediaid;
        ?>)"<?php
    /*}*/
    ?> style="background-repeat:repeat-x;background-position:top;"><table style="width:100%"><tr><td style="width:100%">
    <div style="float:left;margin-right:4px;margin-top:4px;margin-bottom:4px;"><?php    
        img( "download.bc?id=" . $this_avatarmediaid , "Avatar" , $realtext , 64 , 64 ); 
    ?></div><?php
    i/*f( !$defaultavatar ) {*/
        ?><div style="float:right;font-weight:bold;display:none" id="avatarcontrolbx_<?php
        echo $this_avatarmediaid;
        ?>"><a href="javascript:avatardefault('<?php
        echo $this_avatar->avatar_id();
        ?>','<?php
        echo $this_avatarmediaid;
        ?>')" style="text-decoration:none" title="Make this your Default Avatar"><?php
        img( "images/bluetick.png" , "Tick" , "Make this your Default Avatar" , 11 , 16 , "border-style:none" );
        ?></a><a href="javascript:avatardelete('<?php
        echo $this_avatarmediaid;
        ?>')" style="text-decoration:none" title="Delete this Avatar">&times;</a></div><?php
    /*}*/
    ?>
    <div style="padding-top:4px"><h4 title="<?php
    echo $realtext;
    ?>"><?php
    if( $defaultavatar ) {
        $bgcolor = New RGBColor( 204 , 214 , 238 );
    }
    else {
        $bgcolor = New RGBColor( 227 , 233 , 249 );
    }
    echo TextFadeOut( $realtext , New RGBColor( 59 , 122 , 191 ) , $bgcolor , 18 );
    ?></h4><br /><div id="avatartext_<?php
    echo $this_avatarmediaid;
    ?>"><span style="font-size:85%"><?php
    echo HumanSize( $this_avatar->size() );
    ?>, <?php
    echo BCDate( $this_avatar->timestamp() );
    ?></span>
    <div class="inline" id="avatardeftext_<?php
        echo $this_avatarmediaid;
    ?>" <?php
    if (!$defaultavatar) {
        ?>style="display: none;"<?php
    }
    ?>><br /><b>Default Avatar</b></div>
    </div></div></td></tr></table></div>