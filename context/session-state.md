# ns_faq — Session state

## 2026-10-15 — ns_license integration + premium strip + agent context

- Working copy on `master` (3c65054), uncommitted; no feature branch created.
- Added `nitsan/ns-license ^14.5.4` / `ns_license 14.5.4-14.9.99` (minimum stays TYPO3 12.4.37).
  Version 14.0.1 → 14.1.0.
- Premium strip with "Start Free Trial" → ns_license module `?trial=ns_faq#free-section` in the Page module
  preview and above the plugin options (tab "Configuration"), team-wide wording and design
  (see `features/premium-cta.md`).
- Verified live on TYPO3 14.3.7 classic (temporary FAQ content element on page 10 and temporary
  system-maintainer admin, both removed; `settings.php` restored byte-identical). PHP 8.4 and 8.1 lint clean.
- Created agent context (`AGENTS.md`, `CLAUDE.md`, `context/`, `tasks/`); no previous context existed.
- Open: ns_license 14.5.4 not yet published; v12/v13 not tested; frontend only code-read.

## 2026-10-15 — Context audit

- Code-read audit: completed the list of TCA-less `ext_tables.sql` columns, noted the v14 `addPiFlexFormValue()`
  deprecation (`core.md`) and that the frontend ignores `settings.layout` (`features/faq-plugin.md`).
