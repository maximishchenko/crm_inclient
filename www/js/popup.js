/**
 * Minified by jsDelivr using Terser v3.14.1.
 * Original file: /gh/vast-engineering/jquery-popup-overlay@2.1.1/jquery.popupoverlay.js
 * 
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
! function(t) {
    var e, o, i = t(window),
        n = {},
        a = [],
        p = [],
        s = null,
        l = [],
        d = null,
        r = /(iPad|iPhone|iPod)/.test(navigator.userAgent),
        c = {
            _init: function(e) {
                var o = t(e),
                    i = o.data("popupoptions");
                p[e.id] = !1, a[e.id] = 0, o.data("popup-initialized") || (o.attr("data-popup-initialized", "true"), c._initonce(e)), i.autoopen && setTimeout(function() {
                    c.show(e, 0)
                }, 0)
            },
            _initonce: function(o) {
                var i, n, a, p = t(o),
                    l = t("body"),
                    u = p.data("popupoptions");
                (s = parseInt(l.css("margin-right"), 10), d = void 0 !== document.body.style.webkitTransition || void 0 !== document.body.style.MozTransition || void 0 !== document.body.style.msTransition || void 0 !== document.body.style.OTransition || void 0 !== document.body.style.transition, u.scrolllock) && (void 0 === e && (a = (n = t('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body")).children(), e = a.innerWidth() - a.height(99).innerWidth(), n.remove()));
                if (p.attr("id") || p.attr("id", "j-popup-" + parseInt(1e8 * Math.random(), 10)), p.addClass("popup_content"), u.background && !t("#" + o.id + "_background").length) {
                    l.append('<div id="' + o.id + '_background" class="popup_background"></div>');
                    var f = t("#" + o.id + "_background");
                    f.css({
                        opacity: 0,
                        visibility: "hidden",
                        backgroundColor: u.color,
                        position: "fixed",
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }), u.setzindex && !u.autozindex && f.css("z-index", "100000"), u.transition && f.css("transition", u.transition)
                }
                l.append(o), p.wrap('<div id="' + o.id + '_wrapper" class="popup_wrapper" />'), (i = t("#" + o.id + "_wrapper")).css({
                    opacity: 0,
                    visibility: "hidden",
                    position: "absolute"
                }), r && ((f = t("#" + o.id + "_background")).css("cursor", "pointer"), t(u.pagecontainer).css("cursor", "pointer")), "overlay" == u.type && !u.absolute && u.background && (i.css("overflow", "auto"), i[0].style.WebkitOverflowScrolling = "touch"), p.css({
                    opacity: 0,
                    visibility: "hidden",
                    "pointer-events": "auto",
                    display: "inline-block"
                }), u.setzindex && !u.autozindex && i.css("z-index", "100001"), u.outline || p.css("outline", "none"), u.transition && (p.css("transition", u.transition), i.css("transition", u.transition)), p.attr("aria-hidden", !0), "overlay" == u.type && (p.css({
                    textAlign: "left",
                    position: "relative",
                    verticalAlign: "middle"
                }), i.css({
                    position: "fixed",
                    width: "100%",
                    height: "100%",
                    top: 0,
                    left: 0,
                    textAlign: "center"
                }), i.append('<div class="popup_align" />'), t(".popup_align").css({
                    display: "inline-block",
                    verticalAlign: "middle",
                    height: "100%"
                })), p.attr("role", "dialog");
                var h = u.openelement ? u.openelement : "." + o.id + "_open";
                t(h).each(function(e, o) {
                    t(o).attr("data-popup-ordinal", e), o.id || t(o).attr("id", "open_" + parseInt(1e8 * Math.random(), 10))
                }), p.attr("aria-labelledby") || p.attr("aria-label") || p.attr("aria-labelledby", t(h).attr("id")), "hover" == u.action ? (u.keepfocus = !1, t(h).on("mouseenter", function() {
                    c.show(o, t(this).data("popup-ordinal"))
                }), t(h).on("mouseleave", function() {
                    c.hide(o)
                })) : t(document).on("click.jqp", h, function(e) {
                    e.preventDefault();
                    var i = t(this).data("popup-ordinal");
                    setTimeout(function() {
                        c.show(o, i)
                    }, 0)
                }), u.closebutton && c.addclosebutton(o), u.detach ? p.detach() : p.hide()
            },
            show: function(n, r) {
                var f = t(n);
                if (!f.data("popup-visible")) {
                    f.data("popup-initialized") || c._init(n), f.attr("data-popup-initialized", "true");
                    var h = t("body"),
                        b = f.data("popupoptions"),
                        g = t("#" + n.id + "_wrapper"),
                        v = t("#" + n.id + "_background");
                    if (u(n, r, b.beforeopen), p[n.id] = r, setTimeout(function() {
                            l.push(n.id)
                        }, 0), b.autozindex) {
                        for (var m = document.getElementsByTagName("*"), y = m.length, _ = 0, k = 0; k < y; k++) {
                            var w = t(m[k]).css("z-index");
                            "auto" !== w && _ < (w = parseInt(w, 10)) && (_ = w)
                        }
                        a[n.id] = _, b.background && a[n.id] >= 0 && t("#" + n.id + "_background").css({
                            zIndex: a[n.id] + 1
                        }), a[n.id] >= 0 && g.css({
                            zIndex: a[n.id] + 2
                        })
                    }
                    b.detach ? (g.prepend(n), f.show()) : f.show(), o = setTimeout(function() {
                        g.css({
                            visibility: "visible",
                            opacity: 1
                        }), t("html").addClass("popup_visible").addClass("popup_visible_" + n.id), g.addClass("popup_wrapper_visible")
                    }, 20), b.scrolllock && (h.css("overflow", "hidden"), h.height() > i.height() && h.css("margin-right", s + e)), f.css({
                        visibility: "visible",
                        opacity: 1
                    }), b.background && (v.css({
                        visibility: "visible",
                        opacity: b.opacity
                    }), setTimeout(function() {
                        v.css({
                            opacity: b.opacity
                        })
                    }, 0)), f.data("popup-visible", !0), c.reposition(n, r), f.data("focusedelementbeforepopup", document.activeElement), f.attr("tabindex", -1), setTimeout(function() {
                        "closebutton" === b.focuselement ? t("#" + n.id + " ." + n.id + "_close:first").focus() : b.focuselement ? t(b.focuselement).focus() : (!0 === b.focuselement || b.keepfocus) && f.focus()
                    }, b.focusdelay), b.keepfocus && t(b.pagecontainer).attr("aria-hidden", !0), f.attr("aria-hidden", !1), u(n, r, b.onopen), d ? g.one("transitionend", function() {
                        u(n, r, b.opentransitionend)
                    }) : u(n, r, b.opentransitionend), "tooltip" == b.type && t(window).on("resize." + n.id, function() {
                        c.reposition(n, r)
                    })
                }
            },
            hide: function(e, i) {
                var n = t.inArray(e.id, l);
                if (-1 !== n) {
                    o && clearTimeout(o);
                    var a = t("body"),
                        r = t(e),
                        c = r.data("popupoptions"),
                        f = t("#" + e.id + "_wrapper"),
                        h = t("#" + e.id + "_background");
                    r.data("popup-visible", !1), 1 === l.length ? t("html").removeClass("popup_visible").removeClass("popup_visible_" + e.id) : t("html").hasClass("popup_visible_" + e.id) && t("html").removeClass("popup_visible_" + e.id), l.splice(n, 1), f.hasClass("popup_wrapper_visible") && f.removeClass("popup_wrapper_visible"), c.keepfocus && !i && setTimeout(function() {
                        t(r.data("focusedelementbeforepopup")).is(":visible") && r.data("focusedelementbeforepopup").focus()
                    }, 0), f.css({
                        visibility: "hidden",
                        opacity: 0
                    }), r.css({
                        visibility: "hidden",
                        opacity: 0
                    }), c.background && h.css({
                        visibility: "hidden",
                        opacity: 0
                    }), t(c.pagecontainer).attr("aria-hidden", !1), r.attr("aria-hidden", !0), u(e, p[e.id], c.onclose), d && "0s" !== r.css("transition-duration") ? r.one("transitionend", function() {
                        r.data("popup-visible") || (c.detach ? r.detach() : r.hide()), c.scrolllock && setTimeout(function() {
                            t.grep(l, function(e) {
                                return t("#" + e).data("popupoptions").scrolllock
                            }).length || a.css({
                                overflow: "visible",
                                "margin-right": s
                            })
                        }, 10), u(e, p[e.id], c.closetransitionend)
                    }) : (c.detach ? r.detach() : r.hide(), c.scrolllock && setTimeout(function() {
                        t.grep(l, function(e) {
                            return t("#" + e).data("popupoptions").scrolllock
                        }).length || a.css({
                            overflow: "visible",
                            "margin-right": s
                        })
                    }, 10), u(e, p[e.id], c.closetransitionend)), "tooltip" == c.type && t(window).off("resize." + e.id)
                }
            },
            toggle: function(e, o) {
                t(e).data("popup-visible") ? c.hide(e) : setTimeout(function() {
                    c.show(e, o)
                }, 0)
            },
            reposition: function(e, o) {
                var n = t(e),
                    a = n.data("popupoptions"),
                    p = t("#" + e.id + "_wrapper");
                if (o = o || 0, "tooltip" == a.type) {
                    var s;
                    p.css({
                        position: "absolute"
                    });
                    var l = (s = a.tooltipanchor ? t(a.tooltipanchor) : a.openelement ? t(a.openelement).filter('[data-popup-ordinal="' + o + '"]') : t("." + e.id + '_open[data-popup-ordinal="' + o + '"]')).offset() || {
                        left: 0,
                        top: 0
                    };
                    "right" == a.horizontal ? p.css("left", l.left + s.outerWidth() + a.offsetleft) : "leftedge" == a.horizontal ? p.css("left", l.left + a.offsetleft) : "left" == a.horizontal ? p.css("right", i.width() - l.left - a.offsetleft) : "rightedge" == a.horizontal ? p.css("right", i.width() - l.left - s.outerWidth() - a.offsetleft) : p.css("left", l.left + s.outerWidth() / 2 - n.outerWidth() / 2 - parseFloat(n.css("marginLeft")) + a.offsetleft), "bottom" == a.vertical ? p.css("top", l.top + s.outerHeight() + a.offsettop) : "bottomedge" == a.vertical ? p.css("top", l.top + s.outerHeight() - n.outerHeight() + a.offsettop) : "top" == a.vertical ? p.css("bottom", i.height() - l.top - a.offsettop) : "topedge" == a.vertical ? p.css("bottom", i.height() - l.top - n.outerHeight() - a.offsettop) : p.css("top", l.top + s.outerHeight() / 2 - n.outerHeight() / 2 - parseFloat(n.css("marginTop")) + a.offsettop)
                } else "overlay" == a.type && (a.horizontal ? p.css("text-align", a.horizontal) : p.css("text-align", "center"), a.vertical ? n.css("vertical-align", a.vertical) : n.css("vertical-align", "middle"), a.absolute && p.css({
                    position: "absolute",
                    top: window.scrollY
                }), a.background || (p.css({
                    "pointer-events": "none"
                }), a.absolute || f(e) || (n.css("overflow", "auto"), n[0].style.WebkitOverflowScrolling = "touch", n.css("max-height", "calc(100% - " + n.css("margin-top") + " - " + n.css("margin-bottom") + ")"))))
            },
            addclosebutton: function(e) {
                var o;
                o = t(e).data("popupoptions").closebuttonmarkup ? t(n.closebuttonmarkup).addClass(e.id + "_close") : '<button class="popup_close ' + e.id + '_close" title="Close" aria-label="Close"><span aria-hidden="true">×</span></button>', t(e).data("popup-initialized") && t(e).append(o)
            }
        },
        u = function(e, o, i) {
            var n, a, p = t(e).data("popupoptions");
            void 0 !== p && (n = p.openelement ? p.openelement : "." + e.id + "_open", a = t(n + '[data-popup-ordinal="' + o + '"]'), "function" == typeof i && i.call(t(e), e, a))
        },
        f = function(t) {
            var e = t.getBoundingClientRect();
            return e.top >= 0 && e.left >= 0 && e.bottom <= (window.innerHeight || document.documentElement.clientHeight) && e.right <= (window.innerWidth || document.documentElement.clientWidth)
        };
    t(document).on("keydown", function(e) {
        if (l.length) {
            var o = l[l.length - 1],
                i = document.getElementById(o);
            t(i).data("popupoptions").escape && 27 == e.keyCode && c.hide(i)
        }
    }), t(document).on("click", function(e) {
        if (l.length) {
            var o = l[l.length - 1],
                i = document.getElementById(o),
                n = t(i).data("popupoptions").closeelement ? t(i).data("popupoptions").closeelement : "." + i.id + "_close";
            t(e.target).closest(n).length && (e.preventDefault(), c.hide(i)), t(i).data("popupoptions") && t(i).data("popupoptions").blur && !t(e.target).closest(t(i).data("popupoptions").blurignore).length && !t(e.target).closest("#" + o).length && 2 !== e.which && t(e.target).is(":visible") && (t(i).data("popupoptions").background ? (c.hide(i), e.preventDefault()) : c.hide(i, !0))
        }
    }), t(document).on("keydown", function(e) {
        if (l.length && 9 == e.which) {
            var o = l[l.length - 1],
                i = document.getElementById(o);
            if (!t(i).data("popupoptions").keepfocus) return;
            var n = t(i).find("*").filter("a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex], *[contenteditable]").filter(":visible"),
                a = t(":focus"),
                p = n.length,
                s = n.index(a);
            0 === p ? (t(i).focus(), e.preventDefault()) : e.shiftKey ? 0 === s && (n.get(p - 1).focus(), e.preventDefault()) : s == p - 1 && (n.get(0).focus(), e.preventDefault())
        }
    }), t.fn.popup = function(e) {
        return this.each(function() {
            var o = t(this),
                i = t.extend(!0, {}, t.fn.popup.defaults);
            if (e && "tooltip" === e.type && (i.background = !1), "object" == typeof e) {
                var a = t.extend({}, i, o.data("popupoptions"), e);
                o.data("popupoptions", a), n = o.data("popupoptions"), c._init(this)
            } else "string" == typeof e ? (o.data("popupoptions") || (o.data("popupoptions", i), n = o.data("popupoptions")), c[e].call(this, this)) : (o.data("popupoptions") || (o.data("popupoptions", i), n = o.data("popupoptions")), c._init(this))
        })
    }, t.fn.popup.destroyall = function() {
        for (var e = 0; e < l.length; e++) t("#" + l[e]).popup("hide");
        t(".popup_wrapper").remove(), t(".popup_background").remove(), t(document).off("click.jqp")
    }, t.fn.popup.defaults = {
        type: "overlay",
        absolute: !1,
        autoopen: !1,
        background: !0,
        color: "black",
        opacity: "0.5",
        horizontal: "center",
        vertical: "top", 
        offsettop: 100,
        offsetleft: 0,
        escape: !0,
        blur: !0,
        blurignore: null,
        setzindex: !0,
        autozindex: !1,
        scrolllock: !1,
        closebutton: !1,
        closebuttonmarkup: null,
        keepfocus: !0,
        focuselement: null,
        focusdelay: 50,
        outline: !1,
        pagecontainer: null,
        detach: !1,
        openelement: null,
        closeelement: null,
        transition: null,
        tooltipanchor: null,
        beforeopen: null,
        onclose: null,
        onopen: null,
        opentransitionend: null,
        closetransitionend: null
    }
}(jQuery);
//# sourceMappingURL=/sm/2035d47cc4dfefbedc1c893619378163bec0745a97d37c6079983849bfcce499.map