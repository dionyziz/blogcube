<?php
    /*
        Developer: Dionyziz
    */
    include "js/js.php";
?>
/*
    This file is responsible for handling bfc libraries
    and loading them only once per session, even if they
    are required by more than an element. Each library is
    loaded just before the bfc code of the requiring element
    is executed.
*/

var BFC = {
    Prepend: 'bfc_'
    ,Dependencies: {}
    ,Loaded: {}
    ,Depend: function(elementid, library) {
        if (this.Dependencies[ elementid ] == null) {
            this.Dependencies[ elementid ] = {};
        }
        this.Dependencies[ elementid ][ library.toLowerCase() ] = true;
    }
    ,Undepend: function(elementid, library) {
        this.Dependencies[ elementid ][ library.toLowerCase() ] = false;
    }
    ,Depends: function (elementid) {
        var ret = [];
        var i = 0;
        
        if (this.Dependencies[ elementid ] == null) {
            return [];
        }

        for (library in this.Dependencies[ elementid ]) {
            if ( this.Dependencies[ elementid ][ library ] ) {
                ret[ i ] = library;
                i++;
            }
        }
        return ret;
    }
    ,Loaded: function(library) {
        if (this.Loaded[ library ] != null) {
            return this.Loaded[ library ];
        }
        return false;
    }
    ,LibraryLoaded: function(library) {
        library = library.toLowerCase(); // not really necessary, just second check
        this.Loaded[ library ] = true;
        Debug.Notice( 'BFC Library ' + library + ' loaded' );
    }
    ,UnloadedDepends: function (elementid) {
        var ret = [];
        var i = 0;
        var j = 0;
        var AllDepends = this.Depends(elementid);

        for (i = 0; i < AllDepends.length; i++) {
            if (!this.Loaded(AllDepends[i])) {
                ret[j] = AllDepends[i];
                j++;
            }
        }
        return ret;
    }
    ,Needs: function(elementid) {
        var AllNeeds = this.UnloadedDepends(elementid);
        var i = 0;
        var ret = '';

        for(i = 0; i < AllNeeds.length; i++) {
            ret += AllNeeds[i] + ',';
        }
        return ret;
    }
};
<?php
    $bfc = New BFC(0);

    include "js/bfc/depend.php";

    echo $bfc->GetDependencies();
    echo $bfc->GetRegisters();
    
    $debuglibs = $bfc->GetDebugLibraries();

    foreach($debuglibs as $lib) {
        ?>a('Debugging Library: <?php
        echo $lib;
        ?>');<?php
        include "js/libs/$lib";
    }
?>