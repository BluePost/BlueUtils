<?php
	//BLue Email library by James Bithell
	/*	SETUP
	 		requires composer stuff:
				"sendgrid/sendgrid": "~5.1"
				"twig/twig" : "~1.26"
	*/
	//TODO Require twig and "sendgrid/sendgrid": "~5.1"
	date_default_timezone_set('UTC');
	namespace BluePost;
	class Email {
		$basicdetails = [];
		$body = [];
		private $sendgrid;
		private $sendgridemail;
		private $twig; //TODO Set this up - it's currently not initalized!
		public function __construct ( $FROM = '', array $TO = [], array $CC = [], array $BCC = [], $SUBJECT = '', $SENDGRIDAPIKEY = '') {
			$this->sendgrid = new SendGrid\SendGrid($SENDGRIDAPIKEY); //Set APIKEY
			$this->sendgridemail = new SendGrid\Email(); //Initialize email

			//Set the from address
			$this->basicdetails['from'] = filter_var($FROM, FILTER_SANITIZE_EMAIL);
			$this->sendgridemail->setFrom($this->basicdetails['from']);

			//Set the subject
			$this->basicdetails['subject'] = $SUBJECT;
			$this->sendgridemail->setSubject($this->basicdetails['subject']);

			//Setup TO Messages
			if (is_array($TO)) {
				$this->basicdetails['to'] = [];
				foreach ($TO as $emails) {
					$this->basicdetails['to'][] = filter_var($emails, FILTER_SANITIZE_EMAIL);
					$this->sendgridemail->addTo(filter_var($emails, FILTER_SANITIZE_EMAIL));
				}
			} else {
				$this->sendgridemail->addTo(filter_var($TO, FILTER_SANITIZE_EMAIL));
				$this->basicdetails['to'] = [filter_var($TO, FILTER_SANITIZE_EMAIL)];
			}
			//Setup CC Messages
			if (is_array($CC)) {
				$this->basicdetails['cc'] = [];
				foreach ($CC as $emails) {
					$this->basicdetails['cc'][] = filter_var($emails, FILTER_SANITIZE_EMAIL);
					$this->sendgridemail->addCcfilter_var($emails, FILTER_SANITIZE_EMAIL));
				}
			} else {
				$this->sendgridemail->addCc(filter_var($CC, FILTER_SANITIZE_EMAIL));
				$this->basicdetails['cc'] = [filter_var($CC, FILTER_SANITIZE_EMAIL)];
			}
			//Setup BCC Messages
			if (is_array($BCC)) {
				$this->basicdetails['bcc'] = [];
				foreach ($BCC as $emails) {
					$this->basicdetails['bcc'][] = filter_var($emails, FILTER_SANITIZE_EMAIL);
					$this->sendgridemail->addBcc(filter_var($emails, FILTER_SANITIZE_EMAIL));
				}
			} else {
				$this->basicdetails['bcc'] = [filter_var($BCC, FILTER_SANITIZE_EMAIL)];
				$this->sendgridemail->addBcc(filter_var($CC, FILTER_SANITIZE_EMAIL));
			}
			return true;
		}
		function body($twigfilepath, $plaintextalternative = false, $customvariables = [], $customtemplatepath = '') {
			$html = $this->twig->display($twigfilepath, array(
															'billing' => $this->twig->loadTemplate(__DIR__ . '/themes/billing.twig'), //Load the billing template in case it's based off that
															'alert' => $this->twig->loadTemplate(__DIR__ . '/themes/alert.twig'), //Load the alert template in case it's based off that
															'action' => $this->twig->loadTemplate(__DIR__ . '/themes/action.twig'), //Load the action template in case it's based off that
															'customtemplate' => $this->twig->loadTemplate($customtemplatepath), //Load the custom template if that's being used
															'customvariables' => $customvariables,
															'basicdetails' => $this->basicdetails //Pass the basic variables that you can use in twig if you like
														));
			if ($plaintextalternative) {
				$plaincontent = new SendGrid\Content("text/plain", $plaintextalternative);
	    		$this->sendgridemail->addContent($plaincontent);
				$this->body['plaintext'] = $plaincontent;
			}
    		$htmlcontent = new SendGrid\Content("text/html", $html);
    		$this->sendgridemail->addContent($htmlcontent);
			$this->body['html'] = $htmlcontent;
		}
		function addattachment() {
			return false; //Yeah not ready for this yet
			$attachment = new SendGrid\Attachment();
			$attachment->setContent("TG9yZW0gaXBzdW0gZG9sb3Igc2l0IGFtZXQsIGNvbnNlY3RldHVyIGFkaXBpc2NpbmcgZWxpdC4gQ3JhcyBwdW12");
			$attachment->setType("application/pdf");
			$attachment->setFilename("balance_001.pdf");
			$attachment->setDisposition("attachment");
			$attachment->setContentId("Balance Sheet");
			$this->sendgridemail->addAttachment($attachment);
		}
		function googleanylitics() {
			return false; //Not yet a feature
			$ganalytics = new SendGrid\Ganalytics();
			$ganalytics->setEnable(true);
			$ganalytics->setCampaignSource("some source");
			$ganalytics->setCampaignTerm("some term");
			$ganalytics->setCampaignContent("some content");
			$ganalytics->setCampaignName("some name");
			$ganalytics->setCampaignMedium("some medium");
			$tracking_settings->setGanalytics($ganalytics);
			$this->sendgridemail->setTrackingSettings($tracking_settings);
		}
		function replyto($replytoemail) {
			$reply_to = new SendGrid\ReplyTo(filter_var($replytoemail, FILTER_SANITIZE_EMAIL));
			$this->sendgridemail->setReplyTo($reply_to);
		}
		function delaysend($time) {
			if (((string) (int) $timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX) && $time > time() && (time()+259200) > $time) { //Check valid timestamp
				$personalization = new SendGrid\Personalization();
				$personalization->setSendAt();
				$this->sendgridemail->addPersonalization($personalization);
			} else return false;
		}
		function send() {
			if (!isset($this->body['html'])) throw new Exception("No Body Provided");
			$this->sendgridemail->addHeader("X-BlueEMail", "1");
			//Send the damn thing
			if ($this->sendgrid->send($this->sendgridemail)) return true;
			else return false;
		}
	}
?>
