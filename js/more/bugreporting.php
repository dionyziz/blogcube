var ERROR_BUGNOTITLE = "Your report does not have any title.\nPlease enter one and try again.";
var ERROR_BUGNODESC = "Your report does not have any description.\nPlease enter one and try again.";
var ERROR_BUGNOVERSION = "Your report does not contain any OS Information.\nMake sure that you typed your UNIX/LINUX information and try again.";

function bug_search() {
    bug_search_name = safeencode(g('bug_search_name').value);
    de('tempbuglist','blogging/bugreporting/buglist&bug_name=' + bug_search_name);
}

function bug_osbox() {
    g('bug_browser').style.backgroundImage = "url('images/silk/browser_" + g('bug_browser').value + ".png')";
    if ( g('bug_browser').value == 'other' ) {
        g('bug_browser_version').style.display = "none";
        g('bug_browser_specify').style.display = "";
        g('bug_browser').style.fontWeight = 'bold';
    }
    else {
        g('bug_browser_version').style.display = "";
        g('bug_browser_specify').style.display = "none";
        g('bug_browser').style.fontWeight = 'normal';
    }
}

function bug_advsearch() {
    s = "";
    bug_search_name = safeencode(g('bug_search_name').value);
    bug_search_fixedby = safeencode(g('bug_search_fixedby').value);
    bug_search_fixedbugs = g('bug_search_fixedbugs').checked;
    bug_search_from = g('bug_search_fromyear').value + "-" + g('bug_search_frommonth').value + "-" + g('bug_search_fromday').value + " 00:00:00";
    bug_search_until = g('bug_search_toyear').value + "-" + g('bug_search_tomonth').value + "-" + g('bug_search_today').value + " 00:00:00";
    s += '&bug_datefrom=' + bug_search_from;
    s += '&bug_dateuntil=' + bug_search_until;
    if ( bug_search_name ) s += '&bug_name=' + bug_search_name;
    if ( bug_search_fixedbugs == true ) s += '&bug_fixedby=0';
    else if ( bug_search_fixedby ) s += '&bug_fixedby_n=' + bug_search_fixedby;
    de('tempbuglist','blogging/bugreporting/buglist' + s);
}

function bug_fixedby_de() {
    if ( g('bug_search_fixedbugs').checked == true ) {
        g('bug_search_fixedby').setAttribute('disabled','1');
    }
    else {
        g('bug_search_fixedby').removeAttribute('disabled');
    }
}

function bug_report() {
    s_bug_title = g('bug_title').value;
    s_bug_os = g('bug_os').value;
    s_bug_desc = g('bug_desc').value;
    s_bug_linux_distro = g('bug_linux_distro').value;
    s_bug_linux_kernel_version = g('bug_linux_kernel_version').value;
    s_bug_unix_type = g('bug_unix_type').value;
    s_bug_unix_kernel_version = g('bug_unix_kernel_version').value;
    s_bug_osversion = '.';
    s_bug_browser = g('bug_browser').value;
    if ( s_bug_browser == "other" ) {
        s_bug_browser = g('bug_browser_spec').value;
        s_bug_browserversion = " ";
    }
    else {
        s_bug_browserversion = g('bug_browser_ver').value;
    }
    if (( s_bug_os == "LINUX" ) && ((!s_bug_linux_distro) || (!s_bug_linux_kernel_version))) {
        alert(ERROR_BUGNOVERSION);
    }
    else if (( s_bug_os == "UNIX" ) && ((!s_bug_unix_type) || (!s_bug_unix_kernel_version))) {
        alert(ERROR_BUGNOVERSION);
    }
    else if ( !s_bug_title ) {
        alert(ERROR_BUGNOTITLE);
    }
    else if ( !s_bug_desc ) {
        alert(ERROR_BUGNODESC);
    }
    else {
        switch(s_bug_os) {
            case "LINUX":
                s_bug_osversion = s_bug_linux_distro + " " + s_bug_linux_kernel_version;
                break;
            case "UNIX":
                s_bug_osversion = s_bug_unix_type + " " + s_bug_unix_kernel_version;
                break;
        }
        dm2('bugreporting/bug/report/now', {
            bug_title: s_bug_title
            ,bug_os: s_bug_os
            ,bug_desc: s_bug_desc
            ,bug_osversion: s_bug_osversion
            ,bug_browser: s_bug_browser
            ,bug_browserversion: s_bug_browserversion
        });
    }
}

function bug_os_check() {
    show_linux = show_unix = show_bsd = "none";
    switch( g('bug_os').value ) {
        case "LINUX":
            show_linux = "";
            break;
        case "UNIX":
            show_unix = "";
            break;
        case "BSD":
            show_bsd = "";
            break;
    }
    lin = g('bug_linux_prop');
    unx = g('bug_unix_prop');
    bsd = g('bug_bsd_prop');
    if( lin.style.display != show_linux )
        lin.style.display = show_linux;
    if( unx.style.display != show_unix )
        unx.style.display = show_unix;
    if( bsd.style.display != show_bsd )
        bsd.style.display = show_bsd;
}

function bug_view_sh(click_div) {
    if ( click_div == "bug_reported" ) { notclick_div = "bug_captured"; }
    else { notclick_div = "bug_reported"; }
    if ( g(click_div).style.display == "" ) {
        g(click_div).style.display = "none";
    }
    else {
        g(click_div).style.display = "";
        g(notclick_div).style.display = "none";
    }
}
