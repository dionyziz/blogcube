#!/usr/bin/perl -w
use LWP 5.64;

my $url='http://www.blogcube.net/blog.bc';
my $ua = LWP::UserAgent->new;
my @arr = <STDIN>;
my $maildata = join ("", @arr);

$maildata =~ s/\r?\n/\r\n/g;

# POST to the specified URL
my $response = $ua->post($url,
 ['emailblog_data' => $maildata,
  'emailblog_sender' => $ENV{'SENDER'}
 ]
);

# HTTP POST error, probably temporary
if (!$response->is_success) {
	print 'Retry later';
	exit 111; #QMAIL_RETRY_LATER (SOFT_ERROR)
}

if ($response->content =~ /^Okay$/gm) {
	print 'Delivered';
	exit 0; #QMAIL_DELIVERED
}
print $response->content; #instead of just 'Failed';
exit 100; #QMAIL_FAILED_PERMANENTLY (HARD_ERROR)
