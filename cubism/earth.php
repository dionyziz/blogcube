<?php
    /* 
        Cubism: Earth
        Earth Developers: Makis, Dionyziz
    */
    
    // We need to generate a new EarthId here
    $myearth = New Earth();
    
    echo $doctype;
?><html>
<head>
<link href="style.css.bc" type="text/css" rel="stylesheet" />
<title></title>
</head>
<body style="background-color:<?php
    echo $_GET[ "bgc" ];
    ?>;color:<?php
    echo $_GET[ "fgc" ];
        ?>"><form enctype="multipart/form-data" action="<?php 
        echo $systemurl; 
        ?>earth.pl?earthid=<?php
        echo $myearth->Id();
        ?>" method="post" id="earthform"><!-- 
            Note: Only one earth form should be included in the document each time, because of the uniqueness of the html id attribute, 
            unless we use a different id for each form using a formula in terms of earthform_%earthid%
        -->
        <input type="hidden" name="MAX_FILE_SIZE" value="16777216" />
        <input type="file" name="uploadfile" onchange="parent.earthupload(<?php
        echo $myearth->Id();
        ?>)" />
        <input type="submit" value="Upload" style="display:none" />
    </form></body>
</html>