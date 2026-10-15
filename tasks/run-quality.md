# Run quality checks

The repo has no PHPUnit, PHPStan or CS config. Available checks:

```bash
# PHP syntax; also run once with PHP 8.1, the lowest version TYPO3 12 supports
for f in $(find . -name "*.php" -not -path "./.git/*"); do php -l "$f"; done

# composer.json
composer validate --no-check-publish

# XLF well-formedness
for f in Resources/Private/Language/*.xlf; do xmllint --noout "$f"; done
```

## Manual test (per TYPO3 major 12 / 13 / 14)

1. Page module, page with an "FAQs" content element: strip above the "FAQs / List View" table (on every
   page, with or without the site set; core loads `Configuration/page.tsconfig` globally).
2. Edit the element, tab "Configuration": strip above the plugin options. Other plugins' FlexForms show no
   ns_faq strip (other NITSAN strips may appear for their own plugins).
3. "Start Free Trial" (as system maintainer) opens the ns_license module and its trial modal with
   "FAQ (ns_faq)" preselected. Cancel; don't start a trial.
4. Deactivate ns_license (test system only): both placements show nothing and the form still saves.
5. Frontend: FAQs from the storage folder open and close; scroll-to-top and custom CSS work when enabled.
6. Check light and dark backend themes.

Remove test content elements and FAQ records afterwards.
