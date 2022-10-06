/**
 * Simple function to scroll user to top of page.
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
const goToTop = () => window.scrollTo(0, 0);

/**
 * Simple function to remove HTML elements from text.
 * 
 * @param {string} text - The text to remove HTML from
 * @return {string} The original text but with HTML removed
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
const stripHtml = text => (new DOMParser().parseFromString(text, 'text/html')).body.textContent || '';

/**
 * Simple function to toggle an element on or off using the display css style property.
 * 
 * @param {Element} element - The HTML element to toggle on or off
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
const toggleElementDisplay = element => element.style.display = (element.style.display === "none" ? "block" : "none");

/**
 * Shorten the log accessor
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
const log = console.log.bind(document);

/**
 * Simple, accurate number checker.
 * 
 * @param {*} variable - The variable to check for numbers
 * @return {boolean} Whether the variable contains a number
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function isNumber(variable) { return !isNaN(parseFloat(variable)) && isFinite(variable); }

/**
 * Simple, accurate string checker.
 * 
 * @param {*} variable - The variable to check for strings
 * @return {boolean} Whether the variable contains a string
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
const isString = variable => typeof variable === 'string';

/**
 * Simple, accurate null checker.
 * 
 * @param {*} variable - The variable to check for null
 * @return {boolean} Whether the variable contains null
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
const isNull = variable => variable === null || variable === undefined;

/**
 * Function performance checker that returns in milliseconds.
 * 
 * @param {Function} func - The function to check the performance of
 * @return {integer} The number of milliseconds the function took to finish
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function checkPerformance(func) { var start = performance.now(); func(); var end = performance.now(); return start - end; }

/**
 * Turns a form and its values into key=value& pairs to append to a GET url or POST body.
 * 
 * @param {Element} form - The form to urlify
 * @return {string} The form and its current values, urlified, ready to append to a url
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function urlifyForm(form) {
    const formData = new FormData(form);
    var str = "";

    formData.forEach((value, key) => {
        str += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
    });

    str = str.substring(0, str.length-1);

    log("urlifyForm return: " + str);
    return str;
}

/**
 * Turns a form and its values into key=value pairs, sticks them in an object, and 
 * then json stringifies that object.
 * 
 * @param {Element} form - The form to urlify
 * @return {string} The form and its current values, urlified, ready to append to a url
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function jsonifyForm(form) {
    const formData = new FormData(form);
    var object = {};

    formData.forEach((value, key) => {
        object[key] = value;
    });

    log("jsonifyForm return: " + JSON.stringify(object));
    return JSON.stringify(object);
}

/**
 * Creates a new XML HTTP AJAX Request, then sends it off, retrieves the response 
 * asynchronously, and runs a callback function. Returns a promise object which will 
 * contain the value of the callback function once it is done processing.
 * 
 * @param {string} requestType - The type of request to make (eg: "POST", "GET")
 * @param {string} location - The url or endpoint to send the request to
 * @param {string} content - The content of the request (added to the url if "GET," in 
 * the body otherwise)
 * @param {Function} onloadFunction - A function to be run once the response has been 
 * received
 * @param {Array} authorization - if any authorization is required in the header, add 
 * it here as an array (eg ["Authorization", "Key"])
 * @param {string} contentType - The type of content / format of the body (defaults to 
 * "application/x-www-form-urlencoded")
 * @return {Promise} The result of the onload function (by default, the response, 
 * unmodified)
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
async function makeRequest(requestType, location, content, onloadFunction = function(responseText) { return arguments[0]; }, authorization = null, contentType = "application/x-www-form-urlencoded") {
    return await new Promise(function(resolve, reject) {
        const type = requestType.toUpperCase();
        var xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            log("Received response from {" + (type !== "GET" ? location : location + "?" + content) + "} represented by following responseText:\n" + this.responseText);
            log("Request onload function is starting");
            var functionReturn = (onloadFunction !== undefined ? onloadFunction(this.responseText) : null);
            if (isNull(functionReturn)) {
                log("No onload function specified, returning plain response payload");
                functionReturn = this.responseText;
            } else { 
                log("Onload function complete with following returns:\n" + functionReturn);
            }
            resolve(functionReturn);
        }

        xhttp.open(type, (type !== "GET" ? location : location + "?" + content));
        if (contentType.toLowerCase() != "multipart/form-data") xhttp.setRequestHeader("Content-Type", contentType);
        if (authorization != null) xhttp.setRequestHeader(authorization[0], authorization[1]);
        xhttp.send((type != "GET" ? content : ""));
        xhttp.onreadystatechange = ()=>(xhttp.status > 300 ? reject("{" + type + "} request to {" + (type !== "GET" ? location : location + "?" + content) + "} has failed with {" + xhttp.status + " " + xhttp.statusText + "}!") : log("Good Status {" + xhttp.status + "}"));
        log("Sent {" + type + "} request to {" + location + "} with following {" + contentType + "} content:\n" + content);
    });
}

/**
 * Allows you to assign a promised value to an HTML DOM element via it's 
 * innerHTML, src or value properties (depending on what kind of element), 
 * or assign a function to the promise that accepts the promise's value 
 * as an argument and will be run once the promise is finished processing. 
 * If assignTo is not an Element or Function, then the function simply 
 * returns a false value. The function will also return false if promise 
 * is not a Promise.
 * 
 * @param {Promise} promise - The promise to access the value of
 * @param {*} assignTo - The element or function to assign the promise to.
 * If assigning a function to the promise, it must accept exactly one 
 * argument, which will be given the promise's value.
 * @return {boolean} Whether the function completed successfully or not.
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function assignPromiseTo(promise, assignTo) {
    if (!(promise instanceof Promise)) return false;
    if (assignTo instanceof Function) {
        promise.then((value)=>{ assignTo(value); }, (error)=>{ assignTo(error); });
        return true;
    } else if (assignTo instanceof Element) {
        if (assignTo.tagName == "IMG" || assignTo.tagName == "AUDIO" || assignTo.tagName == "VIDEO" || assignTo.tagName == "SOURCE") {
            promise.then((value)=>{ assignTo.src = value; }, (error)=>{ assignTo.alt = error; });
        } else if (assignTo.tagName == "INPUT") {
            promise.then((value)=>{ assignTo.value = value; }, (error)=>{ assignTo.value = error; });
        } else {
            promise.then((value)=>{ assignTo.innerHTML = value; }, (error)=>{ assignTo.innerHTML = error; });
        }
        return true;
    } else {
        return false;
    }
}



/**
 * Hijacks a given form and then sends it's data elsewhere in an AJAX request, preventing 
 * the default behavior of the form. Urlifies the form data and sends it in the same way
 * the form would normally. Once the AJAX call is complete and the Promise is finished
 * processing, the assignPromiseTo function is called on the returned Promise and the 
 * AJAX response is either assigned to the assignTo element, or if a function is passed 
 * to the assignTo agrument, then the function is run on the response. If the response 
 * needs to be formatted AND assigned to an element, the onloadFunction can be used to 
 * style before the value is sent to the assignTo element.
 * 
 * @param {Element} form - The form element to hijack functionality of
 * @param {*} assignTo - The element or function to assign the promise to.
 * If assigning a function to the promise, it must accept exactly one 
 * argument, which will be given the promise's value.
 * @param {Function} onloadFunction - A function to be run once the response has been 
 * received
 * @param {Array} authorization - if any authorization is required in the header, add it 
 * here as an array (eg ["Authorization", "Key"])
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function hijackForm(form, assignTo, onloadFunction = function(responseText) { return arguments[0]; }, authorization = null) {
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        var promise = makeRequest(form.method, form.action, (form.enctype.toLowerCase() == "multipart/form-data" ? new FormData(form) : urlifyForm(form)), onloadFunction, authorization, (form.enctype.toLowerCase() != "text/plain" ? form.enctype : "application/x-www-form-urlencoded"));
        assignPromiseTo(promise, assignTo);
    });
}



/**
 * Hijacks a given input and then sends it's value elsewhere in an AJAX request. The value
 * is sent in application/x-www-form-urlencoded format with the input's name and value
 * being sent in a urlified form (eg: name=value). Once the AJAX call is complete and the 
 * Promise is finished processing, the assignPromiseTo function is called on the 
 * returned Promise and the AJAX response is either assigned to the assignTo element, or 
 * if a function is passed to the assignTo agrument, then the function is run on the 
 * response. If the response needs to be formatted AND assigned to an element, the
 * onloadFunction can be used to style before the value is sent to the assignTo element.
 * 
 * @param {Element} input - The input element to hijack functionality of
 * @param {string} requestType - The type of request to make (eg: "POST", "GET")
 * @param {string} location - The url or endpoint to send the request to
 * @param {*} assignTo - The element or function to assign the promise to.
 * If assigning a function to the promise, it must accept exactly one 
 * argument, which will be given the promise's value.
 * @param {Function} onloadFunction - A function to be run once the response has been 
 * received
 * @param {Array} authorization - if any authorization is required in the header, add it 
 * here as an array (eg ["Authorization", "Key"])
 * 
 * @author Cody Brant <cbrant@getuwired.com>
 */
function hijackInput(input, requestType, location, assignTo, onloadFunction = function(responseText) { return arguments[0]; }, authorization = null) {
    input.addEventListener("input", function(event) {
        event.preventDefault();
        var promise = makeRequest(requestType, location, input.name + "=" + input.value, onloadFunction, authorization);
        assignPromiseTo(promise, assignTo);
    });
}