
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
	             	alert('connecting to ' + location.hostname + ':65000');
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