<?php
    include "allow.php";
    ob_start( "html_filter" );
    echo $doctype;
?><html>
    <head>
        <title>BlogCube</title>
        <link href="style.css.bc" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php
            include "jscheck.php";
        ?>
        <form action="<?php
            echo $systemurl;
            ?>" method="post" id="f"><div><?php
                for( $i = 0 ; $i < count( $redirfields ) ; $i++ ) {
                    $redirfield = $redirfields[ $i ];
                    ?><input type="hidden" name="redir_<?php
                    echo $redirfield[ 0 ];
                    ?>" value="<?php
                    echo $redirfield[ 1 ];
                    ?>" /><?php
                }
            ?></div>
        </form>
        <script type="text/javascript"><?php
            if ( isset( $rediralert ) ) {
                ?>alert('<?php
                echo escapesinglequotes( $rediralert );
                ?>');<?php
            }
            ?>
            document.title = 'Loading...';
            document.getElementById('f').submit();
        </script>
    </body>
</html><?php
    ob_end_flush();
?>