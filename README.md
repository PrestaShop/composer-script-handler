# Composer script handler for PrestaShop 1.7

This scripts allows to install modules in `modules` folder, with no update
of main `vendor` folder.

## Installation

```
composer require prestashop/composer-script-handler --dev
```

## Use

In your Shop, you can now declare modules into "prestashop.modules" in the "extras" section of Composer.

Once you do that, on "install" and "update" processes, every module will be installed
so you will always get the most "up to date" version of each module!

> This project is under MIT license
