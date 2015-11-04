//= require "smooth-scroll.min.js"
//= require "TweenMax.min.js"
//= require "ScrollMagic.min.js"
//= require "debug.addIndicators.min.js"
//= require "animation.gsap.min.js"
//= require "simplbox.min.js"
//= require "charming.min.js"

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
      featuredProjects = document.querySelector(".featured-projects"),
      sectionHeader = document.querySelector('section header'),
      contentBlock = document.querySelectorAll('.anim-block'),
      workItem = document.querySelectorAll('.client h2'),
      triggerAmount = 0.5,
      contentBlockOffset = -100,
      windowWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

  addFeaturedClass();

  // Fixed header on scroll
  document.addEventListener('scroll', function() {
    console.log('scrolling')
    var scrollTop = (document.documentElement.scrollTop||document.body.scrollTop),
        headerHeight = document.querySelector('.site-header').offsetHeight;
    if(scrollTop >= 5) {
      addClass(body,'scrolling')
    } else {
      removeClass(body,'scrolling')
    }
  });

  // Lettering effects - mainly for letter spacing
  var element = document.querySelector('.design');
  charming(element, {tagName: 'b'});

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

  var triggerValue = function() {
    if(hasClass(body,'work_index')) {
      if(windowWidth < 450) {
        triggerAmount = 0.3;
        contentBlockOffset = -50;
      }
    }
  }

  triggerValue();

  window.addEventListener('resize', function(){
    triggerValue();
  }, true);

  // Set up Scroll Magic
  var controller = new ScrollMagic.Controller({
    globalSceneOptions: {
      triggerHook: triggerAmount
    }
  });
  
  // Animate the intros to each page
  // if(sectionHeader) {
  //   var it = new TimelineMax(),
  //       animContent = '.anim-content';
  //   it
  //   .set(animContent, { force3D: true, transformOrigin: "center center" })
  //   .from(animContent, 1, { opacity: 1, scale: 1 }).to(animContent, 1, { opacity: 0, scale: 1.2 });
  //   //var animBlock = document.querySelectorAll(".anim-content");
  //   //var tween = TweenMax.to(animBlock, 1, { opacity: 0, transform: 'scale(1.5)' });
  //   var homeScene = new ScrollMagic.Scene({
  //     triggerElement: sectionHeader,
  //     offset: -50, 
  //     duration: 1500
  //   })
  //   .setTween(it)
  //   //.addIndicators({name: "tween css class"})
  //   .addTo(controller);
  // }

  // Animate featured projects
  // if(featuredProjects) {
  //   var ft = new TimelineMax();
  //   ft
  //   .set('.ft-anim', {opacity: 0, force3D: true})
  //   .set('.ft-anim-content', {opacity: 0, top: 200, force3D: true})
  //   .to('.ft-anim', 1, { opacity: 1 })
  //   .to('.ft-anim-content', 1, { opacity: 1, top: 0 });
  //   var featuredScene = new ScrollMagic.Scene({
  //     triggerElement: featuredProjects, 
  //     duration: 500, 
  //     offset: -200
  //   })
  //   .setTween(ft)
  //   //.addIndicators({name: "tween css class"}) // add indicators (requires plugin)
  //   .addTo(controller);
  // }

  // Animate text content blocks
  // if(contentBlock) {
  //   var i;
  //   for (i = 0; i < contentBlock.length; i++) {
  //     var parentHolder = contentBlock[i].parentNode, 
  //         cb = new TimelineMax();
  //     cb
  //     .set(contentBlock[i], { opacity: 0, top: 400, force3D: true })
  //     .to(contentBlock[i], 1, { opacity: 1, top: 0 });
  //     var contentBlockScene = new ScrollMagic.Scene({
  //       triggerElement: parentHolder,
  //       duration: 500,
  //       offset: contentBlockOffset
  //     })
  //     .setTween(cb)
  //     .addTo(controller);
  //   }
  // }

  // Animate work list
  // if(workItem) {
  //   var i;
  //   for (i = 0; i < workItem.length; i++) {
  //     var parentHolder = workItem[i].parentNode, 
  //         wi = new TimelineMax();
  //     wi
  //     .set(workItem, { opacity: 0, top: '60%', force3D: true })
  //     .to(workItem[i], 1, { opacity: 1, top: '43%' });
  //     var workItemScene = new ScrollMagic.Scene({
  //       triggerElement: parentHolder,
  //       duration: 300, 
  //       offset: 0
  //     })
  //     .setTween(wi)
  //     //.addIndicators({name: "tween css class"}) // add indicators (requires plugin)
  //     .addTo(controller);
  //   }
  // }

}

// IE8 only

// if (document.all && document.querySelector && !document.addEventListener) {
//   var body = document.querySelector('body');
//   addBodyClass();
//   addClass(body, 'ie8');
// }