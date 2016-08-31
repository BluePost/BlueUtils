if (!window.jQuery)
    console.log("This library requires jQuery");

function Filler(patterns) {

    this.patterns = patterns

}

Filler.prototype.fill = function () {

    var elems = $("[data-page-element]");
    var me = this;

    elems.each(function (i,e) {
        Filler.render(e, me.patterns)
    });

};

Filler.prototype.update = function (pattern) {
    this.pattern = pattern;
    this.fill()
};

Filler.render = function (e, patterns) {
    Filler.resolve($(e).attr("data-page-element"), patterns, function (text) {
        $(e).html(text)
    });
};

Filler.resolve = function (path, patterns, callback) {

    path = path.split(".");

    var working = {};

    path.forEach(function (path_e, path_i) {
        if (path_i == 0) {
            if (patterns[path_e] != undefined) {
                working = patterns[path_e];
            }
            else {
                console.log("Element " + path_e + " not found");
                callback("")
            }
        } else {
            if(working[path_e] != undefined)
                working = working[path_e];
            else {
                console.log("Element " + path_e + " not found");
                callback("")
            }
        }
        if (path_i == path.length-1) {
            callback(working)
        }
    })

};