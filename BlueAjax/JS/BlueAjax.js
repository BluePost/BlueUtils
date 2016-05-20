//TODO: Generalise GET and POST
function GetAjaxRequest(resource, data, successKey, errorKey) {

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
    }
    
    this.onError = function (f) {
        this.onErrorFunction = f;
        return this;
    }
    
    this.onSuccess = function (f) {
        this.onSuccessFunction = f;
        return this;
    }
    
    this.onBadJSON = function (f) {
        this.onBadJSONFunction = f;
        return this;
    }
    
    this.onFail = function (f) {
        this.onFailFunction = f;
        return this;
    }
    
    this.addData = function (d) {
        for (var attrname in d) { this.data[attrname] = d[attrname]; }
        return this;
    }
    
    this.execute = function () {
        var me = this;
        $.get(this.resource, data)
        .done(function (data) {
            try{
                data = JSON.parse(data);
            }
            catch (e) {
                me.onBadJSONFunction(data);
            }
            if (data[me.errorKey] | !data[me.successKey]) {
                me.onErrorFunction (data);
            }
            else {
                me.onSuccessFunction (data);
            }
        })
        .fail(me.onFailFunction);
    }
    
}   

function PostAjaxRequest(resource, data, successKey, errorKey) {

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
    }
    
    this.onError = function (f) {
        this.onErrorFunction = f;
        return this;
    }
    
    this.onSuccess = function (f) {
        this.onSuccessFunction = f;
        return this;
    }
    
    this.onBadJSON = function (f) {
        this.onBadJSONFunction = f;
        return this;
    }
    
    this.onFail = function (f) {
        this.onFailFunction = f;
        return this;
    }
    
    this.addData = function (d) {
        for (var attrname in d) { this.data[attrname] = d[attrname]; }
        return this;
    }
    
    this.execute = function () {
        var me = this;
        $.post(this.resource, data)
        .done(function (data) {
            try{
                data = JSON.parse(data);
            }
            catch (e) {
                me.onBadJSONFunction(data);
            }
            if (data[me.errorKey] | !data[me.successKey]) {
                me.onErrorFunction (data);
            }
            else {
                me.onSuccessFunction (data);
            }
        })
        .fail(me.onFailFunction);
    }
    
}

//Example usage:
//var AjaxRequest = AjaxRequestFactoryFactory ("http://api.foo.com/")
//AjaxRequest("endpoint/1", {"foo":"bar"}).execute()

function AjaxRequestFactoryFactory (base, defaultData, onError, onSuccess, type, successKey, errorKey) {

    var me = {};

    me.type = typeof type !== 'undefined' ?  type : "GET";
    me.defaultData = typeof defaultData !== 'undefined' ?  defaultData : {};
    me.onError = typeof onError !== 'undefined' ?  onError : function (data){console.log("ERROR");};
    me.onSuccess = typeof onSuccess !== 'undefined' ?  onSuccess : function (data){console.log("SUCCESS");};
    me.successKey = successKey;
    me.errorKey = errorKey;
    me.base = base
    
    if (me.type === "GET") {  
        return function (path, data) {
            return (new GetAjaxRequest(me.base + path, jQuery.extend(true, {}, defaultData), me.successKey, me.errorKey).onError(me.onError).onSuccess(me.onSuccess).addData(data));
        };
    }
    else if (me.type === "POST") {
        return function (path, data) {
            return (new PostAjaxRequest(base + path, defaultData, successKey, errorKey).onError(onError).onSuccess(onSuccess).addData(data));
        }
    }
    else {
        return null;
    }

}