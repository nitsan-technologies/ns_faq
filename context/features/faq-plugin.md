# Feature — FAQ plugin

*Status: Done. Code-read (2026-10-15); content element, edit form and Page module preview seen on v14.3.7,
frontend not tested.*

## Editor flow

1. Create FAQ records (`tx_nsfaq_domain_model_faq`: title + RTE content) in a folder; order by drag and drop
   (`sorting`).
2. Add the content element "FAQs" (wizard group plugins, CType `nsfaq_faq`); in tab "Configuration" pick the
   folder in "Record Storage Page" (`pages`) and the layout (only "FAQs List").
3. Include the static template or the site set `nitsan/ns-faq`.

## Rendering

- `FaqController::listAction()` → `findAll()` from the storage folder, ordered by `sorting`; "No FAQs
  Available." when empty.
- The FlexForm `settings.layout` is only read by the Page module preview; the frontend templates ignore it.
- Accordion markup in `Partials/FaqToggle.html`; `default.js` toggles `.ns-faq-body` and the scroll-to-top
  button (`basicSettings.general.scrollToTop`).
- Custom CSS: `settings.usercss` (a file; nothing in the extension sets it, only custom TypoScript) wins over
  `basicSettings.general.customCSS` (inline).
- jQuery 3.4.1 is only included when `includeJquery` is on (constant or set setting), but `default.js` is
  plain jQuery: without a jQuery from the site the accordion does not open.

## Settings

| Classic constant | Site set setting |
|---|---|
| `plugin.tx_nsfaq_faq.view.{templateRootPath,partialRootPath,layoutRootPath}` | `nsFaq.{templateRootPath,partialRootPath,layoutRootPath}` |
| `plugin.tx_nsfaq_faq.basicSettings.general.includeJquery` | `nsFaq.includeJquery` |
| `plugin.tx_nsfaq_faq.basicSettings.general.customCSS` | `nsFaq.customCSS` |
| `plugin.tx_nsfaq_faq.basicSettings.general.scrollToTop` | `nsFaq.scrollToTop` |

## Page module preview

`Resources/Private/Backend/NsFaq.html`: premium strip + table "FAQs / List View / Layout". The layout value is
wrong on v14 (see `core.md`).
