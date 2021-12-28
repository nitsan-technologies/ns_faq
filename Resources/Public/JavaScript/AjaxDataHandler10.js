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
var __importDefault = this && this.__importDefault || function (e) {
    return e && e.__esModule ? e : {default: e}
};
define([
    "require",
    "exports",
    "TYPO3/CMS/Backend/BroadcastMessage",
    "TYPO3/CMS/Core/Ajax/AjaxRequest",
    "TYPO3/CMS/Backend/Enum/Severity",
    "jquery",
    "TYPO3/CMS/Backend/BroadcastService",
    "TYPO3/CMS/Backend/Icons",
    "TYPO3/CMS/Backend/Modal",
    "TYPO3/CMS/Backend/Notification",
    "TYPO3/CMS/Backend/Viewport"
], (function (e, t, a, n, s, i, o, r, d, l, c) {
    "use strict";
    var h;
    i = __importDefault(i), function (e) {
        e.hide = ".t3js-record-hide", e.delete = ".t3js-record-delete", e.icon = ".t3js-icon"
    }(h || (h = {}));

    class u {
        static refreshPageTree() {
            c.NavigationContainer && c.NavigationContainer.PageTree && c.NavigationContainer.PageTree.refreshTree()
        }

        static call(e) {
            return new n(TYPO3.settings.ajaxUrls.record_process).withQueryArguments(e).get().then(async e => await e.resolve())
        }

        constructor() {
            i.default(() => {
                this.initialize()
            })
        }

        process(e, t) {
            return u.call(e).then(e => {
                if (e.hasErrors && this.handleErrors(e), t) {
                    const n = Object.assign(Object.assign({}, t), {hasErrors: e.hasErrors}),
                        s = new a.BroadcastMessage("datahandler", "process", n);
                    o.post(s);
                    const i = new CustomEvent("typo3:datahandler:process", {detail: {payload: n}});
                    document.dispatchEvent(i)
                }
                return e
            })
        }

        initialize() {
            i.default(document).on("click", h.hide, e => {
                e.preventDefault();
                const t = i.default(e.currentTarget), a = t.find(h.icon), n = t.closest("tr[data-uid]"),
                    s = t.data("params");
                this._showSpinnerIcon(a), this.process(s).then(e => {
                    e.hasErrors || this.toggleRow(n)
                })
            }), i.default(document).on("click", h.delete, e => {
                e.preventDefault();
                const t = i.default(e.currentTarget);
                t.tooltip("hide");
                d.confirm(t.data("title"), t.data("message"), s.SeverityEnum.warning, [{
                    text: t.data("button-close-text") || "Cancel",
                    active: !0,
                    btnClass: "btn-default",
                    name: "cancel"
                }, {
                    text: t.data("button-ok-text") || "Delete",
                    btnClass: "btn-warning",
                    name: "delete"
                }]).on("button.clicked", e => {
                    "cancel" === e.target.getAttribute("name") ? d.dismiss() : "delete" === e.target.getAttribute("name") && (d.dismiss(), this.deleteRecord(t))
                })
            })
        }

        toggleRow(e) {
            const t = e.find(h.hide), a = t.closest("table[data-table]").data("table"), n = t.data("params");
            let s, o, d;
            "hidden" === t.data("state") ? (o = "visible", s = n.replace("=0", "=1"), d = "actions-edit-hide") : (o = "hidden", s = n.replace("=1", "=0"), d = "actions-edit-unhide"), t.data("state", o).data("params", s), t.tooltip("hide").one("hidden.bs.tooltip", () => {
                const e = t.data("toggleTitle");
                t.data("toggleTitle", t.attr("data-original-title")).attr("data-original-title", e)
            });
            const l = t.find(h.icon);
            r.getIcon(d, r.sizes.small).then(e => {
                l.replaceWith(e)
            });
            const c = e.find(".col-icon " + h.icon);
            "hidden" === o ? r.getIcon("miscellaneous-placeholder", r.sizes.small, "overlay-hidden").then(e => {
                c.append(i.default(e).find(".icon-overlay"))
            }) : c.find(".icon-overlay").remove(), e.fadeTo("fast", .4, () => {
                e.fadeTo("fast", 1)
            }), "pages" === a && u.refreshPageTree()
        }

        deleteRecord(e) {
            const t = e.data("params");
            let a = e.find(h.icon);
            this._showSpinnerIcon(a);
            const n = e.closest("table[data-table]"), s = n.data("table");
            let i = e.closest("tr[data-uid]");
            const o = i.data("uid"), d = {component: "datahandler", action: "delete", table: s, uid: o};
            this.process(t, d).then(t => {
                if (r.getIcon("actions-edit-delete", r.sizes.small).then(t => {
                    a = e.find(h.icon), a.replaceWith(t)
                }), !t.hasErrors) {
                    const t = e.closest(".panel"), a = t.find(".panel-heading"),
                        r = n.find("[data-l10nparent=" + o + "]").closest("tr[data-uid]");
                    if (i = i.add(r), i.fadeTo("slow", .4, () => {
                        i.slideUp("slow", () => {
                            i.remove(), 0 === n.find("tbody tr").length && t.slideUp("slow")
                        })
                    }), "0" === e.data("l10parent") || "" === e.data("l10parent")) {
                        const e = Number(a.find(".t3js-table-total-items").html());
                        a.find(".t3js-table-total-items").text(e - 1)
                    }
                    "pages" === s && u.refreshPageTree()
                }
            })
        }

        handleErrors(e) {
            i.default.each(e.messages, (e, t) => {
                l.error(t.title, t.message)
            })
        }

        _showSpinnerIcon(e) {
            r.getIcon("spinner-circle-dark", r.sizes.small).then(t => {
                e.replaceWith(t)
            })
        }
    }

    return new u
}));