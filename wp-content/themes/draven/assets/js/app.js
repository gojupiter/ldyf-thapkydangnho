(function($) {
    "use strict";

    // Initialize global variable
    var LA = {
        core        : {},
        ui 			: {},
        utils 	    : {},
        component 	: {}
    };
    window.LA = LA;

    $.exists = function($selector) {
        return ($selector.length > 0);
    };

    $.getCachedScript = function( url ) {
        var options = {
            dataType: "script",
            cache: true,
            url: url
        };
        return $.ajax( options );
    };


})(jQuery);

// Initialize Helper

(function($) {
    'use strict';

    var LA = window.LA || {};

    LA.utils = window.LA.utils || {};

    LA.utils.isDebug = true;

    LA.utils.logger = {
        $cache : {},
        display : function( msg ){
            if(!LA.utils.isDebug) return;
            return console.log( msg );
        },
        set : function( msg, group_name, group_title ){
            if(!LA.utils.isDebug) return;

            if(typeof group_name !== "undefined" ){
                if(typeof group_title === "undefined"){
                    group_title = group_name;
                }
            }else{
                group_name = "anonymous";
                group_title = "Anonymous";
            }

            var self = this,
                _o_obj = self.$cache[group_name] || [];

            _o_obj.push([msg, group_title]);

            self.$cache[group_name] = _o_obj;
        },
        get : function( group ){
            if(!LA.utils.isDebug) return;
            var self = this;
            if(typeof group === "undefined"){
                console.group('ALL');
                $.each(self.$cache,function(key, val){
                    $.each(val, function( k, v ){
                        console.group("Com [" +  key + "] : " +  v[1]);
                        console.log(v[0]);
                        console.groupEnd();
                    })
                });
                console.groupEnd();
            }
            else{
                $.each(self.$cache[group], function(k, v){
                    console.group("Com [" +  group + "] : " +  v[1]);
                    console.log(v[0]);
                    console.groupEnd();
                })
            }
        }
    };

    LA.utils.isCookieEnable = function(){
        if (navigator.cookieEnabled) return true;
        document.cookie = "cookietest=1";
        var ret = document.cookie.indexOf("cookietest=") != -1;
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
        return ret;
    };

    LA.utils.browser = (function() {

        var name,version,platform_name, _tmp;

        var ua = navigator.userAgent.toLowerCase(),
            platform = navigator.platform.toLowerCase(),
            UA = ua.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/) || [null, 'unknown', '0'];


        function getInternetExplorerVersion() {
            var rv = -1;
            if (navigator.appName == 'Microsoft Internet Explorer') {
                var ua2 = navigator.userAgent;
                var re2  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
                if (re2.exec(ua2) != null)
                    rv = parseFloat( RegExp.$1 );
            }
            else if (navigator.appName == 'Netscape') {
                var ua2 = navigator.userAgent;
                var re2  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
                if (re2.exec(ua2) != null)
                    rv = parseFloat( RegExp.$1 );
            }
            return rv;
        }

        _tmp = getInternetExplorerVersion();

        if(_tmp != -1){
            name = 'ie';
            version = _tmp;
        }
        else{
            name = (UA[1] == 'version') ? UA[3] : UA[1];
            version = UA[2].substring(0,2);
        }

        platform_name = ua.match(/ip(?:ad|od|hone)/) ? 'ios' : (ua.match(/(?:webos|android)/) || platform.match(/mac|win|linux/) || ['other'])[0];

        // Expose for css
        $('html').addClass(name).addClass(name + ' ' + name + version + ' platform-' + platform_name);

        return {
            name : name,
            version : version,
            platform: platform_name
        };

    })();

    LA.utils.isMobile = function() {

        function android() {
            return navigator.userAgent.match(/Android/i);
        }

        function blackBerry() {
            return navigator.userAgent.match(/BlackBerry/i);
        }

        function iOS() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        }

        function opera() {
            return navigator.userAgent.match(/Opera Mini/i);
        }

        function windows() {
            return navigator.userAgent.match(/IEMobile/i);
        }

        return (android() || blackBerry() || iOS() || opera() || windows());

    };

    LA.utils.isRTL = function(){
        return $(document.body).hasClass('rtl');
    };

    LA.utils.sanitizeSlug = function( text ){
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');
    };

    LA.utils.randomID = function(){
        var text = "",
            char = "abcdefghijklmnopqrstuvwxyz",
            num = "0123456789",
            i;
        for( i = 0; i < 5; i++ ){
            text += char.charAt(Math.floor(Math.random() * char.length));
        }
        for( i = 0; i < 5; i++ ){
            text += num.charAt(Math.floor(Math.random() * num.length));
        }
        return text;
    };

    LA.utils.getAdminbarHeight = function(){
        var $adminBar = $('#wpadminbar');
        return ($.exists($adminBar) && $adminBar.css('position') == 'fixed') ? $adminBar.height() : 0;
    };

    LA.utils.addStyleSheet = function(css){
        var head, styleElement;
        head = document.getElementsByTagName('head')[0];
        styleElement = document.createElement('style');
        styleElement.setAttribute('type', 'text/css');
        if (styleElement.styleSheet) {
            styleElement.styleSheet.cssText = css;
        } else {
            styleElement.appendChild(document.createTextNode(css));
        }
        head.appendChild(styleElement);
        return styleElement;
    };

    LA.utils.decodeURI = function(uri){
        try {
            uri = decodeURI( uri );
        } catch( e ) {
            uri = uri.replace( /%5B/g, '[' ).replace( /%5D/g, ']' ).replace( /%20/g, ' ' );
        }
        return uri;
    };

    LA.utils.getUrlParameter = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    };

    LA.utils.addQueryArg = function( url, key, value ){
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = url.indexOf('?') !== -1 ? "&" : "?";
        if (url.match(re)){
            return url.replace(re, '$1' + key + "=" + value + '$2');
        }
        else{
            return url + separator + key + "=" + value;
        }
    };

    LA.utils.removeURLParameter = function(url, parameter){
        var urlparts= url.split('?');
        if (urlparts.length>=2) {
            var prefix= encodeURIComponent(parameter)+'=';
            var pars= urlparts[1].split(/[&;]/g);
            //reverse iteration as may be destructive
            for (var i= pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }
            url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        }
        else {
            return url;
        }
    };

    LA.utils.reverseString = function( str ){
        return str.split("").reverse().join("");
    };

    function getHtmlScroll() {
        return {
            x: window.pageXOffset || document.documentElement.scrollLeft,
            y: window.pageYOffset || document.documentElement.scrollTop
        };
    }

    function isHtmlBodyTag(element) {
        return (/^(?:body|html)$/i).test(element.tagName);
    }

    function getElementScroll(elem) {
        var element = elem.parentNode,
            position = {x: 0, y: 0};
        while (element && !isHtmlBodyTag(element)) {
            position.x += element.scrollLeft;
            position.y += element.scrollTop;
            element = element.parentNode;
        }
        return position;
    }

    function getStyleToString(element, style){
        return $(element).css(style);
    }

    function getStyleToNumber(element, style) {
        return parseInt(getStyleToString(element, style)) || 0;
    }

    function getTopBorderOfElement(element) {
        return getStyleToNumber(element, 'border-top-width');
    }

    function getTopLeftOfElement(element) {
        return getStyleToNumber(element, 'border-left-width');
    }

    function elementHasBorderBox(element) {
        return getStyleToString(element, '-moz-box-sizing') == 'border-box';
    }

    function getOffset(elem){
        if (elem.getBoundingClientRect && LA.utils.browser.platform != 'ios') {
            var bound = elem.getBoundingClientRect(),
                html = elem.ownerDocument.documentElement,
                htmlScroll = getHtmlScroll(),
                elemScrolls = getElementScroll(elem),
                isFixed = (getStyleToString(elem, 'position') == 'fixed');
            return {
                x: parseInt(bound.left) + elemScrolls.x + ((isFixed) ? 0 : htmlScroll.x) - html.clientLeft,
                y: parseInt(bound.top) + elemScrolls.y + ((isFixed) ? 0 : htmlScroll.y) - html.clientTop
            };
        }
        var element = elem,
            position = {
                x: 0,
                y: 0
            };

        if (isHtmlBodyTag(elem)) return position;

        while (element && !isHtmlBodyTag(element)) {
            position.x += element.offsetLeft;
            position.y += element.offsetTop;
            if (LA.utils.browser.name == 'firefox') {
                if (!elementHasBorderBox(element)) {
                    position.x += getTopLeftOfElement(element);
                    position.y += getTopBorderOfElement(element);
                }
                var parent = element.parentNode;
                if (parent && getStyleToString(parent, 'overflow') != 'visible') {
                    position.x += getTopLeftOfElement(parent);
                    position.y += getTopBorderOfElement(parent);
                }
            } else if (element != elem && LA.utils.browser.name == 'safari') {
                position.x += getTopLeftOfElement(element);
                position.y += getTopBorderOfElement(element);
            }
            element = element.offsetParent;
        }
        if (LA.utils.browser.name == 'firefox' && !elementHasBorderBox(elem)) {
            position.x -= getTopLeftOfElement(elem);
            position.y -= getTopBorderOfElement(elem);
        }
        return position;
    }

    LA.utils.getOffset = function( $element ){
        return $.exists($element) ? getOffset($element.get(0)) : {x:0, y:0};
    };

    LA.utils.localCache = {
        /**
         * timeout for cache in millis
         * @type {number}
         */
        timeout: 600000, // 10 minutes
        /**
         * @type {{_: number, data: {}}}
         **/
        data: {},
        remove: function (url) {
            delete LA.utils.localCache.data[url];
        },
        exist: function (url) {
            return !!LA.utils.localCache.data[url] && ((new Date().getTime() - LA.utils.localCache.data[url]._) < LA.utils.localCache.timeout);
        },
        get: function (url) {
            console.log('Getting in cache for url ' + url);
            return LA.utils.localCache.data[url].data;
        },
        set: function (url, cachedData, callback) {
            LA.utils.localCache.remove(url);
            LA.utils.localCache.data[url] = {
                _: new Date().getTime(),
                data: cachedData
            };
            if ($.isFunction(callback)) callback(cachedData);
        }
    };

    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (options.cache) {
            //Here is our identifier for the cache. Maybe have a better, safer ID (it depends on the object string representation here) ?
            // on $.ajax call we could also set an ID in originalOptions

            var id = originalOptions.url + ( "undefined" !== typeof originalOptions.ajax_request_id ? JSON.stringify(originalOptions.ajax_request_id) : JSON.stringify(originalOptions.data) );
            options.cache = false;
            options.beforeSend = function () {
                if (!LA.utils.localCache.exist(id)) {
                    jqXHR.promise().done(function (data, textStatus) {
                        LA.utils.localCache.set(id, data);
                    });
                }
                return true;
            };
        }
    });

    $.ajaxTransport("+*", function (options, originalOptions, jqXHR) {

        //same here, careful because options.url has already been through jQuery processing
        var id = originalOptions.url + ( "undefined" !== typeof originalOptions.ajax_request_id ? JSON.stringify(originalOptions.ajax_request_id) : JSON.stringify(originalOptions.data) );

        options.cache = false;

        if (LA.utils.localCache.exist(id)) {
            return {
                send: function (headers, completeCallback) {
                    completeCallback(200, "OK", [LA.utils.localCache.get(id)]);
                },
                abort: function () {
                    /* abort code, nothing needed here I guess... */
                }
            };
        }
    });


}(jQuery));

// Initialize Load

(function($) {
    "use strict";

    var LA = window.LA || {};
    LA.utils = window.LA.utils || {};

    var defaultConfig = {
        rootMargin: '50px',
        threshold: 0,
        load: function load(element) {
            var base_src = element.getAttribute('data-src') || element.getAttribute('data-lazy') || element.getAttribute('data-lazy-src') || element.getAttribute('data-lazy-original'),
                base_srcset = element.getAttribute('data-src') || element.getAttribute('data-lazy-srcset'),
                base_sizes = element.getAttribute('data-sizes') || element.getAttribute('data-lazy-sizes');

            if(element.getAttribute('datanolazy') == 'true'){
                base_src = base_srcset = base_sizes = '';
            }

            if (base_src) {
                element.src = base_src;
            }
            if (base_srcset) {
                element.srcset = base_srcset;
            }
            if (base_sizes) {
                element.sizes = base_sizes;
            }
            if (element.getAttribute('data-background-image')) {
                element.style.backgroundImage = 'url("' + element.getAttribute('data-background-image') + '")';
            }
        },
        complete: function( $elm ){
            // this function will be activated when element has been loaded
        }
    };

    function markAsLoaded(element) {
        element.setAttribute('data-element-loaded', true);
    }

    var isLoaded = function isLoaded(element) {
        return element.getAttribute('data-element-loaded') === 'true';
    };

    var onIntersection = function onIntersection(load) {
        return function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.intersectionRatio > 0) {
                    observer.unobserve(entry.target);

                    if (!isLoaded(entry.target)) {
                        load(entry.target);
                        markAsLoaded(entry.target);
                    }
                }
            });
        };
    };

    LA.utils.LazyLoad = function () {
        var selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

        var _defaultConfig$option = $.extend({}, defaultConfig, options),
            rootMargin = _defaultConfig$option.rootMargin,
            threshold = _defaultConfig$option.threshold,
            load = _defaultConfig$option.load,
            complete = _defaultConfig$option.complete;

        var observer = void 0;

        if (window.IntersectionObserver) {
            observer = new IntersectionObserver(onIntersection(load), {
                rootMargin: rootMargin,
                threshold: threshold
            });
        }

        return {
            observe: function observe() {
                if ( !$.exists(selector) ) {
                    return;
                }
                for (var i = 0; i < selector.length; i++) {
                    if (isLoaded(selector[i])) {
                        continue;
                    }
                    if (observer) {
                        observer.observe(selector[i]);
                        continue;
                    }
                    load(selector[i]);
                    markAsLoaded(selector[i]);
                }
                complete(selector);
            }
        };
    };

    $('body').on('lastudio-lazy-images-load', function () {
        var $elm = $('.la-lazyload-image:not([data-element-loaded="true"])');
        LA.utils.LazyLoad($elm, {rootMargin: '200px'}).observe();
        var jetpackLazyImagesLoadEvent;
        try {
            jetpackLazyImagesLoadEvent = new Event( 'jetpack-lazy-images-load', {
                bubbles: true,
                cancelable: true
            } );
        } catch ( e ) {
            jetpackLazyImagesLoadEvent = document.createEvent( 'Event' );
            jetpackLazyImagesLoadEvent.initEvent( 'jetpack-lazy-images-load', true, true );
        }
        $( 'body' ).get( 0 ).dispatchEvent( jetpackLazyImagesLoadEvent );
    });

}(jQuery));

// Initialize LA Sticky
(function($) {
    "use strict";
    var doc, win;

    win = $(window);

    doc = $(document);

    $.fn.la_sticky = function(opts) {
        var doc_height, elm, enable_bottoming, inner_scrolling, manual_spacer, offset_top, outer_width, parent_selector, recalc_every, sticky_class, win_height, _fn, _i, _len, fake_parent, fake_parent_height;
        if (opts == null) {
            opts = {};
        }
        sticky_class = opts.sticky_class, inner_scrolling = opts.inner_scrolling, recalc_every = opts.recalc_every, parent_selector = opts.parent, offset_top = opts.offset_top, manual_spacer = opts.spacer, enable_bottoming = opts.bottoming, fake_parent = opts.fake_parent, fake_parent_height = opts.fake_parent_height;
        win_height = win.height();
        doc_height = doc.height();
        if (offset_top == null) {
            offset_top = 0;
        }
        if (parent_selector == null) {
            parent_selector = void 0;
        }
        if (inner_scrolling == null) {
            inner_scrolling = true;
        }
        if (sticky_class == null) {
            sticky_class = "is_stuck";
        }
        if (enable_bottoming == null) {
            enable_bottoming = true;
        }

        outer_width = function(el) {
            var computed, w, _el;
            if (window.getComputedStyle) {
                _el = el[0];
                computed = window.getComputedStyle(el[0]);
                w = parseFloat(computed.getPropertyValue("width")) + parseFloat(computed.getPropertyValue("margin-left")) + parseFloat(computed.getPropertyValue("margin-right"));
                if (computed.getPropertyValue("box-sizing") !== "border-box") {
                    w += parseFloat(computed.getPropertyValue("border-left-width")) + parseFloat(computed.getPropertyValue("border-right-width")) + parseFloat(computed.getPropertyValue("padding-left")) + parseFloat(computed.getPropertyValue("padding-right"));
                }
                return w;
            } else {
                return el.outerWidth(true);
            }
        };
        _fn = function(elm, padding_bottom, parent_top, parent_height, top, height, el_float, detached) {
            var bottomed, detach, fixed, last_pos, last_scroll_height, offset, parent, recalc, recalc_and_tick, recalc_counter, spacer, tick;
            var _fake_parent;
            if (elm.data("la_sticky")) {
                return;
            }

            elm.data("la_sticky", true);

            last_scroll_height = doc_height;
            parent = elm.parent();
            if(fake_parent){
                _fake_parent = fake_parent;
            }
            if (parent_selector != null) {
                parent = parent.closest(parent_selector);
            }
            if (!parent.length) {
                throw "failed to find stick parent";
            }
            fixed = false;
            bottomed = false;
            spacer = manual_spacer != null ? manual_spacer && elm.closest(manual_spacer) : $("<div />");
            if (spacer) {
                spacer.css('position', elm.css('position'));
            }
            recalc = function() {
                var border_top, padding_top, restore;
                if (detached) {
                    return;
                }
                win_height = win.height();
                doc_height = doc.height();
                last_scroll_height = doc_height;
                border_top = parseInt(parent.css("border-top-width"), 10);
                padding_top = parseInt(parent.css("padding-top"), 10);
                padding_bottom = parseInt(parent.css("padding-bottom"), 10);
                parent_top = parent.offset().top + border_top + padding_top;
                parent_height = fake_parent ? _fake_parent.height() : parent.height();
                if (fixed) {
                    fixed = false;
                    bottomed = false;
                    if (manual_spacer == null) {
                        elm.insertAfter(spacer);
                        spacer.detach();
                    }
                    elm.css({
                        position: "",
                        top: "",
                        width: "",
                        bottom: ""
                    }).removeClass(sticky_class);
                    restore = true;
                }
                top = elm.offset().top - (parseInt(elm.css("margin-top"), 10) || 0) - offset_top;
                height = elm.outerHeight(true);
                el_float = elm.css("float");
                if (spacer) {
                    spacer.css({
                        width: outer_width(elm),
                        height: height,
                        display: elm.css("display"),
                        "vertical-align": elm.css("vertical-align"),
                        "float": el_float
                    });
                }
                if (restore) {
                    return tick();
                }
            };
            recalc();
            if (height === parent_height) {
                return;
            }
            last_pos = void 0;
            offset = offset_top;
            recalc_counter = recalc_every;
            tick = function() {
                var css, delta, recalced, scroll, will_bottom;
                if (detached) {
                    return;
                }
                recalced = false;
                if (recalc_counter != null) {
                    recalc_counter -= 1;
                    if (recalc_counter <= 0) {
                        recalc_counter = recalc_every;
                        recalc();
                        recalced = true;
                    }
                }
                if (!recalced && doc_height !== last_scroll_height) {
                    recalc();
                    recalced = true;
                }
                scroll = win.scrollTop();
                if (last_pos != null) {
                    delta = scroll - last_pos;
                }
                last_pos = scroll;
                if (fixed) {
                    if (enable_bottoming) {
                        will_bottom = scroll + height + offset > parent_height + parent_top;
                        if (bottomed && !will_bottom) {
                            bottomed = false;
                            elm.css({
                                position: "fixed",
                                bottom: "",
                                top: offset
                            }).trigger("la_sticky:unbottom");
                        }
                    }
                    if (scroll <= top) {
                        fixed = false;
                        offset = offset_top;
                        if (manual_spacer == null) {
                            if (el_float === "left" || el_float === "right") {
                                elm.insertAfter(spacer);
                            }
                            spacer.detach();
                        }
                        css = {
                            position: "",
                            width: "",
                            top: ""
                        };
                        elm.css(css).removeClass(sticky_class).trigger("la_sticky:unstick");
                    }
                    if (inner_scrolling) {
                        if (height + offset_top > win_height) {
                            if (!bottomed) {
                                offset -= delta;
                                offset = Math.max(win_height - height, offset);
                                offset = Math.min(offset_top, offset);
                                if (fixed) {
                                    elm.css({
                                        top: offset + "px"
                                    });
                                }
                            }
                        }
                    }
                } else {
                    if (scroll > top) {
                        fixed = true;
                        css = {
                            position: "fixed",
                            top: offset
                        };
                        css.width = elm.css("box-sizing") === "border-box" ? elm.outerWidth() + "px" : elm.width() + "px";
                        elm.css(css).addClass(sticky_class);
                        if (manual_spacer == null) {
                            elm.after(spacer);
                            if (el_float === "left" || el_float === "right") {
                                spacer.append(elm);
                            }
                        }
                        elm.trigger("la_sticky:stick");
                    }
                }
                if (fixed && enable_bottoming) {
                    if (will_bottom == null) {
                        will_bottom = scroll + height + offset > parent_height + parent_top;
                    }
                    if (!bottomed && will_bottom) {
                        bottomed = true;
                        if (parent.css("position") === "static") {
                            parent.css({
                                position: "relative"
                            });
                        }
                        return elm.css({
                            position: "absolute",
                            bottom: padding_bottom,
                            top: "auto"
                        }).trigger("la_sticky:bottom");
                    }
                }
            };
            recalc_and_tick = function() {
                recalc();
                return tick();
            };
            detach = function() {
                detached = true;
                win.off("touchmove", tick);
                win.off("scroll", tick);
                win.off("resize", recalc_and_tick);
                $(document.body).off("la_sticky:recalc", recalc_and_tick);
                elm.off("la_sticky:detach", detach);
                elm.removeData("la_sticky");
                elm.css({
                    position: "",
                    bottom: "",
                    top: "",
                    width: ""
                });
                parent.position("position", "");
                if (fixed) {
                    if (manual_spacer == null) {
                        if (el_float === "left" || el_float === "right") {
                            elm.insertAfter(spacer);
                        }
                        spacer.remove();
                    }
                    return elm.removeClass(sticky_class);
                }
            };
            win.on("touchmove", tick);
            win.on("scroll", tick);
            win.on("resize", recalc_and_tick);
            $(document.body).on("la_sticky:recalc", recalc_and_tick);
            elm.on("la_sticky:detach", detach);
            return setTimeout(tick, 0);
        };
        for (_i = 0, _len = this.length; _i < _len; _i++) {
            elm = this[_i];
            _fn($(elm));
        }
        return this;
    };

}(jQuery));

