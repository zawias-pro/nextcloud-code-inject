# Changelog

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/).

## [Unreleased]

## [1.0.0] – 2026-03-10

### Added
- Admin settings page to configure HTML snippets for `<head>` and `<body>` injection
- Client-side injector that appends configured HTML on every Nextcloud page load
- Proper handling of `<script>` elements via `document.createElement` to ensure execution
- `#[AuthorizedAdminSetting]` protection on save/load API endpoints
- CHANGELOG.en.md support for Nextcloud update notifications (NC29+)
