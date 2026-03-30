# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-03-30

### Added
- Added PHP 8.2 requirement
- Added support for Laravel 13

### Changed
- Updated `illuminate/support` to support `^11.0 || ^12.0 || ^13.0`
- Updated `orchestra/testbench` to support `^10.4 || ^11.0`
- Updated `phpunit/phpunit` to support `^12.0 || ^13.0`
- Updated `laravel/pint` to `^1.29`
- Dropped support for Laravel 10

### Fixed
- Fixed `getPointInquiry()` method in `CreditCard.php` to use correct static method call
- Fixed missing trailing slash in `getPointInquiry()` endpoint path

## [1.0.3] - 2025-07-24

### Added
- Implemented `chargeTransaction` method in Base trait

## [1.0.2] - 2025-07-24

### Added
- Added `cancelTransaction`, `refundTransaction`, and `directRefundTransaction` methods to Midtrans facade

## [1.0.1] - 2025-07-18

### Added
- Added `translateFraudStatus` method
- Added `FraudStatus` translator class

## [1.0.0] - 2025-07-18

### Added
- Initial release
- Support for Credit Card payments
- Support for GoPay payments
- Transaction status translation
- Subscription management
- Refund and cancellation functionality
