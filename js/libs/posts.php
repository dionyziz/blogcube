/*
    Developer: dionyziz
*/

Posts = {
    CommentsToggle: function(postid) {
        var z = g('postcomments_' + postid);
        if (z.style.display == 'none') {
            z.style.display = '';
        }
        else {
            z.style.display = 'none';
        }
    }
};
