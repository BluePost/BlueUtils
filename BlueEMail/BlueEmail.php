<?php
	//BLue Email library by James Bithell
	/*	SETUP
	 		requires composer packages:
				"sendgrid/sendgrid": "~5.1"
				"twig/twig" : "~1.26"
	*/
	//TODO Require twig and "sendgrid/sendgrid": "~5.1"
	namespace BluePost;

	date_default_timezone_set('UTC');

	class Person {

		private $name, $email;

		public function __construct ($name, $email) {
			$this->name = $name;
			$this->email = $email;
		}

		public function asSGEmail () {
			return new \SendGrid\Email($this->name, $this->email);
		}

		public function asArray () {
			return [
				"name" => $this->name,
				"email" => $this->email
			];
		}

	}

	class Email {


		public $basicdetails = [];
		public $body = [];

		private $sendgrid;
		private $sendgridemail;
		private $twig; //TODO Set this up - it's currently not initalized!


		public function __construct ( $FROM, $TO, $CC = [], $BCC = [], $SUBJECT = '') {

			global $BLUEUTILS_SETTINGS;

			require($BLUEUTILS_SETTINGS->PATH_TO_VENDOR."/autoload.php");
			$loader = new \Twig_Loader_Filesystem($BLUEUTILS_SETTINGS->PROJECT_ROOT_DIR);
			$this->twig = new \Twig_Environment($loader, [
				"debug" => true
			]);

			$this->twig->addExtension(new \Twig_Extension_Debug());


			$this->sendgrid = new \SendGrid($BLUEUTILS_SETTINGS->SENDGRID_API_KEY); //Set APIKEY
			$this->sendgridemail = new \SendGrid\Mail(); //Initialize email

			//Set the from address
			if ($FROM instanceof \SendGrid\Email) {
				$FROM = new Person($FROM->getName(), $FROM->getEmail());
			} else if (is_string($FROM)) {
				$FROM = new Person(filter_var($FROM, FILTER_SANITIZE_EMAIL), filter_var($FROM, FILTER_SANITIZE_EMAIL));
			}
			$this->basicdetails['from'] = $FROM->asArray();
			$this->sendgridemail->setFrom($FROM->asSGEmail());

			//Set the subject
			$this->basicdetails['subject'] = $SUBJECT;
			$this->sendgridemail->setSubject($this->basicdetails['subject']);

			$this->mainPersonalization = new \SendGrid\Personalization();

			//Setup TO Messages
			if (!is_array($TO)) {
				$TO = [$TO];
			}
			$this->basicdetails['to'] = [];
			foreach ($TO as $email) {
				if ($email instanceof \SendGrid\Email) {
					$email = new Person($email->getName(), $email->getEmail());
				} else if (is_string($email)) {
					$email = new Person(filter_var($email, FILTER_SANITIZE_EMAIL), filter_var($email, FILTER_SANITIZE_EMAIL));
				}
				$this->basicdetails['to'][] = $email->asArray();
				$this->mainPersonalization->addTo($email->asSGEmail());
			}


			//Setup CC Messages
			if (!is_array($CC)) {
				$CC = [$CC];
			}
			$this->basicdetails['cc'] = [];
			foreach ($CC as $email) {
				if ($email instanceof \SendGrid\Email) {
					$email = new Person($email->getName(), $email->getEmail());
				} else if (is_string($email)) {
					$email = new Person(filter_var($email, FILTER_SANITIZE_EMAIL), filter_var($email, FILTER_SANITIZE_EMAIL));
				}
				$this->basicdetails['bcc'][] = $email->asArray();
				$this->mainPersonalization->addTo($email->asSGEmail());
			}

			//Setup BCC Messages
			if (!is_array($BCC)) {
				$BCC = [$BCC];
			}
			$this->basicdetails['bcc'] = [];
			foreach ($BCC as $email) {
				if ($email instanceof \SendGrid\Email) {
					$email = new Person($email->getName(), $email->getEmail());
				} else if (is_string($email)) {
					$email = new Person(filter_var($email, FILTER_SANITIZE_EMAIL), filter_var($email, FILTER_SANITIZE_EMAIL));
				}
				$this->basicdetails['bcc'][] = $email->asArray();
				$this->mainPersonalization->addTo($email->asSGEmail());
			}

			$this->sendgridemail->addPersonalization($this->mainPersonalization);

			return true;
		}



		function setBody($templatePath, $plainTextAlternative = false, $customVariables = [], $customTemplatePath = false) {

			$template = $this->twig->loadTemplate($templatePath);

			if ($customTemplatePath)
				$customTemplate = loadTemplate($customTemplate);
			else $customTemplate = false;

			$PAGEDATA = Array (
				"billing" => $this->twig->loadTemplate('helpers/BlueEMail/themes/billing.twig'),//Load the billing template in case it's based off that
				"alert" => $this->twig->loadTemplate('helpers/BlueEMail/themes/alert.twig'), //Load the alert template in case it's based off that
				"action" => $this->twig->loadTemplate('helpers/BlueEMail/themes/action.twig'), //Load the action template in case it's based off that
				"customTemplate" => $customTemplate,
				"customVariables" => $customVariables,
				"basicDetails" => $this->basicdetails
			);

			$html = $this->twig->render($templatePath, $PAGEDATA);

			if ($plainTextAlternative) {
				$plaincontent = new \SendGrid\Content("text/plain", $plainTextAlternative);
				$this->sendgridemail->addContent($plaincontent);
				$this->body['plaintext'] = $plaincontent;
			}

  		$htmlcontent = new \SendGrid\Content("text/html", $html);
  		$this->sendgridemail->addContent($htmlcontent);
			$this->body['html'] = $htmlcontent;
		}


		function addAttachment($filename, $type = 'text/plain', $content = '') {
			return false; //Not implemented
			$attachment = new SendGrid\Attachment();
			$attachment->setContent($content);
			$attachment->setType($type); //TODO Validate This
			$attachment->setFilename($filename);
			$attachment->setDisposition("attachment"); //Alternative is inline for a picture - but we'll ignore this for now
			$attachment->setContentId($filename);
			$this->sendgridemail->addAttachment($attachment);
		}

		function googleAnalytics() {
			return false; //Not implemented
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

		function delaySend($time) {
			if (((string) (int) $timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX) && $time > time() && (time()+259200) > $time) { //Check valid timestamp
				$personalization = new SendGrid\Personalization();
				$personalization->setSendAt();
				$this->sendgridemail->addPersonalization($personalization);
			} else return false;
		}

		function send() {
			if (!isset($this->body['html'])) throw new Exception("No Body Provided");
			$this->sendgridemail->addHeader("X-BlueEMail", "1");

			//var_dump($this->sendgridemail->jsonSerialize());
			//Send the damn thing
			return $this->sendgrid->client->mail()->send()->post($this->sendgridemail);
		}

	}
?>