// Initialize Event Manager
(function($) {
    'use strict';

    var LA = window.LA || {};
    LA.utils = window.LA.utils || {};

    LA.utils.eventManager = {};

    LA.utils.eventManager.subscribe = function(evt, func) {
        $(this).on(evt, func);
    };

    LA.utils.eventManager.unsubscribe = function(evt, func) {
        $(this).off(evt, func);
    };

    LA.utils.eventManager.publish = function(evt, params) {
        $(this).trigger(evt, params);
    };

}(jQuery));

/*
 Initialize LA_ProductGallery
 */
(function($) {
    'use strict';

    /**
     * Product gallery class.
     */
    var LA_ProductGallery = function( $target, args ) {

        this.$target = $target;
        this.$images = $( '.woocommerce-product-gallery__image', $target );

        if(!$target.parent('.product--large-image').data('old_gallery')){
            $target.parent('.product--large-image').data('old_gallery', $target.find('.woocommerce-product-gallery__wrapper').html()).data('prev_gallery', $target.find('.woocommerce-product-gallery__wrapper').html());
        }

        this.$target.parent().attr('data-totalG', this.$images.length);

        // No images? Abort.
        if ( 0 === this.$images.length ) {
            this.$target.css( 'opacity', 1 );
            this.$target.parent().addClass('no-gallery');
            return;
        }
        if( 1 === this.$images.length ){
            this.$target.parent().addClass('no-gallery');
        }
        else{
            this.$target.parent().removeClass('no-gallery');
        }


        // Make this object available.
        $target.data( 'product_gallery', this );

        // Pick functionality to initialize...
        this.flexslider_enabled = true;

        if ($target.hasClass('no-slider-script') || $target.hasClass('force-disable-slider-script') ){
            this.flexslider_enabled = false;
        }

        //this.flexslider_enabled = false;
        this.zoom_enabled       = $.isFunction( $.fn.zoom ) && wc_single_product_params.zoom_enabled;
        this.photoswipe_enabled = typeof PhotoSwipe !== 'undefined' && wc_single_product_params.photoswipe_enabled;

        // ...also taking args into account.
        if ( args ) {
            this.flexslider_enabled = false === args.flexslider_enabled ? false : this.flexslider_enabled;
            this.zoom_enabled       = false === args.zoom_enabled ? false : this.zoom_enabled;
            this.photoswipe_enabled = false === args.photoswipe_enabled ? false : this.photoswipe_enabled;
        }

        if($target.hasClass('force-disable-slider-script')){
            this.flexslider_enabled = false;
            //this.zoom_enabled       = false;
        }

        this.thumb_verital = false;


        if(this.$images.length < 2){
            this.flexslider_enabled = false;
        }

        try {
            if(la_theme_config.product_single_design == 2){
                this.thumb_verital = true;
            }
        }catch (ex){
            this.thumb_verital = false;
        }

        this.parent_is_quickview = false;

        if($target.closest('.lightcase-contentInner').length){
            this.thumb_verital = true;
            //this.zoom_enabled = false;
            this.parent_is_quickview = true;
        }

        // Bind functions to this.
        this.initSlickslider       = this.initSlickslider.bind( this );
        this.initZoom             = this.initZoom.bind( this );
        this.initPhotoswipe       = this.initPhotoswipe.bind( this );
        this.onResetSlidePosition = this.onResetSlidePosition.bind( this );
        this.getGalleryItems      = this.getGalleryItems.bind( this );
        this.openPhotoswipe       = this.openPhotoswipe.bind( this );

        if ( this.flexslider_enabled ) {

            if($.isFunction( $.fn.slick )){
                this.initSlickslider();
                $target.on( 'woocommerce_gallery_reset_slide_position', this.onResetSlidePosition );
            }else{
                var _self = this;
                LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.slick.js'], function(){
                    _self.initSlickslider();
                    $target.on( 'woocommerce_gallery_reset_slide_position', _self.onResetSlidePosition );
                })
            }
        }
        else {

            if(this.parent_is_quickview){
                $('body').removeClass('lightcase--pending').addClass('lightcase--completed');
            }
            else{
                setTimeout(function(){
                    $('body').trigger("la_sticky:recalc");
                },200);
            }

            this.$target.css( 'opacity', 1 );
            $target.removeClass('la-rebuild-product-gallery').parent().removeClass('swatch-loading');
        }

        if ( this.zoom_enabled ) {
            this.initZoom();
            $target.on( 'woocommerce_gallery_init_zoom', this.initZoom );
        }

        if ( this.photoswipe_enabled ) {
            this.initPhotoswipe();
        }

    };

    /**
     * Initialize flexSlider.
     */
    LA_ProductGallery.prototype.initSlickslider = function() {
        var images  = this.$images,
            $target = this.$target,
            $slides = $target.find('.woocommerce-product-gallery__wrapper'),
            $thumb = $target.parent().find('.la-thumb-inner'),
            rand_num = Math.floor((Math.random() * 100) + 1),
            thumb_id = 'la_woo_thumb_' + rand_num,
            target_id = 'la_woo_target_' + rand_num,
            is_quickview = this.parent_is_quickview;

        $slides.attr('id', target_id);
        $thumb.attr('id', thumb_id);

        images.each(function(){
            var $that = $(this);
            var video_code = $that.find('a[data-videolink]').data('videolink');
            var image_h = $slides.css('height');
            var thumb_html = '<div class="la-thumb"><img src="'+ $that.attr('data-thumb') +'"/></div>';
            if (typeof video_code != 'undefined' && video_code) {

                $that.unbind('click');
                $that.find('.zoomImg').css({
                    'display': 'none!important'
                });

                if (video_code.indexOf("http://selfhosted/") == 0) {
                    video_code = video_code.replace('http://selfhosted/', '');
                    thumb_html = '<div class="la-thumb has-thumb-video"><div><img src="'+ $that.attr('data-thumb') +'"/><span class="play-overlay"><i class="fa fa-play-circle-o" aria-hidden="true"></i></span></div></div>';
                    $that.append('<video class="selfhostedvid  noLightbox" width="460" height="315" controls preload="auto"><source src="' + video_code + '" /></video>');
                    $that.attr('data-video', '<div class="la-media-wrapper"><video class="selfhostedvid  noLightbox" width="460" height="315" controls preload="auto"><source src="' + video_code + '" /></video></div>');
                } else {
                    thumb_html = '<div class="la-thumb has-thumb-video"><div><img src="'+ $that.attr('data-thumb') +'"/><span class="play-overlay"><i class="fa-play-circle-o"></i></span></div></div>';
                    $that.append('<iframe src ="' + video_code + '" width="460" " style="height:' + image_h + '; z-index:999999;" frameborder="no"></iframe>');
                    $that.attr('data-video', '<div class="la-media-wrapper"><iframe src ="' + video_code + '" width="980" height="551" frameborder="no" allowfullscreen></iframe></div>');
                }

                $that.find('img').css({
                    'opacity': '0',
                    'z-index': '-1'
                });

                $that.find('iframe').next().remove();
            }
            $thumb.append(thumb_html);
        });

        var _thumb_column = $.extend({
            'xlg' : 3,
            'lg'  : 3,
            'md'  : 3,
            'sm'  : 5,
            'xs'  : 4,
            'mb'  : 3
        }, (JSON.parse(la_theme_config.product_gallery_column) || {}) );

        var _thumb_carousel_config = {
            infinite: false,
            slidesToShow: parseInt(_thumb_column['xlg']),
            slidesToScroll: 1,
            asNavFor: '#' + target_id,
            dots: false,
            arrows: true,
            focusOnSelect: true,
            prevArrow: '<span class="slick-prev"><i class="dlicon arrows-1_tail-left"></i></span>',
            nextArrow: '<span class="slick-next"><i class="dlicon arrows-1_tail-right"></i></span>',
            vertical: this.thumb_verital,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        vertical: this.thumb_verital,
                        slidesToShow: parseInt(_thumb_column['lg'])
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        vertical: false,
                        slidesToShow: parseInt(_thumb_column['md'])
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        vertical: false,
                        slidesToShow: parseInt(_thumb_column['sm'])
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        vertical: false,
                        slidesToShow: parseInt(_thumb_column['xs'])
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        vertical: false,
                        slidesToShow: parseInt(_thumb_column['mb'])
                    }
                }
            ]
        };

        if(!this.thumb_verital){
            _thumb_carousel_config.infinite = false;
            _thumb_carousel_config.centerMode = false;
            _thumb_carousel_config.centerPadding = '0px';
        }

        var _slide_carousel_config = {
            infinite: false,
            swipe: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            asNavFor: '#' + thumb_id,
            prevArrow: '<span class="slick-prev"><i class="dlicon arrows-1_tail-left"></i></span>',
            nextArrow: '<span class="slick-next"><i class="dlicon arrows-1_tail-right"></i></span>'
        };
        if(!this.thumb_verital){
            _slide_carousel_config.infinite = false;
        }

        $thumb.slick(_thumb_carousel_config);
        $slides.slick(_slide_carousel_config);

        LA.utils.LazyLoad($('img,.la-lazyload-image', $target.parent()), {
            rootMargin: '0px',
            complete : function(){
                $target.css( 'opacity', 1 );
                $thumb.slick('setPosition');
                $target.parent().removeClass('swatch-loading');

                if(is_quickview){
                    setTimeout(function(){
                        $slides.resize();
                        setTimeout(function(){
                            $('body').removeClass('lightcase--pending').addClass('lightcase--completed');
                        }, 50);
                    }, 150);
                }
                else{
                    setTimeout(function(){
                        $('body').trigger("la_sticky:recalc");
                    },200);
                }
            }
        }).observe();

    };

    /**
     * Init zoom.
     */
    LA_ProductGallery.prototype.initZoom = function() {
        this.initZoomForTarget( this.$images );
    };

    LA_ProductGallery.prototype.initZoomForTarget = function( zoomTarget ) {
        if ( ! this.zoom_enabled ) {
            return false;
        }

        var galleryWidth = this.$target.width(),
            zoomEnabled  = false,
            zoom_options;

        $( zoomTarget ).each( function( index, target ) {
            var image = $( target ).find( 'img' );

            if ( image.data( 'large_image_width' ) > galleryWidth ) {
                zoomEnabled = true;
                return false;
            }
        } );

        // But only zoom if the img is larger than its container.
        if ( zoomEnabled ) {
            try{
                zoom_options = $.extend( {
                    touch: false
                }, wc_single_product_params.zoom_options );
            }
            catch (ex){
                zoom_options = {
                    touch: false
                };
            }

            if ( 'ontouchstart' in document.documentElement ) {
                zoom_options.on = 'click';
            }

            zoomTarget.trigger( 'zoom.destroy' );
            zoomTarget.zoom( zoom_options );
        }
    };

    /**
     * Init PhotoSwipe.
     */
    LA_ProductGallery.prototype.initPhotoswipe = function() {
        if ( this.zoom_enabled && this.$images.length > 0 ) {
            this.$target.find('.woocommerce-product-gallery__actions').prepend( '<a href="#" class="woocommerce-product-gallery__trigger"><span><i class="dlicon ui-1_zoom-in"></i></span></a>' );
            this.$target.on( 'click', '.woocommerce-product-gallery__trigger', this.openPhotoswipe );
        }
        this.$target.on( 'click', '.woocommerce-product-gallery__image a', this.openPhotoswipe );
    };

    /**
     * Reset slide position to 0.
     */
    LA_ProductGallery.prototype.onResetSlidePosition = function() {
        this.$target.parent().removeClass('swatch-loading');
        this.$target.find('.woocommerce-product-gallery__wrapper').slick('slickGoTo', 0);
    };

    /**
     * Get product gallery image items.
     */
    LA_ProductGallery.prototype.getGalleryItems = function() {
        var $slides = this.$images,
            items   = [];

        if ( $slides.length > 0 ) {
            $slides.each( function( i, el ) {
                var img = $( el ).find( 'img' ),
                    large_image_src = img.attr( 'data-large_image' ),
                    large_image_w   = img.attr( 'data-large_image_width' ),
                    large_image_h   = img.attr( 'data-large_image_height' ),
                    item            = {
                        src: large_image_src,
                        w:   large_image_w,
                        h:   large_image_h,
                        title: img.attr( 'title' )
                    };
                if($(el).attr('data-video')){
                    item = {
                        html: $(el).attr('data-video')
                    };
                }
                items.push( item );
            } );
        }

        return items;
    };

    /**
     * Open photoswipe modal.
     */
    LA_ProductGallery.prototype.openPhotoswipe = function( e ) {
        e.preventDefault();

        var pswpElement = $( '.pswp' )[0],
            items       = this.getGalleryItems(),
            eventTarget = $( e.target ),
            clicked;

        if ( ! eventTarget.is( '.woocommerce-product-gallery__trigger' ) ) {
            clicked = eventTarget.closest( '.woocommerce-product-gallery__image' );
        } else {
            clicked = this.$target.find( '.slick-current' );
        }

        var options = {
            index:                 $( clicked ).index(),
            shareEl:               false,
            closeOnScroll:         false,
            history:               false,
            hideAnimationDuration: 0,
            showAnimationDuration: 0
        };

        // Initializes and opens PhotoSwipe.
        var photoswipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
        photoswipe.init();
    };

    /**
     * Function to call la_product_gallery on jquery selector.
     */
    $.fn.la_product_gallery = function( args ) {
        new LA_ProductGallery( this, args );
        return this;
    };

}(jQuery));

/*
 Initialize LA Swatches
 */
