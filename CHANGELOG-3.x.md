# Changes in 3.x

All notable changes of the CompatInfoDB 2 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

### Added

- [#49](https://github.com/llaville/php-compatinfo-db/issues/49) Add support to PHP 8.0
- [#52](https://github.com/llaville/php-compatinfo-db/issues/52) Configuration - read it from a compatible PSR11 container

### Changed

- [#50](https://github.com/llaville/php-compatinfo-db/issues/50) Dependency-Injection with Symfony component
replace old `ContainerService` that was introduced in version 2.13
- [#54](https://github.com/llaville/php-compatinfo-db/issues/54) update Sqlite3 reference to support PHP 8.0
- Replaces `InMemoryLocator` in Tactician command bus, by `ContainerLocator` (see https://tactician.thephpleague.com/plugins/container/)

### Fixed

- [#48](https://github.com/llaville/php-compatinfo-db/issues/48) GenericTest - checkValuesFromReference failed to proceed good assertions

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/2.19.0...HEAD