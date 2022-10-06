var win = jQuery(window).height();
var doc = jQuery(document).height();
var footer = jQuery('#colophon');
var content = jQuery('.site-content-contain');
var fh = footer.height();
var ch = jQuery('#content').height() + jQuery('#masthead').height();
if (win === doc){
    let diff = (win - (fh + ch));
    footer.css('top', diff);
    content.css('height', (win - fh));
}