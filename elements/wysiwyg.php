<?php
    $random = new Random();
    if( !$wysid ) {
        $code = $random->get( 5 );
    }
    else {
        $code = $wysid;
    }
    $bfc->start();
    ?>
    lastwsicode='<?php
    echo $code;
    ?>';
    <?php
    $bfc->end();
    
    function WYSIWYGToolbarButton( $imgsrc , $alt , $title , $function ) {
        ?><div onclick="BCWYS<?php
        echo $function;
        ?>()" style="display:inline"><?php
            img( "images/coi/$imgsrc" . "22.png" , $alt , $title );
        ?></div><?php
    }
    ?><table class="wysiwygtoolbar" style="width:100%;height:20px"><?php
    if( $wysiwygtop != "" ) {
        ?><tr><td colspan="7"><?php
        echo $wysiwygtop;
        $wysiwygtop = "";
        ?></td></tr><?php
    }
    ?><tr><td class="wy"><?php
    WYSIWYGToolbarButton( "bold" , "Bold" , "Bold" , "Strong" );
    WYSIWYGToolbarButton( "italic" , "Italics" , "Italics" , "Italix" );
    WYSIWYGToolbarButton( "underline" , "Underline" , "Underline" , "Underbar" );
    WYSIWYGToolbarButton( "strikout" , "Strikeout" , "Strikeout" , "Strike" );
    ?></td><td class="wy"><?php
    WYSIWYGToolbarButton( "alignleft" , "Align Left" , "Align Left" , "AlignL" );
    WYSIWYGToolbarButton( "aligncenter" , "Align Center" , "Align Center" , "AlignC" );
    WYSIWYGToolbarButton( "alignright" , "Align Right" , "Align Right" , "AlignR" );
    ?></td><td class="wy"><?php
    WYSIWYGToolbarButton( "picture" , "Picture" , "Insert Picture" , "InsertPicture" );
    WYSIWYGToolbarButton( "world" , "Link" , "Make Link" , "Link" );
    WYSIWYGToolbarButton( "hr" , "Line" , "Insert Horizontal Line" , "Hr" );
    ?></td><td class="wy"><?php
    WYSIWYGToolbarButton( "ul" , "Bullet List" , "Insert Bullet List" , "Ul" );
    WYSIWYGToolbarButton( "ol" , "Numerical List" , "Insert Numerical List" , "Ul" );
    ?></td><td class="wy"><?php
    WYSIWYGToolbarButton( "undo" , "Undo" , "Undo" , "Woops" );
    WYSIWYGToolbarButton( "redo" , "Redo" , "Redo" , "Unwoops" );
    ?></td><td class="wy"><?php
    WYSIWYGToolbarButton( "superscript" , "Superscript" , "Superscript" , "Supdo" );
    WYSIWYGToolbarButton( "underscript" , "Underscript" , "Underscript" , "Subdo" );
    ?></td><td class="wy"><?php
    WYSIWYGToolbarButton( "sum" , "Insert Formula" , "Insert Formula" , "Formula" );
    ?></td></tr>
    <tr>
        <td colspan="7" class="bcuteedif" id="mathtobpar" style="width:600px;height:1px"><div id="mathtoolbarp" class="inline"><div id="mathtoolbar" class="inline"></div></div></td>
    </tr>
    <tr>
        <td colspan="7" class="bcuteedif" id="wyswgreal" style="width:600px">
            <div id="wyswgrealr">
                <iframe id="wsiareaif_<?php
                    echo $code;
                ?>" src="cubism.bc?g=post<?php if ($postedit) echo ( "&id=" . $post->Id() ); ?>" frameborder="no" onload="initwsi('<?php
                echo $code;
                $wysid = $code;
                ?>');" style="width:100%;background-color:white"></iframe>
                <div id="formula_loader"></div>
            </div>
        </td>
    </tr>
</table>