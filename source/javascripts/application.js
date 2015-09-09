//= require "smooth-scroll.min.js"
//= require "skrollr.min.js"
//= require "simplbox.min.js"

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
function addFeaturedClass() {
  if(hasClass(body, 'page-index') ) {
    var featuredClasses = ['alfred-app','sports-digest-8-50','alton-convent-school','pantera'];
    var randomClass = Math.floor(Math.random()*featuredClasses.length);
    addClass(featuredProjects, featuredClasses[randomClass]);
  }
}

if ( 'querySelector' in document && 'addEventListener' in window ) {

  var body = document.querySelector('body'),
      html = document.querySelector('html'),
      featuredProjects = document.querySelector(".featured-projects");

  addFeaturedClass();

  document.addEventListener('scroll', function() {
    console.log('scrolling')
    var scrollTop = (document.documentElement.scrollTop||document.body.scrollTop),
        headerHeight = document.querySelector('#header').offsetHeight;
    if(scrollTop >= 5) {
      addClass(body,'scrolling')
    } else {
      removeClass(body,'scrolling')
    }
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
      var anchor = event.target.href.split('#')[1],
        offset = 100;
          //offset = document.querySelector('#header').offsetHeight;
        console.log(offset)
      event.preventDefault();
      smoothScroll.animateScroll( null, '#contact-scroll', {
        "offset": offset,
        "updateURL": false
      });
    }, false);
  });

  // Zoom images on blog posts
  var links = document.querySelectorAll("[data-simplbox]");
  var simplbox = new SimplBox(links);
  //console.log(simplbox)
  simplbox.init();

}

// IE8 only

// if (document.all && document.querySelector && !document.addEventListener) {
//   var body = document.querySelector('body');
//   addBodyClass();
//   addClass(body, 'ie8');
// }