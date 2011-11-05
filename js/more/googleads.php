<?php
    include 'js/js.php';
?>

var GoogleAdsense = {
    AdsThing: null
    ,Init: function() {
        this.AdsThing = document.createElement('div');
        this.AdsThing.display = "none";
        google_ad_client = "pub-9675840962794412";
        google_ad_width = 250;
        google_ad_height = 250;
        google_ad_format = "250x250_as";
        google_ad_type = "text_image";
        google_ad_channel = "9329872096";
        google_color_border = "336699";
        google_color_bg = "FFFFFF";
        google_color_link = "0000FF";
        google_color_text = "000000";
        google_color_url = "008000";

        var ads = document.createElement("script");
        ads.setAttribute('type','text/javascript'); 
        ads.setAttribute('src',"http://pagead2.googlesyndication.com/pagead/show_ads.js");
        this.AdsThing.appendChild(ads);
        // document.body.appendChild(this.AdsThing);
    }
    ,Inject: function() {
        return this.AdsThing;
    }
};

GoogleAdsense.Init();
