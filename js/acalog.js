jQuery.fn.acalogWidgetize = function(a) {
    var e = this;
    return new AcalogWidgetAPI(a, function(a) {
        e.each(function() {
            a.widgetize(jQuery(this))
        })
    }), this
};
var AcalogWidgetAPI = function(a) {
    "use strict";
    var e = function() {
            function e(a) {
                try {
                    a = JSON.parse(a), a.program_display = !!a.program_display, a.course_display = !!a.course_display, a.program_show_only_active_visible = !!a.program_show_only_active, a.searchable = !!a.searchable, a.program_grouping = 2 === a.program_grouping ? "degree-type" : 1 === a.program_grouping ? "type" : "none", a.course_grouping = 1 === a.course_grouping ? "type" : "none"
                } catch (e) {
                    a = {}, a.program_display = !1, a.course_display = !1, a.program_show_only_active_visible = !0, a.searchable = !1, a.program_grouping = "none", a.course_grouping = "none"
                }
                return a
            }

            function o(a, e) {
                var t = {
                    left: [],
                    right: [],
                    before: [],
                    after: []
                };
                if (e.length)
                    for (var o = 0; o < e.length; o++) {
                        var r = e[o];
                        a === r["course-id"] && ("left" === r.placement ? t.left.push(r) : "right" === r.placement ? t.right.push(r) : "before" === r.placement ? t.before.push(r) : "after" === r.placement && t.after.push(r))
                    }
                return t
            }

            function r(a) {
                for (var e = [], t = {}, o = "", r = 0; r < a.length; r++) {
                    var l = a[r];
                    if (l.status.active !== !1)
                        if (l.course_types.length)
                            for (var g = 0; g < l.course_types.length; g++) o = l.course_types[g].name, o in t ? t[o].push(l) : t[o] = [l];
                        else o = "Other Courses", o in t ? t[o].push(l) : t[o] = [l]
                }
                var n = Object.keys(t).sort();
                n.indexOf("Other Courses") > -1 && n.splice(n.length, 0, n.splice(n.indexOf("Other Courses"), 1)[0]);
                for (var i = 0; i < n.length; i++) e.push({
                    type: n[i],
                    courses: t[n[i]]
                });
                return e
            }

            function l(a) {
                for (var e = [], t = 0; t < a.length; t++) {
                    var o = a[t];
                    o.status.active !== !1 && e.push(o)
                }
                return e
            }

            function g(a, e) {
                for (var t = [], o = {}, r = "", l = 0; l < a.length; l++) {
                    var g = a[l];
                    if (!(e.program_show_only_active_visible && g.status.visible === !1 || g.status.active === !1))
                        if (g.program_types.length)
                            for (var n = 0; n < g.program_types.length; n++) r = g.program_types[n].name, r in o ? o[r].push(g) : o[r] = [g];
                        else r = "Other Programs", r in o ? o[r].push(g) : o[r] = [g]
                }
                var i = Object.keys(o).sort();
                i.indexOf("Other Programs") > -1 && i.splice(i.length, 0, i.splice(i.indexOf("Other Programs"), 1)[0]);
                for (var c = 0; c < i.length; c++) t.push({
                    type: i[c],
                    programs: o[i[c]]
                });
                return t
            }

            function n(a, e) {
                for (var t = [], o = {}, r = "", l = 0; l < a.length; l++) {
                    var g = a[l];
                    if (!(e.program_show_only_active_visible && g.status.visible === !1 || g.status.active === !1))
                        if (g.degree_types.length)
                            for (var n = 0; n < g.degree_types.length; n++) r = g.degree_types[n].name, r in o ? o[r].push(g) : o[r] = [g];
                        else r = "Other Programs", r in o ? o[r].push(g) : o[r] = [g]
                }
                var i = Object.keys(o).sort();
                i.indexOf("Other Programs") > -1 && i.splice(i.length, 0, i.splice(i.indexOf("Other Programs"), 1)[0]);
                for (var c = 0; c < i.length; c++) t.push({
                    type: i[c],
                    programs: o[i[c]]
                });
                return t
            }

            function i(a, e) {
                for (var t = [], o = 0; o < a.length; o++) {
                    var r = a[o];
                    e.program_show_only_active_visible && r.status.visible === !1 || r.status.active === !1 || t.push(r)
                }
                return t
            }

            function c(a) {
                var e = "";
                return e += '<h1 class="acalog-catalog-name">' + a.name + "</h1>", e += '<div class="acalog-catalog-description">' + a.description + "</div>", e += '<a href="#" class="acalog-close">Close</a>'
            }

            function s(a, e) {
                var t = "";
                return t += '<h1 class="acalog-course-title">' + a.title + "</h1>", a.body ? t += '<div class="acalog-course-body">' + a.body + "</div>" : (a.api = "/" + a.url.split("/").slice(3).join("/"), t += '<div class="acalog-course-body" data-acalog-ajax="' + a.api + '" data-acalog-ajax-type="course-body">' + e.placeholder + "</div>"), t += '<a href="#" class="acalog-close">Close</a>'
            }

            function p(a, e) {
                var t = "";
                return t += '<h1 class="acalog-program-name">' + a.name + "</h1>", t += '<div class="acalog-program-description">' + a.description + "</div>", a.hasOwnProperty("cores") ? t += '<div class="acalog-program-cores">' + d(a.cores, 2, e) + "</div>" : (a.api = "/" + a.url.split("/").slice(3).join("/"), t += '<div class="acalog-program-cores" data-acalog-ajax="' + a.api + '" data-acalog-ajax-type="cores">' + e.placeholder + "</div>"), t += '<a href="#" class="acalog-close">Close</a>'
            }

            function d(a, e, t) {
                for (var r = "", l = e > 6 ? 6 : e, g = 6 === l ? 6 : l + 1, n = 0; n < a.length; n++) {
                    var i = a[n];
                    if (r += '<div class="acalog-program-core">', r += "<h" + l + ' class="acalog-program-core-name">' + i.name + "</h" + l + ">", i.description.length && (r += '<div class="acalog-program-core-description">' + i.description + "</div>"), i.courses.length) {
                        r += '<ul class="acalog-program-core-courses">';
                        for (var c = 0; c < i.courses.length; c++) {
                            var s = i.courses[c];
                            s.gateway = t.gateway + I("course", t.gatewayCatalogId, s["legacy-id"]), s.api = "/" + s.url.split("/").slice(3).join("/"), s.adhocs = o(s.id, i.adhocs);
                            for (var p = 0; p < s.adhocs.before.length; p++) {
                                var y = s.adhocs.before[p];
                                r += y.display
                            }
                            r += '<li class="acalog-program-core-course">';
                            for (var u = 0; u < s.adhocs.left.length; u++) {
                                var m = s.adhocs.left[u];
                                r += m.display + " "
                            }
                            r += '<a class="acalog-program-core-course-link" href="' + s.gateway + '">' + s.title + "</a>";
                            for (var f = 0; f < s.adhocs.right.length; f++) {
                                var h = s.adhocs.right[f];
                                r += " " + h.display
                            }
                            r += '<div class="acalog-program-core-course-container">', r += "<h" + g + ' class="acalog-program-core-course-title">' + s.title + "</h" + g + ">", r += '<div class="acalog-program-core-course-body" data-acalog-ajax="' + s.api + '" data-acalog-ajax-type="course-body">' + t.placeholder + "</div>", r += '<a href="#" class="acalog-close">Close</a>', r += "</div>", r += "</li>";
                            for (var v = 0; v < s.adhocs.after.length; v++) {
                                var w = s.adhocs.after[v];
                                r += w.display
                            }
                        }
                        r += "</ul>"
                    }
                    i.children.length && (r += '<div class="acalog-program-cores">' + d(i.children, e + 1, t) + "</div>"), r += "</div>"
                }
                return r
            }

            function y(a, e, t) {
                var o, r = "";
                if ("type" === e.program_grouping) {
                    var l = g(a, e);
                    if (l.length) {
                        r += "<h2>Programs</h2>";
                        for (var c = 0; c < l.length; c++) {
                            r += "<h3>" + l[c].type + "</h3>", r += "<ul>";
                            for (var s = 0; s < l[c].programs.length; s++) o = l[c].programs[s], o.gateway = t.gateway + I("program", t.gatewayCatalogId, o["legacy-id"]), r += '<li class="acalog-program">', r += '<a class="acalog-program-link" href="' + o.gateway + '">' + o.name + "</a>", r += '<div class="acalog-program-container">', r += p(o, t), r += "</div>", r += "</li>";
                            r += "</ul>"
                        }
                    }
                } else if ("degree-type" === e.program_grouping) {
                    var d = n(a, e);
                    if (d.length) {
                        r += "<h2>Programs</h2>";
                        for (var y = 0; y < d.length; y++) {
                            r += "<h3>" + d[y].type + "</h3>", r += "<ul>";
                            for (var u = 0; u < d[y].programs.length; u++) o = d[y].programs[u], o.gateway = t.gateway + I("program", t.gatewayCatalogId, o["legacy-id"]), r += '<li class="acalog-program">', r += '<a class="acalog-program-link" href="' + o.gateway + '">' + o.name + "</a>", r += '<div class="acalog-program-container">', r += p(o, t), r += "</div>", r += "</li>";
                            r += "</ul>"
                        }
                    }
                } else {
                    var m = i(a, e);
                    if (m.length) {
                        r += "<h2>Programs</h2>", r += "<ul>";
                        for (var f = 0; f < m.length; f++) o = m[f], o.gateway = t.gateway + I("program", t.gatewayCatalogId, o["legacy-id"]), r += '<li class="acalog-program">', r += '<a class="acalog-program-link" href="' + o.gateway + '">' + o.name + "</a>", r += '<div class="acalog-program-container">', r += p(o, t), r += "</div>", r += "</li>";
                        r += "</ul>"
                    }
                }
                return r
            }

            function u(a, e, t) {
                var o, g = "";
                if (a.length)
                    if ("type" === e.course_grouping) {
                        var n = r(a);
                        if (n.length) {
                            g += "<h2>Courses</h2>";
                            for (var i = 0; i < n.length; i++) {
                                g += "<h3>" + n[i].type + "</h3>", g += "<ul>";
                                for (var c = 0; c < n[i].courses.length; c++) o = n[i].courses[c], o.gateway = t.gateway + I("course", t.gatewayCatalogId, o["legacy-id"]), g += '<li class="acalog-course">', g += '<a class="acalog-course-link" href="' + o.gateway + '">' + o.title + "</a>", g += '<div class="acalog-course-container">', g += s(o, t), g += "</div>", g += "</li>";
                                g += "</ul>"
                            }
                        }
                    } else {
                        var p = l(a);
                        if (p.length) {
                            g += "<h2>Courses</h2>", g += "<ul>";
                            for (var d = 0; d < p.length; d++) o = p[d], o.gateway = t.gateway + I("course", t.gatewayCatalogId, o["legacy-id"]), g += '<li class="acalog-course">', g += '<a class="acalog-course-link" href="' + o.gateway + '">' + o.title + "</a>", g += '<div class="acalog-course-container">', g += s(o, t), g += "</div>", g += "</li>";
                            g += "</ul>"
                        }
                    }
                return g
            }

            function m(a, t) {
                a.options = e(a.options);
                var o = "";
                return o += '<h1 class="acalog-entity-name">' + a.name + "</h1>", o += '<div class="acalog-entity-description">' + a.description + "</div>", a.hasOwnProperty("programs") && a.hasOwnProperty("options") && a.options.program_display ? o += '<div class="acalog-entity-programs">' + y(a.programs, a.options, t) + "</div>" : a.hasOwnProperty("options") && a.options.program_display && (a.api = "/" + a.url.split("/").slice(3).join("/"), o += '<div class="acalog-entity-programs" data-acalog-ajax="' + a.api + '" data-acalog-ajax-type="programs">' + t.placeholder + "</div>"), a.hasOwnProperty("courses") && a.hasOwnProperty("options") && a.options.course_display ? o += '<div class="acalog-entity-courses">' + u(a.courses, a.options, t) + "</div>" : a.hasOwnProperty("options") && a.options.course_display && (a.api = "/" + a.url.split("/").slice(3).join("/"), o += '<div class="acalog-entity-courses" data-acalog-ajax="' + a.api + '" data-acalog-ajax-type="courses">' + t.placeholder + "</div>"), o += '<a href="#" class="acalog-close">Close</a>'
            }

            function f(a, e) {
                var t = '<p>Full filter support coming soon. <a href="' + e.gateway + I("filter", e.gatewayCatalogId, a["legacy-id"]) + '">Click here</a> to view the filter.</p>',
                    o = "";
                return o += '<h1 class="acalog-filter-name">' + a.name + "</h1>", o += '<div class="acalog-filter-description">' + a.content + "</div>", o += '<div class="acalog-filter-content">' + t + "</div>", o += '<a href="#" class="acalog-close">Close</a>'
            }

            function h(a) {
                var e = "";
                return e += '<h1 class="acalog-page-name">' + a.name + "</h1>", e += '<div class="acalog-page-description">' + a.content + "</div>", e += '<a href="#" class="acalog-close">Close</a>'
            }

            function v(a, e) {
                var t = "";
                return t += '<h1 class="acalog-program-name">' + a.name + "</h1>", t += '<div class="acalog-program-description">' + a.description + "</div>", a.hasOwnProperty("cores") ? t += '<div class="acalog-program-cores">' + d(a.cores, 2, e) + "</div>" : (a.api = "/" + a.url.split("/").slice(3).join("/"), t += '<div class="acalog-program-cores" data-acalog-ajax="' + a.api + '" data-acalog-ajax-type="cores">' + e.placeholder + "</div>"), t += '<a href="#" class="acalog-close">Close</a>'
            }

            function w(a, e, t) {
                var o, r = "";
                if ("type" === e.program_grouping) {
                    var l = g(a, e);
                    if (l.length) {
                        r += "<h2>Programs</h2>";
                        for (var c = 0; c < l.length; c++) {
                            r += "<h3>" + l[c].type + "</h3>", r += "<ul>";
                            for (var s = 0; s < l[c].programs.length; s++) o = l[c].programs[s], o.gateway = t.gateway + I("program", t.gatewayCatalogId, o["legacy-id"]), r += '<li class="acalog-program">', r += '<a class="acalog-program-link" href="' + o.gateway + '">' + o.name + "</a>", r += '<div class="acalog-program-container">', r += v(o, t), r += "</div>", r += "</li>";
                            r += "</ul>"
                        }
                    }
                } else if ("degree-type" === e.program_grouping) {
                    var p = n(a, e);
                    if (p.length) {
                        r += "<h2>Programs</h2>";
                        for (var d = 0; d < p.length; d++) {
                            r += "<h3>" + p[d].type + "</h3>", r += "<ul>";
                            for (var y = 0; y < p[d].programs.length; y++) o = p[d].programs[y], o.gateway = t.gateway + I("program", t.gatewayCatalogId, o["legacy-id"]), r += '<li class="acalog-program">', r += '<a class="acalog-program-link" href="' + o.gateway + '">' + o.name + "</a>", r += '<div class="acalog-program-container">', r += v(o, t), r += "</div>", r += "</li>";
                            r += "</ul>"
                        }
                    }
                } else {
                    var u = i(a, e);
                    if (u.length) {
                        r += "<h2>Programs</h2>", r += "<ul>";
                        for (var m = 0; m < u.length; m++) o = u[m], o.gateway = t.gateway + I("program", t.gatewayCatalogId, o["legacy-id"]), r += '<li class="acalog-program">', r += '<a class="acalog-program-link" href="' + o.gateway + '">' + o.name + "</a>", r += '<div class="acalog-program-container">', r += v(o, t), r += "</div>", r += "</li>";
                        r += "</ul>"
                    }
                }
                return r
            }

            function I(a, e, t) {
                var o = "";
                return "index" === a ? o += "/index.php?catoid=" + e : "course" === a ? o += "/preview_course_nopop.php?catoid=" + e + "&coid=" + t : "program" === a ? o += "/preview_program.php?catoid=" + e + "&poid=" + t : "entity" === a ? o += "/preview_entity.php?catoid=" + e + "&ent_oid=" + t : "filter" === a ? o += "/content.php?catoid=" + e + "&navoid=" + t : "page" === a ? o += "/content.php?catoid=" + e + "&navoid=" + t : "media" === a ? o += "/mime/media/view/" + e + "/" + t + "/" : "degree_planner" === a && (o += "/preview_degree_planner.php?catoid=" + e + "&poid=" + t + "&print"), o
            }

            function C(a, o, r) {
                var l = o.api + "/widget-api" + a.data("acalog-ajax"),
                    g = a.data("acalog-ajax-type");
                a.removeData("acalog-ajax"), a.removeAttr("data-acalog-ajax"), a.removeData("acalog-ajax-type"), a.removeAttr("data-acalog-ajax-type"), t.get(l, [], function(t) {
                    if (t.length)
                        if (t = t[0], "course-body" === g && t.hasOwnProperty("body")) a.html(t.body);
                        else if ("cores" === g && t.hasOwnProperty("cores")) {
                        var l = d(t.cores, 2, o);
                        a.html(l)
                    } else if ("programs" === g && t.hasOwnProperty("programs") && t.hasOwnProperty("options")) {
                        t.options = e(t.options);
                        var n = y(t.programs, t.options, o);
                        a.html(n)
                    } else if ("courses" === g && t.hasOwnProperty("courses") && t.hasOwnProperty("options")) {
                        t.options = e(t.options);
                        var i = u(t.courses, t.options, o);
                        a.html(i)
                    } else if ("course" === g) {
                        var c = s(t, o);
                        a.html(c)
                    } else if ("program" === g) {
                        var I = p(t, o);
                        a.html(I)
                    } else if ("entity" === g) {
                        var C = m(t, o);
                        a.html(C)
                    } else if ("filter" === g) {
                        var k = f(t, o);
                        a.html(k)
                    } else if ("page" === g) {
                        var x = h(t, o);
                        a.html(x)
                    } else if ("media" === g) a.html("");
                    else if ("degree_planner" === g && t.hasOwnProperty("programs") && t.hasOwnProperty("options")) {
                        t.options = e(t.options);
                        var L = w(t.programs, t.options, o);
                        a.html(L)
                    } else if ("degree_planner" === g) {
                        var _ = v(t, o);
                        a.html(_)
                    } else a.html('<span class="acalog-error">Error</span>');
                    else a.html('<span class="acalog-error">Error</span>');
                    r()
                })
            }

            function k(e, t, o) {
                e.find(".permalink").each(function() {
                    var e = "",
                        r = a(this).data();
                    r.display = a(this).text(), "hierarchy" === r.to_type ? r.to_type = "entity" : "content" === r.to_type && -1 !== r.to_url.indexOf("page") ? r.to_type = "page" : "content" === r.to_type && -1 !== r.to_url.indexOf("media") ? r.to_type = "media" : "content" === r.to_type && -1 !== r.to_url.indexOf("filter") ? r.to_type = "filter" : "content" === r.to_type && -1 !== r.to_url.indexOf("direct-link") && (r.to_type = "filter"), r.api = "/" + r.to_url.split("/").slice(3).join("/"), r.gateway = t.gateway + I(r.to_type, t.gatewayCatalogId, r.to_legacy_id), r.anchor_text.length && (r.gateway += "#" + r.anchor_text), r.inactive === !0 ? (e += '<div class="acalog-permalink">', e += '<span class="acalog-permalink-inactive">' + r.display + "</span>", e += "</div>") : a(this).is("img") ? e += '<img class="acalog-permalink" src="' + r.gateway + '">' : "inline" === r.display_type && o === !0 ? (e += '<div class="acalog-permalink">', e += '<a target="_blank" class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += "</div>") : "inline" === r.display_type ? (e += '<div class="acalog-permalink acalog-permalink-inline">', e += '<a class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += '<div class="acalog-permalink-container' + (r.show_title ? "" : " acalog-permalink-hidetitle") + '" data-acalog-ajax="' + r.api + '" data-acalog-ajax-type="' + r.to_type + '">' + t.placeholder + "</div>", e += "</div>") : "tooltip" === r.display_type ? (e += '<div class="acalog-permalink acalog-permalink-tooltip">', e += '<a class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += '<div class="acalog-permalink-container" data-acalog-ajax="' + r.api + '"  data-acalog-ajax-type="' + r.to_type + '">' + t.placeholder + "</div>", e += "</div>") : "dynamic" === r.display_type ? (e += '<div class="acalog-permalink acalog-permalink-showhide">', e += '<a class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += '<div class="acalog-permalink-container" data-acalog-ajax="' + r.api + '" data-acalog-ajax-type="' + r.to_type + '">' + t.placeholder + "</div>", e += "</div>") : "same" === r.display_type ? (e += '<div class="acalog-permalink">', e += '<a class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += "</div>") : "new" === r.display_type ? (e += '<div class="acalog-permalink">', e += '<a target="_blank" class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += "</div>") : "popup" === r.display_type ? (e += '<div class="acalog-permalink">', e += '<a target="_blank" class="acalog-permalink-link" href="' + r.gateway + '" >' + r.display + "</a>", e += "</div>") : (e += '<div class="acalog-permalink">', e += '<span class="acalog-permalink-text">' + r.display + "</span>", e += "</div>"), a(this).replaceWith(e)
                }), e.find(".acalog-permalink-inline").each(function() {
                    var e = a(this),
                        o = a(this).children(".acalog-permalink-container");
                    e.addClass("acalog-permalink-open"), o.attr("data-acalog-ajax") && C(o, t, function() {
                        k(o, t, !0)
                    })
                }), "dynamic" === t.display && (e.on("click", ".acalog-permalink-tooltip > .acalog-permalink-link, .acalog-permalink-showhide > .acalog-permalink-link", function(e) {
                    e.preventDefault(), e.stopPropagation();
                    var o = a(this).parent(),
                        r = o.children(".acalog-permalink-container");
                    o.toggleClass("acalog-permalink-open"), r.attr("data-acalog-ajax") && C(r, t, function() {
                        k(r, t, !0)
                    })
                }), e.on("click", ".acalog-permalink .acalog-permalink-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-permalink-open")
                }), e.on("click", ".acalog-course > .acalog-course-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-course-open")
                }), e.on("click", ".acalog-program > .acalog-program-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-program-open")
                }), e.on("click", ".acalog-program-core-course > .acalog-program-core-course-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-program-core-course-open")
                }), e.on("click", ".acalog-entity > .acalog-entity-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-entity-open")
                }), e.on("click", ".acalog-filter > .acalog-filter-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-filter-open")
                }), e.on("click", ".acalog-page > .acalog-page-container > .acalog-close", function(e) {
                    e.preventDefault(), e.stopPropagation(), a(this).parent().parent().toggleClass("acalog-page-open")
                }))
            }

            function x() {
                function a(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += c(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(e, o, r) {
                    0 === o.length && t.reportError(r.api, "catalog-content", e.prop("outerHTML"));
                    var l = a(o, r);
                    e.html(l)
                }
            }

            function L() {
                function a(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("index", r["legacy-id"], r["legacy-id"]), t += '<li class="acalog-catalog">', t += '<a class="acalog-catalog-link" href="' + r.gateway + '">' + r.name + "</a>", t += '<div class="acalog-catalog-container">', t += c(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-catalog">None</li>';
                    return t
                }
                this.widgetize = function(e, o, r) {
                    0 === o.length && t.reportError(r.api, "catalog-list", e.prop("outerHTML"));
                    var l = a(o, r);
                    e.html(l)
                }
            }

            function _() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("index", g, g), l = e[0].name
                    } else t.reportError(o.api, "catalog-link", a.prop("outerHTML"));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function T() {
                function a(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += s(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        null !== e.gatewayCatalogId && (r += I("index", e.gatewayCatalogId, e.gatewayCatalogId), l += I("index", e.gatewayCatalogId, e.gatewayCatalogId)), t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(e, o, r) {
                    0 === o.length && t.reportError(r.api, "course-content", e.prop("outerHTML"));
                    var l = a(o, r);
                    e.html(l), k(e, r, !1)
                }
            }

            function P() {
                function e(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("course", e.gatewayCatalogId, r["legacy-id"]), t += '<li class="acalog-course">', t += '<a class="acalog-course-link" href="' + r.gateway + '">' + r.title + "</a>", t += '<div class="acalog-course-container">', t += s(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-course">None</li>';
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "course-list", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1), "dynamic" === l.display && o.on("click", ".acalog-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-course-open")
                    })
                }
            }

            function D() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("course", o.gatewayCatalogId, g), l = e[0].title
                    } else t.reportError(o.api, "course-link", a.prop("outerHTML")), null !== o.gatewayCatalogId && (r += I("index", o.gatewayCatalogId, o.gatewayCatalogId), l += I("index", o.gatewayCatalogId, o.gatewayCatalogId));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function N() {
                function e(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += p(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        null !== e.gatewayCatalogId && (r += I("index", e.gatewayCatalogId, e.gatewayCatalogId), l += I("index", e.gatewayCatalogId, e.gatewayCatalogId)), t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "program-content", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1);
                    var n = o.children(".acalog-program-cores");
                    n.attr("data-acalog-ajax") && C(n, l, function() {
                        k(n, l, !1)
                    }), "dynamic" === l.display && o.on("click", ".acalog-program-core-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-core-course-open");
                        var t = a(this).next(".acalog-program-core-course-container").children(".acalog-program-core-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    })
                }
            }

            function j() {
                function e(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("program", e.gatewayCatalogId, r["legacy-id"]), t += '<li class="acalog-program">', t += '<a class="acalog-program-link" href="' + r.gateway + '">' + r.name + "</a>", t += '<div class="acalog-program-container">', t += p(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-program">None</li>';
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "program-list", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1), "dynamic" === l.display && (o.on("click", ".acalog-program-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-open");
                        var t = a(this).next(".acalog-program-container").children(".acalog-program-cores");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }), o.on("click", ".acalog-program-core-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-core-course-open");
                        var t = a(this).next(".acalog-program-core-course-container").children(".acalog-program-core-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }))
                }
            }

            function W() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("program", o.gatewayCatalogId, g), l = e[0].name
                    } else t.reportError(o.api, "program-link", a.prop("outerHTML")), null !== o.gatewayCatalogId && (r += I("index", o.gatewayCatalogId, o.gatewayCatalogId), l += I("index", o.gatewayCatalogId, o.gatewayCatalogId));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function E() {
                function e(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += m(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        null !== e.gatewayCatalogId && (r += I("index", e.gatewayCatalogId, e.gatewayCatalogId), l += I("index", e.gatewayCatalogId, e.gatewayCatalogId)), t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "entity-content", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1);
                    var n = o.children(".acalog-entity-programs");
                    n.attr("data-acalog-ajax") && C(n, l, function() {
                        k(n, l, !1)
                    });
                    var i = o.children(".acalog-entity-courses");
                    i.attr("data-acalog-ajax") && C(i, l, function() {
                        k(i, l, !1)
                    }), "dynamic" === l.display && (o.on("click", ".acalog-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-course-open");
                        var t = a(this).next(".acalog-course-container").children(".acalog-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }), o.on("click", ".acalog-program-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-open");
                        var t = a(this).next(".acalog-program-container").children(".acalog-program-cores");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }), o.on("click", ".acalog-program-core-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-core-course-open");
                        var t = a(this).next(".acalog-program-core-course-container").children(".acalog-program-core-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }))
                }
            }

            function b() {
                function e(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("entity", e.gatewayCatalogId, r["legacy-id"]), t += '<li class="acalog-entity">', t += '<a class="acalog-entity-link" href="' + r.gateway + '">' + r.name + "</a>", t += '<div class="acalog-entity-container">', t += m(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-page">None</li>';
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "entity-list", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1), "dynamic" === l.display && (o.on("click", ".acalog-entity-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-entity-open");
                        var t = a(this).next(".acalog-entity-container").children(".acalog-entity-programs");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        });
                        var o = a(this).next(".acalog-entity-container").children(".acalog-entity-courses");
                        o.attr("data-acalog-ajax") && C(o, l, function() {
                            k(o, l, !1)
                        })
                    }), o.on("click", ".acalog-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-course-open");
                        var t = a(this).next(".acalog-course-container").children(".acalog-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }), o.on("click", ".acalog-program-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-open");
                        var t = a(this).next(".acalog-program-container").children(".acalog-program-cores");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }), o.on("click", ".acalog-program-core-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-core-course-open");
                        var t = a(this).next(".acalog-program-core-course-container").children(".acalog-program-core-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }))
                }
            }

            function O() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("entity", o.gatewayCatalogId, g), l = e[0].name
                    } else t.reportError(o.api, "entity-link", a.prop("outerHTML")), null !== o.gatewayCatalogId && (r += I("index", o.gatewayCatalogId, o.gatewayCatalogId), l += I("index", o.gatewayCatalogId, o.gatewayCatalogId));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function z() {
                function a(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += f(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        null !== e.gatewayCatalogId && (r += I("index", e.gatewayCatalogId, e.gatewayCatalogId), l += I("index", e.gatewayCatalogId, e.gatewayCatalogId)), t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(e, o, r) {
                    0 === o.length && t.reportError(r.api, "filter-content", e.prop("outerHTML"));
                    var l = a(o, r);
                    e.html(l), k(e, r, !1)
                }
            }

            function H() {
                function e(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("filter", e.gatewayCatalogId, r["legacy-id"]), t += '<li class="acalog-filter">', t += '<a class="acalog-filter-link" href="' + r.gateway + '">' + r.name + "</a>", t += '<div class="acalog-filter-container">', t += f(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-page">None</li>';
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "filter-list", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1), "dynamic" === l.display && o.on("click", ".acalog-filter-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-filter-open")
                    })
                }
            }

            function M() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("filter", o.gatewayCatalogId, g), l = e[0].name
                    } else t.reportError(o.api, "filter-link", a.prop("outerHTML")), null !== o.gatewayCatalogId && (r += I("index", o.gatewayCatalogId, o.gatewayCatalogId), l += I("index", o.gatewayCatalogId, o.gatewayCatalogId));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function F() {
                function a(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += h(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        null !== e.gatewayCatalogId && (r += I("index", e.gatewayCatalogId, e.gatewayCatalogId), l += I("index", e.gatewayCatalogId, e.gatewayCatalogId)), t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(e, o, r) {
                    0 === o.length && t.reportError(r.api, "page-content", e.prop("outerHTML"));
                    var l = a(o, r);
                    e.html(l), k(e, r, !1)
                }
            }

            function A() {
                function e(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("page", e.gatewayCatalogId, r["legacy-id"]), t += '<li class="acalog-page">', t += '<a class="acalog-page-link" href="' + r.gateway + '">' + r.name + "</a>", t += '<div class="acalog-page-container">', t += h(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-page">None</li>';
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "page-list", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1), "dynamic" === l.display && o.on("click", ".acalog-page-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-page-open")
                    })
                }
            }

            function U() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("page", o.gatewayCatalogId, g), l = e[0].name
                    } else t.reportError(o.api, "page-link", a.prop("outerHTML")), null !== o.gatewayCatalogId && (r += I("index", o.gatewayCatalogId, o.gatewayCatalogId), l += I("index", o.gatewayCatalogId, o.gatewayCatalogId));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function Q() {
                function e(a, e) {
                    var t = "";
                    if (a.length) {
                        var o = a[0];
                        t += v(o, e)
                    } else {
                        var r = e.gateway,
                            l = e.gateway;
                        null !== e.gatewayCatalogId && (r += I("index", e.gatewayCatalogId, e.gatewayCatalogId), l += I("index", e.gatewayCatalogId, e.gatewayCatalogId)), t += '<a href="' + r + '">' + l + "</a>"
                    }
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "degree-planner-content", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1);
                    var n = o.children(".acalog-program-cores");
                    n.attr("data-acalog-ajax") && C(n, l, function() {
                        k(n, l, !1)
                    }), "dynamic" === l.display && o.on("click", ".acalog-program-core-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-core-course-open");
                        var t = a(this).next(".acalog-program-core-course-container").children(".acalog-program-core-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    })
                }
            }

            function J() {
                function e(a, e) {
                    var t = "";
                    if (a.length)
                        for (var o = 0; o < a.length; o++) {
                            var r = a[o];
                            r.gateway = e.gateway + I("degree_planner", e.gatewayCatalogId, r["legacy-id"]), t += '<li class="acalog-program">', t += '<a class="acalog-program-link" href="' + r.gateway + '">' + r.name + "</a>", t += '<div class="acalog-program-container">', t += v(r, e), t += "</div>", t += "</li>"
                        } else t += '<li class="acalog-program">None</li>';
                    return t
                }
                this.widgetize = function(o, r, l) {
                    0 === r.length && t.reportError(l.api, "degree-planner-list", o.prop("outerHTML"));
                    var g = e(r, l);
                    o.html(g), k(o, l, !1), "dynamic" === l.display && (o.on("click", ".acalog-program-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-open");
                        var t = a(this).next(".acalog-program-container").children(".acalog-program-cores");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }), o.on("click", ".acalog-program-core-course-link", function(e) {
                        e.preventDefault(), a(this).parent().toggleClass("acalog-program-core-course-open");
                        var t = a(this).next(".acalog-program-core-course-container").children(".acalog-program-core-course-body");
                        t.attr("data-acalog-ajax") && C(t, l, function() {
                            k(t, l, !1)
                        })
                    }))
                }
            }

            function R() {
                this.widgetize = function(a, e, o) {
                    var r = o.gateway,
                        l = o.gateway;
                    if (e.length) {
                        var g = e[0]["legacy-id"];
                        r += I("degree_planner", o.gatewayCatalogId, g), l = e[0].name
                    } else t.reportError(o.api, "degree-planner-link", a.prop("outerHTML")), null !== o.gatewayCatalogId && (r += I("index", o.gatewayCatalogId, o.gatewayCatalogId), l += I("index", o.gatewayCatalogId, o.gatewayCatalogId));
                    o.linkText && (l = o.linkText), a.prop("href", r), a.text(l)
                }
            }

            function S() {
                this.widgetize = function(a, e, o) {
                    t.reportError(o.api, "unknown-content", a.prop("outerHTML")), a.html('<span class="acalog-error">Error</span>')
                }
            }

            function q() {
                this.widgetize = function(a, e, o) {
                    t.reportError(o.api, "unknown-list", a.prop("outerHTML")), a.html('<li><span class="acalog-error">Error</span></li>')
                }
            }

            function B() {
                this.widgetize = function(a, e, o) {
                    t.reportError(o.api, "unknown-link", a.prop("outerHTML")), a.text("Error")
                }
            }
            return {
                CatalogContentWidget: x,
                CatalogListWidget: L,
                CatalogLinkWidget: _,
                CourseContentWidget: T,
                CourseListWidget: P,
                CourseLinkWidget: D,
                ProgramContentWidget: N,
                ProgramListWidget: j,
                ProgramLinkWidget: W,
                EntityContentWidget: E,
                EntityListWidget: b,
                EntityLinkWidget: O,
                FilterContentWidget: z,
                FilterListWidget: H,
                FilterLinkWidget: M,
                PageContentWidget: F,
                PageListWidget: A,
                PageLinkWidget: U,
                DegreePlannerContentWidget: Q,
                DegreePlannerListWidget: J,
                DegreePlannerLinkWidget: R,
                UnknownContentWidget: S,
                UnknownListWidget: q,
                UnknownLinkWidget: B
            }
        }(),
        t = function() {
            function e(a) {
                return encodeURIComponent(a).replace(/\-/g, "%2D").replace(/\_/g, "%5F").replace(/\./g, "%2E").replace(/\!/g, "%21").replace(/\~/g, "%7E").replace(/\*/g, "%2A").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29")
            }

            function t(a) {
                var e = a.match(/&page=(\d+)/),
                    t = e[0],
                    o = "&page=" + (parseInt(e[1]) + 1);
                return a.replace(t, o)
            }

            function o(e, r, l) {
                a.ajax({
                    url: e
                }).done(function(a) {
                    if (void 0 !== a.count && Object.keys(a).forEach(function(e) {
                            e.indexOf("-list") > -1 && (a.list = a[e], delete a[e])
                        }), void 0 !== a.count && void 0 !== a.list)
                        if (r = r.concat(a.list), a.count > r.length) {
                            var g = t(e);
                            o(g, r, l)
                        } else l(r);
                    else r.push(a), l(r)
                }).fail(function() {
                    l([])
                })
            }

            function r(a, e) {
                if (0 === a.length) return a;
                if ("catalogs" === e.data) {
                    for (var t = 0; t < a.length; t++) {
                        var o = a[t];
                        e.catalogId && o.id !== e.catalogId && delete a[t], e.gatewayCatalogId && o["legacy-id"] !== e.gatewayCatalogId && delete a[t]
                    }
                    a = a.filter(function(a) {
                        return a
                    })
                }
                return a
            }

            function l(e, t, o) {
                var r = {
                    error: t,
                    widget: o,
                    location: window.location.href
                };
                a.post(e + "/widget-api/error/", r)
            }
            return {
                encodeData: e,
                get: o,
                fauxFilterData: r,
                reportError: l
            }
        }(),
        o = function(o, r) {
            function l() {
                var a = {};
                return o.api && (a.api = o.api), o.gateway && (a.gateway = o.gateway), o.data && (a.data = o.data), o.catalogId && (a.catalogId = o.catalogId), o.gatewayCatalogId && (a.catalogLegacyId = o.gatewayCatalogId), o.catalogType && (a.catalogType = o.catalogType), o.catalogName && (a.catalogName = o.catalogName), o.courseId && (a.courseId = o.courseId), o.courseLegacyId && (a.courseLegacyId = o.courseLegacyId), o.courseType && (a.courseType = o.courseType), o.coursePrefix && (a.coursePrefix = o.coursePrefix), o.courseCode && (a.courseCode = o.courseCode), o.courseName && (a.courseName = o.courseName), o.programId && (a.programId = o.programId), o.programLegacyId && (a.programLegacyId = o.programLegacyId), o.programType && (a.programType = o.programType), o.programDegreeType && (a.programDegreeType = o.programDegreeType), o.programCode && (a.programCode = o.programCode), o.programName && (a.programName = o.programName), o.entityId && (a.entityId = o.entityId), o.entityLegacyId && (a.entityLegacyId = o.entityLegacyId), o.entityType && (a.entityType = o.entityType), o.entityName && (a.entityName = o.entityName), o.filterId && (a.filterId = o.filterId), o.filterLegacyId && (a.filterLegacyId = o.filterLegacyId), o.filterName && (a.filterName = o.filterName), o.pageId && (a.pageId = o.pageId), o.pageLegacyId && (a.pageLegacyId = o.pageLegacyId), o.pageName && (a.pageName = o.pageName), o.display && (a.display = o.display), o.linkText && (a.linkText = o.linkText), a
            }

            function g(a) {
                var e = {},
                    t = a.data();
                return t.acalogData && (e.data = t.acalogData), t.acalogCatalogId && (e.catalogId = t.acalogCatalogId), t.acalogCatalogLegacyId && (e.catalogLegacyId = t.acalogCatalogLegacyId), t.acalogCatalogType && (e.catalogType = t.acalogCatalogType), t.acalogCatalogName && (e.catalogName = t.acalogCatalogName), t.acalogCourseId && (e.courseId = t.acalogCourseId), t.acalogCourseLegacyId && (e.courseLegacyId = t.acalogCourseLegacyId), t.acalogCourseType && (e.courseType = t.acalogCourseType), t.acalogCoursePrefix && (e.coursePrefix = t.acalogCoursePrefix), t.acalogCourseCode && (e.courseCode = t.acalogCourseCode), t.acalogCourseName && (e.courseName = t.acalogCourseName), t.acalogProgramId && (e.programId = t.acalogProgramId), t.acalogProgramLegacyId && (e.programLegacyId = t.acalogProgramLegacyId), t.acalogProgramType && (e.programType = t.acalogProgramType), t.acalogProgramDegreeType && (e.programDegreeType = t.acalogProgramDegreeType), t.acalogProgramCode && (e.programCode = t.acalogProgramCode), t.acalogProgramName && (e.programName = t.acalogProgramName), t.acalogEntityId && (e.entityId = t.acalogEntityId), t.acalogEntityLegacyId && (e.entityLegacyId = t.acalogEntityLegacyId), t.acalogEntityType && (e.entityType = t.acalogEntityType), t.acalogEntityName && (e.entityName = t.acalogEntityName), t.acalogFilterId && (e.filterId = t.acalogFilterId), t.acalogFilterLegacyId && (e.filterLegacyId = t.acalogFilterLegacyId), t.acalogFilterName && (e.filterName = t.acalogFilterName), t.acalogPageId && (e.pageId = t.acalogPageId), t.acalogPageLegacyId && (e.pageLegacyId = t.acalogPageLegacyId), t.acalogPageName && (e.pageName = t.acalogPageName), t.acalogDisplay && (e.display = t.acalogDisplay), e.placeholder = a.is("ul") ? a.children("li").html() : a.html(), t.acalogLinkText && (e.linkText = t.acalogLinkText), e
            }

            function n(a, e) {
                for (var t = null, o = 0; o < a.length; o++) {
                    var r = a[o];
                    r["legacy-id"] === e && (t = r.id)
                }
                return t
            }

            function i(a, e) {
                for (var t = null, o = 0; o < a.length; o++) {
                    var r = a[o];
                    r.archived === !1 && r["catalog-type"].name === e && (t = r.id)
                }
                return t
            }

            function c(a, e) {
                for (var t = null, o = 0; o < a.length; o++) {
                    var r = a[o];
                    r.name === e && (t = r.id)
                }
                return t
            }

            function s(a, e) {
                for (var t = null, o = 0; o < a.length; o++) {
                    var r = a[o];
                    r.id === e && (t = r["legacy-id"])
                }
                return t
            }

            function p(e, t) {
                var o = a.extend({}, e, t);
                return o.catalogLegacyId && "catalogs" !== o.data && (o.catalogId = n(m, o.catalogLegacyId), delete o.catalogLegacyId), o.catalogType && "catalogs" !== o.data && (o.catalogId = i(m, o.catalogType), delete o.catalogType), o.catalogName && "catalogs" !== o.data && (o.catalogId = c(m, o.catalogName), delete o.catalogName), void 0 === o.catalogId && (o.catalogId = null), o.gatewayCatalogId = s(m, o.catalogId), o
            }

            function d(a) {
                var e = a.api + "/widget-api";
                return "catalogs" === a.data ? (e += "/catalogs/?page-size=100&page=1", a.catalogLegacyId && (e += "&legacy-id=" + t.encodeData(a.catalogLegacyId)), a.catalogType && (e += "&type=" + t.encodeData(a.catalogType)), a.catalogName && (e += "&name=" + t.encodeData(a.catalogName))) : "courses" === a.data && a.courseId ? e += "/catalog/" + a.catalogId + "/course/" + a.courseId + "/" : "courses" === a.data ? (e += "/catalog/" + a.catalogId + "/courses/?page-size=100&page=1", a.courseLegacyId && (e += "&legacy-id=" + t.encodeData(a.courseLegacyId)), a.courseType && (e += "&type=" + t.encodeData(a.courseType)), a.coursePrefix && (e += "&prefix=" + t.encodeData(a.coursePrefix)), a.courseCode && (e += "&code=" + t.encodeData(a.courseCode)), a.courseName && (e += "&name=" + t.encodeData(a.courseName))) : "programs" === a.data && a.programId ? e += "/catalog/" + a.catalogId + "/program/" + a.programId + "/" : "programs" === a.data ? (e += "/catalog/" + a.catalogId + "/programs/?page-size=100&page=1", a.programLegacyId && (e += "&legacy-id=" + t.encodeData(a.programLegacyId)), a.programType && (e += "&type=" + t.encodeData(a.programType)), a.programDegreeType && (e += "&degree-type=" + t.encodeData(a.programDegreeType)), a.programCode && (e += "&code=" + t.encodeData(a.programCode)), a.programName && (e += "&name=" + t.encodeData(a.programName))) : "entities" === a.data && a.entityId ? e += "/catalog/" + a.catalogId + "/hierarchy/" + a.entityId + "/" : "entities" === a.data ? (e += "/catalog/" + a.catalogId + "/hierarchies/?page-size=100&page=1", a.entityLegacyId && (e += "&legacy-id=" + t.encodeData(a.entityLegacyId)), a.entityType && (e += "&type=" + t.encodeData(a.entityType)), a.entityName && (e += "&name=" + t.encodeData(a.entityName))) : "filters" === a.data && a.filterId ? e += "/catalog/" + a.catalogId + "/filter/" + a.filterId + "/" : "filters" === a.data ? (e += "/catalog/" + a.catalogId + "/filters/?page-size=100&page=1", a.filterLegacyId && (e += "&legacy-id=" + t.encodeData(a.filterLegacyId)), a.filterName && (e += "&name=" + t.encodeData(a.filterName))) : "pages" === a.data && a.pageId ? e += "/catalog/" + a.catalogId + "/page/" + a.pageId + "/" : "pages" === a.data ? (e += "/catalog/" + a.catalogId + "/pages/?page-size=100&page=1", a.pageLegacyId && (e += "&legacy-id=" + t.encodeData(a.pageLegacyId)), a.pageName && (e += "&name=" + t.encodeData(a.pageName))) : "degree_planner" === a.data && a.programId ? e += "/catalog/" + a.catalogId + "/program/" + a.programId + "/" : "degree_planner" === a.data && (e += "/catalog/" + a.catalogId + "/programs/?page-size=100&page=1", a.programLegacyId && (e += "&legacy-id=" + t.encodeData(a.programLegacyId)), a.programType && (e += "&type=" + t.encodeData(a.programType)), a.programDegreeType && (e += "&degree-type=" + t.encodeData(a.programDegreeType)), a.programCode && (e += "&code=" + t.encodeData(a.programCode)), a.programName && (e += "&name=" + t.encodeData(a.programName))), e
            }

            function y(a, e, t) {
                var o = null;
                return o = a.is("a") ? "catalogs" === t.data ? "catalog-link" : "courses" === t.data ? "course-link" : "programs" === t.data ? "program-link" : "entities" === t.data ? "entity-link" : "filters" === t.data ? "filter-link" : "pages" === t.data ? "page-link" : "degree_planner" === t.data ? "degree-planner-link" : "unknown-link" : a.is("ul") ? "catalogs" === t.data ? "catalog-list" : "courses" === t.data ? "course-list" : "programs" === t.data ? "program-list" : "entities" === t.data ? "entity-list" : "filters" === t.data ? "filter-list" : "pages" === t.data ? "page-list" : "degree_planner" === t.data ? "degree-planner-list" : "unknown-list" : "catalogs" === t.data ? "catalog-content" : "courses" === t.data ? "course-content" : "programs" === t.data ? "program-content" : "entities" === t.data ? "entity-content" : "filters" === t.data ? "filter-content" : "pages" === t.data ? "page-content" : "degree_planner" === t.data ? "degree-planner-content" : "unknown-content"
            }
            var u = this;
            o = void 0 !== o ? o : {}, o.api = void 0 !== o.api ? o.api : null, o.gateway = void 0 !== o.gateway ? o.gateway : null, o.api = null !== o.api ? o.api : o.gateway;
            var m = [],
                f = {
                    "catalog-content": e.CatalogContentWidget,
                    "catalog-list": e.CatalogListWidget,
                    "catalog-link": e.CatalogLinkWidget,
                    "course-content": e.CourseContentWidget,
                    "course-list": e.CourseListWidget,
                    "course-link": e.CourseLinkWidget,
                    "program-content": e.ProgramContentWidget,
                    "program-list": e.ProgramListWidget,
                    "program-link": e.ProgramLinkWidget,
                    "entity-content": e.EntityContentWidget,
                    "entity-list": e.EntityListWidget,
                    "entity-link": e.EntityLinkWidget,
                    "filter-content": e.FilterContentWidget,
                    "filter-list": e.FilterListWidget,
                    "filter-link": e.FilterLinkWidget,
                    "page-content": e.PageContentWidget,
                    "page-list": e.PageListWidget,
                    "page-link": e.PageLinkWidget,
                    "degree-planner-content": e.DegreePlannerContentWidget,
                    "degree-planner-list": e.DegreePlannerListWidget,
                    "degree-planner-link": e.DegreePlannerLinkWidget,
                    "unknown-content": e.UnknownContentWidget,
                    "unknown-list": e.UnknownListWidget,
                    "unknown-link": e.UnknownLinkWidget
                };
            this.widgetize = function(a) {
                var e, o = l(),
                    r = g(a),
                    n = p(o, r),
                    i = d(n);
                t.get(i, [], function(o) {
                    o = t.fauxFilterData(o, n);
                    var r = y(a, o, n);
                    e = new f[r], e.widgetize(a, o, n)
                })
            }, t.get(o.api + "/widget-api/catalogs/", [], function(a) {
                m = null !== a ? a : [], r(u)
            })
        };
    return o
}(jQuery);