(function($) {
    'use strict';

    function variation_calculator(variation_attributes, product_variations) {

        this.recalc_needed = true;
        this.variation_attributes = variation_attributes;

        //The actual variations that are configured in woocommerce.
        this.variations_available = product_variations;

        //Stores the calculation result for attribute + values that are available based on the selected attributes.
        this.variations_current = {};

        //Stores the selected attributes + values
        this.variations_selected = {};

        //Reset all the attributes + values to disabled.  They will be reenabled durring the calcution.
        this.reset_current = function () {
            for (var attribute in this.variation_attributes) {
                this.variations_current[attribute] = {};
                for (var av = 0; av < this.variation_attributes[attribute].length; av++) {
                    this.variations_current[attribute.toString()][this.variation_attributes[attribute][av].toString()] = 0;
                }
            }
        };

        //Do the things to update the variations_current object with attributes + values which are enabled.
        this.update_current = function () {
            this.reset_current();
            for (var i = 0; i < this.variations_available.length; i++) {
                if (!this.variations_available[i].variation_is_active) {
                    continue; //Variation is unavailable, probably out of stock.
                }

                //the variation attributes for the product this variation.
                var variation_attributes = this.variations_available[i].attributes;

                //loop though each variation attribute, turning on and off attributes which won't be available.
                for (var attribute in variation_attributes) {

                    var maybe_available_attribute_value = variation_attributes[attribute];
                    var selected_value = this.variations_selected[attribute];

                    if (selected_value && selected_value == maybe_available_attribute_value) {
                        this.variations_current[attribute][maybe_available_attribute_value] = 1; //this is a currently selected attribute value
                    } else {

                        var result = true;

                        /*

                         Loop though any other item that is selected,
                         checking to see if the attribute value does not match one of the attributes for this variation.
                         If it does not match the attributes for this variation we do nothing.
                         If none have matched at the end of these loops, the atttribute_option will remain off and unavailable.

                         */
                        for (var other_selected_attribute in this.variations_selected) {

                            if (other_selected_attribute == attribute) {
                                //We are looking to see if any attribute that is selected will cause this to fail.
                                //Continue the loop since this is the attribute from above and we don't need to check against ourselves.
                                continue;
                            }

                            //Grab the value that is selected for the other attribute.
                            var other_selected_attribute_value = this.variations_selected[other_selected_attribute];

                            //Grab the current product variations attribute value for the other selected attribute we are checking.
                            var other_available_attribute_value = variation_attributes[other_selected_attribute];

                            if (other_selected_attribute_value) {
                                if (other_available_attribute_value) {
                                    if (other_selected_attribute_value != other_available_attribute_value) {
                                        /*
                                         The value this variation has for the "other_selected_attribute" does not match.
                                         Since it does not match it does not allow us to turn on an available attribute value.

                                         Set the result to false so we skip turning anything on.

                                         Set the result to false so that we do not enable this attribute value.

                                         If the value does match then we know that the current attribute we are looping through
                                         might be available for us to set available attribute values.
                                         */
                                        result = false;
                                        //Something on this variation didn't match the current selection, so we don't care about any of it's attributes.
                                    }
                                }
                            }
                        }

                        /**
                         After checking this attribute against this variation's attributes
                         we either have an attribute which should be enabled or not.

                         If the result is false we know that something on this variation did not match the currently selected attribute values.

                         **/
                        if (result) {
                            if (maybe_available_attribute_value === "") {
                                for (var av in this.variations_current[attribute]) {
                                    this.variations_current[attribute][av] = 1;
                                }

                            } else {
                                this.variations_current[attribute][maybe_available_attribute_value] = 1;
                            }
                        }

                    }
                }
            }

            this.recalc_needed = false;
        };

        this.get_current = function () {
            if (this.recalc_needed) {
                this.update_current();
            }
            return this.variations_current;
        };

        this.reset_selected = function () {
            this.recalc_needed = true;
            this.variations_selected = {};
        }

        this.set_selected = function (key, value) {
            this.recalc_needed = true;
            this.variations_selected[key] = value;
        };

        this.get_selected = function () {
            return this.variations_selected;
        }
    }

    function la_generator_gallery_html( variation ){
        var _html = '';
        if( typeof variation !== "undefined" && $.isArray(variation.la_additional_images) ){
            $.each(variation.la_additional_images, function(idx, val){
                _html += '<div data-thumb="'+val.thumb[0]+'" class="woocommerce-product-gallery__image">';
                _html += '<a href="'+val.large[0]+'" data-videolink="'+val.videolink+'">';
                _html += '<span class="g-overlay" style="background-image: url('+val.large[0]+')"></span>';
                _html += '<img ';
                _html += 'width="'+val.single[1]+'" ';
                _html += 'height="'+val.single[2]+'" ';
                _html += 'src="'+val.single[0]+'" ';
                _html += 'class="attachment-shop_single size-shop_single" ';
                _html += 'alt="'+val.alt+'" ';
                _html += 'title="'+val.title+'" ';
                _html += 'data-caption="'+val.caption+'" ';
                _html += 'data-src="'+val.large[0]+'" ';
                _html += 'data-large_image="'+val.large[0]+'" ';
                _html += 'data-large_image_width="'+val.large[1]+'" ';
                _html += 'data-large_image_height="'+val.large[2]+'" ';
                _html += 'srcset="'+val.srcset+'" ';
                _html += 'sizes="'+val.sizes+'" ';
                _html += '</a>';
                _html += '</div>';
            });
        }
        return _html;
    }

    function la_update_swatches_gallery($form, variation ){
        var $product_selector = $form.closest('.la-p-single-wrap'),
            $main_image_col = $product_selector.find('.product--large-image'),
            _html = '';
        if(variation !== null){
            _html = la_generator_gallery_html(variation);
        }
        else{
            var _old_gallery = $main_image_col.data('old_gallery') || false;
            if(_old_gallery){
                _html = _old_gallery;
            }
        }
        if (_html != '') {

            if(!!$main_image_col.data('prev_gallery')){

                var $_oldGalleryObject = $($main_image_col.data('prev_gallery')),
                    $_newGalleryObject = $(_html);

                var _donot_swap = true;

                if($_oldGalleryObject.length == $_newGalleryObject.length){
                    for (var idx = 0; idx < $_oldGalleryObject.length; idx++){
                        if($($_oldGalleryObject[idx]).attr('data-thumb') != $($_newGalleryObject[idx]).attr('data-thumb')){
                            _donot_swap = false;
                        }
                    }
                }else{
                    _donot_swap = false;
                }

                if(_donot_swap){
                    return;
                }

            }

            $main_image_col.data('prev_gallery', _html);

            _html = '<div class="woocommerce-product-gallery--with-images la-woo-product-gallery'+ ($main_image_col.hasClass('force-disable-slider-script') ? ' force-disable-slider-script' : '') +'"><figure class="woocommerce-product-gallery__wrapper">'+_html+'</figure><div class="la_woo_loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>';
            _html += '<div id="la_woo_thumbs" class="la-woo-thumbs"><div class="la-thumb-inner"></div></div>';
            $main_image_col.css({
                'max-height': $main_image_col.height(),
                'min-height': $main_image_col.height()
            }).addClass('swatch-loading');

            LA.utils.LazyLoad($('img,.la-lazyload-image', $(_html)), {
                rootMargin: '0px',
                complete : function(){
                    $main_image_col.html(_html);
                    var $la_gallery_selector = $main_image_col.find('.la-woo-product-gallery');
                    if(variation !== null){
                        $la_gallery_selector.addClass('la-rebuild-product-gallery');
                    }
                    $la_gallery_selector.la_product_gallery().addClass('swatch-loaded');
                    $main_image_col.css({
                        'max-height': 'none',
                        'min-height': '0'
                    });
                }
            }).observe();
        }
    }

    try{

        $.fn.la_variation_form = function () {
            var $form = this;
            var $product_id = parseInt($form.data('product_id'), 10);
            var calculator = null;
            var $use_ajax = false;
            var $swatches_xhr = null;

            $form.addClass('la-init-swatches');

            $form.find('td.label').each(function(){
                var $label = $(this).find('label');
                $label.append('<span class="swatch-label"></span>');
            });

            $form.on('bind_calculator', function () {

                var $product_variations = $form.data('product_variations');
                $use_ajax = $product_variations === false;

                if ($use_ajax) {
                    $form.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
                }

                var attribute_keys = {};

                //Set the default label.
                $form.find('.select-option.selected').each(function (index, el) {
                    var $this = $(this);

                    //Get the wrapper select div
                    var $option_wrapper = $this.closest('div.select').eq(0);
                    var $label = $option_wrapper.closest('tr').find('.swatch-label').eq(0);
                    var $la_select_box = $option_wrapper.find('select').first();

                    // Decode entities
                    var attr_val = $('<div/>').html($this.data('value')).text();

                    // Add slashes
                    attr_val = attr_val.replace(/'/g, '\\\'');
                    attr_val = attr_val.replace(/"/g, '\\\"');

                    if ($label) {
                        $label.html($la_select_box.children("[value='" + attr_val + "']").eq(0).text());
                    }
                    $la_select_box.trigger('change');
                });

                $form.find('.variations select').each(function (index, el) {
                    var $current_attr_select = $(el);
                    var current_attribute_name = $current_attr_select.data('attribute_name') || $current_attr_select.attr('name');

                    attribute_keys[current_attribute_name] = [];

                    //Build out a list of all available attributes and their values.
                    var current_options = '';
                    current_options = $current_attr_select.find('option:gt(0)').get();

                    if (current_options.length) {
                        for (var i = 0; i < current_options.length; i++) {
                            var option = current_options[i];
                            attribute_keys[current_attribute_name].push($(option).val());
                        }
                    }
                });

                if ($use_ajax) {
                    if ($swatches_xhr) {
                        $swatches_xhr.abort();
                    }

                    var data = {
                        product_id: $product_id,
                        action: 'la_swatch_get_product_variations'
                    };

                    $swatches_xhr = $.ajax({
                        url: la_theme_config.ajax_url,
                        type: 'POST',
                        data: data,
                        success: function (response) {
                            calculator = new variation_calculator(attribute_keys, response.data, null, null);
                            $form.unblock();
                        }
                    });
                } else {
                    calculator = new variation_calculator(attribute_keys, $product_variations, null, null);
                }

                $form.trigger('woocommerce_variation_has_changed');
            });

            $form
                .on('change', '.wc-default-select', function(e){
                    var $__that = $(this);
                    var $label = $__that.closest('tr').find('.swatch-label').eq(0);
                    if($__that.val() != ''){
                        $label.html($__that.find('option:selected').html());
                    }else{
                        $label.html('');
                    }
                });

            $form.find('.wc-default-select').trigger('change');

            $form
                // On clicking the reset variation button
                .on('click', '.reset_variations', function () {
                    $form.find('.swatch-label').html('');
                    $form.find('.select-option').removeClass('selected');
                    $form.find('.radio-option').prop('checked', false);
                    return false;
                })
                .on('click', '.select-option', function (e) {
                    e.preventDefault();

                    var $this = $(this);

                    //Get the wrapper select div
                    var $option_wrapper = $this.closest('div.select').eq(0);
                    var $label = $option_wrapper.closest('tr').find('.swatch-label').eq(0);
                    var $la_select_box = $option_wrapper.find('select').first();
                    if ($this.hasClass('disabled')) {
                        return false;
                    }
                    else if ($this.hasClass('selected')) {
                        $this.removeClass('selected');
                        $la_select_box.children('option:eq(0)').prop("selected", "selected").change();
                        if ($label) {
                            $label.html('');
                        }
                    }
                    else {

                        $option_wrapper.find('.select-option').removeClass('selected');
                        //Set the option to selected.
                        $this.addClass('selected');

                        // Decode entities
                        var attr_val = $('<div/>').html($this.data('value')).text();

                        // Add slashes
                        attr_val = attr_val.replace(/'/g, '\\\'');
                        attr_val = attr_val.replace(/"/g, '\\\"');

                        $la_select_box.trigger('focusin').children("[value='" + attr_val + "']").prop("selected", "selected").change();
                        if ($label) {
                            $label.html($la_select_box.children("[value='" + attr_val + "']").eq(0).text());
                        }
                    }
                })
                .on('change', '.radio-option', function (e) {

                    var $this = $(this);

                    //Get the wrapper select div
                    var $option_wrapper = $this.closest('div.select').eq(0);

                    //Select the option.
                    var $la_select_box = $option_wrapper.find('select').first();

                    // Decode entities
                    var attr_val = $('<div/>').html($this.val()).text();

                    // Add slashes
                    attr_val = attr_val.replace(/'/g, '\\\'');
                    attr_val = attr_val.replace(/"/g, '\\\"');

                    $la_select_box.trigger('focusin').children("[value='" + attr_val + "']").prop("selected", "selected").change();


                })
                .on('woocommerce_variation_has_changed', function () {
                    if (calculator === null) {
                        return;
                    }

                    $form.find('.variations select').each(function () {
                        var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
                        calculator.set_selected(attribute_name, $(this).val());
                    });

                    var current_options = calculator.get_current();

                    //Grey out or show valid options.
                    $form.find('div.select').each(function (index, element) {
                        var $la_select_box = $(element).find('select').first();

                        var attribute_name = $la_select_box.data('attribute_name') || $la_select_box.attr('name');
                        var avaiable_options = current_options[attribute_name];

                        $(element).find('div.select-option').each(function (index, option) {
                            if (!avaiable_options[$(option).data('value')]) {
                                $(option).addClass('disabled', 'disabled');
                            } else {
                                $(option).removeClass('disabled');
                            }
                        });

                        $(element).find('input.radio-option').each(function (index, option) {
                            if (!avaiable_options[$(option).val()]) {
                                $(option).attr('disabled', 'disabled');
                                $(option).parent().addClass('disabled', 'disabled');
                            } else {
                                $(option).removeAttr('disabled');
                                $(option).parent().removeClass('disabled');
                            }
                        });
                    });

                    if ($use_ajax) {
                        //Manage a regular  default select list.
                        // WooCommerce core does not do this if it's using AJAX for it's processing.
                        $form.find('.wc-default-select').each(function (index, element) {
                            var $la_select_box = $(element);

                            var attribute_name = $la_select_box.data('attribute_name') || $la_select_box.attr('name');
                            var avaiable_options = current_options[attribute_name];

                            $la_select_box.find('option:gt(0)').removeClass('attached');
                            $la_select_box.find('option:gt(0)').removeClass('enabled');
                            $la_select_box.find('option:gt(0)').removeAttr('disabled');

                            //Disable all options
                            $la_select_box.find('option:gt(0)').each(function (optindex, option_element) {
                                if (!avaiable_options[$(option_element).val()]) {
                                    $(option_element).addClass('disabled', 'disabled');
                                } else {
                                    $(option_element).addClass('attached');
                                    $(option_element).addClass('enabled');
                                }
                            });

                            $la_select_box.find('option:gt(0):not(.enabled)').attr('disabled', 'disabled');

                        });
                    }
                })
                .on('found_variation', function( event, variation ){
                    la_update_swatches_gallery($form, variation);
                })
                .on('reset_image', function( event ){
                    la_update_swatches_gallery($form, null);
                });

            $form.find('.single_variation').on('show_variation', function(e, variation, purchasable ){
                var $priceWrapper = $form.siblings('.single-price-wrapper');
                $('span.price', $priceWrapper).remove();
                $priceWrapper.append(variation.price_html);
            })
        };

        var forms = [];

        if(la_theme_config.la_extension_available.swatches){
            $(document).on('wc_variation_form', '.variations_form',  function (e) {
                var $form = $(e.target);
                forms.push($form);
                if ( !$form.data('has_swatches_form') ) {
                    if (true || $form.find('.swatch-control').length) {
                        $form.data('has_swatches_form', true);

                        $form.la_variation_form();
                        $form.trigger('bind_calculator');

                        $form.on('reload_product_variations', function () {
                            for (var i = 0; i < forms.length; i++) {

                                forms[i].trigger('woocommerce_variation_has_changed');
                                forms[i].trigger('bind_calculator');
                                forms[i].trigger('woocommerce_variation_has_changed');
                            }
                        })
                    }
                }
            });
        }
    }catch (ex){
        console.log('la_theme_config.la_extension_available.swatches is not activate');
    }

})(jQuery);

/*
 Initialize Core
 */
(function($) {
    'use strict';

    var LA = window.LA || {};
    LA.core = window.LA.core || {};

    var _loadedDependencies = [],
        _inQueue = {};

    LA.core.initAll = function( $scope ) {
        var $el = $scope.find( '.js-el' ),
            $components = $el.filter( '[data-la_component]' ),
            component = null;

        $('body').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );

        if($components.length <= 0 ){
            return;
        }

        // initialize  component
        var init = function init(name, el) {
            var $el = $(el);

            if ( $el.data('init-' + name) ) return;

            if ( typeof LA.component[ name ] !== 'function' ){
                LA.utils.logger.set( name, 'Component' , 'Component init error' );
            }
            else {
                component = new LA.component[ name ]( el );
                component.init();
                $el.data('init-' + name, true);
                LA.utils.eventManager.publish('LA:component_inited');
            }
        };

        $components.each( function() {
            var self = this,
                $this = $( this ),
                names = $this.data( 'la_component' );

            if( typeof names === 'string' ) {
                var _name = names ;
                init( _name , self);
            }
            else {
                names.forEach( function( name ) {
                    init(name, self);
                });
            }
        });

        $('body').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );

    };

    LA.core.loadDependencies = function( dependencies, callback ) {
        var _callback = callback || function() {};

        if( !dependencies ) {
            _callback();
            return;
        }

        var newDeps = dependencies.map( function( dep ) {
            if( _loadedDependencies.indexOf( dep ) === -1 ) {
                if( typeof _inQueue[ dep ] === 'undefined' ) {
                    LA.utils.logger.display(dep);
                    return dep;
                } else {
                    _inQueue[ dep ].push( _callback );
                    return true;
                }
            } else {
                return false;
            }
        });

        if( newDeps[0] === true ) {
            LA.utils.logger.set({
                new_deps : newDeps[0],
                waitingFor : dependencies[0]
            }, 'Component', 'waitingFor: load js file before running callback');
            return;
        }

        if( newDeps[0] === false ) {
            _callback();
            return;
        }

        var queue = newDeps.map( function( script ) {
            //LA.utils.logger.display(script);
            _inQueue[ script ] = [ _callback ];
            return $.getCachedScript( script );
        });

        // Callbacks invoking
        var onLoad = function onLoad() {
            var index = 0;
            newDeps.map( function( loaded ) {
                index++;
                _inQueue[ loaded ].forEach( function( callback ) {
                    if(index == newDeps.length){
                        callback();
                    }
                });
                delete _inQueue[ loaded ];
                _loadedDependencies.push( loaded );
            });
        };

        // Run callbacks when promise is resolved
        $.when.apply( null, queue ).done( onLoad );
    };

    LA.core.path = {
        theme   : la_theme_config.theme_path,
        plugins : la_theme_config.js_path,
        ajaxUrl : la_theme_config.ajax_url,
        security : la_theme_config.security
    };

})(jQuery);

/*
 Initialize Component
 */

(function($) {
    "use strict";

    var LA = window.LA || {},
        $window = $(window),
        $document = $(document),
        $htmlbody = $('html,body'),
        $body = $('body.draven-body'),
        $masthead = $('#lastudio-header-builder');

    LA.utils = window.LA.utils || {};
    LA.component = window.LA.component || {};
    LA.ui = window.LA.ui || {};
    LA.core = window.LA.core || {};

    LA.ui.AnimateLoadElement = function( effect_name, $elements, callback ){
        var _callback = callback || function() {};
        var animation_timeout = 0;

        // hide all element that not yet loaded
        $elements.css({ 'opacity': 0 });

        if ( effect_name == 'fade') {
            $elements.each(function () {
                $(this).stop().animate({
                    'opacity': 1
                }, 1000 );
            });
            animation_timeout = 1000;
        }
        else if ( effect_name == 'sequencefade'){
            $elements.each(function (i) {
                var $elm = $(this);
                setTimeout(function () {
                    $elm.stop().animate({
                        'opacity': 1
                    }, 1000 );
                }, 100 + (i * 50) );
            });
            animation_timeout = 500 + ($elements.length * 50);
        }
        else if ( effect_name == 'upfade'){

            $elements.each(function(){
                var $elm = $(this),
                    t = parseInt($elm.css('top'), 10) + ( $elm.height() / 2);
                $elm.css({
                    top: t + 'px',
                    opacity: 0
                });
            });

            $elements.each(function () {
                var $el = $(this);
                $el.stop().animate({
                    top: parseInt($el.css('top'), 10) - ( $el.height() / 2),
                    opacity: 1
                }, 1500);
            });

            animation_timeout = 2000;
        }
        else if ( effect_name == 'sequenceupfade'){

            $elements.each(function(){
                var $elm = $(this),
                    t = parseInt($elm.css('top'), 10) + ( $elm.height() / 2);
                $elm.css({
                    top: t + 'px',
                    opacity: 0
                });
            });

            $elements.each(function (i) {
                var $elm = $(this);
                setTimeout(function () {
                    $elm.stop().animate({
                        top: parseInt($elm.css('top'), 10) - ( $elm.height() / 2),
                        opacity: 1
                    }, 1000);
                }, 100 + i * 50);
            });

            animation_timeout = 500 + ($elements.length * 50);
        }
        else{
            $elements.css({ 'opacity': 1 });
            animation_timeout = 1000;
        }

        /* run callback */
        setTimeout(function(){
            _callback.call();
        }, animation_timeout );
    };

    LA.ui.LazyLoadElementEffect = function( selector, $container ){
        function _init_effect(){
            var _effect_name = false === !!$container.attr('data-la-effect') ? 'sequenceupfade' : $container.attr('data-la-effect');
            LA.ui.AnimateLoadElement(_effect_name, $(selector, $container), function(){
                $(selector, $container).addClass('showmenow');
            });
        }

        if($container.hasClass('LazyLoadElementEffect-inited')){
            return;
        }

        LA.utils.LazyLoad($container, {
            load : _init_effect()
        }).observe();

        $container.addClass('LazyLoadElementEffect-inited');

    };

    LA.ui.ShowMessageBox = function( html, ex_class ) {

        var show_popup = function(){
            lightcase.start({
                href: '#',
                showSequenceInfo: false,
                maxWidth:600,
                maxHeight: 500,
                onInit: {
                    clearTimeout: function(){
                        clearTimeout(LA['timeOutMessageBox']);
                    }
                },
                onFinish: {
                    insertContent: function () {
                        if(ex_class) {
                            $body.addClass(ex_class);
                        }
                        lightcase.get('content').append('<div class="custom-lightcase-overlay"></div>');
                        lightcase.get('contentInner').children().html('<div class="la-global-message">' + html + '</div>');
                        lightcase.get('contentInner').append('<a class="custom-lighcase-btn-close" href="#"><i class="dlicon ui-1_simple-remove"></i></a>');
                        lightcase.resize();
                        LA['timeOutMessageBox'] = setTimeout(function(){
                            lightcase.close();
                        }, 20 * 1000);
                    }
                },
                onClose : {
                    qux: function() {
                        if(ex_class){
                            $body.removeClass(ex_class);
                        }
                        $('.custom-lightcase-overlay').remove();
                        $('.custom-lighcase-btn-close').remove();
                        clearTimeout(LA['timeOutMessageBox']);
                    }
                }
            });
        };

        if($.fn.lightcase){
            show_popup();
        }else{
            LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.lightcase.js'], show_popup )
        }
    };

    LA.component.AutoCarousel = function(el){

        var $slider = $(el),
            options =  $slider.data('slider_config') || {};

        var init = function(){
            if($.isFunction( $.fn.slick )){
                setup_slick();
            }else{
                LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.slick.js'], setup_slick );
            }
        };

        var setup_slick = function(){
            var laptopSlides, tabletPortraitSlides, tabletSlides, mobileSlides, mobilePortraitSlides, defaultOptions, slickOptions;
            laptopSlides = parseInt(options.slidesToShow.laptop) || 1;
            tabletSlides = parseInt(options.slidesToShow.tablet) || 1;
            tabletPortraitSlides = parseInt(options.slidesToShow.tablet_portrait) || 1;
            mobileSlides = parseInt(options.slidesToShow.mobile) || 1;
            mobilePortraitSlides = parseInt(options.slidesToShow.mobile_portrait) || 1;
            options.slidesToShow = parseInt(options.slidesToShow.desktop) || 1;

            defaultOptions = {
                customPaging: function(slider, i) {
                    return $( '<span />' ).text( i + 1 );
                },
                dotsClass: 'lastudio-slick-dots',
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: laptopSlides,
                            slidesToScroll: options.slidesToScroll
                        }
                    },
                    {
                        breakpoint: 1025,
                        settings: {
                            slidesToShow: tabletSlides,
                            slidesToScroll: tabletSlides
                        }
                    },
                    {
                        breakpoint: 800,
                        settings: {
                            slidesToShow: tabletPortraitSlides,
                            slidesToScroll: tabletPortraitSlides
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: mobileSlides,
                            slidesToScroll: mobileSlides
                        }
                    },
                    {
                        breakpoint: 577,
                        settings: {
                            slidesToShow: mobilePortraitSlides,
                            slidesToScroll: mobilePortraitSlides
                        }
                    }
                ]
            };

            slickOptions = $.extend( {}, defaultOptions, options );

            var _autoPlay = slickOptions.autoplay || false;

            $slider.on('init', function(e, slick){
                if(slick.slideCount <= slick.options.slidesToShow){
                    slick.$slider.addClass('hidden-dots');
                }
                else{
                    slick.$slider.removeClass('hidden-dots');
                }

                if(slick.options.centerMode){
                    slick.$slider.addClass('la-slick-centerMode');
                }
                LA.utils.LazyLoad($('.la-lazyload-image'), {rootMargin: '0px'}).observe();
            });

            $slider.slick( slickOptions );

            if(_autoPlay){
                var $bar = $('<div class="slick-controls-auto"><a class="slick-control-start" href="#"><i class="fa fa-play" aria-hidden="true"></i></a><a class="slick-control-stop active" href="#"><i class="fa fa-pause" aria-hidden="true"></i></a></div>');
                $bar.appendTo( $slider );
                $slider
                    .on('click', '.slick-control-start', function (e) {
                        e.preventDefault();
                        $(this).removeClass('active').siblings('a').addClass('active');
                        $slider.slick('slickPlay');
                    })
                    .on('click', '.slick-control-stop', function (e) {
                        e.preventDefault();
                        $(this).removeClass('active').siblings('a').addClass('active');
                        $slider.slick('slickPause');
                    })
            }
        };

        return {
            init : init
        };

    };

    LA.component.DefaultMasonry = function(el){
        var $isotope_container = $(el),
            item_selector   = $isotope_container.data('item_selector'),
            configs         = ( $isotope_container.data('config_isotope') || {} );

        configs = $.extend({
            percentPosition: true,
            itemSelector : item_selector
        },configs);

        var setup_masonry = function(){
            $isotope_container.find('img[data-lazy-src!=""]').each(function(){
                $(this).attr('src', $(this).attr('data-lazy-src')).removeAttr('data-lazy-src');
            });
            $isotope_container.isotope(configs);
            if(!$isotope_container.hasClass('showposts-loop') && !$isotope_container.hasClass('loaded')){
                $isotope_container.on('layoutComplete', function(e){
                    LA.ui.LazyLoadElementEffect(item_selector, $isotope_container);
                });
            }

            LA.utils.LazyLoad($('img,.la-lazyload-image',$isotope_container), {
                rootMargin: '0px',
                complete : function(){
                    $('.la-isotope-loading', $isotope_container).hide();
                    $isotope_container.addClass('loaded').isotope('layout');
                }
            }).observe();

        };

        return {
            init : function(){
                $('.la-isotope-loading', $isotope_container).show();
                if($.isFunction( $.fn.isotope )){
                    setup_masonry();
                }else{
                    LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.isotope.pkgd.js'], setup_masonry );
                }
            }
        }

    };

    LA.component.AdvancedMasonry = function(el){
        var $isotope_container = $(el),
            item_selector   = $isotope_container.data('item_selector'),
            configs         = ( $isotope_container.data('config_isotope') || {} );

        configs = $.extend({
            percentPosition: true,
            itemSelector : item_selector,
            masonry : {
                gutter: 0
            }
        },configs);

        var get_isotope_column_number = function (w_w, item_w) {
            //w_w = ( w_w > 1920 ) ? 1920 : w_w;
            return Math.round(w_w / item_w);
        };

        LA.utils.eventManager.subscribe('LA:AdvancedMasonry:calculatorItemWidth', function( e, $isotope_container ){
            if($isotope_container.hasClass('grid-items')){
                return;
            }
            var ww = $window.width(),
                _base_w = $isotope_container.data('item-width'),
                _base_h = $isotope_container.data('item-height'),
                _container_width_base = ( false !== !!$isotope_container.data('container-width') ? $isotope_container.data('container-width') : $isotope_container.width()),
                _container_width = $isotope_container.width();

            var portfolionumber = get_isotope_column_number(_container_width_base, _base_w);

            if( ww > 1300){

                var __maxItem = $isotope_container.parent().attr('class').match(/masonry-max-item-per-row-(\d+)/);
                var __minItem = $isotope_container.parent().attr('class').match(/masonry-min-item-per-row-(\d+)/);

                if(__maxItem && __maxItem[1] && portfolionumber > parseInt(__maxItem[1])){
                    portfolionumber = parseInt(__maxItem[1]);
                }
                if(__minItem && __minItem[1] && portfolionumber < parseInt(__minItem[1])){
                    portfolionumber = parseInt(__minItem[1]);
                }
            }

            if( ww < 1024){
                portfolionumber = $isotope_container.data('md-col');
                $isotope_container.removeClass('cover-img-bg');
            }
            else{
                $isotope_container.addClass('cover-img-bg');
            }
            if( ww < 992){
                portfolionumber = $isotope_container.data('sm-col');
            }
            if( ww < 768){
                portfolionumber = $isotope_container.data('xs-col');
            }
            if( ww < 576){
                portfolionumber = $isotope_container.data('mb-col');
            }

            var itemwidth = Math.floor(_container_width / portfolionumber),
                selector = $isotope_container.data('item_selector'),
                margin = parseInt($isotope_container.data('item_margin') || 0),
                dimension = parseFloat( _base_w / _base_h );

            $( selector, $isotope_container ).each(function (idx) {

                var thiswidth = parseFloat( $(this).data('width') || 1 ),
                    thisheight = parseFloat( $(this).data('height') || 1),
                    _css = {};

                if (isNaN(thiswidth)) thiswidth = 1;
                if (isNaN(thisheight)) thisheight = 1;

                if( ww < 1024){
                    thiswidth = thisheight = 1;
                }

                _css.width = Math.floor((itemwidth * thiswidth) - (margin / 2));
                _css.height = Math.floor((itemwidth / dimension) * thisheight);

                if( ww < 1024){
                    _css.height = 'auto';
                }

                $(this).css(_css);

            });
        });

        var setup_masonry = function(){

            $isotope_container.find('img[data-lazy-src!=""]').each(function(){
                $(this).attr('src', $(this).attr('data-lazy-src')).removeAttr('data-lazy-src');
            });
            if(!$isotope_container.hasClass('masonry__column-type-default')){
                configs.masonry.columnWidth = 1;
            }
            $isotope_container.isotope(configs);
            $isotope_container.on('layoutComplete', function(e){
                LA.ui.LazyLoadElementEffect(item_selector, $isotope_container);
            });

            LA.utils.LazyLoad($('img,.la-lazyload-image',$isotope_container), {
                rootMargin: '0px',
                complete : function(){
                    $('.la-isotope-loading', $isotope_container).hide();
                    $isotope_container.addClass('loaded').isotope('layout');
                }
            }).observe();

            $window.on('resize', function(e) {
                LA.utils.eventManager.publish('LA:AdvancedMasonry:calculatorItemWidth', [$isotope_container]);
            });
        };

        return {
            init : function(){
                $('.la-isotope-loading', $isotope_container).show();
                LA.utils.eventManager.publish('LA:AdvancedMasonry:calculatorItemWidth', [$isotope_container]);
                if($.isFunction( $.fn.isotope )){
                    setup_masonry();
                }else{
                    LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.isotope.pkgd.js'], setup_masonry );
                }
            }
        }
    };

    LA.component.CountDownTimer = function(el){
        var $scope = $(el);

        var timeInterval,
            $coutdown = $scope.find( '[data-due-date]' ),
            endTime = new Date( $coutdown.data( 'due-date' ) * 1000 ),
            elements = {
                days: $coutdown.find( '[data-value="days"]' ),
                hours: $coutdown.find( '[data-value="hours"]' ),
                minutes: $coutdown.find( '[data-value="minutes"]' ),
                seconds: $coutdown.find( '[data-value="seconds"]' )
            };

        LA.component.CountDownTimer.updateClock = function() {

            var timeRemaining = LA.component.CountDownTimer.getTimeRemaining( endTime );

            $.each( timeRemaining.parts, function( timePart ) {

                var $element = elements[ timePart ];

                if ( $element.length ) {
                    $element.html( this );
                }

            } );

            if ( timeRemaining.total <= 0 ) {
                clearInterval( timeInterval );
            }
        };

        LA.component.CountDownTimer.initClock = function() {
            LA.component.CountDownTimer.updateClock();
            timeInterval = setInterval( LA.component.CountDownTimer.updateClock, 1000 );
        };

        LA.component.CountDownTimer.splitNum = function( num ) {

            var num   = num.toString(),
                arr   = [],
                reult = '';

            if ( 1 === num.length ) {
                num = 0 + num;
            }

            arr = num.match(/\d{1}/g);

            $.each( arr, function( index, val ) {
                reult += '<span class="lastudio-countdown-timer__digit">' + val + '</span>';
            });

            return reult;
        };

        LA.component.CountDownTimer.getTimeRemaining = function( endTime ) {

            var timeRemaining = endTime - new Date(),
                seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
                minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
                hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
                days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

            if ( days < 0 || hours < 0 || minutes < 0 ) {
                seconds = minutes = hours = days = 0;
            }

            return {
                total: timeRemaining,
                parts: {
                    days: LA.component.CountDownTimer.splitNum( days ),
                    hours: LA.component.CountDownTimer.splitNum( hours ),
                    minutes: LA.component.CountDownTimer.splitNum( minutes ),
                    seconds: LA.component.CountDownTimer.splitNum( seconds )
                }
            };
        };

        LA.component.CountDownTimer.initClock();

        return {
            init : function(){
                LA.component.CountDownTimer.initClock();
            }
        }
    };

    LA.component.InfiniteScroll = function(el){

        var $this = $(el),
            itemSelector    = $this.data('item_selector'),
            curr_page       = $this.data('page_num'),
            max_page        = $this.data('page_num_max'),
            navSelector     = $this.data('navSelector') || ".la-pagination",
            nextSelector    = $this.data('nextSelector') || ".la-pagination a.next";

        var default_options =  {
            navSelector  : navSelector,
            nextSelector : nextSelector,
            loading      : {
                finished: function(){
                    $('.la-infinite-loading', $this).remove();
                },
                msg: $("<div class='la-infinite-loading'><div class='la-loader spinner3'><div class='dot1'></div><div class='dot2'></div><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div></div>")
            }
        };

        var setup_infinite = function(){

            $this.parent().append('<div class="la-infinite-container-flag"></div>');
            default_options = $.extend( default_options, {
                itemSelector : itemSelector,
                state : {
                    currPage: curr_page
                },
                maxPage : max_page,
                debug : false
            });
            $this.infinitescroll(
                default_options,
                function(data) {

                    var $data = $(data);

                    if( $this.data('isotope') ){
                        $this.isotope('insert', $data.addClass('showmenow') );
                        if($.inArray('AdvancedMasonry', $this.data('la_component')) != -1){
                            LA.utils.eventManager.publish('LA:AdvancedMasonry:calculatorItemWidth', [$this]);
                            $this.isotope('layout');
                        }
                        $this.trigger('LA:Masonry:ajax_loadmore', [$this]);
                    }
                    else{
                        $data.each(function(idx){
                            if(idx == 0){
                                idx = 1;
                            }
                            $(this).css({
                                'animation-delay': (idx * 100) + 'ms',
                                '-webkit-animation-delay': (idx * 100) + 'ms'
                            });
                        });
                        $data.addClass('fadeInUp animated');
                    }

                    LA.core.initAll($data);

                    $('.la-infinite-loading', $this).remove();

                    if($('.la-infinite-container-flag', $this.parent()).length){
                        var _offset = LA.utils.getOffset($('.la-infinite-container-flag', $this.parent()));
                        if(_offset.y  < window.innerHeight - 200){
                            $this.infinitescroll('retrieve');
                        }
                    }

                    var __instance = $this.data('infinitescroll');
                    try{
                        $('.blog-main-loop__btn-loadmore').removeClass('loading');
                        if(max_page == __instance.options.state.currPage ){
                            $('.blog-main-loop__btn-loadmore').addClass('nothing-to-load');
                        }
                    }
                    catch (ex){
                        LA.utils.logger.set(ex, 'infinitescroll', 'error when call')
                    }

                }
            );
            if( $this.hasClass('infinite-show-loadmore')){
                $this.infinitescroll('pause');
            }
            if($('.la-infinite-container-flag', $this.parent()).length){
                var _offset = LA.utils.getOffset($('.la-infinite-container-flag', $this.parent()));
                if(_offset.y < window.innerHeight - 200){
                    $this.infinitescroll('retrieve');
                }
            }

            $(document).on('click', '.blog-main-loop__btn-loadmore a', function(e){
                e.preventDefault();
                if($(this).parent().hasClass('loading')){
                    return false;
                }
                else{
                    $(this).parent().addClass('loading');
                    $(this).parent().parent().find('.la-infinite-container').infinitescroll('retrieve');
                }
            });
        };

        var init = function(){
            if($.isFunction( $.fn.infinitescroll )){
                setup_infinite();
            }
            else{
                LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.infinitescroll.js'], setup_infinite );
            }
        };

        return {
            init: init
        }
    };

    LA.component.WooThreeSixty = function(el){
        var $shortcode = $(el);

        if( $shortcode.data('woothreesixty_vars') == '') return;

        var init = function(){
            if($.isFunction( $.fn.ThreeSixty )){
                init_threesixy();
            }
            else{
                LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.threesixty.js'], init_threesixy );
            }
        };

        var init_threesixy = function(){
            var opts = $shortcode.data('woothreesixty_vars');
            var woothreesixty_image_array = JSON.parse(opts.images);
            try {
                var $threesixy_instance = $shortcode.ThreeSixty({
                    totalFrames : woothreesixty_image_array.length,
                    currentFrame: 1,
                    endFrame    : woothreesixty_image_array.length,
                    framerate   : opts.framerate,
                    playSpeed   : opts.playspeed,
                    imgList     : '.threesixty_images',
                    progress    : '.spinner',
                    filePrefix  : '',
                    height      : opts.height,
                    width       : opts.width,
                    navigation  : opts.navigation,
                    imgArray    : woothreesixty_image_array,
                    responsive  : opts.responsive,
                    drag        : opts.drag,
                    disableSpin : opts.spin
                });
                LA.utils.eventManager.publish('LA:WooThreeSixty', [$threesixy_instance, $shortcode]);
            }catch (ex){
                LA.utils.logger.display(ex);
            }
        };

        return {
            init : init
        }

    };

    LA.core.MegaMenu = function(){

        function fix_megamenu_position( $elem, $container, container_width, isVerticalMenu) {

            if($('.megamenu-inited', $elem).length){
                return false;
            }

            var $popup = $('> .sub-menu', $elem);

            if ($popup.length == 0) return;
            var megamenu_width = $popup.outerWidth();

            if (megamenu_width > container_width) {
                megamenu_width = container_width;
            }
            if (!isVerticalMenu) {

                // if(containerClass == 'body.draven-body'){
                //     $popup.css('left', 0 - $elem.offset().left).css('left');
                //     return;
                // }

                var container_padding_left = parseInt($container.css('padding-left')),
                    container_padding_right = parseInt($container.css('padding-right')),
                    parent_width = $popup.parent().outerWidth(),
                    left = 0,
                    container_offset = LA.utils.getOffset($container),
                    megamenu_offset = LA.utils.getOffset($popup);

                if (megamenu_width > parent_width) {
                    left = -(megamenu_width - parent_width) / 2;
                }
                else{
                    left = 0
                }

                if ((megamenu_offset.x - container_offset.x - container_padding_left + left) < 0) {
                    left = -(megamenu_offset.x - container_offset.x - container_padding_left);
                }
                if ((megamenu_offset.x + megamenu_width + left) > (container_offset.x + $container.outerWidth() - container_padding_right)) {
                    left -= (megamenu_offset.x + megamenu_width + left) - (container_offset.x + $container.outerWidth() - container_padding_right);
                }
                $popup.css('left', left).css('left');
            }

            if (isVerticalMenu) {
                var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                    itemOffset = $popup.offset(),
                    itemHeight = $popup.outerHeight(),
                    scrollTop = $window.scrollTop();
                if (itemOffset.top - scrollTop + itemHeight > clientHeight) {
                    $popup.css({top: clientHeight - itemOffset.top + scrollTop - itemHeight - 20});
                }
            }

            $popup.addClass('megamenu-inited');

        }

        LA.utils.eventManager.subscribe('LA:MegaMenuBuilder:MenuPosition', function(e, $megamenu){
            if($.exists($megamenu)){

                $megamenu.closest('.lahfb-content-wrap').addClass('position-relative');

                $megamenu.each(function(){
                    var _that = $(this),
                        $container = _that.closest('.lahfb-content-wrap'),
                        container_width = $container.width(),
                        isVerticalMenu = false;

                    $('li.mega .megamenu-inited', _that).removeClass('megamenu-inited');

                    $('li.mega > .sub-menu', _that).removeAttr('style');

                    $('li.mega', _that).each(function(){
                        var $menu_item = $(this),
                            $popup = $('> .sub-menu', $menu_item),
                            $inner_popup = $('> .sub-menu > .mm-mega-li', $menu_item),
                            item_max_width = parseInt(!!$inner_popup.data('maxWidth') ? $inner_popup.data('maxWidth') : $inner_popup.css('maxWidth')),
                            $_container = $container;

                        var default_width = 1170;

                        if(container_width < default_width){
                            default_width = container_width;
                        }

                        if(default_width > item_max_width){
                            default_width = parseInt(item_max_width);
                        }

                        if( $menu_item.hasClass('mm-popup-force-fullwidth')){
                            $inner_popup.data('maxWidth', item_max_width).css('maxWidth', 'none');
                            $('> ul', $inner_popup).css('width', item_max_width);
                            if(!isVerticalMenu){
                                default_width = $window.width();
                                $_container = $('body.draven-body');
                            }
                            else{
                                default_width = $('#page.site > .site-inner').width();
                            }
                        }
                        $popup.width(default_width);
                        fix_megamenu_position( $menu_item, $_container, container_width, isVerticalMenu);
                    });
                })
            }
        });

        LA.utils.eventManager.publish('LA:MegaMenuBuilder:MenuPosition', [ $('body:not(.header-type-vertical) .lahfb-nav-wrap.has-megamenu') ]);

        $window.on('resize', function(){
            LA.utils.eventManager.publish('LA:MegaMenuBuilder:MenuPosition', [ $('body:not(.header-type-vertical) .lahfb-nav-wrap.has-megamenu') ]);
        });
    };

    LA.core.SitePreload = function(){
        setTimeout(function(){
            $body.removeClass('site-loading');
        }, 500);

        $window.on('load', function () {
            $body.removeClass('site-loading');
        });

        $window.on('beforeunload', function(e){
            if(LA.utils.browser.name != 'safari' && window.self === window.top){
                if( typeof window['hack_beforeunload_time'] === "undefined" || ( typeof window['hack_beforeunload_time'] !== "undefined" && e.timeStamp - window['hack_beforeunload_time'] > 1000 ) ) {
                    $('#page.site').css('opacity', '0');
                    $body.addClass('site-loading');
                }
            }
        });
        $(document).on('click', 'a[href^="tel:"], a[href^="mailto:"], a[href^="callto"], a[href^="skype"], a[href^="whatsapp"]', function(e){
            window['hack_beforeunload_time'] = parseInt(e.timeStamp);
        });
        $window.on('pageshow', function(e){
            if (e.originalEvent.persisted) {
                $body.removeClass('site-loading');
            }
        });

        LA.utils.LazyLoad($('.la-lazyload-image'), {rootMargin: '0px'}).observe();

        LA.utils.eventManager.subscribe('LA:Component:LazyLoadImage', function(e, $container){
            $container.find('img[data-lazy-src], img[data-lazy-original]').each(function(idx, img){
                var srcset = img.getAttribute('data-lazy-srcset'),
                    sizes = img.getAttribute('data-lazy-sizes'),
                    src = img.getAttribute('data-lazy-src') || img.getAttribute('data-lazy-original');
                if(srcset){
                    img.setAttribute('srcset', srcset);
                }
                if(sizes){
                    img.setAttribute('sizes', sizes);
                }
                if(src){
                    img.setAttribute('src', src);
                }
                $(img).removeClass('lazyload');
            })
        });
    };

    LA.core.ElementAjaxClickEvent = function(){
        $document
            .on('click', '.products__loadmore_ajax a', function (e) {
                e.preventDefault();
                var $this_control, $ul_products, $pagination, url_request, parent_id;
                $this_control = $(this);
                $ul_products = $this_control.closest('.woocommerce').find('.ul_products');
                $pagination = $this_control.closest('.woocommerce-pagination');
                parent_id = $this_control.closest('.woocommerce').attr('id');
                if( $('a.next', $pagination).length ) {
                    $pagination.addClass('doing-ajax');
                    $('#' + parent_id).addClass('doing-ajax');
                    if (LA.utils.logger.ajax_xhr) {
                        LA.utils.logger.ajax_xhr.abort();
                    }
                    url_request = $('a.next', $pagination).attr('href');
                    LA.utils.logger.ajax_xhr = $.get( url_request , function ( response ) {
                        $(response).find('#' + parent_id + ' .ul_products > li').appendTo($ul_products);
                        $('#' + parent_id).removeClass('doing-ajax');
                        $pagination.removeClass('doing-ajax');
                        if($(response).find('#' + parent_id + ' .woocommerce-pagination').length){
                            $pagination.replaceWith($(response).find('#' + parent_id + ' .woocommerce-pagination'));
                        }
                        LA.utils.eventManager.publish('LA:AjaxShopFilter:success', [response, url_request, $this_control]);
                    }, 'html');
                }
            })
            .on('click', '.elementor-widget-lastudio-products .woocommerce-pagination .page-numbers a', function (e) {
                e.preventDefault();
                var $this_control, $ul_products, $pagination, url_request, parent_id;
                $this_control = $(this);
                $ul_products = $this_control.closest('.woocommerce').find('.ul_products');
                $pagination = $this_control.closest('.woocommerce-pagination');
                parent_id = $this_control.closest('.woocommerce').attr('id');

                $pagination.addClass('doing-ajax');
                $('#' + parent_id).addClass('doing-ajax');

                if (LA.utils.logger.ajax_xhr) {
                    LA.utils.logger.ajax_xhr.abort();
                }

                url_request = $this_control.attr('href');
                LA.utils.logger.ajax_xhr = $.get( url_request , function ( response ) {
                    $ul_products.replaceWith($(response).find('#' + parent_id + ' .ul_products'));
                    $pagination.removeClass('doing-ajax');
                    $('#' + parent_id).removeClass('doing-ajax');
                    if($(response).find('#' + parent_id + ' .woocommerce-pagination').length){
                        $pagination.replaceWith($(response).find('#' + parent_id + ' .woocommerce-pagination'));
                    }
                    var position = $('#' + parent_id).offset().top - 200;
                    $htmlbody.stop().animate({
                        scrollTop: position
                    }, 800 );

                    LA.utils.eventManager.publish('LA:AjaxShopFilter:success', [response, url_request, $this_control]);
                }, 'html');

            })
            .on('click', '.la-ajax-pagination .pagination_ajax_loadmore a', function (e) {
                e.preventDefault();

                if($('body').hasClass('elementor-editor-active')){
                    return false;
                }

                var $pagination, url_request, _parent_container, _container, _item_selector;
                $pagination = $(this).closest('.la-ajax-pagination');
                _parent_container =  $pagination.data('parent-container');
                _container =  $pagination.data('container');
                _item_selector =  $pagination.data('item-selector');

                var _infinite_flag = false;

                if( $pagination.data('infinite-flag') ) {
                    _infinite_flag = $pagination.data('infinite-flag');
                }

                if( $('a.next', $pagination).length ) {

                    if($pagination.hasClass('doing-ajax')){
                        return false;
                    }

                    $pagination.addClass('doing-ajax');
                    $(_parent_container).addClass('doing-ajax');

                    url_request = $('a.next', $pagination).attr('href');

                    var ajaxOpts = {
                        url: url_request,
                        type: "get",
                        dataType: 'html',
                        success: function (response) {
                            var $data = $(response).find(_container + ' ' + _item_selector);

                            if($(_container).hasClass('la-slick-slider')) {
                                $(_container).slick('slickAdd', $data);
                                $(_container).slick('setPosition');
                            }
                            else if( $(_container).data('isotope') ){
                                $(_container).isotope('insert', $data.addClass('showmenow') );
                                if( $(_container).data('la_component') == 'AdvancedMasonry' ) {
                                    LA.utils.eventManager.publish('LaStudio:AdvancedMasonry:calculatorItemWidth', [$(_container), false]);
                                    $(_container).isotope('layout');
                                }
                                else{
                                    setTimeout(function(){
                                        $(_container).isotope('layout');
                                    }, 500);
                                }
                                $(_container).trigger('LaStudio:Masonry:ajax_loadmore', [$(_container)]);
                            }
                            else{
                                $data.addClass('fadeIn animated').appendTo($(_container));
                            }

                            $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );

                            LA.core.initAll($(_parent_container));

                            $(_parent_container).removeClass('doing-ajax');
                            $pagination.removeClass('doing-ajax la-ajax-load-first');

                            if($(response).find(_parent_container + ' .la-ajax-pagination').length){
                                var $new_pagination = $(response).find(_parent_container + ' .la-ajax-pagination');
                                $pagination.replaceWith($new_pagination);
                                $pagination = $new_pagination;
                            }
                            else{
                                $pagination.addClass('nothingtoshow');
                            }

                            if(_infinite_flag !== false){
                                setTimeout(function () {
                                    LA.component.InfiniteScroll($pagination);
                                }, 2000);
                            }
                        }
                    };

                    $.ajax(ajaxOpts);
                }
            })
            .on('click', '.la-ajax-pagination .page-numbers a', function (e) {
                e.preventDefault();

                if($('body').hasClass('elementor-editor-active')){
                    return false;
                }

                var $pagination, url_request, _parent_container, _container, _item_selector;
                $pagination = $(this).closest('.la-ajax-pagination');
                _parent_container =  $pagination.data('parent-container');
                _container =  $pagination.data('container');
                _item_selector =  $pagination.data('item-selector');

                if($(_parent_container).hasClass('doing-ajax')){
                    return;
                }

                $(_parent_container).addClass('doing-ajax');
                $pagination.addClass('doing-ajax');

                url_request = LA.utils.removeURLParameter($(this).attr('href'), '_');

                $.ajax({
                    url: url_request,
                    type: "get",
                    dataType: 'html',
                    cache: true,
                    ajax_request_id: LA.utils.getUrlParameter($pagination.data('ajax_request_id'), url_request),
                    success: function (response) {
                        var $data = $(response).find(_container + ' ' + _item_selector);

                        if($(_container).hasClass('la-slick-slider')) {
                            $(_container).slick('unslick').removeData('initAutoCarousel');
                            $data.appendTo($(_container).empty());
                        }
                        else if( $(_container).data('isotope') ){

                            $(_container).isotope('remove', $(_container).isotope('getItemElements'));
                            $(_container).isotope('insert', $data.addClass('showmenow'));
                            if( $(_container).data('la_component') == 'AdvancedMasonry' ) {
                                LA.utils.eventManager.publish('LA:AdvancedMasonry:calculatorItemWidth', [$(_container), false]);
                                $(_container).isotope('layout');
                            }
                            else{
                                setTimeout(function(){
                                    $(_container).isotope('layout');
                                }, 500);
                            }
                            $(_container).trigger('LA:Masonry:ajax_pagination', [$(_container)]);

                        }
                        else{
                            $data.addClass('fadeIn animated');
                            $data.appendTo($(_container).empty());
                        }

                        $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );

                        LA.core.initAll($(_parent_container));

                        $(_parent_container).removeClass('doing-ajax');
                        $pagination.removeClass('doing-ajax');

                        if($(response).find(_parent_container + ' .la-ajax-pagination').length){
                            $pagination.replaceWith($(response).find(_parent_container + ' .la-ajax-pagination'));
                        }
                    }

                });

            });

        $document.on('click', '.la-favorite-link a', function(e){
            e.preventDefault();
            var $this = $(this),
                post_id = $this.data('favorite_id');
            if(!$this.hasClass('loading') && !$this.hasClass('exists')){

                var $el_exists = $('a[data-favorite_id="'+post_id+'"]');
                $el_exists.addClass('loading');

                $.ajax({
                    url : LA.core.path.ajaxUrl,
                    method: "POST",
                    dataType: "json",
                    data : {
                        'action' : 'la_helpers_favorite',
                        'type' : $this.hasClass('added') ? 'remove' : 'add',
                        'post_id' : post_id,
                        'security' : LA.core.path.security.favorite_posts
                    }
                }).done(function( response ){
                    $el_exists.removeClass('loading');
                    if(response.success){
                        if($this.hasClass('added')){
                            $el_exists.removeClass('added exists');
                        }
                        else{
                            $el_exists.addClass('added exists');
                        }
                        $el_exists.find('.favorite_count').html(response.data.count);
                    }
                })
                    .fail(function() {
                        $el_exists.removeClass('loading');
                    })
            }
        })
    };

    LA.core.ElementClickEvent = function(){

        function MyAccountTab( $el ){
            if($el.parent('div').hasClass('col-2')){
                $el.closest('.u-columns').removeClass('active-logf').addClass('active-regf');
            }
            else{
                $el.closest('.u-columns').removeClass('active-regf').addClass('active-logf');
            }
        }

        $('.no-touchevents #customer_login h2').on('click', function (e) {
            e.preventDefault();
            MyAccountTab( $(this) );
        });

        $('.touchevents #customer_login h2').on('touchstart', function (e) {
            e.preventDefault();
            MyAccountTab( $(this) );
        });

        $('.la-column-sticky > .elementor-column-wrap > .elementor-widget-wrap').la_sticky({
            parent: $('.elementor-row'),
            offset_top: ($masthead.length ? parseInt($masthead.height()) + 30 : 30)
        });

        $('.btn-close-search').on('click', function (e) {
            e.preventDefault();
            $body.removeClass('open-search-form');
        });

        /**
         * Mobile Menu Trigger
         */
        $('.footer-handheld-footer-bar .la_com_action--searchbox .component-target').on('click', function (e) {
            e.preventDefault();
            var $_parent = $(this).parent();
            if($_parent.hasClass('active')){
                $body.removeClass('open-search-form');
                $_parent.removeClass('active');
            }
            else{
                $body.addClass('open-search-form');
                $_parent.addClass('active');
                $_parent.siblings().removeClass('active');
                $body.removeClass('open-overlay');
            }
        });

        $('.footer-handheld-footer-bar .la_com_action--dropdownmenu .component-target').on('click', function (e) {
            e.preventDefault();
            var $_parent = $(this).parent();
            $body.removeClass('open-search-form');
            if($_parent.hasClass('active')){
                $_parent.removeClass('active');
                $body.removeClass('open-overlay');
            }
            else{
                $_parent.addClass('active');
                $_parent.siblings().removeClass('active');
                $body.addClass('open-overlay');
            }
        });

        $('.la-overlay-global').on('touchend click', function (e) {
            e.preventDefault();
            $('.la_com_action--primary-menu,.la_com_action--dropdownmenu').removeClass('active');
            $body.removeClass('open-aside open-search-form open-cart-aside open-advanced-shop-filter open-header-aside open-master-aside open-overlay open-burger-menu open-lahfb-vertical');
        });

        /** Back To Top **/
        $window.on('load scroll', function(){
            if($window.scrollTop() > $window.height() + 100){
                $('.backtotop-container').addClass('show');
            }
            else{
                $('.backtotop-container').removeClass('show');
            }
        });

        $('#btn-backtotop').on('click', function (e) {
            e.preventDefault();
            $htmlbody.animate({
                scrollTop: 0
            }, 800)
        });

        /** Other Element **/
        $document
            .on('click', '.la-popup:not(.wpb_single_image):not(.elementor-widget), .banner-video .banner--link-overlay, .la-popup.wpb_single_image a,.la-popup-slideshow, .la-popup.elementor-widget a', function(e){
                e.preventDefault();
                var $that = $(this);

                function parseVideo (url) {
                    // - Supported YouTube URL formats:
                    //   - http://www.youtube.com/watch?v=My2FRPA3Gf8
                    //   - http://youtu.be/My2FRPA3Gf8
                    //   - https://youtube.googleapis.com/v/My2FRPA3Gf8
                    // - Supported Vimeo URL formats:
                    //   - http://vimeo.com/25451551
                    //   - http://player.vimeo.com/video/25451551
                    // - Also supports relative URLs:
                    //   - //player.vimeo.com/video/25451551

                    url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
                    if (RegExp.$3.indexOf('youtu') > -1) {
                        return 'https://www.youtube.com/embed/' + RegExp.$6 + '?autoplay=1&loop=1&rel=0&iv_load_policy3';
                    } else if (RegExp.$3.indexOf('vimeo') > -1) {
                        return 'https://player.vimeo.com/video/' + RegExp.$6 + '?autoplay=1&loop=1&title=0&byline=0&portrait=0';
                    }
                    return url;
                }

                var init_auto_popup = function(){
                    lightcase.start({
                        href: parseVideo($that.attr('href')),
                        showTitle: false,
                        showCaption: false,
                        maxWidth: $window.width(),
                        maxHeight: $window.height(),
                        iframe:{
                            width:1280,
                            height:720
                        },
                        onFinish: {
                            createOverlay: function () {
                                lightcase.get('content').append('<div class="custom-lightcase-overlay"></div>');
                                lightcase.get('contentInner').append('<a class="custom-lighcase-btn-close" href="#"><i class="dlicon ui-1_simple-remove"></i></a>');
                            }
                        },
                        onClose : {
                            qux : function(){
                                $('.custom-lightcase-overlay').remove();
                                $('.custom-lighcase-btn-close').remove();
                            }
                        }
                    });
                }
                if($.fn.lightcase){
                    init_auto_popup();
                }else{
                    LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.lightcase.js'], init_auto_popup )
                }
            })
            .on('click', '.custom-lighcase-btn-close, .popup-button-continue, .custom-lightcase-overlay', function(e){
                e.preventDefault();
                try{
                    lightcase.close();
                }catch (ex){}
            })
            .on('click', '.la-inline-popup', function (e) {
                e.preventDefault();
                var _this = $(this);
                var $popup = $(_this.attr('href'));
                var extra_class = _this.data('component_name');

                lightcase.start({
                    href: '#',
                    maxWidth: parseInt(la_theme_config.popup.max_width),
                    maxHeight: parseInt(la_theme_config.popup.max_height),
                    onInit : {
                        foo: function() {
                            $('body.lastudio-draven').addClass(extra_class);
                        }
                    },
                    onClose : {
                        qux: function() {
                            $('.custom-lightcase-overlay').remove();
                            $('.custom-lighcase-btn-close').remove();
                            $popup.appendTo(_this.parent());
                            $('body.lastudio-draven').removeClass(extra_class);
                        }
                    },
                    onFinish: {
                        injectContent: function () {
                            lightcase.get('content').append('<div class="custom-lightcase-overlay"></div>');
                            lightcase.get('contentInner').children().append($popup);
                            lightcase.get('contentInner').append('<a class="custom-lighcase-btn-close" href="#"><i class="dlicon ui-1_simple-remove"></i></a>');
                            $('.lightcase-icon-close').hide();
                            lightcase.resize();
                        }
                    }
                });
            })

    };

    LA.core.HeaderSticky = function(){
        var $header_builder = $('#lastudio-header-builder');

        var scroll_direction = 'none',
            last_scroll = $window.scrollTop();

        $window.on('scroll', function(){
            var currY = $window.scrollTop();
            scroll_direction = (currY > last_scroll) ? 'down' : ((currY === last_scroll) ? 'none' : 'up');
            last_scroll = currY;
        });

        var prepareHeightForHeader = function (){
            var _current_height = 0;
            if( $.exists($header_builder) ){
                _current_height = $('.lahfbhinner').outerHeight();
                $('.lahfb-wrap-sticky-height').height( _current_height );
            }
        };
        prepareHeightForHeader();
        $window.on('resize', prepareHeightForHeader);

        function init_mobile_bar_sticky(){
            if(!$.exists($('.footer-handheld-footer-bar'))){
                return;
            }

            var $_mobile_bar = $('.footer-handheld-footer-bar');

            $window.on('scroll', function(e){
                if($window.width() > 600) return;

                var mb_height = parseInt(la_theme_config.header_height.mobile.normal);
                if(mb_height < 20){
                    mb_height = 100;
                }

                if($window.scrollTop() > mb_height){
                    if(la_theme_config.mobile_bar == 'down'){
                        if(scroll_direction == 'down'){
                            $_mobile_bar.removeClass('sticky--unpinned').addClass('sticky--pinned');
                        }
                        else{
                            $_mobile_bar.removeClass('sticky--pinned').addClass('sticky--unpinned');
                        }
                    }
                    else if(la_theme_config.mobile_bar == 'up'){
                        if(scroll_direction == 'up'){
                            $_mobile_bar.removeClass('sticky--unpinned').addClass('sticky--pinned');
                        }
                        else{
                            $_mobile_bar.removeClass('sticky--pinned').addClass('sticky--unpinned');
                        }
                    }
                }
                else{
                    $_mobile_bar.removeClass('sticky--pinned sticky--unpinned');
                }
            })
        }
        init_mobile_bar_sticky();


        var sticky_auto_hide = $body.hasClass('header-sticky-type-auto') ? true : false;
        function init_builder_sticky() {
            if( ! $.exists($header_builder) ) {
                return;
            }

            var $_header = $header_builder,
                $_header_outer = $('.lahfbhouter', $header_builder),
                $_header_inner = $('.lahfbhinner', $header_builder);

            var lastY = 0,
                offsetY = LA.utils.getOffset($_header_outer).y;

            $window
                .on('resize', function(e){
                    offsetY = LA.utils.getOffset($_header_outer).y;
                    if($($body).hasClass('header-type-vertical')){
                        if( $window.width() > 992 ){
                            $_header.removeClass('is-sticky');
                            $_header_inner.removeClass('sticky--unpinned sticky--pinned').removeAttr('style');
                            return;
                        }
                    }
                })
                .on('scroll', function(e){

                    if($($body).hasClass('header-type-vertical')){
                        if( $window.width() > 992 ){
                            $_header.removeClass('is-sticky');
                            $_header_inner.removeClass('sticky--unpinned sticky--pinned').removeAttr('style');
                            return;
                        }
                    }

                    var currentScrollY = $window.scrollTop();

                    var _breakpoint = offsetY - LA.utils.getAdminbarHeight();

                    if(sticky_auto_hide){
                        _breakpoint = offsetY - LA.utils.getAdminbarHeight() + $_header_outer.outerHeight();
                    }

                    if( currentScrollY > _breakpoint ) {
                        $_header_inner.css('top', LA.utils.getAdminbarHeight());

                        if( !$_header.hasClass('is-sticky') ) {
                            $_header.addClass('is-sticky');
                        }

                        if(sticky_auto_hide){
                            if(currentScrollY < $body.height() && lastY > currentScrollY){
                                if($_header_inner.hasClass('sticky--unpinned')){
                                    $_header_inner.removeClass('sticky--unpinned');
                                }
                                if(!$_header_inner.hasClass('sticky--pinned')){
                                    $_header_inner.addClass('sticky--pinned');
                                }
                            }else{
                                if($_header_inner.hasClass('sticky--pinned')){
                                    $_header_inner.removeClass('sticky--pinned');
                                }
                                if(!$_header_inner.hasClass('sticky--unpinned')){
                                    $_header_inner.addClass('sticky--unpinned');
                                }
                            }
                        }
                        else{
                            $_header_inner.addClass('sticky--pinned');
                        }
                    }
                    else{
                        if(sticky_auto_hide){
                            if($_header.hasClass('is-sticky')){
                                if(_breakpoint - currentScrollY < $_header_outer.outerHeight()){
                                    //console.log('here !!');
                                    //var diff = $_header_outer.outerHeight() - $_header_inner.outerHeight();
                                    //if(currentScrollY < diff){
                                    //    var _curtop = diff - (currentScrollY + LA.utils.getAdminbarHeight());
                                    //    $_header_inner.css('top', _curtop);
                                    //}else{
                                    //    $_header_inner.css('top', LA.utils.getAdminbarHeight());
                                    //}
                                }else{
                                    /** remove stuck **/
                                    $_header.removeClass('is-sticky');
                                    $_header_inner.css('top','0').removeClass('sticky--pinned sticky--unpinned');
                                }
                            }
                        }else{
                            if($_header.hasClass('is-sticky')){
                                $_header.removeClass('is-sticky');
                                $_header_inner.css('top','0').removeClass('sticky--pinned sticky--unpinned');
                            }
                        }
                    }

                    lastY = currentScrollY;
                })
        }

        if(!$body.hasClass('enable-header-sticky')) return;

        init_builder_sticky();

    };

    LA.core.WooCommerce = function(){
        /*
         * Initialize all galleries on page.
         */
        $( '.la-woo-product-gallery' ).each( function() {
            $( this ).la_product_gallery();
        } );

        $('.variations_form').trigger('wc_variation_form');

        $document.on('click','.product_item .la-swatch-control .swatch-wrapper', function(e){
            e.preventDefault();
            var $swatch_control = $(this),
                $image = $swatch_control.closest('.product_item').find('.product_item--thumbnail-holder img').first();

            if($swatch_control.closest('.product_item--thumbnail').length > 0){
                $image = $swatch_control.closest('.product_item--thumbnail').find('.product_item--thumbnail-holder img').last();
            }
            if($swatch_control.hasClass('selected')) return;
            $swatch_control.addClass('selected').siblings().removeClass('selected');
            if(!$image.hasClass('_has_changed')){
                $image.attr('data-o-src', $image.attr('src')).attr('data-o-sizes', $image.attr('sizes')).attr('data-o-srcset', $image.attr('srcset'));
            }
            if(!!$swatch_control.attr('data-thumb')){
                $image.attr('src', $swatch_control.attr('data-thumb')).removeAttr('sizes srcset');
            }
        });

        $document.on('click','.la-quickview-button',function(e){

            var _qv_mw, _qv_mh;
            if( $window.width() > 1500){
                _qv_mw = 1440;
                _qv_mh = 900;
            }
            else if( $window.width() > 1200 ) {
                _qv_mw = 1000;
                _qv_mh = 650;
            }
            else{
                _qv_mw = 900;
                _qv_mh = 600;
            }

            if(_qv_mh > $window.height()){
                _qv_mh = $window.height() * 0.8;
            }

            if($window.width() > 900){
                e.preventDefault();
                var $this = $(this);
                var show_popup = function(){
                    lightcase.start({
                        href: $this.data('href'),
                        showSequenceInfo: false,
                        type: 'ajax',
                        maxWidth: _qv_mw,
                        maxHeight: _qv_mh,
                        speedIn: 150,
                        speedOut: 100,
                        ajax: {
                            width: _qv_mw,
                            height: _qv_mh,
                            cache: true,
                            ajax_request_id: LA.utils.getUrlParameter('product_quickview', $this.data('href'))
                        },

                        onClose : {
                            qux : function(){
                                $body.removeClass('open-quickview-product lightcase--completed lightcase--pending');
                                $('.custom-lightcase-overlay').remove();
                                $('.custom-lighcase-btn-close').remove();
                            }
                        },
                        onWait: function( obj, $obj, data){
                            $body.addClass('open-quickview-product');
                            obj.objects.content.append('<div class="custom-lightcase-overlay"></div>');
                            obj.objects.contentInner.append('<a class="custom-lighcase-btn-close" href="#"><i class="dlicon ui-1_simple-remove"></i></a>');
                            $obj.html(data);
                            var $woo_gallery = $('.la-woo-product-gallery', $obj);
                            if($woo_gallery.length){
                                $body.addClass('lightcase--pending');
                                $woo_gallery.la_product_gallery();
                                obj._showContent($obj);
                            }
                            else{
                                obj._showContent($obj);
                            }
                        }
                    })
                }
                if($.fn.lightcase){
                    show_popup();
                }
                else{
                    LA.core.loadDependencies([ LA.core.path.plugins + 'jquery.lightcase.js'], show_popup )
                }
            }
        });

        $document.on('click', '#lightcase-case .product-main-image .product--large-image a', function(e){
            e.preventDefault();
        });


        /** Wishlist **/

        function set_attribute_for_wl_table(){
            var $table = $('table.wishlist_table');
            $table.addClass('shop_table_responsive');
            $table.find('thead th').each(function(){
                var _th = $(this),
                    _text = _th.text().trim();
                if(_text != ""){
                    $('td.' + _th.attr('class'), $table).attr('data-title', _text);
                }
            });
        }
        set_attribute_for_wl_table();
        $body.on('removed_from_wishlist', function(e){
            set_attribute_for_wl_table();
        });
        $document.on('added_to_cart', function(e, fragments, cart_hash, $button){
            setTimeout(set_attribute_for_wl_table, 800);
        });
        $document.on('click','.product a.add_wishlist.la-yith-wishlist',function(e){
            if(!$(this).hasClass('added')) {
                e.preventDefault();
                var $button     = $(this),
                    product_id = $button.data( 'product_id' ),
                    $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                    product_name = 'Product',
                    data = {
                        add_to_wishlist: product_id,
                        product_type: $button.data( 'product-type' ),
                        action: yith_wcwl_l10n.actions.add_to_wishlist_action
                    };
                if (!!$button.data('product_title')) {
                    product_name = $button.data('product_title');
                }
                if($button.closest('.product--summary').length){
                    $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                }
                try {
                    if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                        var wishlist_popup_container = $button.parents('.yith-wcwl-popup-footer').prev('.yith-wcwl-popup-content'),
                            wishlist_popup_select = wishlist_popup_container.find('.wishlist-select'),
                            wishlist_popup_name = wishlist_popup_container.find('.wishlist-name'),
                            wishlist_popup_visibility = wishlist_popup_container.find('.wishlist-visibility');

                        data.wishlist_id = wishlist_popup_select.val();
                        data.wishlist_name = wishlist_popup_name.val();
                        data.wishlist_visibility = wishlist_popup_visibility.val();
                    }

                    if (!LA.utils.isCookieEnable()) {
                        alert(yith_wcwl_l10n.labels.cookie_disabled);
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: yith_wcwl_l10n.ajax_url,
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $button.addClass('loading');
                        },
                        complete: function () {
                            $button.removeClass('loading').addClass('added');
                        },
                        success: function (response) {
                            var msg = $('#yith-wcwl-popup-message'),
                                response_result = response.result,
                                response_message = response.message;

                            if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                                var wishlist_select = $('select.wishlist-select');
                                if (typeof $.prettyPhoto !== 'undefined') {
                                    $.prettyPhoto.close();
                                }
                                wishlist_select.each(function (index) {
                                    var t = $(this),
                                        wishlist_options = t.find('option');
                                    wishlist_options = wishlist_options.slice(1, wishlist_options.length - 1);
                                    wishlist_options.remove();

                                    if (typeof response.user_wishlists !== 'undefined') {
                                        var i = 0;
                                        for (i in response.user_wishlists) {
                                            if (response.user_wishlists[i].is_default != "1") {
                                                $('<option>')
                                                    .val(response.user_wishlists[i].ID)
                                                    .html(response.user_wishlists[i].wishlist_name)
                                                    .insertBefore(t.find('option:last-child'))
                                            }
                                        }
                                    }
                                });

                            }
                            var html = '<div class="popup-added-msg">';
                            if (response_result == 'true') {
                                if ($product_image.length){
                                    html += $('<div>').append($product_image.clone()).html();
                                }
                                html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.wishlist.success + '</div>';
                            }else {
                                html += '<div class="popup-message">' + response_message + '</div>';
                            }
                            html += '<a class="button view-popup-wishlish" rel="nofollow" href="' + response.wishlist_url.replace('/view', '') + '">' + la_theme_config.i18n.wishlist.view + '</a>';
                            html += '<a class="button popup-button-continue" rel="nofollow" href="#">' + la_theme_config.i18n.global.continue_shopping + '</a>';
                            html += '</div>';

                            LA.ui.ShowMessageBox(html, 'open-wishlist-msg');

                            $button.attr('href',response.wishlist_url);
                            $('.add_wishlist[data-product_id="' + $button.data('product_id') + '"]').addClass('added');
                            $body.trigger('added_to_wishlist');
                        }
                    });
                } catch (ex) {
                    LA.utils.logger.set(ex, 'WooCommerce', 'Wishlist');
                }
            }
        });

        $document.on('click','.product a.add_wishlist.la-ti-wishlist',function(e){
            e.preventDefault();
            var $ti_action;
            if($(this).closest('.entry-summary').length){
                $ti_action = $(this).closest('.entry-summary').find('form.cart .tinvwl_add_to_wishlist_button');
            }
            else{
                $ti_action = $(this).closest('.product').find('.tinvwl_add_to_wishlist_button');
            }
            $ti_action.trigger('click');
        })

        /** LA Wishlist **/

        $document.on('click','.product a.add_wishlist.la-core-wishlist',function(e){
            if(!$(this).hasClass('added')) {
                e.preventDefault();
                var $button     = $(this),
                    product_id = $button.data( 'product_id' ),
                    $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                    product_name = 'Product',
                    data = {
                        action: 'la_helpers_wishlist',
                        security: la_theme_config.security.wishlist_nonce,
                        post_id: product_id,
                        type: 'add'
                    };
                if (!!$button.data('product_title')) {
                    product_name = $button.data('product_title');
                }
                if($button.closest('.product--summary').length){
                    $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                }

                $.ajax({
                    type: 'POST',
                    url: la_theme_config.ajax_url,
                    data: data,
                    dataType: 'json',
                    beforeSend: function () {
                        $button.addClass('loading');
                    },
                    complete: function () {
                        $button.removeClass('loading').addClass('added');
                    },
                    success: function (response) {
                        var html = '<div class="popup-added-msg">';

                        if (response.success) {
                            if ($product_image.length){
                                html += $('<div>').append($product_image.clone()).html();
                            }
                            html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.wishlist.success + '</div>';
                        }
                        else {
                            html += '<div class="popup-message">' + response.data.message + '</div>';
                        }
                        html += '<a class="button view-popup-wishlish" rel="nofollow" href="'+response.data.wishlist_url+'">' + la_theme_config.i18n.wishlist.view + '</a>';
                        html += '<a class="button popup-button-continue" rel="nofollow" href="#">' + la_theme_config.i18n.global.continue_shopping + '</a>';
                        html += '</div>';

                        LA.ui.ShowMessageBox(html, 'open-wishlist-msg');

                        $('.add_wishlist[data-product_id="' + $button.data('product_id') + '"]').addClass('added').attr('href', response.data.wishlist_url);
                    }
                });

            }
        });

        $document.on('click', '.la_wishlist_table a.la_remove_from_wishlist', function(e){
            e.preventDefault();
            var $table = $('#la_wishlist_table_wrapper');
            if( typeof $.fn.block != 'undefined' ) {
                $table.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
            }
            $table.load( e.target.href + ' #la_wishlist_table_wrapper2', function(){
                if( typeof $.fn.unblock != 'undefined' ) {
                    $table.stop(true).css('opacity', '1').unblock();
                }
            } );
        });

        $document.on('adding_to_cart', function( e, $button, data ){
            if( $button && $button.closest('.la_wishlist_table').length ) {
                data.la_remove_from_wishlist_after_add_to_cart = data.product_id;
            }
        });

        $document.on('added_to_cart', function( e, fragments, cart_hash, $button ){
            if($button && $button.closest('.la_wishlist_table').length ) {
                var $table = $('#la_wishlist_table_wrapper');
                $button.closest('tr').remove();
                $table.load( window.location.href + ' #la_wishlist_table_wrapper2')
            }
        });

        /** Compare **/
        $document.on('click', 'table.compare-list .remove a', function(e){
            e.preventDefault();
            $('.add_compare[data-product_id="' + $(this).data('product_id') + '"]', window.parent.document).removeClass('added');
        });

        $document.on('click','.la_com_action--compare', function(e){
            if(typeof yith_woocompare !== "undefined"){
                e.preventDefault();
                try{
                    lightcase.close();
                }catch (ex){}
                var action_url = LA.utils.addQueryArg('', 'action', yith_woocompare.actionview);
                action_url = LA.utils.addQueryArg(action_url, 'iframe', 'true');
                $body.trigger('yith_woocompare_open_popup', { response: action_url });
            }
        });

        $document.on('click', '.product a.add_compare:not(.la-core-compare)', function(e){
            e.preventDefault();

            if($(this).hasClass('added')){
                $body.trigger('yith_woocompare_open_popup', { response: LA.utils.addQueryArg( LA.utils.addQueryArg('', 'action', yith_woocompare.actionview) , 'iframe', 'true') });
                return;
            }

            var $button     = $(this),
                widget_list = $('.yith-woocompare-widget ul.products-list'),
                $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                data        = {
                    action: yith_woocompare.actionadd,
                    id: $button.data('product_id'),
                    context: 'frontend'
                },
                product_name = 'Product';
            if(!!$button.data('product_title')){
                product_name = $button.data('product_title');
            }

            if($button.closest('.product--summary').length){
                $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
            }

            $.ajax({
                type: 'post',
                url: yith_woocompare.ajaxurl.toString().replace( '%%endpoint%%', yith_woocompare.actionadd ),
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    $button.addClass('loading');
                },
                complete: function(){
                    $button.removeClass('loading').addClass('added');
                },
                success: function(response){
                    if($.isFunction($.fn.block) ) {
                        widget_list.unblock()
                    }
                    var html = '<div class="popup-added-msg">';
                    if ($product_image.length){
                        html += $('<div>').append($product_image.clone()).html();
                    }
                    html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.compare.success + '</div>';
                    html += '<a class="button la_com_action--compare" rel="nofollow" href="'+response.table_url+'">'+la_theme_config.i18n.compare.view+'</a>';
                    html += '<a class="button popup-button-continue" href="#" rel="nofollow">'+ la_theme_config.i18n.global.continue_shopping + '</a>';
                    html += '</div>';

                    LA.ui.ShowMessageBox(html, 'open-compare-msg');

                    $('.add_compare[data-product_id="' + $button.data('product_id') + '"]').addClass('added');

                    widget_list.unblock().html( response.widget_table );
                }
            });
        });

        /** LA Compare **/
        $document.on('click', '.product a.add_compare.la-core-compare', function(e){
            if(!$(this).hasClass('added')) {
                e.preventDefault();
                var $button     = $(this),
                    product_id = $button.data( 'product_id' ),
                    $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                    product_name = 'Product',
                    data = {
                        action: 'la_helpers_compare',
                        security: la_theme_config.security.compare_nonce,
                        post_id: product_id,
                        type: 'add'
                    };
                if (!!$button.data('product_title')) {
                    product_name = $button.data('product_title');
                }
                if($button.closest('.product--summary').length){
                    $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                }

                $.ajax({
                    type: 'POST',
                    url: la_theme_config.ajax_url,
                    data: data,
                    dataType: 'json',
                    beforeSend: function () {
                        $button.addClass('loading');
                    },
                    complete: function () {
                        $button.removeClass('loading').addClass('added');
                    },
                    success: function (response) {
                        var html = '<div class="popup-added-msg">';

                        if (response.success) {
                            if ($product_image.length){
                                html += $('<div>').append($product_image.clone()).html();
                            }
                            html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.compare.success + '</div>';
                        }
                        else {
                            html += '<div class="popup-message">' + response.data.message + '</div>';
                        }
                        html += '<a class="button view-popup-compare" rel="nofollow" href="'+response.data.compare_url+'">' + la_theme_config.i18n.compare.view + '</a>';
                        html += '<a class="button popup-button-continue" rel="nofollow" href="#">' + la_theme_config.i18n.global.continue_shopping + '</a>';
                        html += '</div>';

                        LA.ui.ShowMessageBox(html, 'open-compare-msg');

                        $('.add_compare[data-product_id="' + $button.data('product_id') + '"]').addClass('added').attr('href', response.data.compare_url);
                    }
                });

            }
        });

        $document.on('click', '.la_remove_from_compare', function(e){
            e.preventDefault();
            var $table = $('#la_compare_table_wrapper');
            if( typeof $.fn.block != 'undefined' ) {
                $table.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
            }
            $table.load( e.target.href + ' #la_compare_table_wrapper2', function(){
                if( typeof $.fn.unblock != 'undefined' ) {
                    $table.stop(true).css('opacity', '1').unblock();
                }
            } );
        })

        /** Cart **/
        var cart_timeout = null;
        $(document.body).on('wc_fragments_refreshed updated_wc_div wc_fragments_loaded', function(e){
            clearTimeout( cart_timeout );
            cart_timeout = setTimeout( function(){
                LA.utils.eventManager.publish('LA:Component:LazyLoadImage', [$('.widget_shopping_cart_content')]);
            }, 100);
        });
        $document.on('click', '.la_com_action--cart', function(e){
            if(!$(this).hasClass('force-display-on-mobile')){
                if($window.width() > 767){
                    e.preventDefault();
                    $body.toggleClass('open-cart-aside');
                }
            }
            else{
                e.preventDefault();
                $body.toggleClass('open-cart-aside');
            }
        });

        $document.on('click', '.btn-close-cart', function(e){
            e.preventDefault();
            $body.removeClass('open-cart-aside');
        });

        $document.on('adding_to_cart', function( e ){
            $body.removeClass('open-search-form').addClass('open-cart-aside');
            $('.cart-flyout').addClass('cart-flyout--loading');
            $('.la_com_action--cart > a > i').addClass('fa fa-spinner fa-spin');
        });
        $document.on('added_to_cart', function( e, fragments, cart_hash, $button ){
            $('.cart-flyout').removeClass('cart-flyout--loading');
            $('.la_com_action--cart > a > i').removeClass('fa fa-spinner fa-spin');
        } );

        $('.la-global-message').on('click','.popup-button-continue',function(e){
            e.preventDefault();
            $('.la-global-message .close-message').trigger('click');
        });

        $document
            .on('touchend click','.wc-view-toggle span',function(){
                var _this = $(this),
                    _mode = _this.data('view_mode');
                if(!_this.hasClass('active')){
                    $('.wc-view-toggle span').removeClass('active');
                    _this.addClass('active');

                    var $ul_products = $('.page-content').find('ul.products[data-grid_layout]'),
                        _old_grid = $ul_products.attr('data-grid_layout');
                    $ul_products.removeClass('products-grid').removeClass('products-list').addClass('products-'+_mode);

                    if(_mode == 'grid'){
                        $ul_products.addClass(_old_grid);
                    }
                    else {
                        $ul_products.removeClass(_old_grid);
                    }
                    Cookies.set('draven_wc_catalog_view_mode', _mode, { expires: 2 });
                }
            });

        /**
         * Single
         */
        var single_product_page_sticky_element = '.la-p-single-wrap.la-p-single-3 .la-custom-pright';

        $(single_product_page_sticky_element).la_sticky({
            parent: $('.la-single-product-page'),
            offset_top: ($masthead.length ? parseInt($masthead.height()) + 30 : 30)
        });

        $('.woocommerce-tabs .wc-tab-title a').on('click', function(e){
            e.preventDefault();
            var $this = $(this),
                $wrap = $this.closest('.woocommerce-tabs'),
                $wc_tabs = $wrap.find('.wc-tabs'),
                $panel = $this.closest('.wc-tab');

            $wc_tabs.find('a[href="'+ $this.attr('href') +'"]').parent().toggleClass('active').siblings().removeClass('active');
            $panel.toggleClass('active').siblings().removeClass('active');
        });
        $('.woocommerce-Tabs-panel--description').addClass('active');

        $document
            .on('click', '.quantity .qty-minus', function(e){
                e.preventDefault();
                var $qty = $(this).siblings('.qty'),
                    val = parseInt($qty.val());
                $qty.val( val > 1 ? val-1 : 1).trigger('change');
            })
            .on('click', '.quantity .qty-plus', function(e){
                e.preventDefault();
                var $qty = $(this).siblings('.qty'),
                    val = parseInt($qty.val());
                $qty.val( val > 0 ? val+1 : 1 ).trigger('change');
            })
            .on('click', single_product_page_sticky_element + ' .wc-tabs a', function(e){
                setTimeout(function(){
                    $body.trigger('la_sticky:recalc');
                }, 300);
            });

        if(la_theme_config.single_ajax_add_cart == 'on' || la_theme_config.single_ajax_add_cart == 'yes'){
            $(document).on('submit', '.la-p-single-wrap:not(.product-type-external) .entry-summary form.cart', function(e){
                e.preventDefault();
                $(document).trigger('adding_to_cart');

                var form = $(this),
                    product_url = form.attr('action') || window.location.href,
                    action_url = LA.utils.addQueryArg(product_url, 'product_quickview', '1'),
                    frm_data = '?' + form.serialize();

                var _product_id = form.find('[name="add-to-cart"]').val();
                frm_data = LA.utils.addQueryArg(frm_data, 'add-to-cart', _product_id).slice(1);

                $.post(action_url, frm_data + '&_wp_http_referer=' + product_url, function (result) {
                    // Show message
                    if($(result).eq(0).hasClass('woocommerce-message') || $(result).eq(0).hasClass('woocommerce-error')){
                        $('.woocommerce-message, .woocommerce-error').remove();
                        $('.la-p-single-wrap.type-product').eq(0).before($(result).eq(0));
                    }
                    try{
                        lightcase.close();
                    }catch (ex){}
                    // update fragments
                    $.ajax({
                        url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                        type: 'POST',
                        success: function( data ) {
                            if ( data && data.fragments ) {
                                $.each( data.fragments, function( key, value ) {
                                    $( key ).replaceWith( value );
                                });
                                $( document.body ).trigger( 'wc_fragments_refreshed' );
                                $('.cart-flyout').removeClass('cart-flyout--loading');
                                $('.la_com_action--cart > a > i').removeClass('fa fa-spinner fa-spin');
                            }
                        }
                    });
                });
            });
        }

        /**
         * Other
         */
        $('#coupon_code_ref').on('change', function(e){
            $('.woocommerce-cart-form__contents #coupon_code').val($(this).val());
        });
        $('#coupon_btn_ref').on('click', function(e){
            e.preventDefault();
            $('.woocommerce-cart-form__contents [name="apply_coupon"]').trigger('click');
        });
        $document.on('click', '#la_tabs_customer_login .la_tab_control a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $($this.attr('href'));
            $this.parent().addClass('active').siblings().removeClass('active');
            $target.addClass('active').show().siblings('div').removeClass('active').hide();
            window.location.hash = $(this).attr('href').replace('#la_tab--', '');
        });
        $document.on('click', '#la_tabs_customer_login .btn-create-account', function(e){
            e.preventDefault();
            $('#la_tabs_customer_login .la_tab_control li:eq(1) a').trigger('click');
        });

        if( ( window.location.hash == '#la_tab--register' || window.location.hash == '#register' ) && $.exists($('#la_tabs_customer_login .la_tab_control li a[href="#la_tab--register"]')) ) {
            $('#la_tabs_customer_login .la_tab_control li a[href="#la_tab--register"]').trigger('click');
        }
        else{
            if($.exists($('#la_tabs_customer_login .la_tab_control li a[href="#la_tab--login"]'))){
                $('#la_tabs_customer_login .la_tab_control li a[href="#la_tab--login"]').trigger('click');
            }
        }

    };

    LA.core.AjaxShopFilter = function(){
        LA.utils.logger.ajax_xhr = null;
        if( $('#la_shop_products').length == 0){
            return;
        }
        if($('#la_shop_products').hasClass('deactive-filters')){
            return;
        }
        var elm_to_replace = [
            '.wc-toolbar-top',
            '.la-advanced-product-filters .sidebar-inner',
            '.wc_page_description'
        ];

        if( $('#la_shop_products').hasClass('elementor-widget') ) {
            elm_to_replace.push('ul.ul_products');
            elm_to_replace.push('.la-pagination');
        }
        else{
            elm_to_replace.push('#la_shop_products');
        }

        var target_to_init = '#la_shop_products .la-pagination a, .la-advanced-product-filters-result a',
            target_to_init2 = '.woo-widget-filter a, .wc-ordering a, .wc-view-count a, .woocommerce.product-sort-by a, .woocommerce.la-price-filter-list a, .woocommerce.widget_layered_nav a, .woocommerce.widget_product_tag_cloud li a, .woocommerce.widget_product_categories a',
            target_to_init3 = '.woocommerce.widget_product_tag_cloud:not(.la_product_tag_cloud) a';

        function init_price_filter() {
            if ( typeof woocommerce_price_slider_params === 'undefined' ) {
                return false;
            }

            $( 'input#min_price, input#max_price' ).hide();
            $( '.price_slider, .price_label' ).show();

            var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
                max_price = $( '.price_slider_amount #max_price' ).data( 'max' ),
                current_min_price = $( '.price_slider_amount #min_price' ).val(),
                current_max_price = $( '.price_slider_amount #max_price' ).val();

            $( '.price_slider:not(.ui-slider)' ).slider({
                range: true,
                animate: true,
                min: min_price,
                max: max_price,
                values: [ current_min_price, current_max_price ],
                create: function() {

                    $( '.price_slider_amount #min_price' ).val( current_min_price );
                    $( '.price_slider_amount #max_price' ).val( current_max_price );

                    $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
                },
                slide: function( event, ui ) {

                    $( 'input#min_price' ).val( ui.values[0] );
                    $( 'input#max_price' ).val( ui.values[1] );

                    $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
                },
                change: function( event, ui ) {

                    $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
                }
            });
        }

        LA.utils.eventManager.subscribe('LA:AjaxShopFilter', function(e, url, element){

            if( $('.wc-toolbar-container').length > 0) {
                var position = $('.wc-toolbar-container').offset().top - 200;
                $htmlbody.stop().animate({
                    scrollTop: position
                }, 800 );
            }

            if ('?' == url.slice(-1)) {
                url = url.slice(0, -1);
            }
            url = url.replace(/%2C/g, ',');

            url = LA.utils.removeURLParameter(url,'la_doing_ajax');

            if (typeof (history.pushState) != "undefined") {
                history.pushState(null, null, url);
            }

            LA.utils.eventManager.publish('LA:AjaxShopFilter:before_send', [url, element]);

            if (LA.utils.logger.ajax_xhr) {
                LA.utils.logger.ajax_xhr.abort();
            }

            url = LA.utils.addQueryArg(url, 'la_doing_ajax', 'true');

            LA.utils.logger.ajax_xhr = $.get(url, function ( response ) {

                for ( var i = 0; i < elm_to_replace.length; i++){
                    if( $(elm_to_replace[i]).length ){
                        if( elm_to_replace[i] == '.la-advanced-product-filters .sidebar-inner'){
                            if( $(response).find(elm_to_replace[i]).length ){
                                $(elm_to_replace[i]).replaceWith( $(response).find(elm_to_replace[i]) );
                            }
                        }
                        else{
                            $(elm_to_replace[i]).replaceWith( $(response).find(elm_to_replace[i]) );

                        }
                    }
                }

                if( $('#sidebar_primary').length && $(response).find('#sidebar_primary').length ) {
                    $('#sidebar_primary').replaceWith($(response).find('#sidebar_primary'));
                    LA.core.Blog($('#sidebar_primary'));
                    $('li.current-cat, li.current-cat-parent', $('#sidebar_primary')).each(function(){
                        $(this).addClass('open');
                        $('>ul', $(this)).css('display','block');
                    });
                }

                if( $('#section_page_header').length && $(response).find('#section_page_header').length ) {
                    $('#section_page_header').replaceWith($(response).find('#section_page_header'));
                }

                $('.la-ajax-shop-loading').removeClass('loading');

                LA.utils.eventManager.publish('LA:AjaxShopFilter:success', [response, url, element]);

            }, 'html');
        });
        LA.utils.eventManager.subscribe('LA:AjaxShopFilter:success', function(e, response, url, element){
            if( $('.widget.woocommerce.widget_price_filter').length ) {
                init_price_filter();
            }
            if($body.hasClass('open-advanced-shop-filter')){
                $body.removeClass('open-advanced-shop-filter');
                $('.la-advanced-product-filters').stop().slideUp('fast');
            }
            var pwb_params = LA.utils.getUrlParameter('pwb-brand-filter', location.href);
            if(pwb_params !== null && pwb_params !== ''){
                $('.pwb-filter-products input[type="checkbox"]').prop("checked", false);
                pwb_params.split(',').filter(function (el){
                    $('.pwb-filter-products input[type="checkbox"][value="'+el+'"]').prop("checked", true);
                })
            }
            $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );

            LA.core.initAll($document);
        });

        $document
            .on('click', '.btn-advanced-shop-filter', function(e){
                e.preventDefault();
                $body.toggleClass('open-advanced-shop-filter');
                $('.la-advanced-product-filters').stop().animate({
                    height: 'toggle'
                });
            })
            .on('click', '.la-advanced-product-filters .close-advanced-product-filters', function(e){
                e.preventDefault();
                $('.btn-advanced-shop-filter').trigger('click');
            })
            .on('click', target_to_init, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                LA.utils.eventManager.publish('LA:AjaxShopFilter', [$(this).attr('href'), $(this)]);
            })
            .on('click', target_to_init2, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                if($(this).closest('.widget_layered_nav').length){
                    $(this).parent().addClass('active');
                }
                else{
                    $(this).parent().addClass('active').siblings().removeClass('active');
                }

                var _url = $(this).attr('href'),
                    _preset_from_w = LA.utils.getUrlParameter('la_preset'),
                    _preset_from_e = LA.utils.getUrlParameter('la_preset', _url);

                if(!_preset_from_e && _preset_from_w){
                    _url = LA.utils.addQueryArg(_url, 'la_preset', _preset_from_w);
                }

                LA.utils.eventManager.publish('LA:AjaxShopFilter', [_url, $(this)]);
            })

            .on('click', target_to_init3, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                $(this).addClass('active').siblings().removeClass('active');
                var _url = $(this).attr('href'),
                    _preset_from_w = LA.utils.getUrlParameter('la_preset'),
                    _preset_from_e = LA.utils.getUrlParameter('la_preset', _url);

                if(!_preset_from_e && _preset_from_w){
                    _url = LA.utils.addQueryArg(_url, 'la_preset', _preset_from_w);
                }
                LA.utils.eventManager.publish('LA:AjaxShopFilter', [_url, $(this)]);
            })
            .on('click', '.woocommerce.widget_layered_nav_filters a', function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                LA.utils.eventManager.publish('LA:AjaxShopFilter', [$(this).attr('href'), $(this)]);
            })
            .on('submit', '.widget_price_filter form, .woocommerce-widget-layered-nav form', function(e){
                e.preventDefault();
                var $form = $(this),
                    url = $form.attr('action') + '?' + $form.serialize();
                $('.la-ajax-shop-loading').addClass('loading');
                LA.utils.eventManager.publish('LA:AjaxShopFilter', [url, $form]);
            })
            .on('change', '.woocommerce-widget-layered-nav form select', function(e){
                e.preventDefault();
                var slug = $( this ).val(),
                    _id = $(this).attr('class').split('dropdown_layered_nav_')[1];
                $( ':input[name="filter_'+_id+'"]' ).val( slug );

                // Submit form on change if standard dropdown.
                if ( ! $( this ).attr( 'multiple' ) ) {
                    $( this ).closest( 'form' ).submit();
                }
            })
            .on('change', '.widget_pwb_dropdown_widget .pwb-dropdown-widget', function(e){
                e.preventDefault();
                var $form = $(this),
                    url = $(this).val();
                $('.la-ajax-shop-loading').addClass('loading');
                LA.utils.eventManager.publish('LaStudio:AjaxShopFilter', [url, $form]);
            })
            .on('click', '.widget_pwb_filter_by_brand_widget .pwb-filter-products button', function (e){
                e.preventDefault();
                var $form = $(this).closest('.pwb-filter-products'),
                    _url = $form.data('cat-url'),
                    _params = [];
                $form.find('input[type="checkbox"]:checked').each(function (){
                    _params.push($(this).val());
                });
                if(_params.length > 0){
                    _url = LA.utils.addQueryArg(_url, 'pwb-brand-filter', _params.join(','));
                }
                $('.la-ajax-shop-loading').addClass('loading');
                LA.utils.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $form]);
            })
            .on('change', '.widget_pwb_filter_by_brand_widget .pwb-filter-products.pwb-hide-submit-btn input', function (e){
                e.preventDefault();
                var $form = $(this).closest('.pwb-filter-products'),
                    _url = $form.data('cat-url'),
                    _params = [];
                $form.find('input[type="checkbox"]:checked').each(function (){
                    _params.push($(this).val());
                });
                if(_params.length > 0){
                    _url = LA.utils.addQueryArg(_url, 'pwb-brand-filter', _params.join(','));
                }
                $('.la-ajax-shop-loading').addClass('loading');
                LA.utils.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $form]);
            });
        $('.widget_pwb_dropdown_widget .pwb-dropdown-widget').off('change');
        $('.widget_pwb_filter_by_brand_widget .pwb-filter-products button').off('click');
        $('.widget_pwb_filter_by_brand_widget .pwb-filter-products.pwb-hide-submit-btn input').off('change');
    };

    LA.core.Blog = function( $sidebar_inner ){

        $sidebar_inner = $sidebar_inner || $('.sidebar-inner');

        $('.widget_pages > ul, .widget_archive > ul, .widget_categories > ul, .widget_product_categories > ul, .widget_meta > ul', $sidebar_inner).addClass('menu').closest('.widget').addClass('accordion-menu');
        $('.widget_nav_menu', $sidebar_inner).closest('.widget').addClass('accordion-menu');
        $('.widget_categories > ul li.cat-parent,.widget_product_categories li.cat-parent', $sidebar_inner).addClass('mm-item-has-sub');

        $('.menu li > ul', $sidebar_inner).each(function(){
            var $ul = $(this);
            $ul.before('<span class="narrow"><i></i></span>');
        });

        $sidebar_inner.on('click','.accordion-menu li.menu-item-has-children > a,.menu li.mm-item-has-sub > a,.menu li > .narrow',function(e){
            e.preventDefault();
            var $parent = $(this).parent();
            if ($parent.hasClass('open')) {
                $parent.removeClass('open');
                $parent.find('>ul').stop().slideUp();
            }
            else {
                $parent.addClass('open');
                $parent.find('>ul').stop().slideDown();
                $parent.siblings().removeClass('open').find('>ul').stop().slideUp();
            }
        });
    };

    LA.core.SinglePostShare = function(){

    };

    LA.core.InstanceSearch = function(){
        var xhr = null,
            term = '',
            searchCache = {},
            $modal = $( '.searchform-fly-overlay' ),
            $form = $modal.find( 'form.search-form' ),
            $search = $form.find( 'input.search-field' ),
            $results = $modal.find( '.search-results' ),
            $button = $results.find( '.search-results-button' ),
            post_type = $modal.find( 'input[name=post_type]' ).val();


        var delaySearch = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $modal.on( 'keyup', '.search-field', function ( e ) {

            var valid = false;

            if ( typeof e.which === 'undefined' ) {
                valid = true;
            }
            else if ( typeof e.which === 'number' && e.which > 0 ) {
                valid = !e.ctrlKey && !e.metaKey && !e.altKey;
            }
            if ( !valid ) {
                return;
            }
            if ( xhr ) {
                xhr.abort();
            }
            delaySearch(function(){
                search( true );
            }, 400 );

        })
            .on( 'change', '.product-cats input', function () {
                if ( xhr ) {
                    xhr.abort();
                }
                search( false );
            })
            .on( 'click', '.search-reset', function () {
                if ( xhr ) {
                    xhr.abort();
                }
                $modal.addClass( 'reset' );
                $results.find( '.results-container, .view-more-results' ).slideUp( function () {
                    $modal.removeClass( 'searching searched found-products found-no-product invalid-length reset' );
                });
            } )
            .on( 'focusout', '.search-field', function () {
                if ( $(this).val().length < 2 ) {
                    $results.find( '.results-container, .view-more-results' ).slideUp( function () {
                        $modal.removeClass( 'searching searched found-products found-no-product invalid-length' );
                    });
                }
            });

        /**
         * Private function for searching products
         */
        function search( typing ) {
            var keyword = $search.val(),
                $category = $form.find( '.product-cats input:checked' ),
                category = $category.length ? $category.val() : '',
                key = keyword + '[' + category + ']';

            if ( term === keyword && typing ) {
                return;
            }

            term = keyword;

            if ( keyword.length < 2 ) {
                $modal.removeClass( 'searching found-products found-no-product' ).addClass( 'invalid-length' );
                return;
            }

            var url = $form.attr( 'action' ) + '?' + $form.serialize();

            $button.removeClass( 'fadeInUp' );
            $( '.view-more-results', $results ).slideUp( 10 );
            $modal.removeClass( 'found-products found-no-product' ).addClass( 'searching' );

            if ( key in searchCache ) {
                showResult( searchCache[key] );
            }
            else {
                xhr = $.get( url, function ( response ) {

                    var $content = $( '#site-content', response );

                    if ( 'product' === post_type ) {
                        var $products = $( '#la_shop_products .row ul.products', $content );

                        if ( $products.length ) {
                            $products.children( 'li:eq(7)' ).nextAll().remove();
                            // Cache
                            searchCache[key] = {
                                found: true,
                                items: $products,
                                url  : url
                            };
                        }
                        else {
                            // Cache
                            searchCache[key] = {
                                found: false,
                                text : $( '.woocommerce-info', $content ).text()
                            };
                        }
                    }
                    else {

                        var $posts = $( '#blog_content_container .main-search-loop .blog__item:lt(3)', $content );

                        if ( $posts.length ) {
                            $posts.addClass( 'col-md-4' );

                            searchCache[key] = {
                                found: true,
                                items: $( '<div class="posts" />' ).append( $posts ),
                                url  : url
                            };
                        }
                        else {
                            searchCache[key] = {
                                found: false,
                                text : $( '#blog_content_container article .entry-content', $content ).text()
                            };
                        }
                    }

                    showResult( searchCache[key] );

                    $modal.addClass( 'searched' );
                }, 'html' );
            }
        }

        /**
         * Private function for showing the search result
         *
         * @param result
         */
        function showResult( result ) {

            var extraClass = 'product' === post_type ? 'woocommerce' : 'la-post-grid';

            $modal.removeClass( 'searching' );

            if ( result.found ) {
                var grid = result.items.clone(),
                    items = grid.children();

                $modal.addClass( 'found-products' );

                $results.find( '.results-container' ).addClass( extraClass ).html( grid );

                LA.core.initAll($results);

                // Add animation class
                for ( var index = 0; index < items.length; index++ ) {
                    $( items[index] ).css( 'animation-delay', index * 100 + 'ms' );
                }

                items.addClass( 'fadeInUp animated' );

                $button.attr( 'href', result.url ).css( 'animation-delay', index * 100 + 'ms' ).addClass( 'fadeInUp animated' );

                $results.find( '.results-container, .view-more-results' ).slideDown( 300, function () {
                    $modal.removeClass( 'invalid-length' );
                } );
            }
            else {
                $modal.addClass( 'found-no-product' );

                $results.find( '.results-container' ).removeClass( extraClass ).html( $( '<div class="not-found text-center" />' ).text( result.text ) );
                $button.attr( 'href', '#' );

                $results.find( '.view-more-results' ).slideUp( 300 );
                $results.find( '.results-container' ).slideDown( 300, function () {
                    $modal.removeClass( 'invalid-length' );
                });
            }

            $modal.addClass( 'searched' );
        }
    };

    LA.utils.OpenNewsletterPopup = function( $popup, callback ){
        lightcase.start({
            href: '#',
            maxWidth: parseInt(la_theme_config.popup.max_width),
            maxHeight: parseInt(la_theme_config.popup.max_height),
            inline: {
                width : parseInt(la_theme_config.popup.max_width),
                height : parseInt(la_theme_config.popup.max_height)
            },
            onInit : {
                foo: function() {
                    $('body.lastudio-draven').addClass('open-newsletter-popup');
                }
            },
            onClose : {
                qux: function() {
                    if(typeof callback === 'function'){
                        callback();
                    }
                    $('body.lastudio-draven').removeClass('open-newsletter-popup');
                    $('.custom-lightcase-overlay').remove();
                    $('.custom-lighcase-btn-close').remove();
                }
            },
            onFinish: {
                injectContent: function () {
                    lightcase.get('content').append('<div class="custom-lightcase-overlay"></div>');
                    lightcase.get('contentInner').children().append($popup);
                    lightcase.get('contentInner').append('<a class="custom-lighcase-btn-close" href="#"><i class="dlicon ui-1_simple-remove"></i></a>');
                    $('.lightcase-icon-close').hide();
                    lightcase.resize();
                }
            }
        });
    }

    LA.component.NewsletterPopup = function(el){
        var $popup = $(el),
            disable_on_mobile = parseInt($popup.attr('data-show-mobile') || 0),
            p_delay = parseInt($popup.attr('data-delay') || 2000),
            backtime = parseInt($popup.attr('data-back-time') || 1),
            waitfortrigger = parseInt($popup.attr('data-waitfortrigger') || 0);

        $(document).on('click', '.btn-close-newsletter-popup', function(e){
            e.preventDefault();
            lightcase.close();
        });

        if(waitfortrigger == 1){
            $(document).on('click touchend', '.elm-trigger-open-newsletter', function(e){
                e.preventDefault();
                LA.utils.OpenNewsletterPopup($popup);
            })
        }

        return {
            init : function(){
                if(waitfortrigger != 1){
                    if($(window).width() < 767){
                        if(disable_on_mobile){
                            return;
                        }
                    }
                    try{
                        if(Cookies.get('draven_dont_display_popup') == 'yes'){
                            return;
                        }
                    }catch (ex){ console.log(ex); }

                    $window.on('load', function () {
                        setTimeout(function(){
                            LA.utils.OpenNewsletterPopup($popup, function(){
                                if($('.cbo-dont-show-popup', $popup).length && $('.cbo-dont-show-popup', $popup).is(':checked')){
                                    try {
                                        Cookies.set('draven_dont_display_popup', 'yes', { expires: backtime, path: '/' });
                                    } catch (ex){}
                                }
                            })
                        }, p_delay)
                    });
                }
            }
        }

    };

    $(function(){

        LA.core.SitePreload();
        LA.core.MegaMenu();
        LA.core.InstanceSearch();
        LA.core.initAll($document);
        LA.core.ElementAjaxClickEvent();
        LA.core.ElementClickEvent();

        LA.core.Blog();
        LA.core.SinglePostShare();
        LA.core.WooCommerce();
        LA.core.AjaxShopFilter();

    });

    $window.on('load', function(){
        $body.removeClass('site-loading').addClass('body-loaded');
        LA.core.HeaderSticky();
    });

})(jQuery);


