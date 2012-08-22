
// Key Filter
(function (h) { var f = { pint: /[\d]/, "int": /[\d\-]/, pnum: /[\d\.]/, money: /[\d\.\s,]/, num: /[\d\-\.]/, hex: /[0-9a-f]/i, email: /[a-z0-9_\.\-@]/i, alpha: /[a-z_]/i, alphanum: /[a-z0-9_]/i }; var c = { TAB: 9, RETURN: 13, ESC: 27, BACKSPACE: 8, DELETE: 46 }; var a = { 63234: 37, 63235: 39, 63232: 38, 63233: 40, 63276: 33, 63277: 34, 63272: 46, 63273: 36, 63275: 35 }; var e = function (j) { var i = j.keyCode; i = h.browser.safari ? (a[i] || i) : i; return (i >= 33 && i <= 40) || i == c.RETURN || i == c.TAB || i == c.ESC }; var d = function (j) { var i = j.keyCode; var l = j.charCode; return i == 9 || i == 13 || (i == 40 && (!h.browser.opera || !j.shiftKey)) || i == 27 || i == 16 || i == 17 || (i >= 18 && i <= 20) || (h.browser.opera && !j.shiftKey && (i == 8 || (i >= 33 && i <= 35) || (i >= 36 && i <= 39) || (i >= 44 && i <= 45))) }; var b = function (j) { var i = j.keyCode || j.charCode; return h.browser.safari ? (a[i] || i) : i }; var g = function (i) { return i.charCode || i.keyCode || i.which }; h.fn.keyfilter = function (i) { return this.keypress(function (m) { if (m.ctrlKey || m.altKey) { return } var j = b(m); if (h.browser.mozilla && (e(m) || j == c.BACKSPACE || (j == c.DELETE && m.charCode == 0))) { return } var o = g(m), n = String.fromCharCode(o), l = true; if (!h.browser.mozilla && (d(m) || !n)) { return } if (h.isFunction(i)) { l = i.call(this, n) } else { l = i.test(n) } if (!l) { m.preventDefault() } }) }; h.extend(h.fn.keyfilter, { defaults: { masks: f }, version: 1.7 }); h(document).ready(function () { var i = h("input[class*=mask],textarea[class*=mask]"); for (var j in h.fn.keyfilter.defaults.masks) { i.filter(".mask-" + j).keyfilter(h.fn.keyfilter.defaults.masks[j]) } }) })(jQuery);


