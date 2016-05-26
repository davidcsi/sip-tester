# SIP Tester

This is a web tool to launch tests against any SIP Sever.

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

When a test is executed, the web page connects via websocket on port 8181 to a websocket server (perl), which in turn executes the tests with SIPP and sends the output and logs to the web php page.

