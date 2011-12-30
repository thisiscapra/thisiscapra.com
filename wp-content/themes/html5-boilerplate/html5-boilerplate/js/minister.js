/*
 * MyFonts Webfont Build ID 305241, 2011-01-11T17:16:10-0500
 * 
 * The fonts listed in this notice are subject to the End User License
 * Agreement(s) entered into by the website owner. All other parties are 
 * explicitly restricted from using the Licensed Webfonts(s).
 * 
 * You may obtain a valid license at the urls below.
 * 
 * Webfont: Minister Book
 * Url: http://new.myfonts.com/fonts/urw/minister/t-book/
 * Foundry: URW++
 * Copyright: (URW)++,Copyright 2006 by (URW)++ Design & Development
 * License: http://www.myfonts.com/viewlicense?1056
 * Licensed pageviews: 10,000/month
 * CSS font-family: Minister-Boo
 * CSS font-weight: normal
 * 
 * (c) 2011 Bitstream, Inc
*/



// change this to false if you're having trouble with WOFFs
var woffEnabled = true;
// uncomment this and set it to the path of your webfont directory
//var customPath = "/themes/fonts"; 

var scripts = document.getElementsByTagName("SCRIPT");
var script = scripts[scripts.length-1].src;

if (!script.match("://") && script.charAt(0) != '/')
	script = "./"+script;

var path = script.replace(/\\/g,'/').replace(/\/[^\/]*\/?$/, '');

// safari 3.1: data-css
// firefox 3.6+: woff
// firefox 3.5+: data-css
// chrome 4+: data-css
// chrome 6+: woff
// IE 5+: eot
// IE 9: woff
// opera 10.1+: data-css
// mobile safari: svg



var browserName, browserVersion, webfontType;

var webfontTypeOverride;

if (/webfont=(woff|ttf|eot)/.test(window.location.search))
{
	webfontTypeOverride = RegExp.$1;

	if (webfontTypeOverride == 'ttf')
		webfontTypeOverride = 'data-css';
}

if (/MSIE (\d+\.\d+)/.test(navigator.userAgent))
{
	browserName = 'MSIE';
	browserVersion = new Number(RegExp.$1);
	if (browserVersion >= 9.0 && woffEnabled)
		webfontType = 'woff';
	else if (browserVersion >= 5.0)
		webfontType = 'eot';
}

else if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))
{
	browserName = 'Firefox';
	browserVersion = new Number(RegExp.$1);
	if (browserVersion >= 3.6 && woffEnabled)
		webfontType = 'woff';
	else if (browserVersion >= 3.5)
		webfontType = 'data-css';
}

else if (/Chrome\/(\d+\.\d+)/.test(navigator.userAgent)) // must check before safari
{
	browserName = 'Chrome';
	browserVersion = new Number(RegExp.$1);

	if (browserVersion >= 6.0 && woffEnabled)
		webfontType = 'woff';

	else if (browserVersion >= 4.0)
		webfontType = 'data-css';
}

else if (/Mozilla.*(iPhone|iPad).* OS (\d+)_(\d+).* AppleWebKit.*Safari/.test(navigator.userAgent))
{
        browserName = 'MobileSafari';
        browserVersion = new Number(RegExp.$2) + (new Number(RegExp.$3) / 10)

	if(browserVersion >= 4.2)
		webfontType = 'data-css';

	else
		webfontType = 'svg';
}

else if (/Mozilla.*(iPhone|iPad).*AppleWebKit.*Safari/.test(navigator.userAgent))
{
	browserName = 'MobileSafari';
	webfontType = 'svg';
}

else if (/Safari\/(\d+\.\d+)/.test(navigator.userAgent))
{
	browserName = 'Safari';
	if (/Version\/(\d+\.\d+)/.test(navigator.userAgent))
	{
		browserVersion = new Number(RegExp.$1);
		if (browserVersion >= 3.1)
			webfontType = 'data-css';
	}
}

else if (/Opera\/(\d+\.\d+)/.test(navigator.userAgent))
{
	browserName = 'Opera';
	if (/Version\/(\d+\.\d+)/.test(navigator.userAgent))
	{
		browserVersion = new Number(RegExp.$1);
		if (browserVersion >= 10.1)
			webfontType = 'data-css';
	}
}

if (!webfontType)
{
	// use flash?
}

if (webfontTypeOverride)
	webfontType = webfontTypeOverride;

switch (webfontType)
{
		case 'eot':
		document.write("<style>\n");
				document.write("@font-face {font-family:\"Minister\";src:url(\"" + path + "/webfonts/eot/style_5033.eot\");}\n");
				document.write("</style>");
		break;
		
		case 'woff':
		document.write("<style>\n");
				document.write("@font-face {font-family:\"Minister\";src:url(\"" + path + "/webfonts/woff/style_5033.woff\") format(\"woff\");}\n");
				document.write("</style>");
		break;
	
		case 'data-css':
		document.write("<link rel='stylesheet' type='text/css' href='" + path + "/webfonts/datacss/MyFonts Webfonts Order M2654719.css'>");
		break;
	
		case 'svg':
		document.write("<style>\n");
				document.write("@font-face {font-family:\"Minister\";src:url(\"" + path + "/webfonts/svg/style_5033.svg#Minister-Boo\") format(\"svg\");}\n");
				document.write("</style>");
		break;
		
	default:
		webfontType = 'default';
		break;
}

//document.write(browserName + ' ' + browserVersion + ': ' + webfontType + ' [' + navigator.userAgent + ']');