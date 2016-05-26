<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  
  <title>Realtime tester</title>
  	<style>
    .redrow
    {
        font-family:Consolas; font-size:12pt; color:white; background-color:#FFACAC; text-align: left;
    }
    .boldtable, .boldtable TD
	{ 
		font-family:Consolas; font-size:12pt; color:black; background-color:#AFFFAC; text-align: left;
	}

    .reporttable, .boldtable TD
	{ 
		font-family:Consolas; font-size:12pt; color:black; background-color:#33C1FF; text-align: center;
	}

	th
	{
		font-family:Consolas; font-size:12pt; color:white; background-color:Gray; text-align: center;
	}

	
	.loading { margin: auto; }

	.loading span {
		line-height: 32px;
		margin-left: 12px;
		font-size: 16px;
		vertical-align: middle;
	}

	.loading img { vertical-align: middle; }
	.loading_wrp {
		background-color: #FFF;
		display: block;
		height: 100%;
		left: 0;
		opacity: 0.5;
		filter: alpha(opacity=50);
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 1020;
	}
	
	.loading_wrp .x16 span {
		line-height: 16px;
		font-size: 12px;
		margin-left: 6px;
	}
	
	.loading_wrp .x32 img {
		width: 32px;
		height: 32px;
	}
	
	</style>
  </head>
  <body>
<?php
	$test_users_id = 'test_users_id.csv';
	$test_users_phone_number = 'test_users_phone_number.csv';
	$test_req_domain = 'test_req_domain.csv';
	$test_dest_ip = 'test_dest_ip.csv';
	$test_dest_number = 'test_dest_number.csv';
