# Loadr
A simple tool for loading external Handlebars.js templates at runtime
## Use
### Installation
Download the latest version of `loader.js` from the lib directory. Optionally also include the `loader.css` file, which sets all `<include>` tags and `<div class="dispNone">` to `display:none;`
### Downloading a template
The function `Loadr.load` will download all of the `<include>` tags. It will use the source attribute and place the contents of the document inside the tag. I suggest not using onReady becuase of things
that may take a long time and instead just having a call to `Loadr.load()` at the bottom of the file.

```js
Loadr.load()
```

### Example
To download a template at page load, set it up like this:

```html
<html>
  <head>
    <title>Loadr Tests</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>
    <link rel="stylesheet" href="/path/to/loader.css"/>
    <script src="/path/to/loader.js"></script>
    <script src="/path/to/handlebars.js"></script>
    <include source="test-template.bars.html" onDownload="testLoadr()" id="test"></include>
  </head>
  <body>
    <h1>Hello</h1>
    <div id="content"></div>
    <script>
	//Function caled when the template is downloaded
	function testLoadr() {
		renderName( "Freddie" )
		renderName( "James" )
	}		
	//Renders the template with a specific name
	function renderName (name) {
		Loadr.renderTemplateAppend("#test", "#content", {name:name})
	}	
	//Download all templates when the page has loaded
	Loadr.load()
    </script>
  </body>
</html>
```
