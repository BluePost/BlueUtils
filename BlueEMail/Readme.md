# BlueEMail
A utility that allows simple handling of E-Mails in a project, using sendgrid

## The idea
The idea is that emails can be served using locally hosted twig templates, and then sent using SendGrid's API.

## Documentation
### Installation
Install BlueUtils, using details at https://github.com/BluePost/BlueUtils. This requires BlueUtilsSettings and uses the SENDGRID_API_KEY, PATH_TO_VENDOR and PROJECT_ROOT_DIR members.

### Usage
```php
namespace BluePost;

require_once ( __DIR__ . "/../helpers/header.php" );

$email = new Email(
  new Person("Freddie", "freddie@foo.com"),
  new Person("James", "james@bar.com"),
  [], [],
  "This is sent using BlueEmail"
);

$email->setBody("api/email.twig");

$email->send();

```
### Formatting your twig
Three example twig files are provided in `/themes/examples/` - one for each theme

In your twig file - the variable `{{basicDetails}}` includes some details of your E-Mail that you may wish to use.
If you don't wish to use one of the three templates provided, you can either place all your html in one twig file (ie a twig file without an "extends"), or you can use `{% extends customtemplate %}`, and pass a `customtemplatepath` - see function documentation below.

### Functions
#### new BluePost\EMail
* `FROM` = `BluePost\Person` A from person, representing the name and email
* `TO` = `BluePost\Person or array of BluePost\Person` of E-Mail addresses for the to field. A string can also be passed if just one address wanted. Will be validated
* `CC` = `BluePost\Person or array of BluePost\Person` of E-Mail addresses for the cc (carbon copy) field. A string can also be passed if just one address wanted. Will be validated
* `BCC` = `BluePost\Person or array of BluePost\Person` of E-Mail addresses for the bcc (blind carbon copy) field. A string can also be passed if just one address wanted. Will be validated
* `SUBJECT` = `string` An E-Mail subject - please don't include scripts as most clients will reject this
* `TIMESTAMP` = `int` When the E-Mail should be sent by SendGrid - Number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT) - Upto 72 hours in advance maximum (due to SendGrid Constraints)
#### setBody
This sets the body of the email to a rendered twig template and an option plainTextAlternative
* `templatePath` = `path (string)` A path to your twig file to render, this should be relative to your `BlueUtilsSettings::PROJECT_ROOT_DIR` path
* `plainTextAlternative` = `string` An optional plain text version of your email for clients that don't support HTML
* `customVariables` = `array` An array that can be accessed in your twig using `{{ customvariables.foo }}`
* `customtemplatepath` = `path (string)` A complete path to a custom twig template you may be using relative to `PROJECT_ROOT_DIR` - Three templates are provided by default, or you can add your own custom one by adding `{% extends customtemplate %}` to the top of your file passed as `templatePath`

#### addAttachment - Not implemented
* `filename` = `string` A filename - like "kitten.png"
* `content` = `string` The file's contents - use `file_get_contents($filepath)`
* `type` = `string` The MIME type - you can use `mime_content_type($filepath)` if you like

#### replyTo - Not implemented
* `replytoemail` = `string` A reply to E-Mail address - will be validated


#### send
Send the E-Mail - returns the response object
