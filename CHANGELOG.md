# Changelog

## 0.0.3

- Rebuild command descriptions through the current public protocol API while retaining support for the older public field.
- Ignore empty emoji and color lists instead of calling `array_rand()` on an empty array.
- Keep translation text when regular expression replacement returns no result.
- Add a conventional manifest entry class while retaining the original emoji-named class.
- Add packet checks for Axolotl-PM API 5.0.0 and 5.49.1, PHPStan analysis and a pinned DevTools PHAR build.

## 0.0.2

- Historical Axolotl-PM API 5 release.
