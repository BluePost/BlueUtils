# BlueFiller
A simple library for putting data into an HTML page with javascript

## Goal
Using pure JQuery to keep pages updated with changing data can become a huge mess of: `$("#long-useless-id").html(randomvar.thing)` so BlueFiller intends to be a super simple way of putting data into elements. It simply requires a javascript object with all the data in it. This is then addressed with a path.

## Example

First an element to be filled: `<span data-page-element="object.element"></span>` The `data-page-element` identifies what data will be inserted there. Next an object with the data is required:

```javascript
data = {
    "object" : {
        "element" : "Some text here"
    }
}
```

The element above will then address the element with "Some text here". Finally a `Filler` object is required. This is created with the data object as the constructor parameter. The `fill()` method will then render the data into the page. Putting it together we get:
 
```html

Data goes here: <span data-page-element="object.element"></span>

<script>

data = {
    "object" : {
        "element" : "Some text here"
    }
}

var filler = new Filler(data)
filler.fill()

</script>

```

## Installation
Simply download `src/lib.js` and include it with an external script link. This library requires jQuery. It is also avaliable with `BlueUtils/Setup` where the script file will be found in `static/scripts/BlueFiller.js`.