<?php
    if ( $anonymous ) {
        if( $_SESSION[ "aftermatch" ] ) {
            $bfc->start();
            ?>dm( "<?php
            echo $_SESSION[ "aftermatch" ];
            $_SESSION[ "aftermatch" ] = "";
            ?>" , "Redirecting..." );<?php
            $bfc->end();
        }
        else {
            ?>
            <table style="width:auto">
                <tr><td style="text-align:left;font-family:Arial,Helvetica,sans-serif;color:#4d5b6d;">
                    <h3>BlogCube</h3><br /><br />
                    <h4>Create your own blog, simple.</h4><br /><br />
                    BlogCube is an easy-to-use and fast blogging system, offering a lot of interesting features.
                    <ul>
                        <li style="font-weight:bold;padding-bottom:3px;">Create your own personal blog, pick a topic and start posting in a few seconds.</li>
                        <li style="font-weight:bold;padding-bottom:3px;">Share photos with your friends and family.</li>
                        <li style="font-weight:bold;padding-bottom:3px;">Join a community of friends, view their blogs and images.</li>
                        <li style="font-weight:bold;padding-bottom:3px;">Do everything simply and easily, with just a few clicks.</li>
                    </ul>
                    <br />
                    <h4>Sorry, </h4>but we're not planning to offer new Alpha Testing invitations 
                    very soon.<br />
                    If you have an Alpha Testing Account, use the details you provided during your
                    registration to login.<br /><br />
                    <br /><br />
                </td>
                <td style="width:250px;padding-left:5px;border-left:1px dotted #3979bd">
                    <h3>BlogCube</h3>
                    <br />
                    <small>blogging made simple.</small><br /><br />
                        <?php
                            if( $readonly ) {
                                ?>
                                We're sorry, but <?php echo $system; ?> is
                                in read-only mode for a few moments, because
                                we are doing some updates. You will be
                                able to login again in a few minutes.<br />
                                <br />
                                <?php echo $arrow; ?>
                                <a href="" onclick="de('main','blogging/accounts/login');return false;">Check Status</a>
                                <?php
                            }
                            else {
                                ?>
                                <div id="login_prompt">
                                    Type in your username and password and hit "log in" when you're ready.<br /><br />
                                </div>
                                <div id="user_invalid" style="display:none">
                                    <?php
                                        img( "images/nuvola/messagebox_warning.png" , "Warning" , "Invalid username" , 16 , 16 );
                                    ?> <b style="color:darkred">Invalid username.</b><br />
                                    Please check your details and try again.
                                </div>
                                <div id="pass_invalid" style="display:none">
                                    <?php
                                        img( "images/nuvola/messagebox_warning.png" , "Warning" , "Invalid password" , 16 , 16 );
                                    ?> <b style="color:darkred">Invalid password.</b><br />
                                    Please check your details and try again.
                                </div>
                                <div id="turing_invalid" style="display:none">
                                    <?php
                                        img( "images/nuvola/messagebox_warning.png" , "Warning" , "Invalid security number" , 16 , 16 );
                                    ?> <b style="color:darkred">Invalid security number.</b><br />
                                    Enter the correct security number.
                                </div><br />
                                <form id="loginform" onsubmit="return Users.login();">
                                <table id="login_table" class="formtable">
                                <tr><td class="ffield"><b>Username</b></td><td class="ffield"><acronym title="Type your username"><input autocomplete="off" type="text" id="login_username" class="logininpt" style="color: #275383;" onkeypress="if(!e){e=window.event};if(e.keyCode==13){g('pwd').select();g('pwd').focus;}" /></acronym></td></tr>
                                <tr><td class="nfield"><b>Password</b></td><td class="nfield"><acronym title="Type your password"><input type="password" id="login_pwd" class="loginpwd"<?php
                                 if( UserBrowser() == "MSIE" ) {
                                    // "Enter" does not cause the form to be automatically submitted if the submit input is not displayed
                                    // we need to do it manually using JS
                                    ?> onkeypress="if(!e){e=window.event);if(e.keyCode==13){login()}"<?php
                                }
                                ?> /></acronym></td></tr>
                                <tbody id="turingtest" style="display:none">
                                </tbody>
                                <?php
                               // include $evc->GetElement('blogging/accounts/turingtest');
                                ?>
                                <tr><td class="efield" /><td class="efield" /></tr>
                                <tr><td colspan="2" class="efield" id="login_anchor_td" style="text-align:right;height:30px">
                                <div id="login_anchor" class="inline">
                                    <acronym title="Login to <?php echo $system; ?>"><?php echo $arrow; ?><a href="" onclick="Users.login();return false;">Log In</a></acronym>
                                </div>
                                </td></tr>
                                </table>
                                <table class="formtable" id="login_loader" style="display:none">
                                    <tr><td class="ffield" style="width:245px;text-align:center">
                                    Logging in...<br />
                                    <img src="images/uploading.gif" />
                                    </td></tr>
                                </table>
                                <input type="submit" style="display:none" />
                                </form>
                                <div class="inline" style="display:none">
                                    <div id="login_processor"></div>
                                </div>
                                <?php
                                $bfc->start();
                                ?>
                                g("login_username").focus();
                                setTimeout('g("login_username").focus()',1000);
                                et( "Login" );
                                cacheable = true;
                                
                                <?php
        
                                if ( isset( $_SESSION[ "turingenabled" ] ) && $_SESSION[ "turingenabled" ] ) {
                                ?>    
                                    g( "turingtest" ).style.display = "";
                                    cp ( "blogging/accounts/turingtest" );
                                    de2( "turingtest" , "blogging/accounts/turingtest", {} );
                                <?php
                                }
                                $bfc->end();
                            }
                ?></td></tr>
            </table><?php
        } 
    }
    else {
        $bfc->start(); 
        ?>
        cp( "blogging/accounts/login" );
        dm( "<?php
        if( !$_SESSION[ "aftermatch" ] ) {
            ?>hola<?php
        }
        else {
            echo $_SESSION[ "aftermatch" ];
            $_SESSION[ "aftermatch" ] = "";
        }
        ?>" , "Redirecting..." );<?php
        $bfc->end();
    }
?>
