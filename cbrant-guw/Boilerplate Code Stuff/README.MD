# Boilerplate Code

Boilerplate HTML, CSS, JS and PHP for common styles, layouts and functionality. More functionality
for specific platforms and applications may be added down the line (eg: wordpress-basic.php, 
pdf-basic.php, lazy-loading.js, etc.) as new, recurring situations demand.



## HTML Boilerplate Page Layout and Functionality

* W3C Validator error-free
* Proper doctype
* Meta tags for charset, viewport, and edge compatibility
* Title, description, author and keywords meta tags
* Meta tags for og properties
* Stylesheets for boilerplate CSS file and Font Awesome
* Icons for apple and favicon
* Common CSS Grid page layouts
* HTML5 Shiv functionality for IE and aging browsers
* JavaScript includes for boilerplate JS file and jQuery



## CSS Boilerplate Basic Styling

* Responsive calculated font-sizes set for body (use rem)
* CSS styles for each of the common page layouts in HTML boilerplate
* HTML5 elements given block styling for older browsers
* Basic, modern stylings for tables, lists, paragraphs, headings, etc.
* Inputs get stylings for required or invalid fields
* Inputs get stylings to look more similar across browsers
* Basic, modern button stylings for various colors
* Custom font colors for classes with color names in them
* Basic, modern stylings for forms
* Short-cut classes for styling left aligned, right aligned and center aligned elements
* Short-cut classes for inline, inline-block, and block elements
* Media query for small resolutions (phones, less than 800px)
* Media query for large resolutions (monitors, TV's, over 1600px)



## JavaScript Boilerplate Functions

* Fully documented and commented with JSDoc, works with VS Code IntelliSense
* Common functions: goToTop(), stripHtml(), toggleElementDisplay()
* Shortens console.log to log
* Checker functions: isNumber(), isString(), isNull()
* Function to check performance time of other functions: checkPerformance()
* Functions for form data manipulation: urlifyForm(), jsonifyForm()
* Functions for easy AJAX calls: makeRequest(), assignPromiseTo(), hijackForm(), hijackInput()



## PHP Boilerplate Functions and Enums

* Fully documented and commented with PHPDoc, works with VS Code IntelliSense
* Enums for common items like RESPONSE_FORMAT, URI_ENCODING, DATE_PART, and DATE_FORMAT
* Function to turn debugging on and off
* Functions for null, true and false: is_nullsy(), isnt_nullsy(), is_falsey(), is_truesy()
* Functions for variable type: is_number(), force_number(), force_string()
* Singular function for formatting all kinds of variables: format()
* Functions for url strings: url_encode(), url_decode(), urlify()
* Functions for data type manipulation: objectify(), jsonify()
* Common date functions: now(), now_adjust(), today(), today_adjust(), yesterday(), tomorrow()
* Functions for creating and manipulating date / times: date_create_from_string(), date_set(), date_adjust()
* Functions for AJAX calls: make_request(), send_response()
* Functions for prepared SQL querying: sql_connect(), sql_query(), sql_command(), sql_close()
