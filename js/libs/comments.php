Comments = {
    Colours: {}
    ,LastPos: 0
    ,NewCommentCode: ''
    ,PostComNum: {}
    ,QuickComment: function () {
        // comment_reply_to_##_$$
        // 
        g('commentgobutton').disabled = true;
        g('commentgobutton').value = 'Posting...';
        if ( this.LastPos.substring( 0 , ('comment_reply_to_').length - 1 ) == 'comment_reply_to' ) {
            replynum = this.LastPos.substring( ('comment_reply_to_').length , this.LastPos.length );
            replycode = replynum.split( '_' );
            postid = replycode[ 0 ];
            commentid = replycode[ 1 ];
            de2('comment_poster', 'blog/comment/now', {
                parid: commentid, 
                postid: postid, 
                c: g('commentarea').value
            });
        }
        else {
            postid = this.LastPos.substring( ('newcomment_').length , this.LastPos.length );
            de2('comment_poster', 'blog/comment/now', {
                parid: 0, 
                postid: postid, 
                c: g('commentarea').value
            });
        }
    }
    ,CommentBox: function (positionfieldid) {
        if(this.LastPos!=0) {
            g(this.LastPos).innerHTML = '';
        }
        if(this.LastPos==positionfieldid || positionfieldid == 0) {
            this.LastPos = 0;
            return;
        }
        this.LastPos=positionfieldid;
        with (g(this.LastPos)) {
            style.display = 'none';
            innerHTML = this.NewCommentCode;
            style.display = 'inline';
        }
        g('newcommentreal').style.borderTopColor = g(this.LastPos + '_colour').innerHTML;
        g('commentarea').focus();
    }
    ,OverComment: function (commentid) {
        g('comment_controlbox_' + commentid).style.display = '';
    }
    ,OutComment: function (commentid) {
        g('comment_controlbox_' + commentid).style.display = 'none';
    }
    ,KillComment: function (commentid, postid) {
        if ( confirm( "Do you really want to delete this comment entry?" ) ) {
            g('commentbox_' + commentid).style.display = 'none';
            this.CommentsIncrease(postid, -1);
            de2('comment_killer', 'blog/comment/kill', { cid: commentid });
        }
    }
    ,CommentsIncrease: function (postid, incby) {
        if (Comments.PostComNum[ postid ] == null) {
            getnumc = g('postnumc_' + postid).innerHTML;
            getnumcc = getnumc.split(' ');
            numc = getnumcc[0] - 0;
            Comments.PostComNum[ postid ] = numc;
        }
        Comments.PostComNum[ postid ] += incby;
        g('postnumc_' + postid).innerHTML = pluralize('Comment', Comments.PostComNum[ postid ]);
    }
};
