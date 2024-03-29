/*
 * Nitsan Ns-Faq
 */
import {BroadcastMessage} from "@typo3/backend/broadcast-message.js";
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import DocumentService from "@typo3/core/document-service.js";
import {SeverityEnum} from "@typo3/backend/enum/severity.js";
import $ from "jquery";
import BroadcastService from "@typo3/backend/broadcast-service.js";
import Icons from "@typo3/backend/icons.js";
import Modal from "@typo3/backend/modal.js";
import Notification from "@typo3/backend/notification.js";

var Identifiers;
!function (e) {
    e.hide = ".t3js-record-hide", e.delete = ".t3js-record-delete", e.icon = ".t3js-icon"
}(Identifiers || (Identifiers = {}));

class AjaxDataHandler {
    constructor() {
        DocumentService.ready().then((() => {
            this.initialize()
        }))
    }

    static refreshPageTree() {
        top.document.dispatchEvent(new CustomEvent("typo3:pagetree:refresh"))
    }

    static call(e) {
        return new AjaxRequest(TYPO3.settings.ajaxUrls.record_process).withQueryArguments(e).get().then((async e => await e.resolve()))
    }

    process(e, t) {
        return AjaxDataHandler.call(e).then((e => {
            if (e.hasErrors && this.handleErrors(e), t) {
                const a = {...t, hasErrors: e.hasErrors}, n = new BroadcastMessage("datahandler", "process", a);
                BroadcastService.post(n);
                const s = new CustomEvent("typo3:datahandler:process", {detail: {payload: a}});
                document.dispatchEvent(s)
            }
            return e
        }))
    }

    initialize() {
        $(document).on("click", Identifiers.hide, (e => {
            e.preventDefault();
            const t = $(e.currentTarget), a = t.find(Identifiers.icon), n = t.closest("tr[data-uid]"),
                s = t.data("params");
            this._showSpinnerIcon(a), this.process(s).then((e => {
                e.hasErrors || this.toggleRow(n)
            }))
        })), $(document).on("click", Identifiers.delete, (e => {
            e.preventDefault();
            const t = $(e.currentTarget), a = Modal.confirm(t.data("title"), t.data("message"), SeverityEnum.warning, [{
                text: t.data("button-close-text") || "Cancel",
                active: !0,
                btnClass: "btn-default",
                name: "cancel"
            }, {
                text: t.data("button-ok-text") || "Delete",
                btnClass: "btn-warning",
                name: "delete"
            }]);
            a.addEventListener("button.clicked", (e => {
                "cancel" === e.target.getAttribute("name") ? a.hideModal() : "delete" === e.target.getAttribute("name") && (a.hideModal(), this.deleteRecord(t))
            }))
        }))
    }

    toggleRow(e) {
        const t = e.find(Identifiers.hide), a = t.closest("table[data-table]").data("table"), n = t.data("params");
        let s, o, r;
        "hidden" === t.data("state") ? (o = "visible", s = n.replace("=0", "=1"), r = "actions-edit-hide") : (o = "hidden", s = n.replace("=1", "=0"), r = "actions-edit-unhide"), t.data("state", o).data("params", s);
        const i = t.find(Identifiers.icon);
        Icons.getIcon(r, Icons.sizes.small).then((e => {
            i.replaceWith(e)
        }));
        const d = e.find(".col-icon " + Identifiers.icon);
        "hidden" === o ? Icons.getIcon("miscellaneous-placeholder", Icons.sizes.small, "overlay-hidden").then((e => {
            d.append($(e).find(".icon-overlay"))
        })) : d.find(".icon-overlay").remove(), e.fadeTo("fast", .4, (() => {
            e.fadeTo("fast", 1)
        })), "pages" === a && AjaxDataHandler.refreshPageTree()
    }

    deleteRecord(e) {
        const t = e.data("params");
        let a = e.find(Identifiers.icon);
        this._showSpinnerIcon(a);
        const n = e.closest("table[data-table]"), s = n.data("table");
        let o = e.closest("tr[data-uid]");
        const r = o.data("uid"), i = {component: "datahandler", action: "delete", table: s, uid: r};
        this.process(t, i).then((t => {
            if (Icons.getIcon("actions-edit-delete", Icons.sizes.small).then((t => {
                a = e.find(Identifiers.icon), a.replaceWith(t)
            })), !t.hasErrors) {
                const t = e.closest(".panel"), a = t.find(".panel-heading"),
                    i = n.find("[data-l10nparent=" + r + "]").closest("tr[data-uid]");
                if (o = o.add(i), o.fadeTo("slow", .4, (() => {
                    o.slideUp("slow", (() => {
                        o.remove(), 0 === n.find("tbody tr").length && t.slideUp("slow")
                    }))
                })), "0" === e.data("l10parent") || "" === e.data("l10parent")) {
                    const e = Number(a.find(".t3js-table-total-items").html());
                    a.find(".t3js-table-total-items").text(e - 1)
                }
                "pages" === s && AjaxDataHandler.refreshPageTree()
            }
        }))
    }

    handleErrors(e) {
        for (const t of e.messages) Notification.error(t.title, t.message)
    }

    _showSpinnerIcon(e) {
        Icons.getIcon("spinner-circle-dark", Icons.sizes.small).then((t => {
            e.replaceWith(t)
        }))
    }
}

export default new AjaxDataHandler;