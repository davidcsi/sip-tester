#!/usr/bin/perl -w

use strict;
use CGI::Carp;
use Data::Dumper;
use Cwd qw(abs_path);
use Net::WebSocket::Server;
use UUID::Generator::PurePerl;
use Net::Address::IP::Local;
use File::Basename;

my $local_ip = Net::Address::IP::Local->public;
my $scriptdir = File::Basename::dirname(Cwd::abs_path($0));

print "Local IP: $local_ip\n";

Net::WebSocket::Server->new(
	listen => 65000,
	on_connect => sub {
		my ($serv, $conn) = @_;
		$conn->on(
			handshake => sub {
				my ($conn, $handshake) = @_;
				#$conn->disconnect() unless $handshake->req->origin eq $origin;
			},
			utf8 => sub {
				my ($conn, $msg) = @_;
				
				# This is the A-side launching
				print "vardump: " . Dumper($msg) . "\n";

				my ($user, $user_msisdn, $user_password, $scenario, $domain, $req_domain, $dest_number) = map {substr($_,index($_,"=")+1,length $_)} split(",", $msg);
				my $screen = "";
				my $uuid = generate_uuid();
				my $command = "sudo $scriptdir/sipp_test_launcher.pl 1 0 $local_ip 50001 scenarios/$scenario $user $user_msisdn $user_password $domain $req_domain $dest_number $dest_number 1 $uuid 2>&1 |";
				print "Command: $command\n";
				open(TEST,$command);
				my $log_file;
				my $error_file;
				my $csv_file;

				$conn->send_utf8( 'status:running');
				while(my $data = <TEST>)
				{

					# Get the log file
				    if ($data =~ /-message_file/){ 
				    	($log_file) = $data =~ /-message_file (.*msg\.log?)/; 
				    }
				    if ($data =~ /-error_file/ ){ 
				    	($error_file) = $data =~ /-error_file (.*error\.log?)/; 
				   	}
				    if ($data =~ /-inf / ){ 
				    	print "CSV: $data\n";
				    	($csv_file) = $data =~ /-inf (.*inject\.csv?)/;
				    	print "CSV FILE: $csv_file\n"; 
				   	}
				    

					if( $data =~ /Scenario Screen/ )
					{
						$conn->send_utf8( 'output-list:<table border=1><tr ><th>Message Type</th><th>Direction</th><th>Messages</th><th>Retrans</th><th>Timeout</th><th>Unexpected-Msg</th></tr>'. $screen .'</table>') if length( $screen ) > 0;
						$screen = "";
					}
					
					#	Break the scenario into columns and make them td
					if( $data =~ /-->/ || $data =~ /<--/ || $data =~ /Pause / )
					{
						chomp($data);

						$data =~ s/[\[\]]//g;
						$data =~ s/^ +//gs;
						$data =~ s/ +$//gs;
						$data = substr($data,0,-1);
						if( scalar(split(/\W+/,$data)) < 6 )
						{
							$data .= "   0" x (5-scalar(split(/\W+/,$data)));
						}
						$data =~ s/ +/\t/gs;
						my @columns = split(/\t/,$data);
						
						my $row="";
						foreach my $column (@columns)
						{
							$column = '<td>'.$column.'</td>';
							$row .= $column;
						}
						$row = '<tr CLASS="reporttable">' . $row . '</tr>';
						$screen .= $row;
					}
					
					#	When test is complete, show it on the bottom
					if( $data =~/Test Terminated/ || $data =~ /^RESULT=/)
					{
						$conn->send_utf8( 'status:finished');
					}
				}
				

				my $log_send = "";
				my $goodformat = 'CLASS="boldtable"';
				my $badformat = 'CLASS="redrow"';
				my $class = "";

				#  If trace file exists, show it. Else Show error file
				if (-e $log_file) { 

					# Send the log file to output-log
					my $log_data = `cat $log_file`;
					$log_data = $log_data . "\n-";
	
	
					$log_send = '<TABLE BORDER>';
					foreach my $message ( split(/\n\n-/, $log_data) ) {
						
						if( $message =~ /Unexpected/)
						{
							$class = $badformat;
						}else{
							$class = $goodformat;
						}
						
						$log_send .= '<tr><td '. $class .'><xmp>' . $message . '</xmp></td></tr>';
					}
				 	$log_send .= '</table>';
				}else{
					my $error_data = `cat $error_file`;
					$error_data =~ s/\</\&lt/g;
					$error_data =~ s/\>/\&gt/g;
					$error_data = $error_data . "\n-";
					
					$log_send = '<table BORDER><tr><td'.$badformat.'><pre>'.$error_data.'</pre></td></tr></table>';
				}
				
				$conn->send_utf8( 'output-log:' . $log_send);
				close(TEST);
				system( "rm -rf " . $csv_file );
			},
		);
	}
)->start;


sub generate_uuid {
    my $ug = UUID::Generator::PurePerl->new();
    my $uuid1 = $ug->generate_v1();
    return $uuid1;
}
