Bookmarks = {
    BmCnt: 0
    
    ,SetBmCount: function (newval) {
        this.BmCnt = newval;
    }
    
    ,Delete: function (bookmarkid) {
        if ( confirm("Are you sure that you want to delete the selected bookmark?") ) {
            de2('bookmark_hid', 'blogging/bookmarks/bookmark/delete', {
                bmid: bookmarkid
            });
            g('listbookmark_' + bookmarkid).style.display = "none";
        }
        this.BmCnt--;
        if ( this.BmCnt == 0 ) {
            g("listbookmark_hid").style.display = "";
        }
    }
    
    ,EditLabel: function (bookmarkid) {
        newlabel = prompt("Please enter the new label");
        if ( newlabel ) {
            de2('bookmark_hid', 'blogging/bookmarks/bookmark/changelabel', {
                bmid: bookmarkid,
                bmlabel: newlabel
            });
            g('bookmarklabel_' + bookmarkid).innerHTML = TextFadeOut(newlabel,"","",30,10);
        }
    }
        
    ,Add: function(postid) {
        fade1('bookmarkbox_' + postid);
        g('bookmark_dropdown_' + postid).style.display = "none";
        g('bookmarkbox_' + postid).style.display = "";
        g('bookmark_label_' + postid).style.display = "none";
        g('bookmark_editlabel_' + postid).style.display = "inline";
        g('bookmarkbutton_' + postid).style.display = "none";
        bmnewlabel = g('bookmark_newlabel_' + postid); //input
        bmnewlabel.value = "New Bookmark";
        bmnewlabel.select();
        bmnewlabel.focus();
    }
    
    ,AddNow: function(postid) {
        bmnewlabel = g('bookmark_newlabel_' + postid); //input
        g('bookmark_label_' + postid).innerHTML = "Adding Bookmark...";
        g('bookmark_editlabel_' + postid).style.display = "none";
        g('bookmark_label_' + postid).style.display = "";
        de2('bookmark_hid', 'blogging/bookmarks/bookmark/add', {
            postid: postid,
            bmlabel: bmnewlabel.value
        });
    }
    
    /* Dropdown Menu Functions */
    
    ,DropEditLabel: function(postid) {
        g('bookmark_label_' + postid).style.display = "none";
        g('bookmark_editlabel_' + postid).style.display = "inline";
        bmnewlabel = g('bookmark_newlabel_' + postid); //input
        bmnewlabel.select();
        bmnewlabel.focus();
    }
    
    ,DropEditLabelCancel: function(postid) {
        bmid = g('bookmark_postbmid_' + postid).innerHTML;
        g('bookmark_label_' + postid).style.display = "inline";
        g('bookmark_editlabel_' + postid).style.display = "none";
        if ( bmid == "" ) {
            g('bookmarkbox_' + postid).style.display = "none";
            g('bookmarkbutton_' + postid).style.display = "inline";
        }
    }
    
    ,DropEditLabelSave: function(postid) {
        bmid = g('bookmark_postbmid_' + postid).innerHTML;
        if ( bmid == "" ) {
            Bookmarks.AddNow(postid);
            return;
        }
        bmlabel = g('bookmark_label_' + postid); //div
        bmnewlabel = g('bookmark_newlabel_' + postid); //input
        bmlabel.innerHTML = bmnewlabel.value;
        de2('bookmark_hid', 'blogging/bookmarks/bookmark/changelabel', {
            bmid: bmid,
            bmlabel: bmnewlabel.value
        });
        g('bookmark_editlabel_' + postid).style.display = "none";
        g('bookmark_label_' + postid).style.display = "inline";
    }
    
    ,DropDelete: function(postid) {
        if ( confirm("Are you sure that you want to delete the bookmark?") ) {
            bookmarkid = g('bookmark_postbmid_' + postid).innerHTML;
            de2('bookmark_hid', 'blogging/bookmarks/bookmark/delete', {
                bmid: bookmarkid
            });
            g('bookmarkheart_' + postid).style.display = 'none';
            g('bookmark_postbmid_' + postid).innerHTML = "";
            g('bookmarkbutton_' + postid).style.display = "inline";
            fadeout('bookmarkbox_' + postid, false, 25, 0, 0.1);
        }
    }
};