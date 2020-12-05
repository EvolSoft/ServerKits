![start2](https://cloud.githubusercontent.com/assets/10303538/6315586/9463fa5c-ba06-11e4-8f30-ce7d8219c27d.png)
# ServerKits

Kit plugin for PocketMine-MP

[![Download!](https://user-images.githubusercontent.com/10297075/101246002-cb046780-3710-11eb-950f-ba06934b8138.png)](http://gestyy.com/er3d21)

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.11.0<br>
**Dependency Plugins:** MassiveEconomy v1.0 API 0.90

## Overview

**ServerKits** allows you to create server kits.

**EvolSoft Website:** http://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***<br>
***Economy support is provided by MassiveEconomy plugin. Please install it if you want to add paid kits.***

With ServerKits you can create custom free and paid kits for your server.<br>
You can also set a default kit and players that will join for the first time on your server will get it and you can also place signs to get kits. (read documentation)<br>
*Remember that kits are case sensitive. This means that if you want to get a kit called "ToOls" for example, you can get it with "/kit ToOls" and not "/kit tools" or others...*

**Commands:**

***/serverkits*** *- ServerKits commands*<br>
***/kit*** *- Get a kit*

**To-Do:**

<dd><i>- Bug fix (if bugs will be found)</i></dd>
<dd><i>- Get kit time limit</i></dd>

## Documentation 

**Sign Creation:**

*You must have the permission: "serverkits.create-sign" to create signs and you must have the permission "serverkits.use-sign" to use signs.*

***Line 1:*** [ServerKits]<br>
***Line 2:*** &lt;kit name (case sensitive)&gt;

**Add and configure a kit:**

1. Open *kits.yml* file<br>
Default *kits.yml* file:
```yaml
---
#Kit Name
Default:
#Kit Custom Name
name: "&bDefault_KIT"
#Kit Price
price: 0
#Kit Items
#Items must have this format: "id:damage quantity"
items:
- "272 1"
- "274 1"
- "260 10"
- "298 1"
- "299 1"
- "300 1"
- "301 1"
#Kit Commands (optional)
commands:
- "tell {PLAYER} Kit Command Example"
Tools:
name: "&2Tools"
price: 0
items:
- "272 1"
- "273 1"
- "274 1"
- "275 1"
Iron:
name: "&7IronKit"
price: 50
items:
- "261 1"
- "262 16"
- "267 1"
- "306 1"
- "307 1"
- "308 1"
- "309 1"
Diamond:
name: "&9D&bIAMON&9D"
price: 200
items:
- "261 1"
- "262 32"
- "276 1"
- "310 1"
- "311 1"
- "312 1"
- "313 1"
...
```

**Configuration (config.yml):**

```yaml
---
#Enable this if you want that a player who joined for the first time will get the kit
enable-default-kit: true
#Name of the kit that will get the player when he joins for the first time
default-kit: "Default"
#Enable this if you want to enable kit permissions. This will set to false only the permissions related to kits (ex. serverkits.kit.examplekit)
use-permissions: false
#Enable ServerKits signs creation/usage
enable-signs: true
#Message sent on kit received
#Available tags:
# - {KIT}: Show kit name
# - {KIT_NAME}: Show kit custom name
# - {PLAYER}: Show player name
# - {PRICE}: Show kit price
kit-received-message: "&aYou received the {KIT_NAME} &akit"
...
```

**Commands:**

***/serverkits*** *- ServerKits commands (aliases: [skits])*<br>
***/kit &lt;kit name (case sensitive)&gt;*** - Get a kit*

**Permissions:**

- <dd><i><b>serverkits.*</b> - ServerKits permissions.</i></dd>
- <dd><i><b>serverkits.kit.*</b> - ServerKits Kit permissions.</i></dd>
- <dd><i><b>serverkits.use-sign</b> - Allows player to use ServerKits signs.</i></dd>
- <dd><i><b>serverkits.create-sign</b> - Allows player to create ServerKits signs.</i></dd>
- <dd><i><b>serverkits.commands.*</b> - ServerKits commands permissions.</i></dd>
- <dd><i><b>serverkits.commands.help</b> - ServerKits command Help permission.</i></dd>
- <dd><i><b>serverkits.commands.info</b> - ServerKits command Info permission.</i></dd>
- <dd><i><b>serverkits.commands.reload</b> - ServerKits command Reload permission.</i></dd>
- <dd><i><b>serverkits.commands.kit</b> - ServerKits command Kit permission.</i></dd>
