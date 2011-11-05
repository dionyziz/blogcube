var Debug = {
    Active: true
    ,Visible: false
    ,Notice: function ( str ) {
        <?php
        if ( $debug_version && in_array($user->Username(), $bfc_debuggers) ) {
            ?>
            z = ('debugnotice_' + uid());
            g('debugd').innerHTML += '<div id="' + z + '"></div>';
            g(z).innerHTML += str;
            if (!this.Visible && this.Active) {
                g('debugdp').style.display = '';
                this.Visible = true;
            }
            <?php
        }
        ?>
    }
    ,Deactivate: function () {
        this.Active = false;
        g('debugdp').style.display = 'none';
    }
    ,Clear: function () {
        g('debugd').innerHTML = '';
    }
    ,SQLShow: function (codeid) {
        g('debugsqlreal').innerHTML = g('sql_' + codeid + '_real').innerHTML;
        g('debugsql').style.display = '';
    }
};
