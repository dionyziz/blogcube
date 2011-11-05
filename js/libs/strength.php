Strength = {
    calculate: function (password) {
        sequences = new Array(
        "`1234567890-=" ,
        "qwertyuiop[]\\" ,
        "asdfghjkl;'" ,
        "zxcvbnm,./" , 
        "~!@#$%^&*()_+" ,
        "QWERTYUIOP{}|" ,
        "ASDFGHJKL:\"" ,
        "ZXCVBNM<>?" ,
        "abcdefghijklmnopqrstuvwxyz" ,
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ" );
        //password = g('ao_pwd').value;
        pwdlength = password.length;
        strengthmodifier = 0;
        if ( username.toLowerCase().indexOf(password.toLowerCase()) != -1 ) {
            return 1;
        }
        if ( this.remove_chars(password,'0','9') == "" ) {
            //the whole password consists of numbers
            strengthmodifier = 2.1;
        }
        else {
            for (i in sequences) {
                if (sequences[i].indexOf(password) != -1) {
                    // the whole password is a part of a sequence
                    strengthmodifier = 1.5;
                    break;
                }
            }
            if ( strengthmodifier == 0 ) {
                pwdnonumbers = this.remove_chars(password,'0','9');
                pwdnolowercase = this.remove_chars(password,'a','z');
                pwdnouppercase = this.remove_chars(password,'A','Z');
                if (( pwdnolowercase == "" ) || ( pwdnouppercase == "" )) {
                    // the whole password consists of lower-case-only or upper-case-only latin characters
                    strengthmodifier = 3;
                }
                else if (( this.remove_chars(pwdnonumbers,'a','z') == "" ) || ( this.remove_chars(pwdnonumbers,'A','Z') == "" )) {
                    // the whole password consists of either only lower-case characters and numbers or only upper-case characters and numbers
                    strengthmodifier = 3.8;
                }
                else if ( this.remove_chars(pwdnolowercase,'A','Z') == "" ) {
                    // the whole password consists of mixed lower and upprt case latin characters
                    strengthmodifier = 3.8;
                }
                else {
                    // the whole password consists of mixed lower-case letters, upper-case letters and numbers
                    strengthmodifier = 6;
                }
            }
        }
        result = 10 + strengthmodifier * ( pwdlength - 3 ) * 3;
        if( result > 100 ) {
            result = 100;
        }
        result = Math.round( result );
        return result;
    }

    ,remove_chars: function (string,low,max) {
        newstring = "";
        for (i=0; i < string.length; i++) {
            if (( string.charAt(i) < low ) || ( string.charAt(i) > max ))
                newstring += string.charAt(i);
        }
        return newstring;
    }
    
    ,print: function (strength) {
        temphtml = '<b>Password strength:&nbsp;<span style="color: ';
        if ( result < 33 ) { 
            temphtml += 'red;">Weak</span></b>';
        }
        else if ( result < 66 ) {
            temphtml += 'blue;">Normal</span></b>';
        }
        else {
            temphtml += 'green;">Strong</span></b>';
        }
        return temphtml;    
    }
    
    ,strength: function (passworddiv,shortdiv,rtpdiv,strengthdiv) {
        g(strengthdiv).innerHTML = "";
        password = g(passworddiv).value;
        pwdlength = password.length;
        if  (pwdlength < 4) {
            //password too short
            g(rtpdiv).disabled = true;
            if ( pwdlength != 0 ) g(shortdiv).style.display = "";
            g(strengthdiv).style.display = "none";
            return 0;
        }
        else {
            //password not too short
            g(rtpdiv).disabled = false;
            g(shortdiv).style.display = "none";
            g(strengthdiv).style.display = "";
            g(strengthdiv).innerHTML = this.print(this.calculate(password));
        }
    }
};
