var ERROR_MSGNOSUBJECT = "Your message does not have a subject.\nDo you want to send it anyway?";
var ERROR_MSGNOCONTENT = "Your message does not have any content.\nDo you want to send it anyway?";
var ERROR_MSGNORECIPIENT = "You did not entered any recipients.\nPlease enter at least one.";

function decheight(filtid) {
    idivid = "viewfilterpanel_" + filtid;
    curheight = g(idivid).style.height;
    g(idivid).innerHTML = "";
    g(idivid).style.height = (curheight-5) + "px";
    if ( curheight >= 5 ) setTimeout('decheight(filtid);',50);
}

function messagecardover( id ) {
    id = "" + id;

    //for ( i = 0 ; i < allmessageids.length ; i++ )
    //    g( 'messagecard_' + allmessageids[ i ] ).style.display = 'none';
    if ( ( mcard = g( mcid = 'messagecard_' + id ) ).style.display == "none" ) {
        if ( id.charAt(0) == 'f' ) {
            mcard.style.left = findPosX( mscard = g( 'allmfolder_' + id.slice(1) ) ) + 5 + "px";
            if ( g('mfolder_' + id.slice(1)).style.display != "none" ) return 0;
        }
        else {
            mcard.style.left = findPosX( mscard = g( 'messagesmallcard_' + id ) ) + 5 + "px";
        }
        mcard.style.top = findPosY( mscard ) + 24 + "px";
        fade0( mcid );
        mcard.style.display = 'inline';
        fadein( mcid , false , 50 , 0.9 , 0.1 );
    }
}

function messagecardout( id ) {
    mcard = g( 'messagecard_' + id );
    mcard.style.display = 'none';
}

/*function mfolder_sh(divid,foldid,showmsgs,foldname) {
    if ( g(divid).style.display == "none" ) {
        g(divid).style.display = ""; // show
        if (!trim(g(divid).innerHTML)) { 
            de(divid,'blogging/messaging/folderview&mfid=' + foldid + '&showmsgs=' + showmsgs); 
        }
    }
    else {
        g(divid).style.display = "none"; // hide
    }
    if ( showmsgs == "false" ) {
        g('filter_seltarget').innerHTML = foldname;
        g('filter_seltarget_id').innerHTML = foldid;
    }
}*/

function mfolder_sh(divid) {
    if ( g(divid).style.display == "none" ) g(divid).style.display = "";
    else g(divid).style.display = "none";
}

function mfolder_nm(foldid,newmsgs) {
    if ( newmsgs == 0 ) { 
        g("mfolder_" + foldid + "_nm").innerHTML = "";
    }
    else {
        g("mfolder_" + foldid + "_nm").innerHTML = "<b>(" + newmsgs + ")</b>";
    }
}

function mfolder_delete(foldid) {
    if ( confirm("Are you sure that you want to delete the selected folder, its sub-folders and all the messages that are included in them?\n\nWarning: This action is not reversible.") ) {
        de("hmpanel","blogging/messaging/folder/delete&foldid=" + foldid,"","Deleting folder...");
        g("allmfolder_" + foldid).style.display = "none";
    }
}

function mfolder_add(foldid) {
    foldname = prompt("Enter a name for the new folder?");
    if ( foldname ) {
        de("mpanel_status","blogging/messaging/folder/add&foldid=" + foldid + "&foldname=" + foldname,"","Adding folder...");
    }
    else {
        alert("You did not entered a name!");
    }
}

function mfolder_rename(foldid) {
    foldname = prompt("Enter the new name?");
    if ( foldname ) {
        de("hmpanel","blogging/messaging/folder/rename&foldid=" + foldid + "&newname=" + safeencode(foldname),"","Renaming folder...");
        g("mfolder_" + foldid + "_name").innerHTML = foldname;
    }
}

function msg_send(msg_subject,msg_recipients,msg_content,msg_type) {
    msg_subject = safeencode(msg_subject);
    msg_recipients = safeencode(msg_recipients);
    msg_type = safeencode(msg_type);
    msg_content = safeencode(msg_content);
    if ( !msg_recipients ) {
        alert(ERROR_MSGNORECIPIENT);
    }
    else {
        if ( !msg_subject ) {
            if ( !confirm(ERROR_MSGNOSUBJECT) ) {
                return;
            }
        }
        if ( !msg_content ) {
            if ( !confirm(ERROR_MSGNOCONTENT) ) {
                return;
            }
        }
        de('msg_status','blogging/messaging/message/send&msg_subject=' + msg_subject + '&msg_recipients=' + msg_recipients + '&msg_type=' + msg_type + '&msg_content=' + msg_content,"","Sending Message...");
    }
}

function msg_delete(mid) {
    if ( confirm("Are you sure that you want to delete the selected message?") ) {
        de("hmpanel","blogging/messaging/message/delete&msgid=" + mid,"","Deleting message...");
        g("messagesmallcard_" + mid).style.display = "none";
    }
}

