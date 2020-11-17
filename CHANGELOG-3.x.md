# Changes in 3.x

All notable changes of the CompatInfoDB 2 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

### Added

- PHP 7.3.24 support
- PHP 7.4.12 support
- [#49](https://github.com/llaville/php-compatinfo-db/issues/49) PHP 8.0 support
- [#52](https://github.com/llaville/php-compatinfo-db/issues/52) Configuration - read it from a compatible PSR11 container

### Changed

- new `bartlett:db:release` command combines old `bartlett:db:release:php` and `bartlett:db:publish:php` commands that were removed
- [#50](https://github.com/llaville/php-compatinfo-db/issues/50) Dependency-Injection with Symfony component
replace old `ContainerService` that was introduced in version 2.13
- [#54](https://github.com/llaville/php-compatinfo-db/issues/54) update Sqlite3 reference to support PHP 8.0
- Replaces `InMemoryLocator` in Tactician command bus, by `ContainerLocator` (see https://tactician.thephpleague.com/plugins/container/)
- [#56](https://github.com/llaville/php-compatinfo-db/issues/56) Lite alternative to `laminas-diagnostics` solution
- APCu reference updated to version 5.1.19 (stable)

### Fixed

- [#13](https://github.com/llaville/php-compatinfo-db/issues/13) Missing Reference entries not detected by standard suite tests
- [#48](https://github.com/llaville/php-compatinfo-db/issues/48) GenericTest - checkValuesFromReference failed to proceed good assertions
- [#55](https://github.com/llaville/php-compatinfo-db/issues/55) Wrong assertion results when method checks
- [#57](https://github.com/llaville/php-compatinfo-db/issues/57) GenericTest - function_exists failed to proceed expected assertion with Polyfills

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/2.19.0...HEAD
