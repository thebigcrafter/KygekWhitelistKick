<h1 align="center">KygekWhitelistKick</h1>

<p align="center">
<a href="https://poggit.pmmp.io/p/KygekWhitelistKick"><img src="https://poggit.pmmp.io/shield.dl.total/KygekWhitelistKick?style=for-the-badge" alt="poggit" /></a>
<a href="https://github.com/thebigcrafter/KygekWhitelistKick#GPL-3.0-1"><img src="https://img.shields.io/github/license/thebigcrafter/KygekWhitelistKick?style=for-the-badge" alt="license" /></a>
<a href="https://discord.gg/PykBfE2TZ9"><img src="https://img.shields.io/discord/1087729577004122112?color=7289DA&label=discord&logo=discord&style=for-the-badge" alt="discord" /></a>
</p>

# 📖 About

This plugin will kick players that are not OP or whitelisted in white-list.txt when server whitelist turned on (/whitelist on). This plugin will be useful if you often need to whitelist your server and you want all not whitelisted players to be kicked automatically. You can change the kick reason and to enable/disable kick via command or config.

# 🧩 Features

- Kicks not whitelisted players automatically
- Config file can be reset
- Supports `&` as formatting codes
- Enable or disable plugin with command
- Kick reason can be changed using command
- Command and form mode
- Automatic plugin update checker on server startup
- Missing configuration file detection

# ⬇️ Installation

1. Download the latest version (It is recommended to always download the latest version for the best experience, except you're having compatibility issues).
2. Place the `KygekWhitelistKick.phar` file into the `plugins` folder.
3. Restart the server.
4. Done!

# 📜 Commands & Permissions

**Note:** KygekWhitelistKick uses commands by default. To switch to form mode, set `mode` in `config.yml` to `form`. After switching, execute `/whitelistkick` to open KygekWhitelistKick form.

| Command | Description | Permission | Default |
| --- | --- | --- | --- |
| `/whitelistkick help` | Display KygekWhitelistKick subcommands | `kygekwhitelistkick.cmd.help` | op |
| `/whitelistkick off` | Disable automatic kick on whitelist enabled | `kygekwhitelistkick.cmd.off` | op |
| `/whitelistkick on` | Enable automatic kick on whitelist enabled | `kygekwhitelistkick.cmd.on` | op |
| `/whitelistkick set` | Change whitelist kick reason | `kygekwhitelistkick.cmd.set` | op |

💡 Tips:
- Use `kygekwhitelistkick.cmd` to give players permission to all subcommands. Typing `/whitelistkick` without args or args other than above will show KygekWhitelistKick help (Player needs to have `kygekwhitelistkick.cmd.help` permission).
- Command alias: `/wlkick`
- Use `-` before each permission to blacklist the command(s) permission to groups/users in PurePerms (e.g. adding `-kygekwhitelistkick.cmd.set` will blacklist the `/whitelistkick set` command to groups/users).

# 🚢 Other Versions

- [Nukkit](https://github.com/KygekTeam/KygekWhitelistKick-Nukkit)

# ⚖️ License

Licensed under the [GNU General Public License v3.0](https://github.com/thebigcrafter/KygekWhitelistKick#GPL-3.0-1) license.
