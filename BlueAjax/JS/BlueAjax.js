/**
 * Main class of an AjaxRequest
 * @param method {string} - GET or POST
 * @param resource {string} - The url to fetch
 * @param data {object} - The data to send with the request
 * @param successKey {string} - the key in the result JSON array that indicates success
 * @param errorKey {string} - the key in the result JSON array that indicates error
 * @constructor
 */
function AjaxRequest(method, resource, data, successKey, errorKey) {

    if (typeof method === 'undefined') throw Error("Method Undefined");
    if (typeof resource === 'undefined') throw Error("Resource Undefined");
    this.data = typeof data !== 'undefined' ?  data : {};
    this.successKey = typeof successKey !== 'undefined' ?  successKey : "success";
    this.errorKey = typeof errorKey !== 'undefined' ?  errorKey : "error";
    this.resource = resource;

    this.onErrorFunction = function (data) {console.log("ERROR");};

    this.onSuccessFunction = function (data) {console.log("SUCCESS");};

    this.onBadJSONFunction = function (data) {console.log("BAD JSON");};

    this.onFailFunction = function () {
        console.log("Resource " + this.url + " failed to load")
    };

    /**
     * Set the onError handler to be called if the errorKey is set in the result JSON
     * @param f {function} - The function to be called
     * @returns {AjaxRequest}
     */
    this.onError = function (f) {
        this.onErrorFunction = f;
        return this;
    };

    /**
     * Set the onSuccess handler to be called if the successKey is set in the result JSON
     * @param f {function} - The function to be called
     * @returns {AjaxRequest}
     */
    this.onSuccess = function (f) {
        this.onSuccessFunction = f;
        return this;
    };

    /**
     * Set the onBadJSON handler to be called if the result is not valid JSON
     * @param f {function} - The function to be called
     * @returns {AjaxRequest}
     */
    this.onBadJSON = function (f) {
        this.onBadJSONFunction = f;
        return this;
    };

    /**
     * Set the onFail handler to be called if the AJAX fails to complete the request
     * @param f {function}
     * @returns {AjaxRequest}
     */
    this.onFail = function (f) {
        this.onFailFunction = f;
        return this;
    };

    /**
     * Add data to be sent with the request.
     * Will overwrite existing data
     * @param d {object} the data to set
     * @returns {AjaxRequest}
     */
    this.addData = function (d) {
        for (var attrname in d) { this.data[attrname] = d[attrname]; }
        return this;
    };

    /**
     * Execute the AJAX request
     */
    this.execute = function () {
        var me = this;

        $.ajax({
            url: this.resource,
            data: this.data,
            type: this.type,
            dataType: "text"
        })
        .done (function (data) {
            try{
                data = JSON.parse(data);
            }
            catch (e) {
                me.onBadJSONFunction(data);
            }
            if (data[me.errorKey] || !data[me.successKey]) {
                me.onErrorFunction (data);
            }
            else {
                me.onSuccessFunction (data);
            }
        })
        .fail(me.onFailFunction)

    }

}

/**
 * Returns a function that will construct an AjaxRequest from a set of defaults
 * @param base {string} - The base url for all resources, i.e. api.site.com/ could be with to give api.site.com/endpoint
 * @param defaultData {object} - Default data for the request
 * @param type {string} - Default method ie GET or POST
 * @param onError {function} - Default on error handler
 * @param onSuccess {function} - Default on success handler
 * @param successKey {string}
 * @param errorKey {string}
 * @param log {boolean}
 * @returns {Function}
 */
function AjaxRequestFactoryFactory (base, defaultData, type, onError, onSuccess,  successKey, errorKey, log) {

    var me = {};

    me.type = typeof type !== 'undefined' ?  type : "GET";
    me.defaultData = typeof defaultData !== 'undefined' ?  defaultData : {};
    me.onError = typeof onError !== 'undefined' ?  onError : function (data){console.log("ERROR");};
    me.onSuccess = typeof onSuccess !== 'undefined' ?  onSuccess : function (data){console.log("SUCCESS");};
    me.log = typeof log !== 'undefined' ?  log : false;
    me.successKey = successKey;
    me.errorKey = errorKey;
    me.base = base;

    if (log) {
        console.log("Creating AjaxRequestFactoryMethod with the following settings:");
        console.log("Type: " + me.type);
        console.log("Default Data: " + me.defaultData.toString());
        console.log("Success Key: " + me.successKey);
        console.log("Error Key: " + me.errorKey);
        console.log("Base: " + me.base);
    }

    return function (path, data, type) {
        type = typeof type !== 'undefined' ? type : me.type
        return new AjaxRequest(type, me.base + path, jQuery.extend(true, {}, defaultData),  me.successKey, me.errorKey).onError(me.onError).onSuccess(me.onSuccess).addData(data);
    }

}