//Watermark v3.1.3 (March 22, 2011) plugin for jQuery
(function (a, h, y) { var w = "function", v = "password", j = "maxLength", n = "type", b = "", c = true, u = "placeholder", i = false, t = "watermark", g = t, f = "watermarkClass", q = "watermarkFocus", l = "watermarkSubmit", o = "watermarkMaxLength", e = "watermarkPassword", d = "watermarkText", k = /\r/g, s = "input:data(" + g + "),textarea:data(" + g + ")", m = "input:text,input:password,input[type=search],input:not([type]),textarea", p = ["Page_ClientValidate"], r = i, x = u in document.createElement("input"); a.watermark = a.watermark || { version: "3.1.3", runOnce: c, options: { className: t, useNative: c, hideBeforeUnload: c }, hide: function (b) { a(b).filter(s).each(function () { a.watermark._hide(a(this)) }) }, _hide: function (a, r) { var p = a[0], q = (p.value || b).replace(k, b), l = a.data(d) || b, m = a.data(o) || 0, i = a.data(f); if (l.length && q == l) { p.value = b; if (a.data(e)) if ((a.attr(n) || b) === "text") { var g = a.data(e) || [], c = a.parent() || []; if (g.length && c.length) { c[0].removeChild(a[0]); c[0].appendChild(g[0]); a = g } } if (m) { a.attr(j, m); a.removeData(o) } if (r) { a.attr("autocomplete", "off"); h.setTimeout(function () { a.select() }, 1) } } i && a.removeClass(i) }, show: function (b) { a(b).filter(s).each(function () { a.watermark._show(a(this)) }) }, _show: function (g) { var p = g[0], u = (p.value || b).replace(k, b), h = g.data(d) || b, s = g.attr(n) || b, t = g.data(f); if ((u.length == 0 || u == h) && !g.data(q)) { r = c; if (g.data(e)) if (s === v) { var m = g.data(e) || [], l = g.parent() || []; if (m.length && l.length) { l[0].removeChild(g[0]); l[0].appendChild(m[0]); g = m; g.attr(j, h.length); p = g[0] } } if (s === "text" || s === "search") { var i = g.attr(j) || 0; if (i > 0 && h.length > i) { g.data(o, i); g.attr(j, h.length) } } t && g.addClass(t); p.value = h } else a.watermark._hide(g) }, hideAll: function () { if (r) { a.watermark.hide(m); r = i } }, showAll: function () { a.watermark.show(m) } }; a.fn.watermark = a.fn.watermark || function (p, o) { var t = "string"; if (!this.length) return this; var s = i, r = typeof p === t; if (r) p = p.replace(k, b); if (typeof o === "object") { s = typeof o.className === t; o = a.extend({}, a.watermark.options, o) } else if (typeof o === t) { s = c; o = a.extend({}, a.watermark.options, { className: o }) } else o = a.watermark.options; if (typeof o.useNative !== w) o.useNative = o.useNative ? function () { return c } : function () { return i }; return this.each(function () { var B = "dragleave", A = "dragenter", z = this, i = a(z); if (!i.is(m)) return; if (i.data(g)) { if (r || s) { a.watermark._hide(i); r && i.data(d, p); s && i.data(f, o.className) } } else { if (x && o.useNative.call(z, i) && (i.attr("tagName") || b) !== "TEXTAREA") { r && i.attr(u, p); return } i.data(d, r ? p : b); i.data(f, o.className); i.data(g, 1); if ((i.attr(n) || b) === v) { var C = i.wrap("<span>").parent(), t = a(C.html().replace(/type=["']?password["']?/i, 'type="text"')); t.data(d, i.data(d)); t.data(f, i.data(f)); t.data(g, 1); t.attr(j, p.length); t.focus(function () { a.watermark._hide(t, c) }).bind(A, function () { a.watermark._hide(t) }).bind("dragend", function () { h.setTimeout(function () { t.blur() }, 1) }); i.blur(function () { a.watermark._show(i) }).bind(B, function () { a.watermark._show(i) }); t.data(e, i); i.data(e, t) } else i.focus(function () { i.data(q, 1); a.watermark._hide(i, c) }).blur(function () { i.data(q, 0); a.watermark._show(i) }).bind(A, function () { a.watermark._hide(i) }).bind(B, function () { a.watermark._show(i) }).bind("dragend", function () { h.setTimeout(function () { a.watermark._show(i) }, 1) }).bind("drop", function (e) { var c = i[0], a = e.originalEvent.dataTransfer.getData("Text"); if ((c.value || b).replace(k, b).replace(a, b) === i.data(d)) c.value = a; i.focus() }); if (z.form) { var w = z.form, y = a(w); if (!y.data(l)) { y.submit(a.watermark.hideAll); if (w.submit) { y.data(l, w.submit); w.submit = function (c, b) { return function () { var d = b.data(l); a.watermark.hideAll(); if (d.apply) d.apply(c, Array.prototype.slice.call(arguments)); else d() } } (w, y) } else { y.data(l, 1); w.submit = function (b) { return function () { a.watermark.hideAll(); delete b.submit; b.submit() } } (w) } } } } a.watermark._show(i) }) }; if (a.watermark.runOnce) { a.watermark.runOnce = i; a.extend(a.expr[":"], { data: function (c, d, b) { return !!a.data(c, b[3]) } }); (function (c) { a.fn.val = function () { var e = this; if (!e.length) return arguments.length ? e : y; if (!arguments.length) if (e.data(g)) { var f = (e[0].value || b).replace(k, b); return f === (e.data(d) || b) ? b : f } else return c.apply(e, arguments); else { c.apply(e, arguments); a.watermark.show(e); return e } } })(a.fn.val); p.length && a(function () { for (var b, c, d = p.length - 1; d >= 0; d--) { b = p[d]; c = h[b]; if (typeof c === w) h[b] = function (b) { return function () { a.watermark.hideAll(); return b.apply(null, Array.prototype.slice.call(arguments)) } } (c) } }); a(h).bind("beforeunload", function () { a.watermark.options.hideBeforeUnload && a.watermark.hideAll() }) } })(jQuery, window);

//---Redirect To SessionFailed----
function RedirectSessionFailed() {
    window.location = "/account/login.html?sessionfailed=true";         
}

//---Overlay----
function CloseOverlay() {
    $("div.pic_loader").hide();
    $("div.overlay").remove();
}

function SetOverlay(exitonclick, func) {    
    $('body').append("<div class='pic_loader'><div class='loaderIcon'></div></div>");

    $('body').append("<div class='overlay'></div>");
    $div_loader = $("div.pic_loader");
    $div_overlay = $("div.overlay");
    $div_overlay.height($(document).height());
    $div_overlay.width($(document).width());
    $div_loader.css("top", ($(window).height() / 2));
    $div_loader.css("left", ($(document).width() / 2));
    $div_loader.show();

    if (exitonclick) {
        $div_overlay.click(function () {
            $(this).remove();
            $div_loader.hide();
        });
    }

    $("div.overlay").show().fadeTo(0, 0.8);
    window.setTimeout(function () {
        try { func } catch (e) { }        
    }, 1);    
}

//---Langpanel----
function langpanel() {$("div.language").hover(
  function() {$(this).find("div.languages").show();},
  function() {$(this).find("div.languages").hide();}
);}

//---AjaxQuery----
function AjaxQuery(url, data, succesFunc, errorFunc) {
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        dataFilter: function (data) {           
            return data;
            //if (typeof (JSON) !== 'undefined' &&
            //typeof (JSON.parse) === 'function')
            //  return JSON.parse(data);
            //else return eval('(' + data + ')');
        },
        success: succesFunc,
        error: errorFunc
    });
}

//---Preload-----
(function($) {var cache = [];   $.preLoadImages = function() {var args_len = arguments.length;
for (var i = args_len; i--; ) { var cacheImage = document.createElement('img'); cacheImage.src = arguments[i]; cache.push(cacheImage); } 
}
})(jQuery)
function preloadimg() {
    jQuery.preLoadImages("/img/plain-but-bg-green.png", "/img/plain-but-bg-over-grey.png", "/img/plain-but-bg-over.png", "/img/plain-but-bg-over-blue.png", "/img/plain-but-bg-over-green.png", "/img/plain-but-bg-over-orange.png", "/img/plain-but-bg-over-red.png");
}



//---buttonClick-----
function buttonClick() {
    var div = $('div.input-button-block');
    var btn = div.find('input[type=submit]')
    var func = btn.attr('onclick');btn.attr('onclick', "");
    func = func.toString(); func = func.replace("function onclick(event) {", "").replace("}", "").replace("javascript:", "").replace("false));", "true));");
    if (typeof (func) == "undefined") { div.click(function() { btn.click(); }); } else { div.attr('onclick',func); }        
}


//---HideSpans----
function HideSpans(spans) { for (var i = 0; i < spans.length; i++) { var span = spans[i]; if (span.style.display == 'inline' || span.style.display == '') { span.style.display = 'none'; } if (span.style.visibility == 'visible') { span.style.visibility = 'hidden'; } } }

//---IsNumeric----
function IsNumeric(strString) {
    try { var dec = parseFloat(strString); if (isNaN(dec)) { return false; } if (dec == 0) { return false; } return true; }
    catch (e) { return false; }
}

//---cycle----
(function(D) { var A = "Lite-1.0"; D.fn.cycle = function(E) { return this.each(function() { E = E || {}; if (this.cycleTimeout) { clearTimeout(this.cycleTimeout) } this.cycleTimeout = 0; this.cyclePause = 0; var I = D(this); var J = E.slideExpr ? D(E.slideExpr, this) : I.children(); var G = J.get(); if (G.length < 2) { if (window.console && window.console.log) { window.console.log("terminating; too few slides: " + G.length) } return } var H = D.extend({}, D.fn.cycle.defaults, E || {}, D.metadata ? I.metadata() : D.meta ? I.data() : {}); H.before = H.before ? [H.before] : []; H.after = H.after ? [H.after] : []; H.after.unshift(function() { H.busy = 0 }); var F = this.className; H.width = parseInt((F.match(/w:(\d+)/) || [])[1]) || H.width; H.height = parseInt((F.match(/h:(\d+)/) || [])[1]) || H.height; H.timeout = parseInt((F.match(/t:(\d+)/) || [])[1]) || H.timeout; if (I.css("position") == "static") { I.css("position", "relative") } if (H.width) { I.width(H.width) } if (H.height && H.height != "auto") { I.height(H.height) } var K = 0; J.css({ position: "absolute", top: 0, left: 0 }).hide().each(function(M) { D(this).css("z-index", G.length - M) }); D(G[K]).css("opacity", 1).show(); if (D.browser.msie) { G[K].style.removeAttribute("filter") } if (H.fit && H.width) { J.width(H.width) } if (H.fit && H.height && H.height != "auto") { J.height(H.height) } if (H.pause) { I.hover(function() { this.cyclePause = 1 }, function() { this.cyclePause = 0 }) } D.fn.cycle.transitions.fade(I, J, H); J.each(function() { var M = D(this); this.cycleH = (H.fit && H.height) ? H.height : M.height(); this.cycleW = (H.fit && H.width) ? H.width : M.width() }); J.not(":eq(" + K + ")").css({ opacity: 0 }); if (H.cssFirst) { D(J[K]).css(H.cssFirst) } if (H.timeout) { if (H.speed.constructor == String) { H.speed = { slow: 600, fast: 200}[H.speed] || 400 } if (!H.sync) { H.speed = H.speed / 2 } while ((H.timeout - H.speed) < 250) { H.timeout += H.speed } } H.speedIn = H.speed; H.speedOut = H.speed; H.slideCount = G.length; H.currSlide = K; H.nextSlide = 1; var L = J[K]; if (H.before.length) { H.before[0].apply(L, [L, L, H, true]) } if (H.after.length > 1) { H.after[1].apply(L, [L, L, H, true]) } if (H.click && !H.next) { H.next = H.click } if (H.next) { D(H.next).bind("click", function() { return C(G, H, H.rev ? -1 : 1) }) } if (H.prev) { D(H.prev).bind("click", function() { return C(G, H, H.rev ? 1 : -1) }) } if (H.timeout) { this.cycleTimeout = setTimeout(function() { B(G, H, 0, !H.rev) }, H.timeout + (H.delay || 0)) } }) }; function B(J, E, I, K) { if (E.busy) { return } var H = J[0].parentNode, M = J[E.currSlide], L = J[E.nextSlide]; if (H.cycleTimeout === 0 && !I) { return } if (I || !H.cyclePause) { if (E.before.length) { D.each(E.before, function(N, O) { O.apply(L, [M, L, E, K]) }) } var F = function() { if (D.browser.msie) { this.style.removeAttribute("filter") } D.each(E.after, function(N, O) { O.apply(L, [M, L, E, K]) }) }; if (E.nextSlide != E.currSlide) { E.busy = 1; D.fn.cycle.custom(M, L, E, F) } var G = (E.nextSlide + 1) == J.length; E.nextSlide = G ? 0 : E.nextSlide + 1; E.currSlide = G ? J.length - 1 : E.nextSlide - 1 } if (E.timeout) { H.cycleTimeout = setTimeout(function() { B(J, E, 0, !E.rev) }, E.timeout) } } function C(E, F, I) { var H = E[0].parentNode, G = H.cycleTimeout; if (G) { clearTimeout(G); H.cycleTimeout = 0 } F.nextSlide = F.currSlide + I; if (F.nextSlide < 0) { F.nextSlide = E.length - 1 } else { if (F.nextSlide >= E.length) { F.nextSlide = 0 } } B(E, F, 1, I >= 0); return false } D.fn.cycle.custom = function(K, H, I, E) { var J = D(K), G = D(H); G.css({ opacity: 0 }); var F = function() { G.animate({ opacity: 1 }, I.speedIn, I.easeIn, E) }; J.animate({ opacity: 0 }, I.speedOut, I.easeOut, function() { J.css({ display: "none" }); if (!I.sync) { F() } }); if (I.sync) { F() } }; D.fn.cycle.transitions = { fade: function(F, G, E) { G.not(":eq(0)").css("opacity", 0); E.before.push(function() { D(this).show() }) } }; D.fn.cycle.ver = function() { return A }; D.fn.cycle.defaults = { timeout: 4000, speed: 1000, next: null, prev: null, before: null, after: null, height: "auto", sync: 1, fit: 0, pause: 0, delay: 0, slideExpr: null} })(jQuery)

//---market items
function GetItems() {
    var url = "/WebService/OkPayWebService.asmx/GetTopItems";
    var data = "{}";
    $('#wrap div.marketitems').remove();
    $('div.marketitem_top div.herror').hide();
    $('div.marketitem_top div.contractee-roller').show();
    AjaxQuery(url, data, GetItem_Success, GetItem_Error);
}
function GetItem_Error(xhr, status, error) {
    //$('div.marketitem_top div.contractee-roller').hide();
    //$('div.marketitem_top div.herror').show();
}
function GetItem_Success(msg) {
    $('div.marketitem_top div.contractee-roller').hide();
    if (msg.d != "") {
        $('div.marketitem_top').append(msg.d);
        try {
            SetDraggForMarketPlace();
        }
        catch (e) { }
    }
    else {
        $('div.marketitem_top div.herror').show();
    }
}



//---Deposit Slide

//---Deposit Slide
var slidingDepositType = false;
function DepositType_Slide() {
    if (slidingDepositType) { return; }
    slidingDepositType = true;
    var n = $("div.deposit_line>div.text>div.inner>div.open").length;
    if (n == 0) {
        $(this).parent().find("div.hidden").addClass('open');
        $(this).parent().find("div.hidden").slideToggle("normal", function () {
            slidingDepositType = false;
        });
    }
    else {
        var lnk = $(this);
        $("div.deposit_line>div.text>div.inner>div.open").slideToggle("normal", function () {
            $(lnk).parent().find("div.hidden").addClass('open');
            $(lnk).parent().find("div.hidden").slideToggle("normal", function () {
                slidingDepositType = false;
            });
        });
        $("div.deposit_line>div.text>div.inner>div.hidden").removeClass("open");
    }
}

var slidingDepositCurrency = false;
function DepositCurrency_Slide() {
    if (slidingDepositCurrency) { return; }
    slidingDepositCurrency = true;
    var n = $("div.currency_line>div.text>div.open").length;   
    if (n == 0) {
        var dvCurHidden = $(this).parents("div.currency_line").find("div.currency_hidden");
        dvCurHidden.addClass('open');
        if ($.browser.msie) {
            dvCurHidden.show();
            slidingDepositCurrency = false;
        }
        else {
            dvCurHidden.slideToggle("normal", function () {
                slidingDepositCurrency = false;
            });
        }
        $(this).parents("div.currency_line").find("input[type=radio]").attr("checked", true);
    }
    else {     
        var lnk = $(this);

        if ($.browser.msie) {
            $("div.currency_line>div.text>div.open").hide();
            $("div.currency_line>div.text>div.open").removeClass("open");
            $(lnk).parents("div.currency_line").find("div.currency_hidden").addClass('open');
            $(lnk).parents("div.currency_line").find("div.currency_hidden").show();
            $(lnk).parents("div.currency_line").find("input[type=radio]").attr("checked", true);
            slidingDepositCurrency = false;
        }
        else {
            $("div.currency_line>div.text>div.open").slideToggle("normal", function () {
                $(lnk).parents("div.currency_line").find("div.currency_hidden").addClass('open');
                $(lnk).parents("div.currency_line").find("div.currency_hidden").slideToggle("normal", function () {
                    slidingDepositCurrency = false;
                });
            });
            $(this).parents("div.currency_line").find("input[type=radio]").attr("checked", true);
            $("div.currency_line>div.text>div.currency_hidden").removeClass("open");
        }
      
    }
}



//jCarouselLite
(function ($) { $.fn.jCarouselLite = function (o) { o = $.extend({ btnPrev: null, btnNext: null, btnGo: null, mouseWheel: false, auto: null, speed: 200, easing: null, vertical: false, circular: true, visible: 3, start: 0, scroll: 1, beforeStart: null, afterEnd: null }, o || {}); return this.each(function () { var b = false, animCss = o.vertical ? "top" : "left", sizeCss = o.vertical ? "height" : "width"; var c = $(this), ul = $("ul", c), tLi = $("li", ul), tl = tLi.size(), v = o.visible; if (o.circular) { ul.prepend(tLi.slice(tl - v - 1 + 1).clone()).append(tLi.slice(0, v).clone()); o.start += v } var f = $("li", ul), itemLength = f.size(), curr = o.start; c.css("visibility", "visible"); f.css({ overflow: "hidden", float: o.vertical ? "none" : "left" }); ul.css({ margin: "0", padding: "0", position: "relative", "list-style-type": "none", "z-index": "1" }); c.css({ overflow: "hidden", position: "relative", "z-index": "2", left: "0px" }); var g = o.vertical ? height(f) : width(f); var h = g * itemLength; var j = g * v; f.css({ width: f.width(), height: f.height() }); ul.css(sizeCss, h + "px").css(animCss, -(curr * g)); c.css(sizeCss, j + "px"); if (o.btnPrev) $(o.btnPrev).click(function () { return go(curr - o.scroll) }); if (o.btnNext) $(o.btnNext).click(function () { return go(curr + o.scroll) }); if (o.btnGo) $.each(o.btnGo, function (i, a) { $(a).click(function () { return go(o.circular ? o.visible + i : i) }) }); if (o.mouseWheel && c.mousewheel) c.mousewheel(function (e, d) { return d > 0 ? go(curr - o.scroll) : go(curr + o.scroll) }); if (o.auto) setInterval(function () { go(curr + o.scroll) }, o.auto + o.speed); function vis() { return f.slice(curr).slice(0, v) }; function go(a) { if (!b) { if (o.beforeStart) o.beforeStart.call(this, vis()); if (o.circular) { if (a <= o.start - v - 1) { ul.css(animCss, -((itemLength - (v * 2)) * g) + "px"); curr = a == o.start - v - 1 ? itemLength - (v * 2) - 1 : itemLength - (v * 2) - o.scroll } else if (a >= itemLength - v + 1) { ul.css(animCss, -((v) * g) + "px"); curr = a == itemLength - v + 1 ? v + 1 : v + o.scroll } else curr = a } else { if (a < 0 || a > itemLength - v) return; else curr = a } b = true; ul.animate(animCss == "left" ? { left: -(curr * g)} : { top: -(curr * g) }, o.speed, o.easing, function () { if (o.afterEnd) o.afterEnd.call(this, vis()); b = false }); if (!o.circular) { $(o.btnPrev + "," + o.btnNext).removeClass("disabled"); $((curr - o.scroll < 0 && o.btnPrev) || (curr + o.scroll > itemLength - v && o.btnNext) || []).addClass("disabled") } } return false } }) }; function css(a, b) { return parseInt($.css(a[0], b)) || 0 }; function width(a) { return a[0].offsetWidth + css(a, 'marginLeft') + css(a, 'marginRight') }; function height(a) { return a[0].offsetHeight + css(a, 'marginTop') + css(a, 'marginBottom') } })(jQuery);


/*!
 * jQuery Tools v1.2.6 - The missing UI library for the Web
 * 
 * rangeinput/rangeinput.js
 * scrollable/scrollable.js
 * scrollable/scrollable.autoscroll.js
 * scrollable/scrollable.navigator.js
 * 
 * NO COPYRIGHTS OR LICENSES. DO WHAT YOU LIKE.
 * 
 * http://flowplayer.org/tools/
 * 

(function(a){a.tools=a.tools||{version:"v1.2.6"};var b;b=a.tools.rangeinput={conf:{min:0,max:100,step:"any",steps:0,value:0,precision:undefined,vertical:0,keyboard:!0,progress:!1,speed:100,css:{input:"range",slider:"slider",progress:"progress",handle:"handle"}}};var c,d;a.fn.drag=function(b){document.ondragstart=function(){return!1},b=a.extend({x:!0,y:!0,drag:!0},b),c=c||a(document).bind("mousedown mouseup",function(e){var f=a(e.target);if(e.type=="mousedown"&&f.data("drag")){var g=f.position(),h=e.pageX-g.left,i=e.pageY-g.top,j=!0;c.bind("mousemove.drag",function(a){var c=a.pageX-h,e=a.pageY-i,g={};b.x&&(g.left=c),b.y&&(g.top=e),j&&(f.trigger("dragStart"),j=!1),b.drag&&f.css(g),f.trigger("drag",[e,c]),d=f}),e.preventDefault()}else try{d&&d.trigger("dragEnd")}finally{c.unbind("mousemove.drag"),d=null}});return this.data("drag",!0)};function e(a,b){var c=Math.pow(10,b);return Math.round(a*c)/c}function f(a,b){var c=parseInt(a.css(b),10);if(c)return c;var d=a[0].currentStyle;return d&&d.width&&parseInt(d.width,10)}function g(a){var b=a.data("events");return b&&b.onSlide}function h(b,c){var d=this,h=c.css,i=a("<div><div/><a href='#'/></div>").data("rangeinput",d),j,k,l,m,n;b.before(i);var o=i.addClass(h.slider).find("a").addClass(h.handle),p=i.find("div").addClass(h.progress);a.each("min,max,step,value".split(","),function(a,d){var e=b.attr(d);parseFloat(e)&&(c[d]=parseFloat(e,10))});var q=c.max-c.min,r=c.step=="any"?0:c.step,s=c.precision;if(s===undefined)try{s=r.toString().split(".")[1].length}catch(t){s=0}if(b.attr("type")=="range"){var u=b.clone().wrap("<div/>").parent().html(),v=a(u.replace(/type/i,"type=text data-orig-type"));v.val(c.value),b.replaceWith(v),b=v}b.addClass(h.input);var w=a(d).add(b),x=!0;function y(a,f,g,h){g===undefined?g=f/m*q:h&&(g-=c.min),r&&(g=Math.round(g/r)*r);if(f===undefined||r)f=g*m/q;if(isNaN(g))return d;f=Math.max(0,Math.min(f,m)),g=f/m*q;if(h||!j)g+=c.min;j&&(h?f=m-f:g=c.max-g),g=e(g,s);var i=a.type=="click";if(x&&k!==undefined&&!i){a.type="onSlide",w.trigger(a,[g,f]);if(a.isDefaultPrevented())return d}var l=i?c.speed:0,t=i?function(){a.type="change",w.trigger(a,[g])}:null;j?(o.animate({top:f},l,t),c.progress&&p.animate({height:m-f+o.height()/2},l)):(o.animate({left:f},l,t),c.progress&&p.animate({width:f+o.width()/2},l)),k=g,n=f,b.val(g);return d}a.extend(d,{getValue:function(){return k},setValue:function(b,c){z();return y(c||a.Event("api"),undefined,b,!0)},getConf:function(){return c},getProgress:function(){return p},getHandle:function(){return o},getInput:function(){return b},step:function(b,e){e=e||a.Event();var f=c.step=="any"?1:c.step;d.setValue(k+f*(b||1),e)},stepUp:function(a){return d.step(a||1)},stepDown:function(a){return d.step(-a||-1)}}),a.each("onSlide,change".split(","),function(b,e){a.isFunction(c[e])&&a(d).bind(e,c[e]),d[e]=function(b){b&&a(d).bind(e,b);return d}}),o.drag({drag:!1}).bind("dragStart",function(){z(),x=g(a(d))||g(b)}).bind("drag",function(a,c,d){if(b.is(":disabled"))return!1;y(a,j?c:d)}).bind("dragEnd",function(a){a.isDefaultPrevented()||(a.type="change",w.trigger(a,[k]))}).click(function(a){return a.preventDefault()}),i.click(function(a){if(b.is(":disabled")||a.target==o[0])return a.preventDefault();z();var c=j?o.height()/2:o.width()/2;y(a,j?m-l-c+a.pageY:a.pageX-l-c)}),c.keyboard&&b.keydown(function(c){if(!b.attr("readonly")){var e=c.keyCode,f=a([75,76,38,33,39]).index(e)!=-1,g=a([74,72,40,34,37]).index(e)!=-1;if((f||g)&&!(c.shiftKey||c.altKey||c.ctrlKey)){f?d.step(e==33?10:1,c):g&&d.step(e==34?-10:-1,c);return c.preventDefault()}}}),b.blur(function(b){var c=a(this).val();c!==k&&d.setValue(c,b)}),a.extend(b[0],{stepUp:d.stepUp,stepDown:d.stepDown});function z(){j=c.vertical||f(i,"height")>f(i,"width"),j?(m=f(i,"height")-f(o,"height"),l=i.offset().top+m):(m=f(i,"width")-f(o,"width"),l=i.offset().left)}function A(){z(),d.setValue(c.value!==undefined?c.value:c.min)}A(),m||a(window).load(A)}a.expr[":"].range=function(b){var c=b.getAttribute("type");return c&&c=="range"||a(b).filter("input").data("rangeinput")},a.fn.rangeinput=function(c){if(this.data("rangeinput"))return this;c=a.extend(!0,{},b.conf,c);var d;this.each(function(){var b=new h(a(this),a.extend(!0,{},c)),e=b.getInput().data("rangeinput",b);d=d?d.add(e):e});return d?d:this}})(jQuery);
 */
(function(a){a.tools=a.tools||{version:"v1.2.6"},a.tools.scrollable={conf:{activeClass:"active",circular:!1,clonedClass:"cloned",disabledClass:"disabled",easing:"swing",initialIndex:0,item:"> *",items:".items",keyboard:!0,mousewheel:!1,next:".next",prev:".prev",size:1,speed:400,vertical:!1,touch:!0,wheelSpeed:0}};function b(a,b){var c=parseInt(a.css(b),10);if(c)return c;var d=a[0].currentStyle;return d&&d.width&&parseInt(d.width,10)}function c(b,c){var d=a(c);return d.length<2?d:b.parent().find(c)}var d;function e(b,e){var f=this,g=b.add(f),h=b.children(),i=0,j=e.vertical;d||(d=f),h.length>1&&(h=a(e.items,b)),e.size>1&&(e.circular=!1),a.extend(f,{getConf:function(){return e},getIndex:function(){return i},getSize:function(){return f.getItems().size()},getNaviButtons:function(){return n.add(o)},getRoot:function(){return b},getItemWrap:function(){return h},getItems:function(){return h.find(e.item).not("."+e.clonedClass)},move:function(a,b){return f.seekTo(i+a,b)},next:function(a){return f.move(e.size,a)},prev:function(a){return f.move(-e.size,a)},begin:function(a){return f.seekTo(0,a)},end:function(a){return f.seekTo(f.getSize()-1,a)},focus:function(){d=f;return f},addItem:function(b){b=a(b),e.circular?(h.children().last().before(b),h.children().first().replaceWith(b.clone().addClass(e.clonedClass))):(h.append(b),o.removeClass("disabled")),g.trigger("onAddItem",[b]);return f},seekTo:function(b,c,k){b.jquery||(b*=1);if(e.circular&&b===0&&i==-1&&c!==0)return f;if(!e.circular&&b<0||b>f.getSize()||b<-1)return f;var l=b;b.jquery?b=f.getItems().index(b):l=f.getItems().eq(b);var m=a.Event("onBeforeSeek");if(!k){g.trigger(m,[b,c]);if(m.isDefaultPrevented()||!l.length)return f}var n=j?{top:-l.position().top}:{left:-l.position().left};i=b,d=f,c===undefined&&(c=e.speed),h.animate(n,c,e.easing,k||function(){g.trigger("onSeek",[b])});return f}}),a.each(["onBeforeSeek","onSeek","onAddItem"],function(b,c){a.isFunction(e[c])&&a(f).bind(c,e[c]),f[c]=function(b){b&&a(f).bind(c,b);return f}});if(e.circular){var k=f.getItems().slice(-1).clone().prependTo(h),l=f.getItems().eq(1).clone().appendTo(h);k.add(l).addClass(e.clonedClass),f.onBeforeSeek(function(a,b,c){if(!a.isDefaultPrevented()){if(b==-1){f.seekTo(k,c,function(){f.end(0)});return a.preventDefault()}b==f.getSize()&&f.seekTo(l,c,function(){f.begin(0)})}});var m=b.parents().add(b).filter(function(){if(a(this).css("display")==="none")return!0});m.length?(m.show(),f.seekTo(0,0,function(){}),m.hide()):f.seekTo(0,0,function(){})}var n=c(b,e.prev).click(function(a){a.stopPropagation(),f.prev()}),o=c(b,e.next).click(function(a){a.stopPropagation(),f.next()});e.circular||(f.onBeforeSeek(function(a,b){setTimeout(function(){a.isDefaultPrevented()||(n.toggleClass(e.disabledClass,b<=0),o.toggleClass(e.disabledClass,b>=f.getSize()-1))},1)}),e.initialIndex||n.addClass(e.disabledClass)),f.getSize()<2&&n.add(o).addClass(e.disabledClass),e.mousewheel&&a.fn.mousewheel&&b.mousewheel(function(a,b){if(e.mousewheel){f.move(b<0?1:-1,e.wheelSpeed||50);return!1}});if(e.touch){var p={};h[0].ontouchstart=function(a){var b=a.touches[0];p.x=b.clientX,p.y=b.clientY},h[0].ontouchmove=function(a){if(a.touches.length==1&&!h.is(":animated")){var b=a.touches[0],c=p.x-b.clientX,d=p.y-b.clientY;f[j&&d>0||!j&&c>0?"next":"prev"](),a.preventDefault()}}}e.keyboard&&a(document).bind("keydown.scrollable",function(b){if(!(!e.keyboard||b.altKey||b.ctrlKey||b.metaKey||a(b.target).is(":input"))){if(e.keyboard!="static"&&d!=f)return;var c=b.keyCode;if(j&&(c==38||c==40)){f.move(c==38?-1:1);return b.preventDefault()}if(!j&&(c==37||c==39)){f.move(c==37?-1:1);return b.preventDefault()}}}),e.initialIndex&&f.seekTo(e.initialIndex,0,function(){})}a.fn.scrollable=function(b){var c=this.data("scrollable");if(c)return c;b=a.extend({},a.tools.scrollable.conf,b),this.each(function(){c=new e(a(this),b),a(this).data("scrollable",c)});return b.api?c:this}})(jQuery);
(function(a){var b=a.tools.scrollable;b.autoscroll={conf:{autoplay:!0,interval:3e3,autopause:!0}},a.fn.autoscroll=function(c){typeof c=="number"&&(c={interval:c});var d=a.extend({},b.autoscroll.conf,c),e;this.each(function(){var b=a(this).data("scrollable"),c=b.getRoot(),f,g=!1;function h(){f=setTimeout(function(){b.next()},d.interval)}b&&(e=b),b.play=function(){f||(g=!1,c.bind("onSeek",h),h())},b.pause=function(){f=clearTimeout(f),c.unbind("onSeek",h)},b.resume=function(){g||b.play()},b.stop=function(){g=!0,b.pause()},d.autopause&&c.add(b.getNaviButtons()).hover(b.pause,b.resume),d.autoplay&&b.play()});return d.api?e:this}})(jQuery);
(function(a){var b=a.tools.scrollable;b.navigator={conf:{navi:".navi",naviItem:null,activeClass:"active",indexed:!1,idPrefix:null,history:!1}};function c(b,c){var d=a(c);return d.length<2?d:b.parent().find(c)}a.fn.navigator=function(d){typeof d=="string"&&(d={navi:d}),d=a.extend({},b.navigator.conf,d);var e;this.each(function(){var b=a(this).data("scrollable"),f=d.navi.jquery?d.navi:c(b.getRoot(),d.navi),g=b.getNaviButtons(),h=d.activeClass,i=d.history&&history.pushState,j=b.getConf().size;b&&(e=b),b.getNaviButtons=function(){return g.add(f)},i&&(history.pushState({i:0}),a(window).bind("popstate",function(a){var c=a.originalEvent.state;c&&b.seekTo(c.i)}));function k(a,c,d){b.seekTo(c),d.preventDefault(),i&&history.pushState({i:c})}function l(){return f.find(d.naviItem||"> *")}function m(b){var c=a("<"+(d.naviItem||"a")+"/>").click(function(c){k(a(this),b,c)});b===0&&c.addClass(h),d.indexed&&c.text(b+1),d.idPrefix&&c.attr("id",d.idPrefix+b);return c.appendTo(f)}l().length?l().each(function(b){a(this).click(function(c){k(a(this),b,c)})}):a.each(b.getItems(),function(a){a%j==0&&m(a)}),b.onBeforeSeek(function(a,b){setTimeout(function(){if(!a.isDefaultPrevented()){var c=b/j,d=l().eq(c);d.length&&l().removeClass(h).eq(c).addClass(h)}},1)}),b.onAddItem(function(a,c){var d=b.getItems().index(c);d%j==0&&m(d)})});return d.api?e:this}})(jQuery); 
