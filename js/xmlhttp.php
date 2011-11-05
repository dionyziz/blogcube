<?php
    include "js/js.php";
?>
// bcAJAXSocket class
function bcAJAXSocket() {
    // internal class variables
    var xh; //contains a reference to our xmlhttp object
    var bComplete = false;
    var elementid = "";
    var elementtarget = "";
    
    // public class functions; callable from outside
    
    // trivial get/set functions, necessary to be able to identify the requests
    this.gettarget = function() {
        return elementtarget;
    };
    this.settarget = function( newtarget ) {
        elementtarget = newtarget;
    };
    this.getid = function() {
        return elementid;
    };
    this.setid = function( newid ) {
        elementid = newid;
    };
    
    // main connect function, used to perform an XMLHTTP request
    this.connect = function( sURL , sMethod , sVars , fnDone ) {
        // if we don't have an xmlhttp object there's no point in requesting anything
        if ( !xh )
            // just return false
            return false;
        // okay, let's get started; operation hasn't been completed yet
        bComplete = false;
        // make sure the method is uppercase ("GET" or "POST")
        sMethod = sMethod.toUpperCase();
        
        // just to make sure no errors occur
        try {
            // if it's a GET method (in BC we're using only POST atm, this should change according to the HTTP definition)
            if ( sMethod == "GET" ) {
                // do a simple request
                xh.open( sMethod, sURL+"?"+sVars, true );
                sVars = "";
            }
            else {
                // do a request in the same manner
                xh.open( sMethod, sURL, true );
                // and add the variables to the HTTP header
                xh.setRequestHeader( "Method", "POST "+sURL+" HTTP/1.1" );
                xh.setRequestHeader( "Content-Type",
                    "application/x-www-form-urlencoded" );
            }
            // use the onreadystatechange callback method of the xmlhttp object
            xh.onreadystatechange = function() {
                // if the xmlhttp request was successful (4) and we think that the operation hasn't been completed...
                if ( xh.readyState == 4 && !bComplete ) {
                    // TODO: check if a failure occured when performing the request
                    // and call an appropriate callback function
                    // mark the operation as completed
                    bComplete = true;
                    // and call the callback function passing the requests identifiers and the xmlhttp object required to get the downloaded results
                    fnDone( xh , elementtarget , elementid );
                }
            };
            // okay, after we've set up everything, we can safely send the request
            xh.send(sVars);
        }
        catch(z) { 
            // woops, something went wrong
            return false; 
        }
        // everything okay
        return true;
    };
    
    // constructor function begins here
    // try to create an XMLHTTP object instance
    // try/catch pairs to avoid errors
    try {
        // ActiveX, Msxml2.XMLHTTP
        xh = new ActiveXObject( "Msxml2.XMLHTTP" ); 
    }
    catch (e) { 
        try { 
            // ActiveX, Microsoft.XMLHTTP
            xh = new ActiveXObject( "Microsoft.XMLHTTP" ); 
        }
        catch (e) { 
            try { 
                // non-ActiveX, normal class (used by everyone apart from microsoft ._.)
                xh = new XMLHttpRequest(); 
            }
            catch (e) { 
                // last catch, exceptions everywhere, can't create XMLHTTP
                xh = false; 
            }
        }
    }
    
    // no xmlhttp object was created, the constructor should return null
    if ( !xh )
        return null;
    
    // return the newly created class
    return this;
}
