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

if ( 'querySelector' in document && 'addEventListener' in window ) {

  var body = document.querySelector('body');

  // Adding random classes to the homepage
  if(hasClass(body, 'page-index') ) {
    var bodyClasses = ['alfred-app','trickets','sports-digest-8-50','alton-convent'];
    var randomClass = Math.floor(Math.random()*bodyClasses.length);
    addClass(body, bodyClasses[randomClass]);
  } 

  // Fading background colours on the work page
  var clients = document.getElementsByClassName("client");
  [].forEach.call(clients, function(client){
    client.addEventListener("mouseover", clientHover, false);
    client.addEventListener("mouseout", clientHoverOut, false);
    var clientID = client.getAttribute("id");
  });
  function clientHover(evt) {
    addClass(body, this.getAttribute("id"));
  }
  function clientHoverOut(evt) {
    removeClass(body, this.getAttribute("id"));
  }

}