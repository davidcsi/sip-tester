#!/usr/bin/perl -w
use warnings;
use CGI qw(:standard); 
use CGI::Carp qw(fatalsToBrowser);
use UUID::Generator::PurePerl;
use Data::Dumper;


print "Content-type: text/html\r\n\r\n";

use CGI;
my $q = CGI->new();
my $user = $q->param('user');
my $user_msisdn = $q->param('user_msisdn');
my $user_password = $q->param('user_password');
my $domain = $q->param('domain');
my $req_domain = $q->param('req_domain');
my $dest_libon_id = $q->param('dest_libon_id');
my $scenario = $q->param('scenario');

my $ug = UUID::Generator::PurePerl->new();
my $uuid1 = $ug->generate_v1();

print "<html>\n";
print "<font face='Consolas'>";
$|++;

if(
	defined($user) && 
	defined($user_msisdn) && 
	defined($user_password) && 
	defined($domain) && 
	defined($req_domain) && 
	defined($dest_libon_id) && 
	defined($scenario) 
){
	
	print "<style>
    .redrow
    {
        font-family:Consolas; font-size:12pt; color:white; background-color:red; 
    }
    .boldtable, .boldtable TD, .boldtable TH 
	{ 
		font-family:Consolas; font-size:12pt; color:white; background-color:navy; 
	}
	</style>\n";
	$|++;

	my $command = "sudo ./sipp_test_launcher.pl 1 0 10.0.160.3 50001 scenarios/$scenario $user $user_msisdn $user_password $domain $req_domain $dest_libon_id $dest_libon_id 1 $uuid1";
	print $command."<br>\n";
	$result =  `$command`;

	($log_file) = $result =~ /-message_file (.*msg\.log?)/; 
	
	$result =~ s/\n/<br>/g;
	$result =~ s/\</\&lt/g;
	$result =~ s/\>/\&gt/g;

	$|++;
	
	$log_data = `cat $log_file`;
	$log_data =~ s/\</\&lt/g;
	$log_data =~ s/\>/\&gt/g;
	$log_data = $log_data . "\n-";
	
	my $goodformat = "CLASS=\"boldtable\"";
	my $badformat = "CLASS=\"redrow\"";
	my $class = "";
	
	print "<TABLE BORDER>\n";
	foreach my $message ( split(/\n\n-/, $log_data) ) {
		
		if( $message =~ /Unexpected/)
		{
			$class = $badformat;
		}else{
			$class = $goodformat;
		}
		
		print "<tr><td $class><pre>$message</pre></td></tr>\n";
	}
 	print "</table>\n";
	$log_data =~ s/\n/<br>/g;
	print "</font>";

}else{
	
	
	print "<body font=Consolas>";
	print $q->start_form(
		-name    => 'main_form',
		-method  => 'GET',
		-enctype => &CGI::URL_ENCODED,
		-onsubmit => 'return javascript:validation_function()',
		-action => 'test.pl', # Defaults to  the current program
    );
    
    my @domains = (
    	'staging.voip.lifeisbetteron.com', 
    	'qap.voip.lifeisbetteron.com', 
    	'preprod.voip.lifeisbetteron.com',
    	'voip.lifeisbetteron.com'
    );

	my @scenario_files;
	opendir( DIR, 'scenarios/' ) or die "i couldn't open the directory";
	while( $file=readdir DIR ){
		next unless (-f "scenarios/$file");
		push @scenario_files,$file; 
	}

	print $q->table
    (
        {
            -border=>1, -cellpadding => 3
        },
        $q->Tr({ -bgcolor => '#F5F6CE' }, [$q->td([ 'Libon UserID:', 'Platform', 'MSISDN' ])]),
        $q->Tr([$q->td([ '40311851',	'+33600010001' ,'Staging' ])]),
        $q->Tr([$q->td([ '2932551',		'+33600010001' ,'QAP' ])]),
        $q->Tr([$q->td([ '68403551',	'+33600010001', 'Preprod' ])]),
	$q->Tr([$q->td([ '1040927901',	'+33600010001', 'Production'])]),
    );

my $combo = '
	<input type="text" name="req_domain" list="req_domain"/>
	<datalist id="req_domain">
		<option value="staging.voip.lifeisbetteron.com">staging.voip.lifeisbetteron.com</option>
		<option value="qap.voip.lifeisbetteron.com">qap.voip.lifeisbetteron.com</option>
		<option value="preprod.voip.lifeisbetteron.com">preprod.voip.lifeisbetteron.com</option>
		<option value="voip.lifeisbetteron.com">voip.lifeisbetteron.com</option>
	</datalist>';

	print $q->table
    (
        {
            -border=>1, -cellpadding => 3
        },
        $q->Tr([$q->td([ 'Libon UserID:', 			$q->textfield( -name => 'user', -value => '', -size => 20, -maxlength => 20, ) ])]),
        $q->Tr([$q->td([ 'User MSISDN', 			$q->textfield( -name => 'user_msisdn', 	-value => '', -size => 20, -maxlength => 30,        )])]),
        $q->Tr([$q->td([ 'User Password', 			$q->textfield( -name => 'user_password', 	-value => '', -size => 20, -maxlength => 30,    )])]),
        $q->Tr([$q->td([ 'scenario', 				$q->popup_menu( -name => 'scenario', -values  => \@scenario_files, -default => '' )  ])]),
        $q->Tr([$q->td([ 'Domain (Req)', 			$q->popup_menu( -name => 'domain', -values  => \@domains, -default => 'qap.voip.lifeisbetteron.com' )  ])]),     
        $q->Tr([$q->td([ 'Domain (DNS)', 			$combo ])]),
        $q->Tr([$q->td([ 'Dest LibonID or MSISDN', 	$q->textfield( -name => 'dest_libon_id', 	-value => '', -size => 20, -maxlength => 30,    )])]),
    );
    
    print $q->submit(
		-name     => 'submit_form',
		-value    => 'Execute!'
	);
    print $q->end_form;

}

print "</html>\n";

