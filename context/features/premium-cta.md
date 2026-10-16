# Feature — Premium strip and ns_license free trial

*Status: Done. Verified live on TYPO3 14.3.7 (2026-10-15): strip in the Page module preview and above the
plugin options; the button opens the ns_license module with "FAQ (ns_faq)" preselected in the trial modal.
Requires ns_license 14.5.4 (deep-link support) to be released. v12/v13 not tested.*

## Licensing status

Freemium. Premium product (T3Planet catalog): "FAQ", €129, https://t3planet.de/en/typo3-faq-extension (not
linked from the UI; the trial stays inside the TYPO3 backend). Catalog features: FAQ management dashboard,
category-based FAQs, visitor submissions, accordion layouts, searchable FAQs, moderation workflow, three-level
nesting.

## Dependency

`composer.json` `"nitsan/ns-license": "^14.5.4"`, `ext_emconf.php` `'ns_license' => '14.5.4-14.9.99'`. The
minimum stays TYPO3 12.4.37 (see `core.md`).

## Placements

| Place | Position |
|---|---|
| Page module, preview of an FAQ content element | Above the "FAQs / List View" table (`<faq:be.premiumStrip />` in `Resources/Private/Backend/NsFaq.html`) |
| Content element edit form, tab "Configuration" | Above the plugin options (`pi_flexform`) |

## How it works

- `Backend\PremiumStrip::render()`: `actions-rocket` icon, headline and description on two lines,
  `btn btn-primary` "Start Free Trial →" (the arrow is `aria-hidden` markup). Link = route
  `nitsan_nslicensemodule` + `trial=ns_faq` + `#free-section`, same frame. Returns `''` without ns_license, so
  neither placement shows anything.
- Preview: `ViewHelpers\Be\PremiumStripViewHelper` renders the strip and adds the stylesheet through
  `PageRenderer`. The preview template declares the `faq` namespace inline because it is rendered standalone.
- Edit form: `ext_localconf.php` registers `Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver` for node
  `flexFormNoTabsContainer`, priority **45** (taken: ns_news_comments 40, ns_event 41, ns_gallery 42,
  ns_helpdesk 43, ns_social_login 44, ns_open_streetmap 46, ns_comments 47, ns_timeline 48, ns_all_sliders 49; duplicates make core throw). It matches `tt_content.pi_flexform` of
  CType `nsfaq_faq` (or legacy `list` + `list_type=nsfaq_faq`), not inline children, and returns
  `Form\Container\PremiumStripFlexFormNoTabsContainer`, which prepends the strip and adds the stylesheet.
  If the FlexForm ever gets a second sheet, core switches to `flexFormTabsContainer` and the strip disappears
  from the form.

## Strip

- Labels in `locallang.xlf`: `premium.strip.headline` "You're using the free version of FAQ",
  `premium.strip.description` "Unlock premium features and get more out of FAQ.",
  `premium.strip.trial` "Start Free Trial".
- `Resources/Public/Css/backend-premium-strip.css`: `.ns-faq-premium-strip` — team-wide design (light-blue
  card `#e7f2fc`, border `#8eb6e8`, 4px `#2b6cb0` left accent, 6px radius, padding 14px 18px, `light-dark()`
  for dark mode, button `#007bff` / hover `#0062cc`).
- Measured on v14.3.7: 12px text, 13.2px headline, 12px button; 67px tall when it fits on one line (edit form,
  wide preview column), the button wraps below the text in narrow preview columns.
- ns_license's module requires system maintainer access; other users get access denied after clicking.
