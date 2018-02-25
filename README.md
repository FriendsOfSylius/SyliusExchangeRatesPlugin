<p align="center">
    <a href="http://sylius.org" target="_blank">
        <img src="http://demo.sylius.org/assets/shop/img/logo.png" />
    </a>
</p>
<h1 align="center">FOSSyliusExchangeRatePlugin</h1>
<p align="center">
    <a href="https://packagist.org/packages/friendsofsylius/sylius-exchange-rate-plugin" title="License">
        <img src="https://img.shields.io/packagist/l/friendsofsylius/sylius-exchange-rate-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/friendsofsylius/sylius-exchange-rate-plugin" title="Version">
        <img src="https://img.shields.io/packagist/v/friendsofsylius/sylius-exchange-rate-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/FriendsOfSylius/SyliusExchangeRatePlugin" title="Build status">
        <img src="https://img.shields.io/travis/FriendsOfSylius/SyliusExchangeRatePlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/FriendsOfSylius/SyliusExchangeRatePlugin/" title="Scrutinizer">
        <img src="https://img.shields.io/scrutinizer/g/FriendsOfSylius/SyliusExchangeRatePlugin.svg" />
    </a>
</p>

## Installation

1. Require and install the plugin

  - Run `composer require friendsofsylius/sylius-exchange-rate-plugin --dev`

2. Add plugin dependencies to your AppKernel.php file:

````php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        new \Florianv\SwapBundle\FlorianvSwapBundle(),
        new \FriendsOfSylius\SyliusExchangeRatePlugin\FOSSyliusExchangeRatePlugin(),
    ]);
}
````

## Configuration

### Application configuration:

```yaml
sylius_grid:
    templates:
        action:
            import_exchange_rates: "@FOSSyliusExchangeRatePlugin/importAction.html.twig"
    grids:
        sylius_admin_exchange_rate:
            actions:
                main:
                    import:
                        type: import_exchange_rates

florianv_swap:
    providers:
        # choose the provider you want, for example google finance
        # for the full list of options see:
        # https://github.com/florianv/symfony-swap/blob/master/Resources/doc/index.md#builtin-providers
        google_finance: ~

fos_sylius_exchange_rate: ~
```

### Routing configuration (only necessary if `web_ui` is set to `true`):

```yaml
sylius_exchange_rate:
    resource: "@FOSSyliusExchangeRatePlugin/Resources/config/routing.yml"
```

## Usage

### CLI commands

  - Import configured exchange rates

    ```bash
    $ bin/console sylius:import-exchange-rates
    ```

### Running plugin tests

  - Test application install

    ```bash
    $ composer install
    $ (cd tests/Application && yarn install)
    $ (cd tests/Application && yarn run gulp)
    $ (cd tests/Application && bin/console assets:install web -e test)
    
    $ (cd tests/Application && bin/console doctrine:database:create -e test)
    $ (cd tests/Application && bin/console doctrine:schema:create -e test)

  - PHPUnit

    ```bash
    $ bin/phpunit
    ```

  - PHPSpec

    ```bash
    $ bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    $ bin/behat features --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. Download [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/)
    
    2. Run Selenium server with previously downloaded Chromedriver:
    
        ```bash
        $ bin/selenium-server-standalone -Dwebdriver.chrome.driver=chromedriver
        ```
    3. Run test application's webserver on `localhost:8080`:
    
        ```bash
        $ (cd tests/Application && bin/console server:run 127.0.0.1:8080 -d web -e test)
        ```
    
    4. Run Behat:
    
        ```bash
        $ bin/behat features --tags="@javascript"
        ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    $ (cd tests/Application && bin/console sylius:fixtures:load -e test)
    $ (cd tests/Application && bin/console server:run -d web -e test)
    ```
    
- Using `dev` environment:

    ```bash
    $ (cd tests/Application && bin/console sylius:fixtures:load -e dev)
    $ (cd tests/Application && bin/console server:run -d web -e dev)
    ```
