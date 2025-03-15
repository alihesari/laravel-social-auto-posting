# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Support for Laravel 12
- Enhanced error handling with custom exceptions
- Rate limit handling with exponential backoff
- Debug mode for Facebook API
- Beta mode for testing new features
- Comprehensive test coverage
- Detailed configuration validation
- Proxy support with authentication
- Message retry mechanism
- Support for scheduled posts
- Advanced targeting options for Facebook posts
- Poll creation for X (Twitter)
- Quote tweet functionality
- Location sharing for all platforms
- Media group support for Telegram
- Inline keyboard support for Telegram messages
- Message editing capabilities
- Message pinning/unpinning
- Channel signature support

### Changed
- Updated Facebook Graph API to v19.0
- Improved error messages and logging
- Enhanced security measures
- Optimized performance with caching
- Updated documentation with comprehensive examples
- Improved code organization and structure
- Enhanced type hints and return types
- Updated dependency requirements

### Deprecated
- None

### Removed
- Support for Laravel versions below 8.0
- Legacy API endpoints
- Deprecated configuration options

### Fixed
- Rate limit handling issues
- Media upload failures
- Authentication token refresh
- Error handling inconsistencies
- Configuration validation bugs
- Proxy connection issues
- Message length validation
- Media type validation
- File size validation

### Security
- Added SSL verification by default
- Enhanced API token handling
- Improved input validation
- Added security headers
- Implemented rate limiting protection
- Added proxy authentication support

## [1.0.0] - 2024-03-20

### Added
- Initial release
- Basic support for Telegram, X (Twitter), and Facebook
- Simple text and media posting
- Basic configuration options
- Initial documentation

[Unreleased]: https://github.com/toolkito/laravel-social-auto-posting/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/toolkito/laravel-social-auto-posting/releases/tag/v1.0.0 