# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## v0.0.1 - 2021-02-15
### Added
- Preview version.

## v0.1.0 - 2021-03-23
### Added…
- ability to customize button background with the property `buttonbg`.
- emoji to default button text (now reading “✌️ Clone item ⚠️”) to clarify the *danger zone* character of the button.
### Changed…
- filename `frwssr_cloneitem.php` to `index.php` for better structure and readability.
### Updated…
- `init.js` to reflect changed filename mentioned above.
- `index.php` (formerly known as `frwssr_cloneitem.php`) to make better use of the Perch API.
- `readme.md` to keep up with the additions above.

## v0.1.1 - 2021-04-01
### Added…
- …ability to define fields, that should be unset, e.g. slug fields, with the property `unsetfields`.