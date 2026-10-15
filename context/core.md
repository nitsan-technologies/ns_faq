# ns_faq — Core facts

## Identity

| Item | Value |
|---|---|
| Extension key | `ns_faq` |
| Composer name | `nitsan/ns-faq` |
| Title (ext_emconf) / product name | FAQ Manager for TYPO3 / FAQ |
| Namespace | `NITSAN\NsFaq\` → `Classes/` |
| Version (ext_emconf) | 14.1.0 (latest tag 2.3.0; tags follow an older scheme) |
| Repository | https://github.com/nitsan-technologies/ns_faq (default branch `master`) |
| Licensing status | **Freemium** — this repo is the free version. The NITSAN Free/Freemium sheet needs a Google login; status taken from the T3Planet catalog (`isFree: false` for the premium product) |
| Premium version (T3Planet catalog) | "FAQ", €129, https://t3planet.de/en/typo3-faq-extension |

## Compatibility

| Source | TYPO3 | PHP |
|---|---|---|
| `ext_emconf.php` | `12.4.37-14.9.99` | not declared |
| `composer.json` | `^12.4.37 \|\| ^13 \|\| ^14` | not declared |
| ns_license 14.5.4 (dependency) | `12.4.18-14.9.99` | not declared (TYPO3 12.4 needs 8.1+) |

- ns_license does not raise the minimum: it stays **TYPO3 12.4.37 / PHP 8.1**.

## Dependencies

| Package | Constraint | Why |
|---|---|---|
| `typo3/cms-core` | see above | core |
| `nitsan/ns-license` / `ns_license` | `^14.5.4` / `14.5.4-14.9.99` | TYPO3 Shop module + free-trial deep link (see `features/premium-cta.md`) |

Bundled frontend libraries (no package manager): jQuery 3.4.1, Font Awesome, animate.css, Open Sans.

## Tooling

- No PHPUnit, PHPStan, CS fixer or package.json in the repo.
- CI: `.github/workflows/ter-release.yml` only (publishes tags to TER with tailor on PHP 7.4).
- No Crowdin config; English only: `locallang.xlf` (frontend, constants, preview, premium strip),
  `locallang_db.xlf` (TCA, wizard), `locallang_flex.xlf` (FlexForm), `locallang_faq.xlf`,
  `locallang_faqconstants.xlf`, `BackendModule.xlf`, `locallang_csh_*.xlf`.

## Conventions

- PHP: 4 spaces; the older classes have no `declare(strict_types=1)`, the new premium-strip classes do.
- TypoScript: 4 spaces; Fluid and XLF: tabs.
- Backend CSS in `Resources/Public/Css/` (`backend-premium-strip.css`), frontend assets in
  `Resources/Public/{Css,JavaScript}/frontend/`.

## Known issues (existing, not fixed)

All code-read unless marked.

- **v14 preview (seen live on 14.3.7):** `Resources/Private/Backend/NsFaq.html` reads
  `pi_flexform_transformed.settings.layout`, which TYPO3 14's `FluidBasedContentPreviewRenderer` no longer
  provides (it only assigns `record`), so the "Layout" row shows the row label "FAQs Style" twice instead of
  "FAQs List".
- **FlexForm leftover (seen live):** a `<TCEforms>` block sits directly inside `<el>` (remains of the removed
  `switchableControllerActions` field, value `Faq->list`), so the form shows a meaningless "What would you like
  to display?" select and stores a field named `TCEforms`. The sheet title also still uses the old
  `<TCEforms>` wrapper.
- Backend-module leftovers with no module: `Classes/Utility/*`, `ViewHelpers/Be/TableListViewHelper`,
  `ViewHelpers/Misc/Backend{Edit,New}LinkViewHelper`, `ViewHelpers/ThemeContainsViewHelper`,
  `Resources/Public/JavaScript/{Main,Datatables,ajax-data-handler}.js` (RequireJS `define()`, not loadable on
  v12+), `Resources/Public/Css/{global,main,font-awesome}.css`, `Vendor/bootstrap5.3.min.css`,
  `BackendModule.xlf`, icon `module-nsfaq`, and a `module.tx_nsfaq_faq` TypoScript block that points to missing
  `Backend/*` folders and `module.tx_nsbackup_backup` constants.
- `FaqRepository::fetchConstants()` is unused and calls `->fetch()`, which no longer exists on Doctrine DBAL
  results in TYPO3 12+.
- `ext_tables.sql` has `submittername`, `submitteremail`, `faqcategories`, `accesstoken` and `views` columns
  without TCA (premium leftovers).
- `Configuration/TCA/Overrides/tt_content.php` sets the FlexForm with
  `ExtensionManagementUtility::addPiFlexFormValue()`, which is deprecated in TYPO3 14 (removed in v15) and logs
  a deprecation there.
- Frontend CSS/JS (`default.css`, Font Awesome, animate.css, `default.js`) are added to every page, not only
  pages with FAQs. `default.js` needs jQuery, which is off by default (`includeJquery = 0` / `false`).
- The plugin shows all FAQs of the storage folder; there is no category or selection in the free version.
- README badges show v13.0.2 / TYPO3 13 and GPL-3.0; `composer.json` uses the deprecated SPDX id `GPL-2.0+`;
  `page.tsconfig` sets a legacy `icon = ../typo3conf/...` path next to `iconIdentifier`.
