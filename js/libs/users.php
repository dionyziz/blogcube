Users = {
    ERROR_UNAME: "Please type your username"
    ,ERROR_PWD: "Please type your password"
    ,ERROR_TURING: "Please type the security number in the textbox beneath it"
    
    ,login: function () {
        u = g( "login_username" );
        p = g( "login_pwd" );
        t = g( "login_turing" );
        if ( !u.value ) {
            a( this.ERROR_UNAME );
            u.focus();
        }
        else
            if ( !p.value ) {
                a( this.ERROR_PWD );
                p.focus();
            }
            else {
                if ( t != null  && !t.value ) {
                    a( this.ERROR_TURING );
                    t.focus();
                }
                else {
                    g( "login_loader" ).style.display = "";
                    g( "login_table" ).style.display = g( "user_invalid" ).style.display = g( "pass_invalid" ).style.display = g( "login_prompt" ).style.display = "none";
                    if ( t == null ) {
                        de2( "login_processor" , "blogging/accounts/auth" , {u: u.value, p: p.value} , 'Authenticating...' );
                    } 
                    else {
                        de2( "login_processor" , "blogging/accounts/auth" , {u: u.value, p: p.value, t: t.value} , 'Authenticating...' );
                    }
                }
            }
        return false;
    }
    ,forgot: function() {
        u = g( "username" );
        if ( !u.value ) {
            a( this.ERROR_UNAME );
            u.focus();
        }
        else {
            de( "login" , "blogging/accounts/remindme&u=" + u.value );
        }
        return false;
    }

    ,ERROR_REGUNAME: "Please type a username of your choice."
    ,ERROR_REGPWD: "Please type a password of your choice."
    ,ERROR_REGMISMATCH: "The two passwords you have typed do not match;" + " please make sure you enter the same password twice."
    ,ERROR_REGPWD2: "Please type your password again," + " for verification."
    ,ERROR_REGEMAIL: "Please type your e-mail address."
    ,ERROR_REGTOS: "You need to accept BlogCube Terms of Service and our Privacy Policy in order to create an account."
    
    ,register: function() {
        g( "ureg_ca" ).innerHTML = g( "ureg_ce" ).innerHTML = "";
        u1 = g( "reg_uname" );
        u2 = g( "reg_pwd" );
        u3 = g( "reg_pwd2" );
        u4 = g( "tos" );
        u5 = g( "reg_mail" );
        if( !u1.value ) {
            a( this.ERROR_REGUNAME );
            u1.focus();
        }
        else if ( !u2.value ) {
            a( this.ERROR_REGPWD );
            u2.focus();
        }
        else if ( !u3.value ) {
            a( this.ERROR_REGPWD2 );
            u3.focus();
        }
        else if ( u2.value != u3.value ) {
            a( this.ERROR_REGMISMATCH );
            u2.select();
            u2.focus();
        }
        else if ( !u5.value ) {
            a( this.ERROR_REGEMAIL );
            u5.focus();
        }
        else if ( !u4.checked ) {
            a( this.ERROR_REGTOS );
            u4.focus();
        }
        else {
            de( "oreg" , "blogging/accounts/register_now&u=" + se( u1.value ) + "&p=" + se( u2.value ) + "&e=" + se( u5.value ) + "&i=" + g("iid").innerHTML + "&c=" + g("icd").innerHTML,'', "Creating Account..." );
        }
    }
    ,validUname: function( uname ) {
        if (uname.length < 3) {
            return -1;
        }
        uname2 = uname.replace(/^[a-z][-a-z0-9]{2,}/ig, '');
        if (uname2.length) {
            return -2;
        }
        return 1;
    }
    ,checkRegister: function( field ) {
        validy = this.validUname( field.value );
        switch (validy) {
            case -1:
                g( 'reg_uinvalidshort' ).style.display = '';
                g( 'reg_checkavail' ).style.display = 'none';
                g( 'reg_uinvalid' ).style.display = 'none';
                g( 'ureg_ca' ).style.display = 'none';
                break;
            case -2:
                g( 'reg_uinvalid' ).style.display = '';
                g( 'reg_uinvalidshort' ).style.display = 'none';
                g( 'reg_checkavail' ).style.display = 'none';
                g( 'ureg_ca' ).style.display = 'none';
                break;
            case 1:
                g( 'reg_checkavail' ).style.display = '';
                g( 'reg_uinvalid' ).style.display = 'none';
                g( 'reg_uinvalidshort' ).style.display = 'none';
                g( 'ureg_ca' ).innerHTML = '';
                g( 'ureg_ca' ).style.display = '';
                break;
        }
    }
};
