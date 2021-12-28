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
    "TYPO3/CMS/Backend/Notification"
], (function (e, t, a, s, n, i, o, r, d, l) {
    "use strict";
    var c;
    i = __importDefault(i), function (e) {
        e.hide = ".t3js-record-hide", e.delete = ".t3js-record-delete", e.icon = ".t3js-icon"
    }(c || (c = {}));

    class h {
        static refreshPageTree() {
            top.document.dispatchEvent(new CustomEvent("typo3:pagetree:refresh"))
        }

        static call(e) {
            return new s(TYPO3.settings.ajaxUrls.record_process).withQueryArguments(e).get().then(async e => await e.resolve())
        }

        constructor() {
            i.default(() => {
                this.initialize()
            })
        }

        process(e, t) {
            return h.call(e).then(e => {
                if (e.hasErrors && this.handleErrors(e), t) {
                    const s = Object.assign(Object.assign({}, t), {hasErrors: e.hasErrors}),
                        n = new a.BroadcastMessage("datahandler", "process", s);
                    o.post(n);
                    const i = new CustomEvent("typo3:datahandler:process", {detail: {payload: s}});
                    document.dispatchEvent(i)
                }
                return e
            })
        }

        initialize() {
            i.default(document).on("click", c.hide, e => {
                e.preventDefault();
                const t = i.default(e.currentTarget), a = t.find(c.icon), s = t.closest("tr[data-uid]"),
                    n = t.data("params");
                this._showSpinnerIcon(a), this.process(n).then(e => {
                    e.hasErrors || this.toggleRow(s)
                })
            }), i.default(document).on("click", c.delete, e => {
                e.preventDefault();
                const t = i.default(e.currentTarget);
                t.tooltip("hide");
                d.confirm(t.data("title"), t.data("message"), n.SeverityEnum.warning, [{
                    text: "Cancel",
                    active: !0,
                    btnClass: "btn-default",
                    name: "cancel"
                }, {
                    text: "Delete",
                    btnClass: "btn-warning",
                    name: "delete"
                }]).on("button.clicked", e => {
                    "cancel" === e.target.getAttribute("name") ? d.dismiss() : "delete" === e.target.getAttribute("name") && (d.dismiss(), this.deleteRecord(t))
                })
            })
        }

        toggleRow(e) {
            const t = e.find(c.hide), a = t.closest("table[data-table]").data("table"), s = t.data("params");
            let n, o, d;
            "hidden" === t.data("state") ? (o = "visible", n = s.replace("=0", "=1"), d = "actions-edit-hide") : (o = "hidden", n = s.replace("=1", "=0"), d = "actions-edit-unhide"), t.data("state", o).data("params", n), t.one("hidden.bs.tooltip", () => {
                const e = t.data("toggleTitle");
                t.data("toggleTitle", t.attr("data-bs-original-title")).attr("data-bs-original-title", e)
            }), t.tooltip("hide");
            const l = t.find(c.icon);
            r.getIcon(d, r.sizes.small).then(e => {
                l.replaceWith(e)
            });
            const u = e.find(".col-icon " + c.icon);
            "hidden" === o ? r.getIcon("miscellaneous-placeholder", r.sizes.small, "overlay-hidden").then(e => {
                u.append(i.default(e).find(".icon-overlay"))
            }) : u.find(".icon-overlay").remove(), e.fadeTo("fast", .4, () => {
                e.fadeTo("fast", 1)
            }), "pages" === a && h.refreshPageTree()
        }

        deleteRecord(e) {
            const t = e.data("params");
            let a = e.find(c.icon);
            this._showSpinnerIcon(a);
            const s = e.closest("table[data-table]"), n = s.data("table");
            let i = e.closest("tr[data-uid]");
            const o = i.data("uid"), d = {component: "datahandler", action: "delete", table: n, uid: o};
            this.process(t, d).then(t => {
                if (r.getIcon("actions-edit-delete", r.sizes.small).then(t => {
                    a = e.find(c.icon), a.replaceWith(t)
                }), !t.hasErrors) {
                    const t = e.closest(".panel"), a = t.find(".panel-heading"),
                        r = s.find("[data-l10nparent=" + o + "]").closest("tr[data-uid]");
                    if (i = i.add(r), i.fadeTo("slow", .4, () => {
                        i.slideUp("slow", () => {
                            i.remove(), 0 === s.find("tbody tr").length && t.slideUp("slow")
                        })
                    }), "0" === e.data("l10parent") || "" === e.data("l10parent")) {
                        const e = Number(a.find(".t3js-table-total-items").html());
                        a.find(".t3js-table-total-items").text(e - 1)
                    }
                    "pages" === n && h.refreshPageTree()
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

    return new h
}));