
jQuery(function($) {
	$.cookieCuttr();

	if ($.cookie('cc_cookie_accept') === "cc_cookie_accept"
		&& window.Netcreators.CookieBar.analyticsAccount) {

		window._gaq = window._gaq || [];
		window._gaq.push(['_setAccount', window.Netcreators.CookieBar.analyticsAccount]);
		window._gaq.push(['_trackPageview']);

		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' === document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	}
});