/*
Header Builder FrontEnd
 */

(function ($) {
    "use strict";

    var $document = $(document),
        $htmlbody = $('html,body'),
        $body = $('body.draven-body');

    // Initialize global variable
    var LAHFB = {
        core        : {}
    };
    window.LAHFB = LAHFB;

    LAHFB.core.init = function(){

        var $header_builder = $('#lastudio-header-builder');

        // Navigation Current Menu
        $('.menu li.current-menu-item, .menu li.current_page_item, #side-nav li.current_page_item, .menu li.current-menu-ancestor, .menu li ul li ul li.current-menu-item , .hamburger-nav li.current-menu-item, .hamburger-nav li.current_page_item, .hamburger-nav li.current-menu-ancestor, .hamburger-nav li ul li ul li.current-menu-item, .full-menu li.current-menu-item, .full-menu li.current_page_item, .full-menu li.current-menu-ancestor, .full-menu li ul li ul li.current-menu-item ').addClass('current');
        $('.menu li ul li:has(ul)').addClass('submenu');


        // Social modal
        var header_social = $('.header-social-modal-wrap').html();
        $('.header-social-modal-wrap').remove();
        $(".main-slide-toggle").append(header_social);

        // Search modal Type 2
        var header_search_type2 = $('.header-search-modal-wrap').html();
        $('.header-search-modal-wrap').remove();
        $(".main-slide-toggle").append(header_search_type2);

        // Search Full
        var $header_search_typefull = $('.header-search-full-wrap').first();
        if($header_search_typefull.length){
            $('.searchform-fly > p').replaceWith($header_search_typefull.find('.searchform-fly-text'));
            $('.searchform-fly > .search-form').replaceWith($header_search_typefull.find('.search-form'));
            $('.header-search-full-wrap').remove();
            LA.core.InstanceSearch();
        }

        // Social dropdown
        $('.lahfb-social .js-social_trigger_dropdown').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.header-social-dropdown-wrap').fadeToggle('fast');
        });
        $('.header-social-dropdown-wrap a').on('click', function (e) {
            $('.header-social-dropdown-wrap').css({
                display: 'none'
            });
        });

        // Social Toggles
        $('.lahfb-social .js-social_trigger_slide').on('click', function (e) {
            e.preventDefault();
            if( $header_builder.find('.la-header-social').hasClass('opened') ) {
                $header_builder.find('.main-slide-toggle').slideUp('opened');
                $header_builder.find('.la-header-social').removeClass('opened');
            }
            else{
                $header_builder.find('.main-slide-toggle').slideDown(240);
                $header_builder.find('#header-search-modal').slideUp(240);
                $header_builder.find('#header-social-modal').slideDown(240);
                $header_builder.find('.la-header-social').addClass('opened');
                $header_builder.find('.la-header-search').removeClass('opened');
            }
        });

        $document.on('click', function (e) {
            if( $(e.target).hasClass('js-social_trigger_slide')){
                return;
            }
            if ($header_builder.find('.la-header-social').hasClass('opened')) {
                $header_builder.find('.main-slide-toggle').slideUp('opened');
                $header_builder.find('.la-header-social').removeClass('opened');
            }
        });

        // Search full

        $('.lahfb-cart > a').on('click', function (e) {
            if(!$(this).closest('.lahfb-cart').hasClass('force-display-on-mobile')){
                if($(window).width() > 767){
                    e.preventDefault();
                    $('body').toggleClass('open-cart-aside');
                }
            }
            else{
                e.preventDefault();
                $('body').toggleClass('open-cart-aside');
            }
        });

        $('.lahfb-search.lahfb-header-full > a').on('click', function (e) {
            e.preventDefault()
            $('body').addClass('open-search-form');
            setTimeout(function(){
                $('.searchform-fly .search-field').focus();
            }, 600);
        });

        // Search Toggles
        $('.lahfb-search .js-search_trigger_slide').on('click', function (e) {

            if ($header_builder.find('.la-header-search').hasClass('opened')) {
                $header_builder.find('.main-slide-toggle').slideUp('opened');
                $header_builder.find('.la-header-search').removeClass('opened');
            }
            else {
                $header_builder.find('.main-slide-toggle').slideDown(240);
                $header_builder.find('#header-social-modal').slideUp(240);
                $header_builder.find('#header-search-modal').slideDown(240);
                $header_builder.find('.la-header-search').addClass('opened');
                $header_builder.find('.la-header-social').removeClass('opened');
                $header_builder.find('#header-search-modal .search-field').focus();
            }
        });

        $document.on('click', function (e) {
            if( $(e.target).hasClass('js-search_trigger_slide') || $(e.target).closest('.js-search_trigger_slide').length ) {
                return;
            }
            if($('.lahfb-search .js-search_trigger_slide').length){
                if ($header_builder.find('.la-header-search').hasClass('opened')) {
                    $header_builder.find('.main-slide-toggle').slideUp('opened');
                    $header_builder.find('.la-header-search').removeClass('opened');
                }
            }
        });


        if ($.fn.niceSelect) {
            $('.la-polylang-switcher-dropdown select').niceSelect();
        }

        if ($.fn.superfish) {
            $('.lahfb-area:not(.lahfb-vertical) .lahfb-nav-wrap:not(.has-megamenu) ul.menu').superfish({
                delay: 300,
                hoverClass: 'la-menu-hover',
                animation: {
                    opacity: "show",
                    height: 'show'
                },
                animationOut: {
                    opacity: "hide",
                    height: 'hide'
                },
                easing: 'easeOutQuint',
                speed: 100,
                speedOut: 0,
                pathLevels: 2
            });
        }

        $('.lahfb-nav-wrap .menu li a').addClass('hcolorf');

        // Hamburger Menu
        var $hamurgerMenuWrapClone = $('.hamburger-type-toggle').find('.hamburger-menu-wrap');
        if ($hamurgerMenuWrapClone.length > 0) {
            $hamurgerMenuWrapClone.appendTo('body');
            $('.hamburger-type-toggle .la-hamuburger-bg').remove();
        }

        if ($('.hamburger-menu-wrap').hasClass('toggle-right')) {
            $('body').addClass('lahfb-body lahmb-right');
        }
        else if ($('.hamburger-menu-wrap').hasClass('toggle-left')) {
            $('body').addClass('lahfb-body lahmb-left');
        }

        //Hamburger Nicescroll
        $('.hamburger-menu-main').niceScroll({
            scrollbarid: 'lahfb-hamburger-scroll',
            cursorwidth: "5px",
            autohidemode: true
        });

        $('.btn-close-hamburger-menu').on('click', function (e) {
            e.preventDefault();
            $body.removeClass('is-open');
            $('.lahfb-hamburger-menu').removeClass('is-open');
            $('.hamburger-menu-wrap').removeClass('hm-open');
            $('.hamburger-menu-main').getNiceScroll().resize();
        });

        $('.hamburger-type-toggle a.lahfb-icon-element').on('click', function (e) {
            e.preventDefault();
            var $that = $(this),
                $_parent = $that.closest('.lahfb-hamburger-menu'),
                _cpt_id = $that.attr('data-id');

            if($_parent.hasClass('is-open')){
                $('.btn-close-hamburger-menu').trigger('click');
            }
            else{
                $_parent.addClass('is-open');
                $body.addClass('is-open');
                $body.find('.hamburger-menu-wrap.hamburger-menu-wrap-' + _cpt_id).addClass('hm-open');
                $('.hamburger-menu-main').getNiceScroll().resize();
            }
        });


        $('.hamburger-nav.toggle-menu').find('li').each(function () {
            var $list_item = $(this);

            if ($list_item.children('ul').length) {
                $list_item.children('a').append('<i class="fa fa-angle-down hamburger-nav-icon"></i>');
            }

            $('> a > .hamburger-nav-icon', $list_item).on('click', function (e) {
                e.preventDefault();
                var $that = $(this);
                if( $that.hasClass('active') ){
                    $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                    $('>ul', $list_item).stop().slideUp();
                }
                else{
                    $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                    $('>ul', $list_item).stop().slideDown(350, function () {
                        $('.hamburger-menu-main').getNiceScroll().resize();
                    });
                }
            })
        });

        //Full hamburger Menu
        $('.hamburger-type-full a.lahfb-icon-element').on('click', function (e) {
            e.preventDefault();
            var $that = $(this);
            if( $that.hasClass('open-button') ){
                $that.siblings('.la-hamburger-wrap').removeClass('open-menu');
                $that.removeClass('open-button').addClass('close-button');
            }
            else{
                $that.siblings('.la-hamburger-wrap').addClass('open-menu');
                $that.removeClass('close-button').addClass('open-button');
            }
        });

        $('.btn-close-hamburger-menu-full').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.la-hamburger-wrap').removeClass('open-menu').siblings('.lahfb-icon-element').removeClass('open-button').addClass('close-button');
        });

        $('.full-menu li > ul').each(function () {
            var $ul = $(this);
            $ul.prev('a').append('<i class="fa fa-angle-down hamburger-nav-icon"></i>');
        });

        $('.full-menu').on('click', 'li .hamburger-nav-icon', function (e) {
            e.preventDefault();
            var $that = $(this),
                $li_parent = $that.closest('li');

            if ($li_parent.hasClass('open')) {
                $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                $li_parent.removeClass('open');
                $li_parent.find('li').removeClass('open');
                $li_parent.find('ul').stop().slideUp();
                $li_parent.find('.hamburger-nav-icon').removeClass('active fa-angle-up').addClass('fa-angle-down');
            }
            else {
                $li_parent.addClass('open');
                $that.removeClass('fa-angle-down').addClass('active fa-angle-up');
                $li_parent.find('>ul').stop().slideDown();
                $li_parent.siblings().removeClass('open').find('ul').stop().slideUp();
                $li_parent.siblings().find('li').removeClass('open');
                $li_parent.siblings().find('.hamburger-nav-icon').removeClass('active fa-angle-up').addClass('fa-angle-down');
            }
        });

        $('.touchevents .full-menu').on('touchend', 'li .hamburger-nav-icon', function (e) {
            e.preventDefault();
            var $that = $(this),
                $li_parent = $that.closest('li');

            if ($li_parent.hasClass('open')) {
                $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                $li_parent.removeClass('open');
                $li_parent.find('li').removeClass('open');
                $li_parent.find('ul').stop().slideUp();
                $li_parent.find('.hamburger-nav-icon').removeClass('active fa-angle-up').addClass('fa-angle-down');
            }
            else {
                $li_parent.addClass('open');
                $that.removeClass('fa-angle-down').addClass('active fa-angle-up');
                $li_parent.find('>ul').stop().slideDown();
                $li_parent.siblings().removeClass('open').find('ul').stop().slideUp();
                $li_parent.siblings().find('li').removeClass('open');
                $li_parent.siblings().find('.hamburger-nav-icon').removeClass('active fa-angle-up').addClass('fa-angle-down');
            }
        });

        // Toggle search form
        $('.lahfb-search .js-search_trigger_toggle').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.lahfb-search-form-box').toggleClass('show-sbox');
        });

        $document.on('click', function (e) {
            if( $(e.target).hasClass('js-search_trigger_toggle') || $(e.target).closest('.js-search_trigger_toggle').length){
                return;
            }
            if( $('.lahfb-search-form-box').length ) {
                if( $(e.target).closest('.lahfb-search-form-box').length == 0){
                    $('.lahfb-search-form-box').removeClass('show-sbox');
                }
            }
        });

        // Responsive Menu
        $('.lahfb-responsive-menu-icon-wrap').on('click', function (e) {
            e.preventDefault();
            var $toggleMenuIcon = $(this),
                uniqid = $toggleMenuIcon.data('uniqid'),
                $responsiveMenu = $('.lahfb-responsive-menu-wrap[data-uniqid="' + uniqid + '"]'),
                $closeIcon = $responsiveMenu.find('.close-responsive-nav');

            if ($responsiveMenu.hasClass('open') === false) {
                $toggleMenuIcon.addClass('open-icon-wrap').children().addClass('open');
                $closeIcon.addClass('open-icon-wrap').children().addClass('open');
                $responsiveMenu.animate({'left': 0}, 350).addClass('open');
            } else {
                $toggleMenuIcon.removeClass('open-icon-wrap').children().removeClass('open');
                $closeIcon.removeClass('open-icon-wrap').children().removeClass('open');
                $responsiveMenu.animate({'left': -280}, 350).removeClass('open');
            }
        });

        $('.lahfb-responsive-menu-wrap').each(function () {
            var $this = $(this),
                uniqid = $this.data('uniqid'),
                $responsiveMenu = $this.clone(),
                $closeIcon = $responsiveMenu.find('.close-responsive-nav'),
                $toggleMenuIcon = $('.lahfb-responsive-menu-icon-wrap[data-uniqid="' + uniqid + '"]');

            // append responsive menu to lastudio header builder wrap
            $this.remove();
            $('#lastudio-header-builder').append($responsiveMenu);

            // add arrow down to parent menus
            $responsiveMenu.find('li').each(function () {
                var $list_item = $(this);

                if ($list_item.children('ul').length) {
                    $list_item.children('a').append('<i class="fa fa-angle-down respo-nav-icon"></i>');
                }

                $('> a > .respo-nav-icon', $list_item).on('click', function (e) {
                    e.preventDefault();
                    var $that = $(this);
                    if( $that.hasClass('active') ){
                        $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                        $('>ul', $list_item).stop().slideUp(350);
                    }
                    else{
                        $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                        $('>ul', $list_item).stop().slideDown(350);
                    }
                });
            });

            // close responsive menu
            $closeIcon.on('click', function () {
                if ($toggleMenuIcon.hasClass('open-icon-wrap')) {
                    $toggleMenuIcon.removeClass('open-icon-wrap').children().removeClass('open');
                    $closeIcon.removeClass('open-icon-wrap').children().removeClass('open');
                }
                else {
                    $toggleMenuIcon.addClass('open-icon-wrap').children().addClass('open');
                    $closeIcon.addClass('open-icon-wrap').children().addClass('open');
                }

                if ($responsiveMenu.hasClass('open') === true) {
                    $responsiveMenu.animate({'left': -280}, 350).removeClass('open');
                }
            });

            $responsiveMenu.on('click', 'li.menu-item:not(.menu-item-has-children) > a', function (e) {
                $toggleMenuIcon.removeClass('open-icon-wrap').children().removeClass('open');
                $closeIcon.removeClass('open-icon-wrap').children().removeClass('open');
                $responsiveMenu.animate({'left': -280}, 0).removeClass('open');
            });
        });

        // Login Dropdown

        $('.lahfb-login .js-login_trigger_dropdown').each(function () {
            var $this = $(this);
            if($this.siblings('.lahfb-modal-login').length == 0){
                $('.lahfb-modal-login.la-element-dropdown').first().clone().appendTo($this.parent());
            }
        });

        $('.lahfb-login .js-login_trigger_dropdown').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.lahfb-modal-login').fadeToggle('fast');
        });


        // Contact Dropdown
        $('.lahfb-contact .js-contact_trigger_dropdown').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.la-contact-form').fadeToggle('fast');
        });
        $document.on('click', function (e) {
            if( $(e.target).hasClass('js-contact_trigger_dropdown')){
                return;
            }
            if( $('.la-contact-form.la-element-dropdown').length ) {
                if($(e.target).closest('.la-contact-form.la-element-dropdown').length == 0){
                    $('.la-contact-form.la-element-dropdown').css({
                        'display': 'none'
                    })
                }
            }
        });


        // Icon Menu Dropdown

        $('.lahfb-icon-menu .js-icon_menu_trigger').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.lahfb-icon-menu-content').fadeToggle('fast');
        });

        $document.on('click', function (e) {
            if( $(e.target).hasClass('js-icon_menu_trigger')){
                return;
            }
            if( $('.la-element-dropdown.lahfb-icon-menu-content').length ) {
                if($(e.target).closest('.la-element-dropdown.lahfb-icon-menu-content').length == 0){
                    $('.la-element-dropdown.lahfb-icon-menu-content').css({
                        'display': 'none'
                    })
                }
            }
        });

        // Wishlist Dropdown
        $('.lahfb-wishlist').each(function (index, el) {
            $(this).find('#la-wishlist-icon').on('click', function (event) {
                $(this).siblings('.la-header-wishlist-wrap').fadeToggle('fast', function () {
                    if ($(".la-header-wishlist-wrap").is(":visible")) {
                        $(document).on('click', function (e) {
                            var target = $(e.target);
                            if (target.parents('.lahfb-wishlist').length)
                                return;
                            $(".la-header-wishlist-wrap").css({
                                display: 'none'
                            });
                        });
                    }
                });
            });
        });

        $('.la-header-wishlist-content-wrap').find('.la-wishlist-total').addClass('colorf');


        /* Profile Socials */
        $('.lahfb-profile-socials-text').hover(function () {
            $(this).closest('.lahfb-profile-socials-wrap').find('.lahfb-profile-socials-icons').removeClass('profile-socials-hide').addClass('profile-socials-show');
        }, function () {
            $(this).closest('.lahfb-profile-socials-wrap').find('.lahfb-profile-socials-icons').removeClass('profile-socials-show').addClass('profile-socials-hide');
        });


        /* Vertical Header */

        // Toggle Vertical

        $('.lahfb-vertical-toggle-wrap .vertical-toggle-icon').on('click', function (e) {
            e.preventDefault();
            $body.toggleClass('open-lahfb-vertical');
        });

        // Vertical Nicescroll

        $('.lahfb-vertical .lahfb-content-wrap').niceScroll({
            scrollbarid: 'lahfb-vertical-menu-scroll',
            cursorwidth: "5px",
            autohidemode: true
        });

        // Vertical Menu
        $('.lahfb-vertical .lahfb-nav-wrap').removeClass('has-megamenu has-parent-arrow');
        $('.lahfb-vertical .lahfb-nav-wrap li.mega').removeClass('mega');
        $('.lahfb-vertical .lahfb-nav-wrap li.mm-popup-wide').removeClass('mm-popup-wide');
        $('.lahfb-vertical .menu li').each(function () {
            var $list_item = $(this);

            if ($list_item.children('ul').length) {
                $list_item.children('a').removeClass('sf-with-ul').append('<i class="fa fa-angle-down lahfb-vertical-nav-icon"></i>');
            }

            $('> a > .lahfb-vertical-nav-icon', $list_item).on('click', function (e) {
                e.preventDefault();
                var $that = $(this);
                if( $that.hasClass('active') ){
                    $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                    $('>ul', $list_item).stop().slideUp();
                }
                else{
                    $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                    $('>ul', $list_item).stop().slideDown(350, function () {
                        $('.lahfb-vertical .lahfb-content-wrap').getNiceScroll().resize();
                    });
                }
            });

        });


        $document.on('keyup', function (e) {
            if(e.keyCode == 27){
                $body.removeClass('is-open open-search-form open-cart-aside');
                $('.hamburger-menu-wrap').removeClass('hm-open');
                $('.lahfb-hamburger-menu.hamburger-type-toggle').removeClass('is-open');
                $('.lahfb-hamburger-menu.hamburger-type-full .hamburger-op-icon').removeClass('open-button').addClass('close-button');
                $('.lahfb-hamburger-menu.hamburger-type-full .la-hamburger-wrap').removeClass('open-menu');
            }
        });

    };

    LAHFB.core.reloadAllEvents = function(){
        LAHFB.core.init();
        LA.core.HeaderSticky();
        LA.core.MegaMenu();
        $(window).trigger('scroll');
        console.log('ok -- reloadAllEvents!');
    };

    $(function () {
        LAHFB.core.init();
    });

})(jQuery);

