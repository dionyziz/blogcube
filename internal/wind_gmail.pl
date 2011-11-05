#!/usr/bin/perl -w
use strict;
use LWP 5.64; # use LWP and check that the version is recent enough
use HTTP::Cookies;
use Term::ReadKey;

my $domain = 'http://www.google.com/';
my $mail = 'http://mail.google.com/mail/';
my $login = 'https://www.google.com/accounts/ServiceLogin?service=mail&passive=true&rm=false&continue=http%3A%2F%2Fmail.google.com%2Fmail%3Fui%3Dhtml%26zy%3Dl&ltmpl=googlemail';
my $loginauth = 'https://www.google.com/accounts/ServiceLoginAuth';
my $userid;
my $passwd;

# create a new useragent
my $ua = LWP::UserAgent->new;
$ua->agent('Lynx/1.0');
$ua->cookie_jar( HTTP::Cookies->new() );

# allow post to redirect useragent
push @{ $ua->requests_redirectable }, 'POST';

#print STDERR "Username: ";
$userid = <>;
chomp $userid;
#ReadMode('noecho');
#print STDERR "Password: ";
$passwd = <>;
chomp $passwd;
#ReadMode('restore');
#print STDERR "\n";

my $response = $ua->post($loginauth,
 ['ltmpl' => 'googlemail',
  'continue' => 'http://mail.google.com/mail?ui=html&amp;zy=l',
  'service' => 'mail',
  'rm' => 'false',
  'Email' => $userid,
  'Passwd' => $passwd,
  'PersistentCookie' => 'yes',
  'rmShown' => '1',
  'login_username' => $userid,
  'login_password' => $passwd,
 ]
);

if (!($response->is_success)) {
        print STDERR "I can't download $loginauth: ", $response->status_line, "\n";
        goto pAnyway;
}

if (!( $response->content =~ /^.*\Smeta\scontent\S\S\d*\S\surl\S([^"]*?)["].*refresh.*$/mg )) {
        print STDERR "Login redirection failed\n";
		print STDOUT $response->content;
		goto pAnyway;
}
my $redirect = $1;
print STDERR "Logged in.\n";
#print STDERR "Redirecting to $redirect\n";

$response = $ua->get($redirect);
if (!($response->is_success)) {
        print STDERR "I can't download $redirect: ", $response->status_line, "\n";
        goto pAnyway;
}

print STDERR "Redirected.\n";

if (!( $response->content =~ /^.*\Sbase\shref\S\S([^"]*?)["]\S.*$/mg )) {
        print STDERR "No base-href\n";
		print STDOUT $response->content;
		goto pAnyway;
}
my $base = $1;
my $contacts = $base . '?pnl=a&v=cl';

$response = $ua->get($contacts);
if (!($response->is_success)) {
        print STDERR "I can't download $contacts: ", $response->status_line, "\n";
        goto pAnyway;
}

print STDERR "Got contacts.\n";
#print STDOUT $response->content;

if (!( $response->content =~ /^.*\Stable\s[^>]*class\Sth\S(.*?)\S\/table\S.*$/sg )) {
        print STDERR "Check contacts!\n";
		print STDOUT $response->content;
        goto pAnyway;
}
my $contactlist = $1;
print STDOUT $contactlist;

exit 0; # Done

pAnyway:
exit 66; # Error
