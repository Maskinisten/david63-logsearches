;(function($, document) {
	$(document).ready(function () {
		var cookieName		= phpbbCookieName + '_ca';
    	var cookieAccept	= getCookie(cookieName);

    	if (cookieAccept != true) {
			$(".cookieAcceptBox").addClass(cookieClass);
        	$(".cookieAcceptBox").html(cookieText + '<a href="#">' + acceptText + '</a>');
        	$(".cookieAcceptBox").fadeIn(300);
        	$(".cookieAcceptBox a").click(function ()
			{
            	$(".cookieAcceptBox").fadeOut(300);
            	setCookie(cookieName);
        	})
    	}
	})

	function setCookie(cookieName) {
		var expire		= '';
        var expireDate	= new Date();
		var expireTime	= 60 * 60 *24 * 365 * 1000; // Set default to one year

		if (cookieExpires == false) {
			expireTime = expireTime * 10; // Set to ten years
		}

        expireDate.setTime(expireDate.getTime() + expireTime);
        expire = '; expires=' + expireDate.toGMTString();

    	document.cookie = cookieName + '=1' + expire + '; path=/';
	}

	function getCookie(cookieName) {
    	var name	= cookieName + "=";
    	var chunk	= document.cookie.split(";");

    	for (var i = 0; i < chunk.length; i++) {
        	var element = chunk[i];
        	while (element.charAt(0) == " ") {
        		element = element.substring(1, element.length);
			}
        	if (element.indexOf(name) == 0) {
        		return element.substring(name.length, element.length);
			}
    	}
    	return null
	}

})(jQuery, document);
