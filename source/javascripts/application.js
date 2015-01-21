//= require "smooth-scroll.min.js"
//= require "skrollr.min.js"

var hasClass = function (elem, className) {
  return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}

var addClass = function (elem, className) {
  if (!hasClass(elem, className)) {
    elem.className += ' ' + className;
  }
}

var removeClass = function (elem, className) {
  var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ') + ' ';
  if (hasClass(elem, className)) {
    while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
      newClass = newClass.replace(' ' + className + ' ', ' ');
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, '');
  }
}

// Adding random classes to the homepage
function addBodyClass() {
  addClass(body, 'js');
  if(hasClass(body, 'page-index') ) {
    var bodyClasses = ['alfred-app','sports-digest-8-50','alton-convent-school'];
    var randomClass = Math.floor(Math.random()*bodyClasses.length);
    addClass(body, bodyClasses[randomClass]);
  }
}

if ( 'querySelector' in document && 'addEventListener' in window ) {

  var body = document.querySelector('body');

  addBodyClass();

  function clientHover(evt) {
    addClass(body, this.getAttribute("id"));
  }
  function clientHoverOut(evt) {
    removeClass(body, this.getAttribute("id"));
  }

  // Fading background colours on the work page
  var clients = document.getElementsByClassName("client");
  [].forEach.call(clients, function(client){
    client.addEventListener("mouseover", clientHover, false);
    client.addEventListener("mouseout", clientHoverOut, false);
  });

  // Tabs on the contact page
  var tab_contents = document.getElementsByClassName("contact-form");
  [].forEach.call(tab_contents, function(tab_content){
    addClass(tab_content, 'hidden');
  });

  var tabs = document.getElementsByClassName("tab");
  [].forEach.call(tabs, function(tab){
    tab.addEventListener("click", function(event){
      var actives = document.querySelectorAll('.active');
      // deactivate existing active tab and panel
      for (var i=0; i < actives.length; i++){
        actives[i].className = actives[i].className.replace('active', '');
      }
      event.target.parentElement.className += 'active';
      document.getElementById(event.target.href.split('#')[1]).className += ' active';
      var anchor = event.target.href.split('#')[1];
      //console.log(anchor);
      event.preventDefault();
      smoothScroll.animateScroll( null, '#'+anchor, {
        "offset": 120,
        "updateURL": false
      });
    }, false);
  });

  // Nice scrolling effects on the homepage
  // if (hasClass(body, "page-index")) {
  //   var top = body.offsetTop;
  //   window.addEventListener('scroll', function() {
  //     var yOffset = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
  //     //console.log(top);
  //     if (yOffset >= 1) {
  //       removeClass(body, 'scroll-up');
  //       addClass(body, 'scroll-down');
  //     } else {
  //       removeClass(body, 'scroll-down');
  //       addClass(body, 'scroll-up');
  //     }
  //   });
  // }
}

// IE8 only

if (document.all && document.querySelector && !document.addEventListener) {
  var body = document.querySelector('body');
  addBodyClass();
  addClass(body, 'ie8');
}