#!/usr/bin/perl -w
use CGI;
use Fcntl qw(:DEFAULT :flock);
use LWP::UserAgent;
#use CGI::Cookie;

@qstring=split(/&/,$ENV{'QUERY_STRING'});
@p1 = split(/=/,$qstring[0]);
$earthId = $p1[1];
$earthId =~ s/[^0-9]//g;	# sanitized

$pathto = "/tmp/";	#"/var/www/vhosts/blogcube.net/web_users/makis/";
$numfile = $pathto . "bc_earthlength." . $earthId;
$postdata = $pathto . "bc_earthdata." . $earthId;
$earthfile = $pathto . "bc_earth." . $earthId;
$php_uploader = "http://blogcube.net/upload.bc?earthid=" . $earthId;
@vpath=split(/\//,$ENV{'SCRIPT_NAME'});
if (@vpath[1] eq 'beta') {	# then we are running on the `debug_version`
	$php_uploader = "http://blogcube.net/beta/upload.bc?earthid=" . $earthId;
}

$ip = $ENV{'REMOTE_ADDR'};
$content_type = $ENV{'CONTENT_TYPE'};
$len = $ENV{'CONTENT_LENGTH'};
$bRead=0;
$|=1;

sub bye_bye {
	$mes = shift;
	print "Content-type: text/html\n\n";
	print "$mes<br />\n";

	exit;
}

#Check if it's a valid session
if ( !((length($earthId) > 0) && (-e $earthfile)) ) {
	&bye_bye ("No earth under your feet");
}

#Check if it's the correct IP
open(EARTH,"<",$earthfile) or &bye_bye ("The earth is beyond my reach");
$designated_ip = <EARTH>;
close(EARTH);
chomp($designated_ip);	# strip trailing whitespace and newline
if ($designated_ip ne $ip) {
	&bye_bye ("No earth under your feet, $ip!");
}

#Check if it's actually a POST request or only a GET one
if ( $len < 1 ) {	# if it's a POST, it can't be Content-length: 0, even if it's not a file
	&bye_bye ("You didn't POST anything");
}

if (-e $numfile) {	# clean up $numfile, because if it is already there, we'll get a wrong size (sysopen doesn't create a new file)
	unlink($numfile);
}

sysopen(FH, $numfile, O_RDWR | O_CREAT)
	or die "can't open numfile: $!";
# autoflush FH
$ofh = select(FH); $| = 1; select ($ofh);
flock(FH, LOCK_EX)
	or die "can't write-lock numfile: $!";
seek(FH, 0, 0)
	or die "can't rewind numfile : $!";
print FH $len;	
close(FH);	
	
#sleep(1);	# not sure if we need to pause

open(TMP,">",$postdata) or &bye_bye ("can't open temp file");
unlink($earthfile) or die ("Earth can't unlink $earthfile - wrong permissions!");

print "Content-type: text/html\n\n";	# everything fine so far, so we are sending out headers of a normal reply

#%cookies = fetch CGI::Cookie;
#$cookie_data = "";
#foreach (keys %cookies) {
#        $cookie_data .= "-H 'Cookie: ".$cookies{$_}."' ";
#}

my $multipart='';	# this will be used to store the response we're gonna POST to $php_uploader
$ofh = select(TMP); $| = 1; select ($ofh);
			
while (read (STDIN ,$LINE, 4096) && $bRead < $len )
{
	$bRead += length $LINE;
#	select(undef, undef, undef,0.05);	# sleep for 0.05 of a second, if you want to give some cycles back to the OS.
	
	print TMP $LINE;
	$multipart .= $LINE;
}
close (TMP);	# upload finished, no more input

#POSTing back to $php_uploader (locally)
  $ua = LWP::UserAgent->new;

  my $req = HTTP::Request->new(POST => $php_uploader);
  $req->content_type($content_type);
  $req->content($multipart);

  my $res = $ua->request($req);	# the actual POST request is here
  #print $res->as_string;
  #print $multipart;
  print $res->content;	# print out whatever PHP gave us

#print "OK\n";	# alternatively, just print an OK