/*
 For Demo
 */

(function($) {
    "use strict";

    $(function(){

        $(document)
            .on('click', 'html.touchevents .product_item--thumbnail-holder .woocommerce-loop-product__link', function(e){
                if( $(window).width() < 992) {
                    if (!$(this).hasClass('go-go')) {
                        e.preventDefault();
                        $('.product_item--thumbnail-holder .woocommerce-loop-product__link').removeClass('go-go');
                        $(this).addClass('go-go');
                    }
                }
            })
            .on('click', 'html.touchevents .header_component--dropdown-menu > a', function(e){
                $(this).closest('.la_compt_iem').toggleClass('active');
            })
            .on('touchstart', 'html.touchevents .site-main, html.touchevents .site-footer', function(e){
                $('html.touchevents .header_component--dropdown-menu').removeClass('active');
                if($(e.target).closest('.wc-ordering').length == 0){
                    $('.wc-toolbar .wc-ordering').removeClass('active');
                }
            })
            .on('touchstart', 'html.touchevents .wc-toolbar .wc-ordering', function(e){
                $('.wc-toolbar .wc-ordering').toggleClass('active');
            });

        $(window).on('load', function () {
            $('.slick-slider').on('beforeChange afterChange', function( event, slick, currentSlide, nextSlide ){
                $('body').trigger( 'jetpack-lazy-images-load' );
                LA.utils.LazyLoad($('.la-lazyload-image'), {rootMargin: '0px'}).observe();
            })
        });
    });


})(jQuery);

