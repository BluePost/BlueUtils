<?php
	//BLue Email library by James Bithell
	/* Requires:
			"sendgrid/sendgrid": "~5.1"
			"twig/twig" : "~1.26"
	*/
	//TODO Require twig and "sendgrid/sendgrid": "~5.1"
	namespace BluePost;
	class Email {
		$basicdetails = [];
		$body = [];
		public function __construct ( $FROM = '', array $TO = [], array $CC = [], array $BCC = [], $SUBJECT ) {
			$this->basicdetails['from'] = filter_var($FROM, FILTER_SANITIZE_EMAIL);

			//Setup TO Messages
			if (is_array($TO)) {
				$this->basicdetails['to'] = [];
				foreach ($TO as $emails) {
					$this->basicdetails['to'][] = filter_var($emails, FILTER_SANITIZE_EMAIL);
				}
			} else $this->basicdetails['to'] = [filter_var($TO, FILTER_SANITIZE_EMAIL)];
			//Setup CC Messages
			if (is_array($CC)) {
				$this->basicdetails['cc'] = [];
				foreach ($CC as $emails) {
					$this->basicdetails['cc'][] = filter_var($emails, FILTER_SANITIZE_EMAIL);
				}
			} else $this->basicdetails['cc'] = [filter_var($CC, FILTER_SANITIZE_EMAIL)];
			//Setup BCC Messages
			if (is_array($BCC)) {
				$this->basicdetails['bcc'] = [];
				foreach ($BCC as $emails) {
					$this->basicdetails['bcc'][] = filter_var($emails, FILTER_SANITIZE_EMAIL);
				}
			} else $this->basicdetails['bcc'] = [filter_var($BCC, FILTER_SANITIZE_EMAIL)];

			$this->basicdetails['subject'] = $SUBJECT;
		}
		function body() {
			
		}
		function addattachment() {

		}
		function send() {
			if (!isset($this->body['html'])) throw new Exception("No Body Provided");

		}
	}
?>
