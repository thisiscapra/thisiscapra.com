/***
* SimplBox - v1.0.0 - 2014.08.04
* Author: (c) Dendrochronology - @Dendrochronolo - http://genert.laal.ee/
* Available for use under the MIT License.
***/
;(function (window, document, undefined) {
    "use strict";

    var docElem = document.documentElement,
        bodyElem = document.getElementsByTagName("body")[0],

        FALSE = false,
        ATTACHEVENT = "attachEvent",
        ADDEVENTLISTENER = "addEventListener",

        isEventListener = ADDEVENTLISTENER in document;

    function SimplBox(p_Elements, p_Options) {
        var base = this;

        base.m_Elements = p_Elements;
        base.m_UserOptions = p_Options || {};
        base.m_Options = {};

        base.m_CurrentTargetElements = FALSE;
        base.m_CurrentTargetElementsLength = FALSE;
        base.m_CurrentTargetNumber = FALSE;
        base.m_CurrentImageElement = FALSE;
        base.m_InProgress = FALSE;
        base.m_InstalledImageBox = FALSE;
        base.m_Alt = FALSE;
        base.m_AnimateDone = FALSE; // For browsers that do not support hardware acceleration.

        var __construct = function () {
            for (var i in SimplBox.options) {
                if (base.m_UserOptions.hasOwnProperty(i)) {
                    base.m_Options[i] = base.m_UserOptions[i];
                } else {
                    base.m_Options[i] = SimplBox.options[i];
                }
            }
        }();
    }

    SimplBox.prototype = {
        init: function () {
            var base = this;

            // API start
            base.API_AddEvent = base.addEvent;
            base.API_RemoveImageElement = base.removeImageElement;
            // API end

            base.checkBrowser();
            base.addEvents();
        },

        checkBrowser: function () {
            var base = this,
                isTouch = "ontouchstart" in window || window.navigator.msMaxTouchPoints || navigator.maxTouchPoints || FALSE,
                hasPointers = isTouch && (window.navigator.pointerEnabled || window.navigator.msPointerEnabled);

            base.browser = {
                "isHardwareAccelerated": (base.getcss3prop("transition") !== "undefined" ? true : FALSE),
                "isTouch": isTouch,
                "hasPointers": hasPointers
            };
        },

        addEvents: function () {
            var base = this;

            // Add click events on base elements.
            for (var i = 0; i < base.m_Elements.length; i++) {
                (function (i) {
                    base.addEvent(base.m_Elements[i], (base.browser.hasPointers ? "pointerup MSPointerUp" : "click"), function (event) {
                        if (event.preventDefault) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        if (window.event) {
                            window.event.returnValue = FALSE;
                            window.event.cancelBubble = FALSE;
                        }

                        if (base.isFunction(base.m_Options.onStart())) {
                            base.m_Options.onStart(this);
                        }

                        base.m_CurrentTargetElements = base.m_Elements;
                        base.m_CurrentTargetElementsLength = base.m_Elements.length;
                        base.m_CurrentTargetNumber = i;

                        base.openImage(base.m_Elements[base.m_CurrentTargetNumber]);
                    });
                })(i);
            }

            base.addEvent(window, "resize", function () {
                base.calculateImagePositionAndSize(base.m_CurrentWrapperElement, base.m_CurrentImageElement, true);
            });

            // Add keyboard support.
            base.leftAnimationFunction = function () {
                if (base.m_CurrentTargetNumber - 1 < 0) {
                    base.openImage(base.m_CurrentTargetElements[base.m_CurrentTargetElementsLength - 1], "left");
                    base.m_CurrentTargetNumber = base.m_CurrentTargetElementsLength - 1;
                } else {
                    base.openImage(base.m_CurrentTargetElements[base.m_CurrentTargetNumber - 1], "left");
                    base.m_CurrentTargetNumber = base.m_CurrentTargetNumber - 1;
                }
            };

            base.rightAnimationFunction = function () {
                if (base.m_CurrentTargetNumber + 1 > base.m_CurrentTargetElementsLength - 1) {
                    base.openImage(base.m_CurrentTargetElements[0], "right");
                    base.m_CurrentTargetNumber = 0;
                } else {
                    base.openImage(base.m_CurrentTargetElements[base.m_CurrentTargetNumber+1], "right");
                    base.m_CurrentTargetNumber = base.m_CurrentTargetNumber + 1;
                }
            };

            if (base.m_Options.enableKeyboard) {
                var keyBoard = {
                    left: 37,
                    right: 39,
                    esc: 27
                };

                base.addEvent(window, "keydown", function (event) {
                    if (!base.m_CurrentImageElement || base.m_InProgress) {
                        return;
                    }

                    if (event.preventDefault) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    if (window.event) {
                        var event = window.event;
                        window.event.returnValue = FALSE;
                        window.event.cancelBubble = FALSE;
                    }

                    var keyCode = event.which || event.keyCode;

                    switch (keyCode) {
                        case keyBoard.esc: base.removeImageElement(); return FALSE;
                        case keyBoard.right: base.rightAnimationFunction(); return FALSE;
                        case keyBoard.left: base.leftAnimationFunction(); return FALSE;
                    }
                });
            }

            if (base.m_Options.quitOnDocumentClick) {
                base.addEvent(isEventListener ? bodyElem : document, "click", function (event) {
                    if (base.m_InProgress) {
                        return FALSE;
                    }

                    // if (event.preventDefault) {
                    //     event.preventDefault();
                    // }

                    if (window.event) {
                        var event = window.event;
                    }

                    var target = event.target ? event.target : event.srcElement;

                    if (target && target.id !== base.m_Options.imageElementId && base.m_InstalledImageBox && !base.m_InProgress) {
                        base.removeImageElement();
                        return FALSE;
                    }
                });
            }
        },

        openImage: function (p_Source, p_Direction) {
            var base = this,
                documentFragment = document.createDocumentFragment(),
                divElement = document.createElement("div"),
                imageElement = document.createElement("img"),
                imageElementControl = document.getElementById(base.m_Options.imageElementId);

            // If no 1 argument or 1 argument's tagname is not A, return.
            if (!p_Source || p_Source.tagName.toLowerCase() !== "a") {
                return;
            }

            if (imageElementControl) {
                bodyElem.removeChild(imageElementControl);
                base.m_CurrentImageElement = FALSE;
                base.m_InstalledImageBox = FALSE;
            }

            base.m_Alt = p_Source.firstChild.getAttribute("alt");
            base.m_InProgress = true;

            // Check if it funcion and return.
            if (base.isFunction(base.m_Options.onImageLoadStart())) {
                base.m_Options.onImageLoadStart();
            }

            // Set direction
            if (typeof p_Direction !== "undefined") {
                switch (p_Direction) {
                    case "left": p_Direction = -1; break;
                    case "right": p_Direction = 1; break;
                }
            }

            // Set attributes of new image element.
            divElement.setAttribute("id", base.m_Options.imageElementId);
            divElement.setAttribute("style", "position: fixed; cursor: pointer; opacity: 0;");
            imageElement.setAttribute("id", "simplbox-image");
            imageElement.setAttribute("src", p_Source.getAttribute("href"));
            imageElement.setAttribute("alt", base.m_Alt);
            //imageElement.setAttribute("style", "position: fixed; cursor: pointer; opacity: 0;") ;

            // Append to fragment and append fragment to body.
            divElement.appendChild(imageElement);
            documentFragment.appendChild(divElement);
            bodyElem.appendChild(documentFragment);

            // Set current image element.
            base.m_CurrentWrapperElement = document.getElementById(base.m_Options.imageElementId);
            base.m_CurrentImageElement = document.getElementById("simplbox-image");
            base.m_CurrentWrapperElement.style.filter = 'alpha(opacity=0)'; // IE 8 opacity

            if (base.browser.isHardwareAccelerated) {
                if (typeof p_Direction !== "undefined") {
                    base.m_CurrentWrapperElement.style[base.getcss3prop("transform")] = "translateX(" + (p_Direction * base.m_Options.fadeInDistance) + "px)";
                }
                base.m_CurrentWrapperElement.style[base.getcss3prop("transition")] = "all " + base.m_Options.animationSpeed + "ms ease";
            }

            // Calculate image position and size and set them.
            base.calculateImagePositionAndSize(base.m_CurrentWrapperElement, base.m_CurrentImageElement, FALSE, p_Direction);
                    
            // Add event listener.
            if (base.m_Options.quitOnImageClick) {
                base.addEvent(base.m_CurrentWrapperElement, "click", function (event) {
                    if (event.preventDefault) {
                        event.preventDefault();
                    }

                    if (window.event) {
                        window.event.returnValue = FALSE;
                    }

                    base.removeImageElement();
                });
            }

            // Touch events.
            if (base.browser.isTouch) { // This check fixes bug in IE 10 & 11 because these browsers have pointers for odd reason(s).
                var touchXStart = -1,
                    touchXEnd = -1,
                    swipeDifference = 0;
            
                base.addEvent(base.m_CurrentWrapperElement, "touchstart pointerdown MSPointerDown", function (event) {
                    event.preventDefault();
                    touchXStart = event.pageX || event.touches[0].pageX;
                });
            
                base.addEvent(base.m_CurrentWrapperElement, "touchmove pointermove MSPointerMove", function (event) {
                    event.preventDefault();
                    touchXEnd = event.pageX || event.touches[0].pageX;
                    swipeDifference = touchXStart - touchXEnd;
            
                    if (base.browser.isHardwareAccelerated) {
                        base.m_CurrentWrapperElement.style[base.getcss3prop("transition")] = "none";
                        base.m_CurrentWrapperElement.style[base.getcss3prop("transform")] = "translateX(" + -swipeDifference + "px)";
                    }
                });
            
                base.addEvent(base.m_CurrentWrapperElement, "touchend pointerup pointercancel MSPointerUp MSPointerCancel", function (event) {
                    event.preventDefault();
            
                    if (Math.abs(swipeDifference) > 75) {
                        if (swipeDifference < 0) {
                            base.leftAnimationFunction();
                        } else {
                            base.rightAnimationFunction();
                        }
                    } else {
                        base.m_CurrentWrapperElement.style[base.getcss3prop("transform")] = "translateX(0px)";
                    }
                });
            }
        },

        calculateImagePositionAndSize: function (p_Element, p_Image, p_Resize) {
            var base = this,
                temporaryImageObject = new Image(),
                imageWidth = 0,
                imageHeight = 0,
                imageSizeRatio = 0;

            // If no element provided, quit.
            if (!p_Element) {
                return;
            }

            base.m_ImageSource = p_Image.getAttribute("src"); // Get element's source attribute for loading image.
            base.m_ScreenHeight = window.innerHeight || docElem.offsetHeight; // Get window height.
            base.m_ScreenWidth = window.innerWidth || docElem.offsetWidth; // Get window width.

            temporaryImageObject.onload = function () {
                var thisImageWidth = this.width,
                    thisImageHeight = this.height;

                imageWidth = thisImageWidth;
                imageHeight = thisImageHeight;
                imageSizeRatio = imageWidth / imageHeight;

                //Height of image is too big to fit in viewport
                if (Math.floor(base.m_ScreenWidth / imageSizeRatio) > base.m_ScreenHeight) {
                    imageWidth = base.m_ScreenHeight * imageSizeRatio * base.m_Options.imageSize;
                    imageHeight = base.m_ScreenHeight * base.m_Options.imageSize;
                } else { // Width of image is too big to fit in viewport
                    imageWidth = base.m_ScreenWidth * base.m_Options.imageSize;
                    imageHeight = base.m_ScreenWidth / imageSizeRatio * base.m_Options.imageSize;
                }

                if (imageWidth > thisImageWidth) {
                    imageWidth = thisImageWidth;
                }

                if (imageHeight > thisImageHeight) {
                    imageHeight = thisImageHeight;
                }

                // Set style attributes.
                p_Element.style.top = ((base.m_ScreenHeight - imageHeight) / 2) + "px";
                p_Element.style.left = ((base.m_ScreenWidth - imageWidth) / 2) + "px";
                p_Element.style.width = Math.floor(imageWidth) + "px";
                p_Element.style.height = Math.floor(imageHeight) + "px";

                if (!p_Resize) {
                    setTimeout(function () {
                        if (base.browser.isHardwareAccelerated) {
                            p_Element.style.opacity = 1;
                            p_Element.style[base.getcss3prop("transform")] = "translateX(0px)";
                        } else {
                            var toOpacity = 1;

                            base.animate({
                                delay: 16,
                                duration: base.m_Options.animationSpeed,
                                delta: base.linear,
                                step: function (delta) {
                                    p_Element.style.opacity = (toOpacity * delta);
                                    p_Element.style.filter = "alpha(opacity=" + ((toOpacity * delta) * 100 ) + ")"; 
                                }
                            });
                        }

                        base.m_InProgress = FALSE;
                        base.m_InstalledImageBox = true;

                        if (base.isFunction(base.m_Options.onImageLoadEnd())) {
                            base.m_Options.onImageLoadEnd(p_Element);
                        }
                    }, 100);
                }
            };

            // Must be last because otherwise onload function won't be load.
            temporaryImageObject.src = base.m_ImageSource;
        },

        removeImageElement: function () {
            var base = this;

            if (!base.m_CurrentWrapperElement) {
                return;
            }

            if (base.isFunction(base.m_Options.onEnd())) {
                base.m_Options.onEnd();
            }

            if (base.m_InProgress) {
                if (base.isFunction(base.m_Options.onImageLoadEnd())) {
                    base.m_Options.onImageLoadEnd(p_Element);
                }
            }

            if (base.browser.isHardwareAccelerated) {
                base.m_CurrentWrapperElement.style.opacity = 0;
                base.m_CurrentWrapperElement.style.transition = "opacity 250ms ease";
            } else {
                var toOpacity = 0;

                base.animate({
                    delay: 16,
                    duration: 250,
                    delta: base.linear,
                    step: function (delta) {
                        base.m_CurrentWrapperElement.style.opacity = (toOpacity * delta);
                        base.m_CurrentWrapperElement.style.filter = "alpha(opacity=" + ((toOpacity * delta) * 100 ) + ")"; // IE 8
                    }
                });
            }

            setTimeout(function () {
                if (base.m_CurrentWrapperElement) {
                    base.m_CurrentWrapperElement.parentNode.removeChild(base.m_CurrentWrapperElement);
                }

                base.m_CurrentWrapperElement = FALSE;
                base.m_InstalledImageBox = FALSE;
            }, base.browser.isHardwareAccelerated ? 250 : 350); // Duo animate delay, add 100ms.
        },

        isFunction: function (p_Function) {
            return !!(p_Function && p_Function.constructor && p_Function.call && p_Function.apply);
        },

        addEvent: function (p_Element, p_Events, p_Callback) {
            var i, j;
            p_Events = p_Events.split(" ");

            if (isEventListener) {
                if ((p_Element && !(p_Element instanceof Array && !p_Element.length)) && (p_Element.length !== 0) || p_Element === window) {
                    for (i = 0; i < p_Events.length; i++) {
                        p_Element[ADDEVENTLISTENER](p_Events[i], p_Callback, FALSE);
                    }
                } else if (p_Element && p_Element[0] !== "undefined") {
                    for (i = 0; i < p_Element.length; i++) {
                        for (j = 0; j < p_Events.length; j++) {
                            p_Element[i][ADDEVENTLISTENER](p_Events[j], p_Callback, FALSE);
                        }
                    }
                }
            } else {
                for (i = 0; i < p_Events.length; i++) {
                    if (p_Events[i].indexOf("keydown") !== -1) {
                        document[ATTACHEVENT]("on" + p_Events[i], p_Callback);
                    } else {
                        if ((p_Element && !(p_Element instanceof Array && !p_Element.length)) && (p_Element.length !== 0) || p_Element === window) {
                            p_Element[ATTACHEVENT]("on" + p_Events[i], p_Callback);
                        } else if (p_Element && p_Element[0] !== "undefined") {
                            for (j = 0; j < p_Element.length; j++) {
                                p_Element[j][ATTACHEVENT](p_Events[i], p_Callback);
                            }
                        }
                    }
                }
            }
        },

        animate: function (p_Options) {
            var base = this,
                start = new Date();

            var id = setInterval(function () {
                var timePassed = new Date() - start,
                    progress = timePassed / p_Options.duration;

                if (progress > 1) {
                    progress = 1;
                }
    
                var delta = p_Options.delta(progress);
                p_Options.step(delta);
    
                if (progress == 1) {
                    base.m_AnimateDone = true;
                    clearInterval(id);
                }
            }, p_Options.delay || 10);
        },

        linear: function (progress) {
            return progress;
        },

        getcss3prop: function (p_CSSProp) {
            var vendors = ["", "-moz-", "-webkit-", "-o-", "-ms-", "-khtml-"],
                camelCase = function (str) {
                    return str.replace(/\-([a-z])/gi, function (match, p1) {
                        return p1.toUpperCase(); 
                    });
                };

            for (var i = 0; i < vendors.length; i++) {
                var css3propcamel = camelCase(vendors[i] + p_CSSProp)

                if (css3propcamel.substr(0,2) == "Ms") {
                    css3propcamel = "m" + css3propcamel.substr(1); 
                }

                if (css3propcamel in docElem.style) {
                    return css3propcamel;
                }
            }

            return "undefined";
        }
    };

    SimplBox.options = {
        imageElementId: "simplbox", 

        fadeInDistance: 100,
        animationSpeed: 350,
        imageSize: 0.8,

        quitOnImageClick: true,
        quitOnDocumentClick: true,
        enableKeyboard: true,

        onImageLoadStart: function () {},
        onImageLoadEnd: function () {},
        onStart: function () {},
        onEnd: function () {}
    };

    window.SimplBox = SimplBox;
})(window, document); 