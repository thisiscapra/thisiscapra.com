//= require smooth-scroll.min.js
//= require ScrollMagic.min.js
//= require TweenMax.min.js
//= require animation.gsap.min.js
//= require DrawSVGPlugin.js
//= require twitterFetcher_min.js
//= require instafeed.min.js
//= require turbolinks

if ( 'querySelector' in document && 'addEventListener' in window ) {

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

  var ready;
  ready = function() {

    var body = document.querySelector('body'),
        html = document.querySelector('html'),
        respMenu = document.querySelector('.resp-nav'),
        windowWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0),
        triggerAmount = 0.5;

    if(hasClass(html, 'resp')) {
      removeClass(html, 'resp');
    }

    animations = function() {
      // Set up Scroll Magic
      var controller = new ScrollMagic.Controller({
        globalSceneOptions: {
          triggerHook: triggerAmount
        }
      });
      // Homepage goat animation
      var homeGoat = document.getElementById('home-goat');
      if(homeGoat) {
        var hg = new TimelineMax({ paused: true }),
            hgSubLayerPaths = document.querySelectorAll("#home-goat-sub-lines path"),
            hgTopLayerPaths = document.querySelectorAll("#home-goat-top-lines path")
        if(hgSubLayerPaths.length > 0 || hgTopLayerPaths.length > 0) {
          hg
            .set([hgSubLayerPaths,hgTopLayerPaths], { drawSVG: 0, visibility:"visible", force3D: true })
            .to(hgSubLayerPaths,1,{ drawSVG: "100%", delay:.1 })
            .to(hgTopLayerPaths,1.5,{ drawSVG: "100%", delay:.2 })
        }
        hg.restart();
      }
      // About page alfalfa animation
      var alfalfa = document.getElementById('alfalfa');
      if(alfalfa) {
        var al = new TimelineMax(),
            alSubLayerPaths = document.querySelectorAll("#alfalfa-sub-lines path"),
            alTopLayerPaths = document.querySelectorAll("#alfalfa-top-lines path");
        if(alSubLayerPaths.length > 0 || alTopLayerPaths.length > 0) {
          al
            .set([alSubLayerPaths,alTopLayerPaths], { drawSVG: 0, visibility:"visible", force3D: true })
            .to(alSubLayerPaths,2,{ drawSVG: "100%", delay:.5 })
            .to(alTopLayerPaths,3,{ drawSVG: "100%", delay:.3 })
            var alfalfaScene = new ScrollMagic.Scene({
              triggerElement: alfalfa, 
              duration: 500, 
              offset: 0
            })
            .setTween(al)
            .addTo(controller);
        }
      }
      // About page goat animation
      var aboutGoat = document.getElementById('about-goat');
      if(aboutGoat) {
        var ag = new TimelineMax(),
            agSubLayerPaths = document.querySelectorAll("#about-goat-sub-lines path"),
            agTopLayerPaths = document.querySelectorAll("#about-goat-top-lines path");
        if(agSubLayerPaths.length > 0 || agTopLayerPaths.length > 0) {
          ag
            .set([agSubLayerPaths,agTopLayerPaths], { drawSVG: 0, visibility:"visible", force3D: true })
            .to(agSubLayerPaths,2,{ drawSVG: "100%", delay:.5 })
            .to(agTopLayerPaths,3,{ drawSVG: "100%", delay:.3 })
            var goatScene = new ScrollMagic.Scene({
              triggerElement: document.getElementsByClassName("why-capra"),
              duration: 500, 
              offset: -200
            })
            .setTween(ag)
            .addTo(controller);
        }
      }
    }

    stickyHeader = function(e) {
      document.addEventListener('scroll', function() {
        var scrollTop = (document.documentElement.scrollTop||document.body.scrollTop),
            header = document.querySelector('.site-header');
            headerHeight = header.offsetHeight;
        if(scrollTop >= 5) {
          addClass(body,'scrolling')
        } else {
          removeClass(body,'scrolling')
        }
      });
    }

    // Tabs on the contact page
    contentTabs = function(e) {
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
    }

    // Responsive navigation class
    respNav = function() {
      respMenu.addEventListener("click", function(e) {
        e.preventDefault();
        if(hasClass(html, 'resp')) {
          this.classList.remove("is-active")
          removeClass(html, 'resp')
        } else {
          this.classList.add("is-active")
          addClass(html, 'resp')
        }
      });
    }

    // Twitter feed
    twitterWidget = function() {
      var twitter = document.getElementById('twitter');
      if(twitter) {
        var twitterConfig = {
          "id": '677082141797310464',
          "domId": 'twitter',
          "maxTweets": 1,
          "enableLinks": true,
          "showUser": false,
          "showTime": true,
          "showImages": true,
          "lang": 'en',
          "showRetweet": false,
          "showInteraction": false
        };
        twitterFetcher.fetch(twitterConfig);
      }
    }

    // Instagram
    instagramFeed = function() {
      var instagram = document.getElementById('instagram');
      if(instagram) {
        var feed = new Instafeed({
          get: 'user',
          userId: '2330146703',
          target: instagram,
          resolution: 'standard_resolution',
          accessToken: '2330146703.a9f0bed.4e841c9b95154d3cb9194229dc406c61',
          limit: 4
        });
        feed.run(); 
      }
    }

    animations();
    stickyHeader();
    contentTabs();
    respNav();
    twitterWidget();
    instagramFeed();

  };

  document.addEventListener("DOMContentLoaded", function() {
    ready();
  }, false);

  document.addEventListener("page:load", function() {
    ready();
  }, false);

}