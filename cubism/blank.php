<html>
    <head>
        <title>BlogCube</title>
        <link href="style.css.bc" type="text/css" rel="stylesheet" />
        <script type="text/javascript">
            var o = opener;
            var d = document;
            var b = d.body;
            function reqb() {
                reqid = '<?php
                    echo $_GET[ "reqid" ];
                ?>;
                
                b.innerHTML = o.g( reqid ).innerHTML;
                d.title = o.g( reqid + '_tit' ).innerHTML;
                // eval( o.g( reqid + '_json' ) );
            }
        </script>
    </head>
    <body onload="reqb()">
    </body>
</html>