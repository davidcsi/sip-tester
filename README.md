# Docker SIP Tester

This is a docker to install nginx with php support, and a website that contains the webtool to execute SIP tests 

## Installation
Clone this project on your pc: `git clone git@github.com:davidcsi/sip-tester.git`

The webtool provides some options to make testing easier like user-ids, domain-req and domain-dns which are taken from csv files. You need to create your own csv files:
<<<<<<< HEAD
Before starting the container mounting the volumes, you can add the following info:
=======
>>>>>>> 104f75d1c384c640e5a36a75f5d665092ee7a298
Inside the project's directory, go to app/sip-tester and edit the csv files to add this info.
The meaning of the files:

######- test_users_id.csv
Users IDs to show dor testing. Some domains' user-id differ from the user's actual phone number. If it is the same, just putit here.
######- test_users_phone_number.csv
######- test_req_domain.csv
The realm to use for authentication. Sometimes you have a DNS record pointing to your SIP registrar, and have a separate "test-registrar" on a different IP, but which requires you to send a realm/fqdm for which you don't have a DNS record. Here you enter the Realm/FQDN.
######- test_domain_ip.csv
IP or FQDN where to send the SIP messages. As with "test_req_domain", here you would enter the actual IP to send SIP messages to, which may differ from the DNS record.
######- test_dest_phine_number.csv
Phone Number to call, must be entered always.

## Usage

# Build and run the Docker:

```
cd sip-tester
docker build -t YOURUSER/sip-tester .
```
This will pull a docker from `webdevops/php:centos-7` which is the base docker containing nginx and php already configured :)

To have access to the webtool, you need to forward the http port, i.e. 8888->80.
Also, the webpage connects via websocket to a server running in the docker on port 65000, so you need to forward that port like: 
```
<<<<<<< HEAD
docker run -it -p 8888:80 -p 65000:65000 -v $PWD/app:/app YOURUSER/sip-tester
=======
docker run -it -p 8888:80 -p 65000:65000 YOURUSER/sip-tester
>>>>>>> 104f75d1c384c640e5a36a75f5d665092ee7a298
``` 

when it's up and running, you need to connect to it via http, open your browser to:
`http://docker-host-ip:8888/app`
<<<<<<< HEAD

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
## History
TODO: Write history
## Credits
TODO: Write credits
## License
TODO: Write license

=======
>>>>>>> 104f75d1c384c640e5a36a75f5d665092ee7a298


The main page is a php script gathering necessary information, such as:

- User ID
- User Phone Number
- User Password
- Scenario
- Domain (Req)
- Domain (DNS)
- Dest Number

In some test scenarios, the tester expects specific reaction from the B side, like it rejecting the incoming call with USER_BUSY, Not answering the call, etc. The expected B-side reaction is explained later on the scenarios explanation.

As default, we have the following scenarios:

- A2A_Rejected2VM.xml
- A2ARingTimeout2VM.xml
- call_no_codec.xml
- cancel.xml
- disconnected.xml
- hangup_callee.xml
- hangup_caller.xml
- hold_caller.xml
- register.xml
- reject.xml
- ring_timeout.xml
- rtp_opus.pcap
- successfull-call-answered-by-user-agent-freeswitch.xml
- successfullcall.xml
- wait_for_incomin_call.xml

When a test is executed, the web page connects via websocket written server (perl), which in turn executes the tests with SIPP and sends the output and logs to the web php page.


## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
## History
TODO: Write history
## Credits
TODO: Write credits
## License
TODO: Write license


