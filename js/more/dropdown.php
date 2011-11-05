var DropDown = {
    on: function (id) {
        g(id).style.display = 'block';
    }
    ,off: function(id) {
        g(id).style.display = 'none';
    }
    ,toggle: function(id) {
        if (g(id).style.display=='none') {
            this.on(id);
        }
        else {
            this.off(id);
        }
    }
};