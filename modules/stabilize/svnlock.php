<?php
	/*
	Module: Stabilization script
	File: /modules/stabilize/svnlock.php
	Developers: ch-world
	*/
	
	function svnlock() {
		exec( "sudo /srv/svnlock");
	}
	
	function svnunlock() {
		exec( "sudo /srv/svnunlock");
	}
?>