function Loadr () {}

//TODO: Store the downloaded as a param on the DOM element
/**
 * Loads all <include> tags in the document using the source attribute to find what to download
 * @param {function} [onComplete] Callback to be called when EVERY template has downloaded
 * @param {boolean} [force=false] If true the function will overwrite already downloaded templates (defau
 * @param {boolean} [clearCache=true] Will clear the Loadr cache before downloading
 */
Loadr.load = function (onComplete, force, clearCache) {
    //Check if the onComplete function is a valid callback
    if (typeof onComplete != "function") {
        onComplete = null;
    }
    if (clearCache === "undefined") clearCache = true;

    //Select all of the includes with a source attribute
    var includes = $("include[source]");
    console.log(includes);

    if (includes.length == 0) onComplete()

    //Array of booleans that are true if the template is downloaded
    var includesProcessed = [];

    //Build the includesProcessed array and store the index in the element
    includes.map(function (e, i) {
        includesProcessed[i] = false;
        e.includeID = i;
    });

    if (clearCache) Loadr.clearCache()

    //Loop through all <include> tags
    includes.each(function (index) {
        //Don't load downloded unless force = true and it has a source attribute
        if($(this).html() != "" && !force) return;

        var elem = this;
        //Now ajax load the source
        $.get(
            $(this).attr("source"),
            function (data) {
                //Set the element's html to the downloded source
                $(elem).html(data);
                //Evaluate the onDownload element of the include
                eval($(elem).attr("onDownload"));

                includesProcessed[elem.includeID] = true;
                if (onComplete != null && includesProcessed.every(function (e) {return e})) {
                    onComplete()
                }

            }
        ).fail(function () {eval($(elem).attr("onerror"))})
    })
};

/**
 * Renders a template found in the DOM at sourceLocator with the data. Uses the compilation cache
 * @param {string} sourceLocator
 * @param {Object} data
 * @returns {string}
 */
Loadr.renderTemplate = function(sourceLocator, data) {
    source = $(sourceLocator).html();
    if (Loadr.compileCache[sourceLocator] != undefined) return Loadr.compileCache[sourceLocator](data);
    else {

        if (source == "") return "";

        compiled = Handlebars.compile(source);
        Loadr.compileCache[sourceLocator] = compiled;
        return compiled(data)
    }
};

/**
 * Render a template and append the HTML to the target
 * @param {string} sourceLocator
 * @param {string} targetLocator
 * @param {Object} data
 */
Loadr.renderTemplateAppend = function (sourceLocator, targetLocator, data) {
    $(targetLocator).append(
        Loadr.renderTemplate(sourceLocator, data)
    )
};


/**
 * Render a template and set the HTML of the target to it
 * @param {string} sourceLocator
 * @param {string} targetLocator
 * @param {Object} data
 */
Loadr.renderTemplateHTML = function (sourceLocator, targetLocator, data) {
    $(targetLocator).html(
        Loadr.renderTemplate(sourceLocator, data)
    )
};

/**
 * Clear the compilation cache
 */
Loadr.clearCache = function () {
    Loadr.compileCache = {}
};

/**
 * Clear the cache for a specific template
 * @param {string} templateLocator
 */
Loadr.reset = function (templateLocator) {
    Loadr.compileCache[templateLocator] = undefined
};

/**
 * Force recompilation of a template - will be stored in the compileCache
 * @param {string} templateLocator
 */
Loadr.update = function (templateLocator) {
    source = $(templateLocator).html();
    Loadr.compileCache[templateLocator] = Handlebars.compile(source)
};

Loadr.compileCache = {};
