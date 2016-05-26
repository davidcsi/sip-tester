
sub createSIPPCommand
{
	# Now we will create the actual SIPP command, depending on the arguments specified in the test

	my $brackground = shift;
	my $test = shift;

	my $xml_filename = shift;
	my $csv_filename = shift;
	
	my $log_file = shift;
	my $error_file = shift;
	my $trace_stat_file = shift;
	my $shortmsg_file = shift;
	my $tracemsg_file = shift;
	
	my $domain = shift;
	my $local_ip = shift;
	my $local_port = shift;
	
	my $oocsf = shift;
	if( $brackground > 0 ){ $bg = "-bg"; }else{ $bg = ""; }
	my @command_args = ( "sipp $bg -skip_rlimit " );
	push @command_args, $domain;
	
	if( $domain =~/5061/ )
	{
		push @command_args, "-t ll"; # 				if $test->{transport};
		push @command_args, " -tls_key ./key.pem -tls_cert ./cert.pem";
	}else{
		push @command_args, "-t tn"; # 				if $test->{transport};
	}
	
	# Add configuration options
	push @command_args, "-i $local_ip" 					if $local_ip;
	#push @command_args, "-p $local_port" 				if $local_port;
	push @command_args, "-inf $csv_filename" 					if $csv_filename;
	push @command_args, "-r 1";
	push @command_args, "-sf $xml_filename" 					;
	push @command_args, "-l 1"; # 				if $test->{calls_limit};
	push @command_args, "-m 1"; # 				if $total_calls;
	
	# Add logging specs and filenames
	push @command_args, "-trace_logs -log_file $log_file";
	push @command_args, "-trace_err -error_file $error_file";
	push @command_args, "-trace_stat -stf $trace_stat_file";
	push @command_args, "-trace_shortmsg -shortmessage_file $shortmsg_file";
	push @command_args, "-trace_msg -message_file $tracemsg_file";

	if( $oocsf gt '' )
	{
		push @command_args, "-oocsf $oocsf";
	}
	
	return @command_args;
}


sub createOtherCommand
{
	# Now we will create the actual SIPP command, depending on the arguments specified in the test
	my $test = shift;

	my $command_args = $test->{ command } ." ". $test->{ arguments };

	return $command_args;
}


sub createInjectionFile
{
	my $tmpfile = shift;
	my $registrar = shift;
	my $username = shift;
	my $password = shift;
	my $callee_number = shift; 
	my $callee_msisdn = shift;
	
	print "CSV Temp File: $tmpfile\n";
	print "Platform/Registrar: $registrar\n";
		
	my $csv_line;
	
	open( CSVFH, ">", $tmpfile );
	print CSVFH "SEQUENTIAL\n";
	print "user: $username\n";
	$csv_line = $username . ";" . 
		"[authentication username=" . $username . " password=" . $password . "];" .
		$registrar . ";" . 
		$callee_number . ";" .
		$registrar . ";" . 
		$callee_msisdn . ";\n";

	print CSVFH $csv_line;
	close(CSVFH);
}


sub generate_uuid {
    my $ug = UUID::Generator::PurePerl->new();
    my $uuid1 = $ug->generate_v1();
    return $uuid1;
}


1;
