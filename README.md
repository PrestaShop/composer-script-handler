# Composer script handler for PrestaShop 1.7

This scripts allows to install modules in `modules` folder, with no update
of main `vendor` folder.

## Installation

```
composer require prestashop/composer-script-handler --dev
```

## Use

In your Shop, you can now declare modules into "prestashop.modules" in the "extras" section of Composer.



Once you do that, on "install" process, the list of modules will be installed and can be overwritten on demand.

### Configuration

You can configure the list of modules, the number of process to paralleling the download of modules and the timeout to wait for download status.

* `modules`: this is the list of Composer packages with a version, they **must** be of "prestashop-module" type;
* `processes`: it's the number of parallel process allowed to download modules (**2** by default)
* `update-frequency`: the time to wait before check the status of each current processes,in ms (**2000** by default)

```js
{
    "name": "my/shop",
    "...": "...",
    "scripts": {
        "post-install-cmd": [
            "PrestaShop\\Composer\\ScriptHandler::install"
        ]
    },
    "extra": {
        "prestashop": {
            "modules": {
                "prestashop/blockreassurance": "^3",
                "prestashop/contactform": "^4",
                "prestashop/dashactivity": "^2",
                "prestashop/dashgoals": "^2"
            },
            "processes": 2,
            "update-frequency": 80
        }
    }
}
```

### Performances

You may not need to overwrite modules installation between parallel builds during CI operations.
You can disable this feature using `NO_OVERWRITE` environment variable when calling ``composer install` command.

> This project is under MIT license
