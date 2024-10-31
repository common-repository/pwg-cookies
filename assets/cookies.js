( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window );

	var NAME_COOKIE = $("#hd_pwg_cookie_name").val();
	
	//alert(NAME_COOKIE);
	// Initialize Options cookies:
	_window.load( function() {

		var tieneCookies = getCookie(NAME_COOKIE);	
		if (tieneCookies == "") {
			$("#Cookies").fadeIn();
		}
		
	} );
	
	
	$("#btnAcceptCookies").on("click", function(e) {
		e.preventDefault();

		createCookie(NAME_COOKIE, new Date(), 365);
		$("#Cookies").fadeOut();
		
		document.getElementById('form-cookies').submit();
	});

	function createCookie(name, value, days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			var expires = "; expires=" + date.toGMTString();
		} else
			var expires = "";
		document.cookie = name + "=" + value + expires + "; path=/";
	}

	function getCookie(c_name) {
		if (document.cookie.length > 0) {
			c_start = document.cookie.indexOf(c_name + "=");
			if (c_start != -1) {
				c_start = c_start + c_name.length + 1;
				c_end = document.cookie.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = document.cookie.length;
				}
				return unescape(document.cookie.substring(c_start, c_end));
			}
		}
		return "";
	}

//	$('#link_avisocookies').click(function(){		
//		_window.open('/aviso_cookies','popup','location=0,status=0,scrollbars=1,width=450,height=550'); return false;
//	});
	
} )( jQuery );



