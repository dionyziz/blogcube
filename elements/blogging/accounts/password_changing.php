<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if ( $user->CheckPassword( $_POST[ "old_pwd" ] ) ) {
        $user->SetPassword( $_POST[ "new_pwd" ] );
        $user->Su();
        $bfc->start();
        ?>/*setTimeout( 'fadeout( "ao_pwd_changed", false , 100 , 0 , 0.05 , "g(\"ao_pwd_changed\").innerHTML = \"\";")' , 2000 );
        g("ao_old").value = "";
        g("ao_pwd").value = "";
        g("ao_rtp").value = "";
        g("ao_pwd").disabled = true;
        g("ao_rtp").disabled = true;
        g("ao_strength").style.display = "none";
        ao_old_password = Md5.md5('<?php echo $_POST["new_pwd"]; ?>');*/
        dm('hola');
        <?php
        $bfc->end();
    }
?>
