# BlueEMail
A utility that allows simple handling of E-Mails in a project, using sendgrid

## The idea
The idea is that emails can be served using locally hosted twig templates, and then sent using SendGrid's API.

## Documentation
### Installation
Install BlueUtils, using details at https://github.com/BluePost/BlueUtils.

### Usage
```php
$email = new BluePost\EMail('from@example.com', ["to@example.com"], ["cc@example.com"], ["bcc@example.com"], 'Greetings from BlueUtils BlueEMail');
$email->body(__DIR__ . "/mytwigfile.twig");
$email->send();
```
### Formatting your twig
Three example twig files are provided in `/themes/examples/` - one for each theme

In your twig file - the variable `{{basicdetails}}` includes some details of your E-Mail that you may wish to use.
If you don't wish to use one of the three templates provided, you can either place all your html in one twig file (ie a twig file without an "extends"), or you can use `{% extends customtemplate %}`, and pass a `customtemplatepath` - see function documentation below.

### Functions
#### new BluePost\EMail
* `FROM` = `string` A from E-Mail address - will be validated
* `TO` = `simple array` of E-Mail addresses for the to field. A string can also be passed if just one address wanted. Will be validated
* `CC` = `simple array` of E-Mail addresses for the cc (carbon copy) field. A string can also be passed if just one address wanted. Will be validated
* `BCC` = `simple array` of E-Mail addresses for the bcc (blind carbon copy) field. A string can also be passed if just one address wanted. Will be validated
* `SUBJECT` = `string` An E-Mail subject - please don't include scripts as most clients will reject this
* `SENDGRIDAPIKEY ` = `string` An API key for SendGrid - get one here: https://sendgrid.com/docs/Classroom/Send/How_Emails_Are_Sent/api_keys.html
#### body

A complex function used to set the body of the email using a twig template
* `twigfilepath` = `path (string)` A complete path to your twig file (including __DIR__ etc.) - see "formatting your twig" for details of what to write!
* `plaintextalternative` = `string` An optional plain text version of your email for clients that don't support HTML
* `customvariables` = `array` An array that can be accessed in your twig using `{{ customvariables.foo }}` - you might choose to pass the user's name here for example
* `customtemplatepath` = `path (string)` A complete path to a custom twig template you may be using (including __DIR__ etc.) - Three templates are provided by default, or you can add your own custom one by adding `{% extends customtemplate %}` to the top of your file passed as `twigfilepath`

#### addattachment
* `filename` = `string` A filename - like "kitten.png"
* `type` = `string` The MIME type - you can use `mime_content_type($filepath)` if you like
* `content` = `string` The file's contents - use `file_get_contents($filepath)`
#### replyto
* `replytoemail` = `string` A reply to E-Mail address - will be validated

#### delaysend
Delay the sending of the E-Mail, upto 72 hours in advance
* `time` = `int` When the E-Mail should be sent by SendGrid - Number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT) - Upto 72 hours in advance maximum (due to SendGrid Constraints)

#### send
Send the E-Mail - returns `true` if successful
