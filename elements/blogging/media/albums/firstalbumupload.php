<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
?><div style="border:1px solid #8baed5;width:280px;padding-left:3px;padding-right:1px;background-color:#f7f7ff;">
            <div style="text-align:left"><?php
                img( "images/nuvola/upload64.png" , "Upload a photo" , "Upload a photo" );
            ?>
            <span style="color:#3b5ea5">Upload your photo...</span><br /><br /></div>
            <div id="photouperror" style="display:none"></div>
            <iframe src="cubism.bc?g=media/firstalbumsform" frameborder="no" id="alb_photoupl" style="height:50px;width:280px;"></iframe>
            <div id="uploadanims" style="display:none;background-color:#f7f7ff;width:150px;height:150px;">
            Uploading...<br />
            <img src="images/uploading.gif" alt="Please wait..." title="Uploading..." />
            </div>
</div><br />
<?php
    img( "images/nuvola/tip.png" , "Tip" , "Tip" );
?>You haven't created an album. To do so just upload a photo. You will be able to choose a name for the album later on.