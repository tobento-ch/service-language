# Language Service

The Language Service provides a way for managing languages for PHP applications.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
- [Documentation](#documentation)
    - [Language](#language)
        - [Create Language](#create-language)
        - [Language Interface](#language-interface)
	- [Languages](#languages)
        - [Create Languages](#create-languages)
        - [Get Language](#get-language)
        - [Default Language](#default-language)
        - [Current Language](#current-language)
        - [All Languages](#all-languages)
        - [Columns](#columns)
        - [Fallbacks](#fallbacks)
    - [Area Languages](#area-languages)
    - [Current Language Resolver](#current-language-resolver)
- [Credits](#credits)
___

# Getting started

Add the latest version of the Language service project running this command.

```
composer require tobento/service-language
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Language

### Create Language

Easily create a language with the provided factory:

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\LanguageInterface;

$languageFactory = new LanguageFactory();

$language = $languageFactory->createLanguage('en');

var_dump($language instanceof LanguageInterface);
// bool(true)
```

**Parameters:**

```php
use Tobento\Service\Language\LanguageFactory;

$languageFactory = new LanguageFactory();

$language = $languageFactory->createLanguage(
    locale: 'en-US',
    name: 'English',
    key: 'en-us',
    id: 1,
    iso: 'en',
    region: 'US',
    slug: 'en',
    directory: 'enUs',
    area: 'frontend',
    domain: 'example.com',
    url: 'https://www.example.com',
    fallback: 'en',
    default: false,
    active: true,
);
```

### Language Interface

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\LanguageInterface;

$languageFactory = new LanguageFactory();

$language = $languageFactory->createLanguage('en-US');

var_dump($language instanceof LanguageInterface);
// bool(true)

var_dump($language->locale());
// string(5) "en-US"

var_dump($language->iso());
// string(2) "en"

var_dump($language->region());
// string(2) "US" or returns NULL if no region.

var_dump($language->name());
// string(5) "en-US"

var_dump($language->key());
// string(5) "en-us"

var_dump($language->id());
// int(0)

var_dump($language->slug());
// string(5) "en-us"

var_dump($language->directory());
// string(5) "en-us"

var_dump($language->area());
// string(7) "default"

var_dump($language->domain());
// NULL or returns string if has domain.

var_dump($language->url());
// NULL or returns string if has url.

var_dump($language->fallback());
// NULL or returns string if has fallback

var_dump($language->default());
// bool(false)

var_dump($language->active());
// bool(true)
```

## Languages

### Create Languages

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\Languages;
use Tobento\Service\Language\LanguagesInterface;

$languageFactory = new LanguageFactory();

$languages = new Languages(
    $languageFactory->createLanguage('en', default: true),
    $languageFactory->createLanguage('de', fallback: 'en'),
    $languageFactory->createLanguage('de-CH', fallback: 'de'),
    $languageFactory->createLanguage('fr', fallback: 'en', active: false),
    $languageFactory->createLanguage('it', fallback: 'de', active: false),
);

var_dump($languages instanceof LanguagesInterface);
// bool(true)
```

**Create languages from an array:**

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\Languages;
use Tobento\Service\Language\LanguagesInterface;

$languageFactory = new LanguageFactory();

$languages = new Languages(
    ...$languageFactory->createLanguagesFromArray([
        ['locale' => 'en', 'default' => true],
        ['locale' => 'de', 'fallback' => 'en'],
        ['locale' => 'fr', 'fallback' => 'en'],
    ])
);

var_dump($languages instanceof LanguagesInterface);
// bool(true)
```

### Get Language

```php
use Tobento\Service\Language\LanguageInterface;

$language = $languages->get('de-CH'); // supports locale, id, key or slug values.

var_dump($language instanceof LanguageInterface);
// bool(true)
```

If the language is inactive, it will return the fallback language and if the language does not exist it will return the default language:

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\Languages;
use Tobento\Service\Language\LanguagesInterface;

$languageFactory = new LanguageFactory();

$languages = new Languages(
    $languageFactory->createLanguage('en', default: true),
    $languageFactory->createLanguage('de', fallback: 'en'),
    $languageFactory->createLanguage('fr', fallback: 'de', active: false),
);

// Will return the fallback language as fr is inactive.
var_dump($languages->get('fr')->locale());
// string(2) "de"

// Will return the default language as language does not exist.
var_dump($languages->get('it')->locale());
// string(2) "en"

// Without fallback
var_dump($languages->get('fr', fallback: false)?->locale());
// NULL

var_dump($languages->get('it', fallback: false)?->locale());
// NULL
```

**Check if a language exist:**

```php
var_dump($languages->has('de-CH')); // supports locale, id, key or slug values.
// bool(true)

var_dump($languages->has('fr'));
// bool(false)

var_dump($languages->has('fr', activeOnly: false));
// bool(true)
```

### Default Language

```php
use Tobento\Service\Language\LanguageInterface;

$defaultLanguage = $languages->default();

var_dump($defaultLanguage instanceof LanguageInterface);
// bool(true)
```

### Current Language

**Set the current language:**

```php
$languages->current('en'); // supports locale, id, key or slug values.
```

> :warning: If the language does not exist or is inactive, the default language will be set as the current language but only if none is defined.

**Get the current language:**

```php
use Tobento\Service\Language\LanguageInterface;

$currentLanguage = $languages->current();

var_dump($currentLanguage instanceof LanguageInterface);
// bool(true)
```

### All Languages

```php
use Tobento\Service\Language\LanguageInterface;

$allLanguages = $languages->all();
// array<string, LanguageInterface>

// This returns only the active languages.
```

**Indexed by language parameter:**

You may get the languages indexed by a parameter such as locale, key, id or slug.

```php
use Tobento\Service\Language\LanguageInterface;

$allLanguages = $languages->all(indexKey: 'id'); // default is 'locale'
// array<int, LanguageInterface>
```

**Get all languages inclusive inactive:**

By default calling the all() method returns only the active languages. You may return all languages by the following way:

```php
use Tobento\Service\Language\LanguageInterface;

$allLanguages = $languages->all(activeOnly: false); // default is true
// array<string, LanguageInterface>
```

### Columns

Sometimes you may need only specific columns from the languages.

```php
$locales = $languages->column('locale');
/*Array
(
    [0] => en
    [1] => de
    [2] => de-CH
)*/
```

**Index by language id:**

```php
$locales = $languages->column('locale', 'id');
/*Array
(
    [1] => en
    [2] => de
    [3] => de-CH
)*/
```

**Inclusive active languages:**

```php
$locales = $languages->column('locale', activeOnly: false);
/*Array
(
    [0] => en
    [1] => de
    [2] => de-CH
    [3] => fr
    [4] => it
)*/
```

### Fallbacks

**Get the fallbacks**

Returns fallbacks for all languages inclusive inactive.

```php
// by locale
$fallbacks = $languages->fallbacks();
/*Array
(
    [de] => en
    [de-CH] => de
    [fr] => en
    [it] => de
)*/

// by id:
$fallbacks = $languages->fallbacks('id');
/*Array
(
    [2] => 1
    [3] => 2
    [4] => 1
    [5] => 2
)*/

// by key:
$fallbacks = $languages->fallbacks('key');
/*Array
(
    [de] => en
    [de-ch] => de
    [fr] => en
    [it] => de
)*/

// by slug:
$fallbacks = $languages->fallbacks('slug');
/*Array
(
    [de] => en
    [de-ch] => de
    [fr] => en
    [it] => de
)*/
```

**Get the fallback language for a specific locale, id, key or slug**

If no fallback language is provided, the default language will be returned.

```php
use Tobento\Service\Language\LanguageInterface;

$fallbackLanguage = $languages->getFallback('de-CH');

var_dump($fallbackLanguage instanceof LanguageInterface);
// bool(true)
```

## Area Languages

**Create area languages**

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\AreaLanguages;
use Tobento\Service\Language\AreaLanguagesInterface;
use Tobento\Service\Language\LanguagesFactoryInterface;

$languageFactory = new LanguageFactory();

$areaLanguages = new AreaLanguages(
    null, // null|LanguagesFactoryInterface
    $languageFactory->createLanguage('en', area: 'frontend', default: true),
    $languageFactory->createLanguage('de', area: 'frontend', fallback: 'en'),
    $languageFactory->createLanguage('de-CH', area: 'frontend', fallback: 'de'),   
    $languageFactory->createLanguage('en', area: 'backend', default: true),
    $languageFactory->createLanguage('de', area: 'backend', fallback: 'en'), 
);

var_dump($areaLanguages instanceof AreaLanguagesInterface);
// bool(true)
```

**get / has**

```php
use Tobento\Service\Language\LanguagesInterface;

$frontendLanguages = $areaLanguages->get('frontend');

var_dump($frontendLanguages instanceof LanguagesInterface);
// bool(true)

// return NULL if no languages found:
var_dump($areaLanguages->get('api'));
// NULL

var_dump($areaLanguages->has('frontend'));
// bool(true)
```

## Current Language Resolver

You might want to use the current language resolver to set the current language. You might also write your own resolver depending on your needs such as a CurrentLanguageSessionResolver, CurrentLanguageServerRequestResolver and so on.

```php
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\Languages;
use Tobento\Service\Language\LanguagesInterface;
use Tobento\Service\Language\CurrentLanguageResolver;
use Tobento\Service\Language\CurrentLanguageResolverInterface;
use Tobento\Service\Language\CurrentLanguageResolverException;

$languageFactory = new LanguageFactory();

$languages = new Languages(
    $languageFactory->createLanguage('en', default: true),
    $languageFactory->createLanguage('de'),
);

$resolver = new CurrentLanguageResolver(
    currentLanguage: 'fr',
    allowFallbackToDefaultLanguage: false, // default is true
);

var_dump($resolver instanceof CurrentLanguageResolverInterface);
// bool(true)

try {
    $resolver->resolve($languages);
} catch (CurrentLanguageResolverException $e) {
    // do something
}
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)