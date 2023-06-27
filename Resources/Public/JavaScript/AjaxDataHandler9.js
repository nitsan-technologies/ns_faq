/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
define([
    "require",
    "exports",
    "TYPO3/CMS/Backend/Enum/Severity",
    "jquery",
    "TYPO3/CMS/Backend/Icons",
    "TYPO3/CMS/Backend/Modal",
    "TYPO3/CMS/Backend/Notification",
    "TYPO3/CMS/Backend/Viewport"
], (function (e, t, n, a, i, o, r, d) {
    "use strict";
    var l;
    return function (e) {
        e.hide = ".t3js-record-hide", e.delete = ".t3js-record-delete", e.icon = ".t3js-icon"
    }(l || (l = {})), new (function () {
        function e() {
            var e = this;
            a((function () {
                e.initialize()
            }))
        }

        return e.refreshPageTree = function () {
            d.NavigationContainer && d.NavigationContainer.PageTree && d.NavigationContainer.PageTree.refreshTree()
        }, e.prototype.process = function (e) {
            var t = this;
            return this._call(e).done((function (e) {
                e.hasErrors && t.handleErrors(e)
            }))
        }, e.prototype.initialize = function () {
            var e = this;
            a(document).on("click", l.hide, (function (t) {
                t.preventDefault();
                var n = a(t.currentTarget), i = n.find(l.icon), o = n.closest("tr[data-uid]"), r = n.data("params");
                e._showSpinnerIcon(i), e._call(r).done((function (t) {
                    t.hasErrors ? e.handleErrors(t) : e.toggleRow(o)
                }))
            })), a(document).on("click", l.delete, (function (t) {
                t.preventDefault();
                var i = a(t.currentTarget);
                i.tooltip("hide"), o.confirm(i.data("title"), i.data("message"), n.SeverityEnum.warning, [{
                    text: i.data("button-close-text") || "Cancel",
                    active: !0,
                    btnClass: "btn-default",
                    name: "cancel"
                }, {
                    text: i.data("button-ok-text") || "Delete",
                    btnClass: "btn-warning",
                    name: "delete"
                }]).on("button.clicked", (function (t) {
                    "cancel" === t.target.getAttribute("name") ? o.dismiss() : "delete" === t.target.getAttribute("name") && (o.dismiss(), e.deleteRecord(i))
                }))
            }))
        }, e.prototype.toggleRow = function (t) {
            var n, o, r, d = t.find(l.hide), s = d.closest("table[data-table]").data("table"), c = d.data("params");
            "hidden" === d.data("state") ? (o = "visible", n = c.replace("=0", "=1"), r = "actions-edit-hide") : (o = "hidden", n = c.replace("=1", "=0"), r = "actions-edit-unhide"), d.data("state", o).data("params", n), d.tooltip("hide").one("hidden.bs.tooltip", (function () {
                var e = d.data("toggleTitle");
                d.data("toggleTitle", d.attr("data-original-title")).attr("data-original-title", e)
            }));
            var f = d.find(l.icon);
            i.getIcon(r, i.sizes.small).done((function (e) {
                f.replaceWith(e)
            }));
            var u = t.find(".col-icon " + l.icon);
            "hidden" === o ? i.getIcon("miscellaneous-placeholder", i.sizes.small, "overlay-hidden").done((function (e) {
                u.append(a(e).find(".icon-overlay"))
            })) : u.find(".icon-overlay").remove(), t.fadeTo("fast", .4, (function () {
                t.fadeTo("fast", 1)
            })), "pages" === s && e.refreshPageTree()
        }, e.prototype.deleteRecord = function (t) {
            var n = this, a = t.data("params"), o = t.find(l.icon);
            this._showSpinnerIcon(o), this._call(a).done((function (a) {
                if (i.getIcon("actions-edit-delete", i.sizes.small).done((function (e) {
                    (o = t.find(l.icon)).replaceWith(e)
                })), a.hasErrors) n.handleErrors(a); else {
                    var r = t.closest("table[data-table]"), d = t.closest(".panel"), s = d.find(".panel-heading"),
                        c = r.data("table"), f = t.closest("tr[data-uid]"), u = f.data("uid"),
                        p = r.find("[data-l10nparent=" + u + "]").closest("tr[data-uid]");
                    if ((f = f.add(p)).fadeTo("slow", .4, (function () {
                        f.slideUp("slow", (function () {
                            f.remove(), 0 === r.find("tbody tr").length && d.slideUp("slow")
                        }))
                    })), "0" === t.data("l10parent") || "" === t.data("l10parent")) {
                        var h = Number(s.find(".t3js-table-total-items").html());
                        s.find(".t3js-table-total-items").text(h - 1)
                    }
                    "pages" === c && e.refreshPageTree()
                }
            }))
        }, e.prototype.handleErrors = function (e) {
            a.each(e.messages, (function (e, t) {
                r.error(t.title, t.message)
            }))
        }, e.prototype._call = function (e) {
            return a.getJSON(TYPO3.settings.ajaxUrls.record_process, e)
        }, e.prototype._showSpinnerIcon = function (e) {
            i.getIcon("spinner-circle-dark", i.sizes.small).done((function (t) {
                e.replaceWith(t)
            }))
        }, e
    }())
}));