(function($) {
    "use strict";


    $(function(){

        /* ------------------------------------- */
        /*   Image animation
        /* ------------------------------------- */
        VanillaTilt.init(document.querySelectorAll(".img-perspective"), {
            max:   12,
            speed: 1000,
            easing: "cubic-bezier(.03,.98,.52,.99)",
            transition: false,
            perspective: 1000,
            scale:   1
        });

        $( 'body' )
            .on( 'init', '.more_demo_aside .la-tabs-wrapper', function() {
                $( '.more_demo_aside .la-tab' ).hide();

                var hash  = window.location.hash;
                var url   = window.location.href;
                var $tabs = $( this ).find( '.la-tabs' ).first();
                $tabs.find( 'li:first a' ).click();
            } )
            .on( 'click', '.more_demo_aside .la-tabs li a', function( e ) {
                e.preventDefault();
                var $tab          = $( this );
                var $tabs_wrapper = $tab.closest( '.la-tabs-wrapper' );
                var $tabs         = $tabs_wrapper.find( '.la-tabs' );

                $tabs.find( 'li' ).removeClass( 'active' );
                $tabs_wrapper.find( '.la-tab' ).hide();

                $tab.closest( 'li' ).addClass( 'active' );
                $tabs_wrapper.find( $tab.attr( 'href' ) ).show();
            });

        $( '.more_demo_aside .la-tabs-wrapper' ).trigger( 'init' );

        $(document).on('click', '.more_demo_aside button', function(e){
            $('body').toggleClass('open-aside-demo');
        });

        $(window).on('load', function () {
            if(location.hash != '' && $(location.hash).length){
                $('html,body').animate({
                    scrollTop: $(location.hash).offset().top - 150
                }, 800);
            }
        })

    });

})(jQuery);