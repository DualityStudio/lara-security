# Lara Security

Simple way to add various security headers to a Laravel application.

This project is WIP and could with cleanup, better implementation and some docs.

# Docs
TODO, the bellow is 5 minute notes.

### Install
```bash
composer require duality-studio/lara-security

php artisan vendor:publish --provider="DualityStudio\LaraSecurity\LaraSecurityServiceProvider"
```

In your `app/Http/Kernel.php` add the following to the `$middleware` array or as you see fit.

```php
\DualityStudio\LaraSecurity\SecurityHeaders::class,
``` 

In your `config/lara-security.php` you can configure the headers you want to use, all are enabled by default. In the CSP header is broken into directives.

### Nonces
If you intend to use a nonce in your you will need to add a script or style directive for your static files.

```
<script @nonce(\DualityStudio\LaraSecurity\Directives::SCRIPT)>
    window.addEventListener('load', function () {
        console.log(1);
    });
</script>
```

```
<style @nonce(\DualityStudio\LaraSecurity\Directives::STYLE)>
    body {
        background: #fff;
    }
</style>
```

### Usage with Vite
Set `use_vite` to true in the config file. This will automatically add the `nonce` to the script and style tags in the vite manifest.

Usage of the package is problematic when using the vite dev server, so you can disable the package when in dev mode by adding the following to your .env

```
LARA_SECURITY_ENABLED=false
```

### Usage with Inertia.JS
If you are using Inertia.JS you will need to add the following to your `app.blade.php` file.

``` 
<!-- Scripts -->
@routes(null, nonce(\DualityStudio\LaraSecurity\Directives::SCRIPT))
@viteReactRefresh
@vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
@inertiaHead
```

