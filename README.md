# KygekWhitelistKick

<a href="https://poggit.pmmp.io/p/KygekWhitelistKick"><img src="https://poggit.pmmp.io/shield.dl.total/KygekWhitelistKick"></a>
[![Discord](https://img.shields.io/discord/735439472992321587.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/CXtqUZv)

This plugin will kick players that are not OP or whitelisted in white-list.txt when server whitelist turned on (/whitelist on). This plugin will be useful if you often need to whitelist your server and you want all not whitelisted players to be kicked automatically. You can change the kick reason and to enable/disable kick via command or config.

# Features

- Kicks not whitelisted players automatically
- Config file can be reset
- Supports `&` as formatting codes
- Enable or disable plugin with command
- Kick reason can be changed using command
- Automatic plugin update checker on server startup

# How to Install

1. Download the latest version (It is recommended to always download the latest version for the best experience, except you're having compatibility issues).
2. Place the `KygekWhitelistKick.phar` file into the `plugins` folder.
3. Restart the server.
4. Done!

# Commands & Permissions

| Command | Description | Permission | Default |
| --- | --- | --- | --- |
| `/whitelistkick help` | Permission to see KygekWhitelistKick commands | `kygekwhitelistkick.cmd.help` | op |
| `/whitelistkick off` | Permission to turn off KygekWhitelistKick | `kygekwhitelistkick.cmd.off` | op |
| `/whitelistkick on` | Permission to turn on KygekWhitelistKick | `kygekwhitelistkick.cmd.on` | op |
| `/whitelistkick set` | Permission to change kick reason | `kygekwhitelistkick.cmd.set` | op |

Use `kygekwhitelistkick.cmd` to give players permission to all subcommands. Typing `/whitelistkick` without args or args other than above will show KygekWhitelistKick help (Player needs to have `kygekwhitelistkick.cmd.help` permission).

Command alias: `/wlkick`

Use `-` before each permission to blacklist the command(s) permission to groups/users in PurePerms (e.g. adding `-kygekwhitelistkick.cmd.set` will blacklist the `/whitelistkick set` command to groups/users).

# Upcoming Features

- Form mode
- Missing config file detection
- And much more

# Additional Notes

- Join our Discord server <a href="https://discord.gg/CXtqUZv">here</a> for latest updates from Kygekraqmak.
- If you found bugs or want to give suggestions, please visit <a href="https://github.com/Kygekraqmak/KygekWhitelistKick/issues">here</a> or DM KygekDev#6415 via Discord.
- We accept any contributions! If you want to contribute please make a pull request in <a href="https://github.com/Kygekraqmak/KygekWhitelistKick/pulls">here</a>.