?>
    <script src="jquery-2.2.3.min.js"></script>
    <script src="Simple-jQuery-Loading-Spinner-Overlay-Plugin-Loader/jquery-loader.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.2.12/jquery.jgrowl.min.js"></script>
	<form id='testform'>

		<table border="1" cellpadding="3">
			<tr><td>User ID:</td> <td><input type="text" id="user" name="user" size="20" maxlength="20" list="users_default"/>
				<datalist id="users_default">
                    <?php
                    $file = fopen( $test_users_id, 'r' );
                    $data = fread($file, filesize( $test_users_id ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( substr( $line,0,1 ) != "#" ){
	                        list($val,$desc) = explode(",",$line);
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
				</datalist>
			</td></tr> 
			<tr><td>User Phone Number</td> <td><input type="text" id="user_msisdn" name="user_msisdn" size="20" maxlength="30" list="users_msisdn_default"/>
				<datalist id="users_msisdn_default">
                    <?php
                    $file = fopen( $test_users_phone_number, 'r' );
                    $data = fread($file, filesize( $test_users_phone_number ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( substr( $line,0,1 ) != "#" ){
	                        list($val,$desc) = explode(",",$line);                        
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
				</datalist>
			</td></tr> 
			<tr><td>User Password</td> <td><input type="text" id="user_password" name="user_password" size="20" maxlength="30" /></td></tr> 
			<tr><td>scenario</td> <td>
				<select id="scenario" name="scenario">
					<?php
					
						$directory = 'scenarios/';
						$scanned_directory = array_diff(scandir($directory), array('..', '.'));
						sort($scanned_directory);
						
						foreach($scanned_directory as $key => $value):
							echo '<option value="'.$value.'">'.$value.'</option>'; //close your tags!!
						endforeach;
					
					?>
				</select></td></tr> 
			<tr><td>Domain (Req)</td><td>
			<input type="text" id="domain" list="domain_list" name="domain"/>
				<datalist id="domain_list">
                    <?php
                    $file = fopen( $test_req_domain, 'r' );
                    $data = fread($file, filesize( $test_req_domain ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( substr( $line,0,1 ) != "#" ){
	                        list($val,$desc) = explode(",",$line);                        
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
                    </datalist></td></tr> 
			<tr><td>Domain (DNS)</td> <td>
			<input type="text" id="req_domain" list="req_domain2" name="req_domain"/>
				<datalist id="req_domain2">
                    <?php
                    $file = fopen( $test_dest_ip, 'r' );
                    $data = fread($file, filesize( $test_dest_ip ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( substr( $line,0,1 ) != "#" ){
	                        list($val,$desc) = explode(",",$line);                        
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
				</datalist></td></tr> 
			<tr><td>Dest Phone Number</td> <td><input type="text" id="dest_libon_id" name="dest_libon_id" size="20" maxlength="30" list="dest_test_dids" />
				<datalist id="dest_test_dids">
                    <?php
                    $file = fopen( $test_dest_number, 'r' );
                    $data = fread($file, filesize( $test_dest_number ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( substr( $line,0,1 ) != "#" ){
	                        list($val,$desc) = explode(",",$line);                        
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
				</datalist></td></tr>
		</table>
    <div>
      <button type="submit" id="load-data" style='height:60px;'>Start Test</button>
    </div>
   	</form>
    <script>
    
	$(document).ready(function () {
	    $('#testform').validate({ // initialize the plugin
	    	debug: true,
	        rules: {
	        	user: {
	        		required: true,
	        		minlength: 6
	        	},
	        	user_msisdn: {
	        		required: true,
	        		minlength: 10
	        	},
	        	user_password: {
	        		required: true,
	        		minlength: 5
	        	},
	        	scenario: {
	        		required: true
	        	},
	        	domain: {
	        		required: true
	        	},
	        	req_domain: {
	        		required: true
	        	},
	        	dest_libon_id: {
	        		required: true,
	        		minlength: 5
	        	}
	        },
	        submitHandler: function (form) { // If everything is correct, we connect to the websocket and execute the test
	        	
	            $('#load-data').click(function () {

	                $data = {
        				autoCheck: $('#autoCheck').is(':checked') ? 32 : false,
        				size: $('#size').val(),
        				bgColor: $('#bgColor').val(),
        				bgOpacity: $('#bgOpacity').val(),
        				fontColor: $('#fontColor').val(),
        				//title: $('#title').val(),
        				title: 'Running...',
        				isOnly: !$('#isOnly').is(':checked'),
        				imgUrl: 'Simple-jQuery-Loading-Spinner-Overlay-Plugin-Loader/images/loading16x16.gif'
        			};
					$('#load-data').loader($data);
	                     
	             	$('#output-list').html("<table></table>");
	             	$('#output-log').html("<table></table>");
					var socket = new WebSocket('ws://' + location.hostname + ':65000');
					setTimeout(bindEvents, 5000);
					setReadyState();

					function bindEvents() {
						socket.onopen = function() {
							setReadyState();
						};
					};

					function setReadyState() {
						//log('ws.readyState: ' + socket.readyState);
						if( socket.readyState == 0 )
						{
							$('output-list').html('<h1>Still Connecting</h1>');
						}
						if( socket.readyState == 1 )
						{
							$('output-list').html('<h1>Connected</h1>');
						}
					};

	                 // On error
	                 socket.onerror =function(err){
	                	$('output-list').html('<h1>A connection to the WebSocket could not be established!</h1>');
	                 };
	                 
	                 socket.onerror = function (error) {
		                 window.alert("There was a problem connecting to the websocket server. This type of error usually happens when tehe connection request is rejected");
	                     console.error('There was an un-identified Web Socket error');
	                     $('#load-data').loader({ title: 'Start Test' }); $('#load-data').close(true);
	                 };
	                 
	                 socket.onclose = function() {
	                	 $('output-list').html('<h1>The connection to the WebSocket Has been closed!</h1>');
	                 };
	                 
	                 socket.onmessage = function (e) {
	                 	var data = e.data;
	                     var output_item = data.substring( 0, data.indexOf(':') );
	                     var output_data = data.substring( data.indexOf(':')+1,data.length );
	                     
	                     if( output_item == 'status' && output_data == 'running'){ $('#load-data').loader($data); }
	                     if( output_item == 'status' && output_data == 'finished'){ $('#load-data').loader({ title: 'Start Test' }); $('#load-data').close(true); }
	                     
	                     $( '#' + output_item ).html( output_data );
	                 };
	     			
	                 socket.onopen = function () {
	     				var query = "";
	     				query += 'user=' + $('#user').val();
	     				query += ',user_msisdn=' + $('#user_msisdn').val();
	     				query += ',user_password=' + $('#user_password').val();
	     				query += ',scenario=' + $('#scenario').val();
	     				query += ',domain=' + $('#domain').val();
	     				query += ',req_domain=' + $('#req_domain').val();
	     				query += ',dest_libon_id=' + $('#dest_libon_id').val();
	     				socket.send( query );
	                };

	             });
	        	return false; // for demo
	        }
	    });
	});

    </script>

	<div id='output-list'>
	</div>
	<div id='output-log'>
	</div>

  </body>
  
</html>
