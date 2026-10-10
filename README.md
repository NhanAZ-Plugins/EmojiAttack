# EmojiAttack

EmojiAttack adds a random emoji suffix to selected text sent to players on an Axolotl-PM server.

[![Build and validate EmojiAttack plugin](https://github.com/NhanAZ-Plugins/EmojiAttack/actions/workflows/build.yml/badge.svg)](https://github.com/NhanAZ-Plugins/EmojiAttack/actions/workflows/build.yml)

![Example of EmojiAttack text in game](https://user-images.githubusercontent.com/60387689/175365250-802e953a-022c-44e3-a00e-784af9782b94.png)

## Features

- Append a random entry from `🛒.txt` to ordinary text packets and command descriptions.
- Preserve translation placeholders when modifying translated text.
- Leave popup, jukebox popup and tip packets unchanged.
- Optionally add a random configured color before the emoji suffix.

The plugin changes outgoing packet text. Other plugins or server features that send the same packets may also be affected.

## Requirements and compatibility

The manifest requires Axolotl-PM API `5.0.0`. The server requires PHP 8.1 or newer. Packet transformations and PHPStan analysis were checked against the API 5.0.0 and 5.49.1 source revisions. A [pinned workflow](.github/workflows/build.yml) records the exact revisions used in CI. These two checks do not establish compatibility with every Axolotl-PM 5.x release.

EmojiAttack has no runtime virion dependencies.

## Installation and configuration

Download a verified PHAR from [GitHub Releases](https://github.com/NhanAZ-Plugins/EmojiAttack/releases), place it in the server's `plugins` directory and restart the server. The plugin creates `plugins/EmojiAttack/config.yml` and `plugins/EmojiAttack/🛒.txt`. Keep the previous PHAR and copies of both files for rollback.

Add one emoji or text suffix per line to `🛒.txt`. Edit the configuration to control colors, then restart the server to reload the files.

```yaml
randomColor: false
arrColor: ["§e", "§a", "§d", "§c", "§b"]
```

Set `randomColor` to `true` to choose a color from `arrColor` for each suffix. Empty or invalid color entries are ignored. An empty emoji file leaves the original packet text unchanged.

## Build and development

The [GitHub Actions workflow](.github/workflows/build.yml) tests packet transformations against pinned Axolotl-PM source revisions. It uses [DevTools](https://github.com/NhanAZ/DevTools) to run PHPStan at maximum level, build the plugin PHAR and validate the artifact before upload. DevTools and server sources are pinned to verified commits in the workflow.

With an installed Axolotl-PM source checkout, run `php tests/packet-transform.php <server-source-path>` to test text, translation, popup, command description and empty-resource handling. A packet test without a connected Bedrock client does not confirm how the text appears in game.

## Support and license

Report issues through [GitHub Issues](https://github.com/NhanAZ-Plugins/EmojiAttack/issues) or [NhanAZ Discord](https://discord.gg/j2X83ujT6c). EmojiAttack is licensed under the [GNU Lesser General Public License v3.0](LICENSE).