function msg_showerror(errtext,errplace) {
    divid = "msg_error_" + errplace;
    g(divid).style.display = "";
    g(divid).innerHTML = errtext;
    g("msg_ferror_" + errplace).style.borderTop = "1px solid #8dafd4";
}

function msg_newmsgs(newmsgs) {
    if ( newmsgs == 0 ) {
        temphtml = "<p align=\"center\">You have no new messages</p>";
    }
    else {
        temphtml = "<p align=\"center\">You have <b>" + " " + newmsgs + " " + "</b> new message";
        if ( newmsgs > 1 ) { temphtml += "s"; }
        temphtml += ".</p>";
    }
    g('msg_newmsgs').innerHTML = temphtml;
}

function msg_unmark(idivid) {
    g(idivid).style.fontWeight = "normal";
}

/*
================
FILTER FUNCTIONS
================ 
*/
var elm_critdiv = "mfilter_crit_list";
var elm_filterdiv = "mfilter_fhidd";
var elm_filtername = "filter_caption";
var elm_filtertarget = "filter_seltarget_id";
var elm_addcriterion = "mfilter_addcriterion";
var elm_filterview = "msg_filterview";
var elm_crit_msgsentby = 0;

function mfilter_critlist_sh() {
    cdiv = g(elm_addcriterion);
    if ( cdiv.style.opacity == 0 ) {
        fade0(elm_addcriterion);
        //cdiv.style.display = ""; // show
        fadein(elm_addcriterion, false, 50, 0.9, 0.1);
    }
    else {
        fadeout(elm_addcriterion, false, 25, 0, 0.1);
        //cdiv.style.display = "none"; // hide
    }
}

function mfilter_crit_sh(divid,hidemenu) {
    if ( hidemenu == 'y' ) {
        g(divid).style.display = "";
    }
    else if ( cdiv.style.display == "none" ) {
        g(divid).style.display = ""; // show
    }
    else {
        g(divid).style.display = "none"; // hide
    }
}

function mfilter_crit_add(filter_type) {
    if (( filter_type == "1" ) && ( elm_crit_msgsentby == 1 )) {
        a("This kind of criterion cannot be assigned twice.\nPlease choose another criterion.");
    }
    else {
        if ( filter_type == "1" ) elm_crit_msgsentby = 1;
        filter_info = "filter_type" + filter_type + "_text";
        g(elm_filterdiv).innerHTML += filter_type + "\t" + g(filter_info).value + "\n";
        mfilter_crit_upd();
    }
}

function mfilter_crit_del(filter_type,filter_info) {
    //TODO: code :P
    mfilter_crit_upd();
}

function mfilter_crit_upd() {
    filterdivc = g(elm_filterdiv).innerHTML;
    splitted = filterdivc.split("\n");
    if (splitted.length-1 >= 0 ) {  temphtml = "a" + splitted.length-1 + "<table width=\"100%\"><tr><td><b>Type</b></td><td><b>Condition</b></td><td>&nbsp;</td></tr>"; }
    else { temphtml = "No filter criteria added."; }
    for (var i=0;i<=splitted.length-1; i++) {
        linesplit = splitted[i].split("\t");
        switch (linesplit[0]) {
            case "1":
                temphtml += "<tr><td>If message is sent by</td><td>" + TextFadeOut(linesplit[1],"","",10,5) + "</td><td>.</td></tr>";
                break;
            case "2":
                temphtml += "<tr><td>If message subject contains</td><td>" + TextFadeOut(linesplit[1],"","",10,5) + "</td><td>x</td></tr>";
                break;
            case "3":
                temphtml += "<tr><td>If message body contains</td><td>" + TextFadeOut(linesplit[1],"","",10,5) + "</td><td>x</td></tr>";
                break;
        }
    }
    temphtml += "</table>";
    g(elm_critdiv).innerHTML = temphtml;
}

function mfilter_add() {
    strcriteria = safeencode(g(elm_filterdiv).innerHTML);
    fname = safeencode(g(elm_filtername).value);
    ftarget = g(elm_filtertarget).innerHTML;
    de("filter_addprocess","blogging/messaging/filter/add/now&strcriteria=" + strcriteria + "&filtername=" + fname + "&filtertarget=" + ftarget,"","Adding filter...");
}

function mfilter_delete(filterid) {
    if ( confirm("Are you sure that you want to delete the selected filter?") ) {
        de('mfilter_delete','blogging/messaging/filter/delete&filterid=' + filterid,'','Deleting filter...');
    }
}

function mfilter_view_sh(click_div) {
    if ( click_div == "mfilter_char" ) { notclick_div = "mfilter_criteria"; }
    else { notclick_div = "mfilter_char"; }
    if ( g(click_div).style.display == "" ) {
        g(click_div).style.display = "none";
    }
    else {
        g(click_div).style.display = "";
        g(notclick_div).style.display = "none";
    }
}
