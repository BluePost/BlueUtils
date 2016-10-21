<?php
	//BLue Email library by James Bithell
	/*	SETUP
	 		requires composer stuff:
				"sendgrid/sendgrid": "~5.1"
				"twig/twig" : "~1.26"
		USAGE
			Initalise it with (FROM, TO*, CC*, BCC*, SUBJECT)
				Everthing except the stuff with a * is an string - * stuff is a simple array of strings.
	*/
	//TODO Require twig and "sendgrid/sendgrid": "~5.1"
	namespace BluePost;
	class Email {
		$basicdetails = [];
		$body = [];
		public function __construct ( $FROM = '', array $TO = [], array $CC = [], array $BCC = [], $SUBJECT = '') {
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
		function body($html) {
			//THIS FUNCTION WILL TAKE A TWIG FILE - BUT FOR NOW (AS IT'S NOT INCLUDED) JUST PASS SOME RAW html
			$this->body['html'] = $html;
		}
		function addattachment() {
			//Yeah not ready for this yet
		}
		function send() {
			if (!isset($this->body['html'])) throw new Exception("No Body Provided");

			$sendgrid = new SendGrid("SENDGRID_APIKEY"); //Set APIKEY
			$email    = new SendGrid\Email(); //Initialize email

			//Set Basic Details
			foreach ($this->basicdetails['to'] as $email) {
				 $email->addTo($email);
			}
			foreach ($this->basicdetails['cc'] as $email) {
				 $email->addCc($email);
			}
			foreach ($this->basicdetails['bcc'] as $email) {
				 $email->addBcc($email);
			}
			$email->setFrom($this->basicdetails['from']);
			$email->setSubject($this->basicdetails['subject']);

			//Set body
			$email->setHtml($this->body['html']);


			//Send the damn thing
			if ($sendgrid->send($email)) return true;
			else return false;
		}
	}
?>
