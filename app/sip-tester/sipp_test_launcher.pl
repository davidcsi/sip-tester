#!/usr/bin/perl 
#-d:Trace

use strict;
use DBI;
use Data::Dumper;
use File::Temp;
use File::Basename;
use Cwd qw(abs_path);
use Time::HiRes qw(gettimeofday);

my $scriptdir = File::Basename::dirname(Cwd::abs_path($0));
require $scriptdir . "/sipp_test_functions.pl";

if ( @ARGV < 14 )
{
	print "
	You must specify:
		LOCAL_ECHO?
		BACKGROUND?
		LOCAL_IP_ADDRESS
		LOCAL_PORT - Port to use on loca ip
		SCENARIO_FILE - xml file
		USERNAME - Register username
		MSISDN - Username's MSISDN
		PASSWORD - Register password
		DOMAIN - Register domain
		IP:PORT - Registrar IP address:Port Number
		NUMBER_TO_CALL - Phone number to call (Libon User-ID if A2A)
		MSISDN_OUT - If A2A, set the callee's MSISDN
		DEBUG - Debug? (1/0)
		TEST-UUID
		OOCSF - Out-of-call scenario file (If applicable)
	";
	exit -1;
}

my $local_echo = shift;
my $brackground = shift;
my $local_ip = shift;
my $local_port = shift;
my $scenario = shift;
my $username = shift;
my $msisdn = shift;
my $password = shift;
my $domain = shift;
my $domain_ip = shift;
my $callee_number = shift;
my $callee_msisdn = shift;

my $debug = shift;
my $test_uuid = shift;
my $oocsf = shift;


print "ip: $local_ip
port: $local_port
scenario: $scenario
username: $username
pasword: $password
domain name: $domain
domain ip: $domain_ip
user msisdn: $msisdn
callee number: $callee_number
callee msisdn: $callee_msisdn
";

my $dbh;

# Prototyping

#connect2db();

print "********************************************************************************\n";
print "*                                                                              *\n";
print "                     $scenario\n";
print "*                                                                              *\n";
print "********************************************************************************\n";

# Create a temporary file for the XML scenario
my $timestamp = int (gettimeofday * 1000);

$timestamp = "";

my $tmpXMLfile =   $scriptdir . "/" . $scenario;
my $tmpCSVfile =   $scriptdir . "/configs/$timestamp"."_testuuid_" . $test_uuid . "_inject.csv";
my $logfile =      $scriptdir . "/logfiles/$timestamp"."_testuuid_" . $test_uuid . ".log";
my $error_file =   $scriptdir . "/logfiles/$timestamp"."_testuuid_" . $test_uuid . "_error.log";
my $trace_stats =  $scriptdir . "/logfiles/$timestamp"."_testuuid_" . $test_uuid . "_stats.log";
my $shortmsg =     $scriptdir . "/logfiles/$timestamp"."_testuuid_" . $test_uuid . "_shortmsg.csv";
my $trace_msg =    $scriptdir . "/logfiles/$timestamp"."_testuuid_" . $test_uuid . "_trace_msg.log";

print "csv: $tmpCSVfile \n";
print "log: $logfile \n";
print "error: $error_file \n";
print "stats: $trace_stats \n";
print "shortmsg: $shortmsg \n";
print "trace: $trace_msg \n";

# Create the scenario based on the xml inside the test we got from the db

#my $scenario_file = createScenarioXML($test, $tmpXMLfile);

# Create inject csv file
my $result;
my $platform = $domain;

my $sipp_result;
my $result;


my $injection_result = createInjectionFile( 
	$tmpCSVfile, 
	$domain, 
	$username, 
	$password,
	$callee_number, 
	$callee_msisdn
);

# SIPP Command creation
$oocsf = "" if !defined($oocsf);

my @SIPPCommand = createSIPPCommand( $brackground, $test_uuid, $tmpXMLfile, $tmpCSVfile, $logfile, $error_file, $trace_stats, $shortmsg, $trace_msg, $domain_ip, $local_ip, $local_port, $oocsf );
print "Command: \n\n" . join(" ",@SIPPCommand) . "\n\n";

if( $local_echo == 1 )
{
	$sipp_result = system( join(" ",@SIPPCommand) );
}else{
	$sipp_result = system( join(" ",@SIPPCommand) . ">/dev/null" );
}
chomp($sipp_result);

system("cat $trace_msg"); 

print "RESULT=(" . $sipp_result .")";
exit $sipp_result;

