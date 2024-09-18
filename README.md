# Commitvel- your git commit friendly tool! 🚀


[![Latest Version on Packagist](https://img.shields.io/packagist/v/coding-wisely/commitvel.svg?style=flat-square)](https://packagist.org/packages/coding-wisely/commitvel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/coding-wisely/commitvel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/coding-wisely/commitvel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/coding-wisely/commitvel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/coding-wisely/commitvel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/coding-wisely/commitvel.svg?style=flat-square)](https://packagist.org/packages/coding-wisely/commitvel)


Commitvel is a tailor-made package for Laravel developers to streamline their git workflow, ensuring code quality and efficiency without relying on git hooks. With a simple console command, Commitvel automates the mundane tasks of preparing your code for version control. Here's what Commitvel brings to the table:


## Key Features:

### Change Detection: 
Effortlessly identifies and stages your code changes. 📋 Commitvel manually detects your changes, ensuring no modification goes unnoticed.

### Code Fixing with Laravel Pint: 
Automatically formats and styles your code with Laravel Pint. 🧼 Say goodbye to manual code styling—Commitvel makes your code shine with one command.

### Automated Unit Tests: 

Executes your unit tests to guarantee code reliability. 🔍 Commitvel integrates with Pest to run tests, giving you peace of mind that your changes won't break existing functionality.

### Smart Committing: 

Helps you compose meaningful commit messages and stages your code changes for you. 📝 Commitvel guides you through the process, ensuring your git history is clean and informative.

### Seamless Pushing: 

Pushes your commits to the remote repository effortlessly. 🌐 Commitvel makes git pushing as simple as a single command, freeing you from the intricacies of git.



## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/Commitvel.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/Commitvel)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require coding-wisely/commitvel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="commitvel-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="commitvel-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="commitvel-views"
```

## Usage

```php
$commitvel = new CodingWisely\Commitvel();
echo $commitvel->echoPhrase('Hello, CodingWisely!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vladimir Nikolic](https://github.com/coding-wisely)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
