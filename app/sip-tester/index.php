<html>
  <head>
		<link rel="stylesheet" href="website.css" type="text/css"/>
		<meta http-equiv="content-type" content="text/html; charset=windows-1250">
	    
		<title>Realtime tester</title>

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
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.20/jquery.form-validator.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>

	<form id='testform' class="form-horizontal well test-form">
		<table border=1>
			<tr>
				<td>
		        	<label class="control-label">
		        		User ID
		        	</label>
		        </td>
		        <td>
					<input 
						type="text" 
						id="user" 
						name="user" 
						size="20" 
						maxlength="20" 
						list="users_default" 
						data-validation="required" 
						data-validation="alphanumeric length"
						data-validation-length="min3"
						data-validation-error-msg="The user name has to be an alphanumeric value longer than 3 characters"
					/>
					<datalist id="users_default">
	                    <?php
	                    $file = fopen( $test_users_id, 'r' );
	                    $data = fread($file, filesize( $test_users_id ));
	                    fclose($file);
	
	                    $lines =  explode(PHP_EOL,$data);
	                    foreach($lines as $line) {
	                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
		                        list($val,$desc) = explode(",",$line);
		                        echo '<option value="'. $val .'">'.$desc.'</option>';
	                        }
	                    }
	                    ?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td>
		        	<label class="control-label">
						User Phone Number
		        	</label>
			    </td>
			    <td>
					<input type="text" id="user_msisdn" name="user_msisdn" size="20" maxlength="30" list="users_msisdn_default" 
						data-validation="required"
						data-validation="alphanumeric length"
						data-validation-length="min3"
						data-validation-error-msg="The user name has to be an alphanumeric value longer than 3 characters"
					/>
					<datalist id="users_msisdn_default">
	                    <?php
	                    $file = fopen( $test_users_phone_number, 'r' );
	                    $data = fread($file, filesize( $test_users_phone_number ));
	                    fclose($file);
	
	                    $lines =  explode(PHP_EOL,$data);
	                    foreach($lines as $line) {
	                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
		                        list($val,$desc) = explode(",",$line);                        
		                        echo '<option value="'. $val .'">'.$desc.'</option>';
	                        }
	                    }
	                    ?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td>
		        	<label class="control-label">
						User Password
		        	</label>
			    </td>
			    <td>
					<input type="text" id="user_password" name="user_password" size="20" maxlength="30"
						data-validation="required"
						data-validation="required"
						data-validation="alphanumeric length"
						data-validation-length="min3"
						data-validation-error-msg="The user name has to be an alphanumeric value longer than 3 characters"
					/>
				</td>
			</tr>
			<tr>
				<td>
		        	<label class="control-label">
						Scenario
		        	</label>
			    </td>
			    <td>
					<select id="scenario" name="scenario">
						<?php
						
							$directory = 'scenarios/';
							$scanned_directory = array_diff(scandir($directory), array('..', '.'));
							sort($scanned_directory);
							
							foreach($scanned_directory as $key => $value):
								echo '<option value="'.$value.'">'.$value.'</option>'; //close your tags!!
							endforeach;
						
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
		        	<label class="control-label">
						Domain (Req)
		        	</label>
			   	</td>
			    <td>
					<input type="text" id="domain" list="domain_list" name="domain" data-validation="required"/>
					<datalist id="domain_list">
	                    <?php
	                    $file = fopen( $test_req_domain, 'r' );
	                    $data = fread($file, filesize( $test_req_domain ));
	                    fclose($file);
	
	                    $lines =  explode(PHP_EOL,$data);
	                    foreach($lines as $line) {
	                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
		                        list($val,$desc) = explode(",",$line);                        
		                        echo '<option value="'. $val .'">'.$desc.'</option>';
	                        }
	                    }
	                    ?>
                    </datalist>
				</td>
			</tr>
			<tr>
				<td>
		        	<label class="control-label">
						Domain (DNS)
		        	</label>
			    </td>
			    <td>
					<input type="text" id="req_domain" list="req_domain2" name="req_domain" data-validation="required"/>
					<datalist id="req_domain2">
	                    <?php
	                    $file = fopen( $test_dest_ip, 'r' );
	                    $data = fread($file, filesize( $test_dest_ip ));
	                    fclose($file);
	
	                    $lines =  explode(PHP_EOL,$data);
	                    foreach($lines as $line) {
	                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
		                        list($val,$desc) = explode(",",$line);                        
		                        echo '<option value="'. $val .'">'.$desc.'</option>';
	                        }
	                    }
	                    ?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td>
		        	<label class="control-label">
						Dest Phone Number
		        	</label>
			    </td>
			    <td>
					<input type="text" id="dest_libon_id" name="dest_libon_id" size="20" maxlength="30" list="dest_test_dids"  data-validation="required"/>
					<datalist id="dest_test_dids">
	                    <?php
	                    $file = fopen( $test_dest_number, 'r' );
	                    $data = fread($file, filesize( $test_dest_number ));
	                    fclose($file);
	
	                    $lines =  explode(PHP_EOL,$data);
	                    foreach($lines as $line) {
	                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
		                        list($val,$desc) = explode(",",$line);                        
		                        echo '<option value="'. $val .'">'.$desc.'</option>';
	                        }
	                    }
	                    ?>
					</datalist>
				</td>
			</tr>
		</table>
	</form>
   	
	<div>
      <button type="submit" id="load-data" style='height:60px;'>Start Test</button>
    </div>
   
   
   <!-- -------- -->
   <!--  Side B  -->
   <!-- -------- -->
   
   	<input type="checkbox" id="launch_b_side" value="launch_b_side">Launch B-side?
   	
   	<div style="display: none" id="b_side_form" name="b_side_form">
	   	<form id="b_side" name="b_side">
			<table border="1" cellpadding="3">
				<tr><td>User ID:</td> <td><input type="text" id="b_user" name="b_user" size="20" maxlength="20" list="users_default"/></td></tr>
				<tr><td>User MSISDN:</td> <td><input type="text" id="b_msisdn" name="b_msisdn" size="20" maxlength="20" list="users_msisdn_default"/></td></tr>
				<tr><td>Password:</td> <td><input type="password" id="b_password" name="b_password" size="20" maxlength="20" list="users_default"/></td></tr>
				<tr><td>scenario</td>
					<td>
						<select id="b_scenario" name="b_scenario">
							<?php
							
								$directory = 'scenarios/';
								$scanned_directory = array_diff(scandir($directory), array('..', '.'));
								sort($scanned_directory);
								
								foreach($scanned_directory as $key => $value):
									if( preg_match("/^b_.*/", $value ) )
									{
										echo '<option value="'.$value.'">'.$value.'</option>'; //close your tags!!
									}
								endforeach;
							
							?>
						</select>
					</td>
				</tr>
				<tr><td>Domain (Req):</td> <td><input type="text" id="b_domain_req" name="b_domain_req" size="50" list="domain_list"/>
				<datalist id="b_domain_list">
                    <?php
                    $file = fopen( $test_req_domain, 'r' );
                    $data = fread($file, filesize( $test_req_domain ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
	                        list($val,$desc) = explode(",",$line);                        
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
                    </datalist></td></tr>
				<tr><td>Domain (DNS):</td> <td><input type="text" id="b_domain_ip" name="b_domain_ip" size="50" list="b_req_domain2"/>
				<datalist id="b_req_domain2">
                    <?php
                    $file = fopen( $test_dest_ip, 'r' );
                    $data = fread($file, filesize( $test_dest_ip ));
                    fclose($file);

                    $lines =  explode(PHP_EOL,$data);
                    foreach($lines as $line) {
                    	if( !preg_match( "/^#/", $line) && !preg_match( "/^$/", $line)  ){
	                        list($val,$desc) = explode(",",$line);                        
	                        echo '<option value="'. $val .'">'.$desc.'</option>';
                        }
                    }
                    ?>
				</datalist></td></tr>
			</table>
	   	</form>
   	</div>
   	 
    
    
   
   	<script>
    
	$(document).ready(function () {

		// When "launch-b-side is selected or unselected, toggle accordingly
		
		$('#launch_b_side').click(function() {
		    $("#b_side_form").toggle(this.checked);
		});

		
		// We try to bind all input boxes' focus events so that if the text is "REQUIRED!", 
		// we erase it and set the font back to black.
		$("form#testform :input").each(function(){
			var input = $(this); // This is the jquery object of the input, do what you will
	
			input.focus(function() {
				if( input.val() == 'REQUIRED!')
				{
					input.css('color', 'black');
					input.val('');
				}
			});
		});

		// We want to validate the form , there must be something on all fields. If the content's length is < 3 chars, 
		// Set a message saying it is required and setting the font color to RED
		
		$('#load-data').click(function () {
			var allFieldsInput = true;
			
			$("form#testform :input").each(function(){
				var input = $(this); // This is the jquery object of the input, do what you will
				if( input.val().length < 3 )
				{
					input.val('REQUIRED!');
					input.css('color', 'red');
					allFieldsInput = false;
				}else{
					input.css('color', 'black');
				}
			});
			alert("In function");
	       	// If everything is correct, we call the processing function ( I justr wanted it more clear )
	       	if( allFieldsInput == true )
	       	{
				mainFunc();
	       	}
		});
	});


	function mainFunc()
	{

		// Set the button as "working..."
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
		
		// Cleaup output areas
     	$('#output-list').html("<table></table>");
     	$('#output-log').html("<table></table>");

     	// just for debug
     	alert('connecting to ' + location.hostname + ':65000');

     	//conncet to the remote websocket, where the sipp test will be launched
		var socket = new WebSocket('ws://' + location.hostname + ':65000');

		// Set timeouts and binding events so we can raise an error if we can't connect.
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

			// When the connection is accepted, send the test parameters
        socket.onopen = function () {
			var query = "";
 			query += 'user=' + $('#user').val();
 			query += ',user_msisdn=' + $('#user_msisdn').val();
 			query += ',user_password=' + $('#user_password').val();
 			query += ',scenario=' + $('#scenario').val();
 			query += ',domain=' + $('#domain').val();
 			query += ',req_domain=' + $('#req_domain').val();
 			query += ',dest_libon_id=' + $('#dest_libon_id').val();

			if ($('#launch_b_side').is(':checked')) {
				query += ',b_user=' + $('#b_user').val();
				query += ',b_msisdn=' + $('#b_msisdn').val();
				query += ',b_password=' + $('#b_password').val();
   				query += ',b_scenario=' + $('#b_scenario').val();
   				query += ',b_domain_req=' + $('#b_domain_req').val();
   				query += ',b_domain_ip=' + $('#b_domain_ip').val();
			}
 			
			socket.send( query );
        };
	}

    </script>

	<div id='output-list'>
	</div>
	<h1>Output Log (Caller)</h1>
	<div id='output-log'>
	</div>
	<h1>Output Log (Callee)</h1>
	<div id='output-log'>
	</div>
	
  </body>
  
</html>
