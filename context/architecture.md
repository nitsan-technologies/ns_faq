# ns_faq — Architecture

One Extbase content-element plugin (`nsfaq_faq`, action `list`), one table, no backend module. Backend
additions: Page module preview, FormEngine resolver for the premium strip.

## Configuration

| File | What |
|---|---|
| `ext_localconf.php` | Plugin `NsFaq`/`Faq` as `PLUGIN_TYPE_CONTENT_ELEMENT` (cached `list`); FormEngine `nodeResolver` 1792047381 (`flexFormNoTabsContainer`, priority 45) |
| `Configuration/TCA/Overrides/tt_content.php` | Registers CType `nsfaq_faq` ("FAQs", group `plugins`), tab "Configuration" with `pi_flexform` + `pages` (record storage), FlexForm `NsFaq_flexForm.xml` |
| `Configuration/TCA/tx_nsfaq_domain_model_faq.php` | FAQ record (`faq_title`, `faq_content` RTE, language, hidden, start/end time, `sortby` `sorting`) |
| `Configuration/FlexForm/NsFaq_flexForm.xml` | One sheet: leftover display-mode field (see `core.md`) + `settings.layout` (only value 2 "FAQs List") |
| `Configuration/page.tsconfig` | New content element wizard item + `web_layout.tt_content.preview.nsfaq_faq` → `Resources/Private/Backend/NsFaq.html` (loaded globally by core since v12; the site set imports it again) |
| `Configuration/TCA/Overrides/sys_template.php` | Static template (`Configuration/TypoScript`) |
| `Configuration/TypoScript/` | Constants (`plugin.tx_nsfaq_faq.view.*`, `basicSettings.general.{includeJquery,customCSS,scrollToTop}`), setup (view paths, settings, global `page.includeCSS` / `includeJSFooter`, jQuery condition) |
| `Configuration/Sets/NsFaq/` | Site set `nitsan/ns-faq`; settings `nsFaq.{templateRootPath,partialRootPath,layoutRootPath,includeJquery,customCSS,scrollToTop}`; setup imports the classic setup and overrides with the set settings; imports `page.tsconfig` |
| `Configuration/Services.yaml` | Autowires `Classes/*` except `Domain/Model` |
| `Configuration/Icons.php` | `ns_faq-plugin-faq`, `nsfaq_icon`, `module-nsfaq` |
| `Configuration/JavaScriptModules.php` | Import map `@nitsan/ns-faq/` (nothing imports it) |

## Classes

| Class | Role |
|---|---|
| `Controller\FaqController` | `listAction`: `findAll()` (storage from the element's `pages`), custom CSS file or inline CSS |
| `Domain\Model\Faq`, `Domain\Repository\FaqRepository` | FAQ record, ordered by `sorting` |
| `Updates\PluginMigration` | Upgrade wizard `txNsFaqPluginMigration`: `list` + `list_type=nsfaq_faq` → CType `nsfaq_faq` |
| `Backend\PremiumStrip` | Strip HTML + trial URL; `''` when the ns_license route is missing |
| `Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver` | Picks the strip container for `pi_flexform` of FAQ elements |
| `Form\Container\PremiumStripFlexFormNoTabsContainer` | Core single-sheet FlexForm container + strip |
| `ViewHelpers\Be\PremiumStripViewHelper` | Strip in the Page module preview |
| `Utility\*`, other ViewHelpers | Unused leftovers (see `core.md`) |

## Views

- Frontend: `Templates/Faq/List.html` → `Layouts/Default.html` (wrapper, optional scroll-to-top) →
  `Partials/FaqToggle.html` (accordion, `fadeIn` reveal).
- Backend: `Resources/Private/Backend/NsFaq.html` (preview).

## Assets

- Frontend: `Css/frontend/{default.css,fontAwesome,animate,fonts,webfonts}`,
  `JavaScript/frontend/{default.js,jquery-3.4.1.min.js}`.
- Backend: `Css/backend-premium-strip.css`; everything else in `Css/` and `JavaScript/` root is unused.
