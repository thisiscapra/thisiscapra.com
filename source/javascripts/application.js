//= require ScrollMagic
//= require smooth-scroll.min
//= require TweenMax.min
//= require animation.gsap.min
//= require DrawSVGPlugin.min
//= require twitterFetcher_min
//= require instafeed.min
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
              triggerElement: aboutGoat,
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

    // Soundcloud track of the day
    soundcloudWidget = function() {
      var clientID = '7869138d09320422ec7e924d81b0e2a9';
      var $view = document.getElementById('soundcloud');
      var player = new SoundCloudAudio(clientID);
      var prettyTime = function (time) {
        //var newTime = time*1000;
        var hours = Math.floor(time / 3600);
        var mins = '0' + Math.floor((time % 3600) / 60);
        var secs = '0' + Math.floor((time % 60));
        mins = mins.substr(mins.length - 2);
        secs = secs.substr(secs.length - 2);
        if (!isNaN(secs)) {
          if (hours) {
            return hours + ':' + mins + ':' + secs;
            } else {
              return mins + ':' + secs;
            }
        } else {
          return '00:00';
        }
      };
      var render = function (playlist) {
        var track = playlist.tracks[playlist.tracks.length-1];
        console.log(track);
        // track info
        var $info = document.createElement('h3');
        $info.innerText = 'Playing: ' + track.user.username + ' - ' + track.title + ' - ';

        // track timings
        var $timer = document.createElement('span');
        var renderTimer = function () {
          // Convert the milliseconds back to seconds
          var timeSeconds = track.duration/1000;
          $timer.innerText = prettyTime(player.audio.currentTime) + '/' + prettyTime(timeSeconds);
        };
        // rerender timer on every second
        player.on('timeupdate', renderTimer);
        renderTimer();
        $info.appendChild($timer);

        // album cover
        var $img = document.createElement('img');
        artwork = track.artwork_url;
        largeArtwork = artwork.replace('-large', '-t500x500');
        $img.src = largeArtwork;

        // play/pause button
        var $button = document.createElement('button');
        var toggleButton = function () {
          if (player.playing) {
            $button.innerText = 'Play';
            player.pause();
          } else {
            $button.innerText = 'Pause';
            player.play({playlistIndex: playlist.tracks.length-1});
          }
        };
        $button.style.display = 'block';
        $button.innerText = 'PLAY';
        $button.addEventListener('click', toggleButton);

        // clean view
        $view.removeChild($view.firstChild);

        // append elements
        $view.appendChild($info);
        $view.appendChild($img);
        $view.appendChild($button);
      };
      player.resolve('https://soundcloud.com/capra-design/sets/track-of-the-day', render);
    }

    animations();
    stickyHeader();
    contentTabs();
    respNav();
    // Only load the social stuff on the about page
    if(hasClass(body, 'page-about')) {
      //soundcloudWidget();
      instagramFeed();
      twitterWidget();
    }

  };

  document.addEventListener("DOMContentLoaded", function() {
    ready();
  }, false);

  document.addEventListener("page:fetch", function() {
    var wrapper = document.getElementById('wrapper');
    removeClass(wrapper, 'fadeInDown');
    addClass(wrapper, 'fadeOutUp');
  }, false);

  document.addEventListener("page:load", function() {
    var wrapper = document.getElementById('wrapper');
    removeClass(wrapper, 'fadeOutUp');
    addClass(wrapper, 'fadeInDown');
    ready();
  }, false);

  document.addEventListener("page:restore", function() {
    var wrapper = document.getElementById('wrapper');
    removeClass(wrapper, 'fadeOutUp');
    addClass(wrapper, 'fadeInDown');
  }, false);

}