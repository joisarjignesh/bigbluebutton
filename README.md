# BigBlueButton Server API Library for Laravel 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joisarjignesh/bigbluebutton.svg?style=flat-square)](https://packagist.org/packages/joisarjignesh/bigbluebutton)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://travis-ci.org/joisarjignesh/bigbluebutton)
[![Quality Score](https://img.shields.io/scrutinizer/g/joisarjignesh/bigbluebutton.svg?style=flat-square)](https://scrutinizer-ci.com/g/joisarjignesh/bigbluebutton)
[![Total Downloads](https://img.shields.io/packagist/dt/joisarjignesh/bigbluebutton.svg?style=flat-square)](https://packagist.org/packages/joisarjignesh/bigbluebutton)
![Laravel Framework](https://img.shields.io/badge/laravel-%3E%3D5.5-blue)

Package that provides easily communicate between BigBlueButton server and laravel framework
## Requirements
- Laravel 5.5 or above.

## Installation
You can install the package via composer:

```bash
composer require joisarjignesh/bigbluebutton
```
After install package publish config file
```
php artisan vendor:publish --tag=bigbluebutton-config
```

## Usage
- Define in config/bigbluebutton.php file

``` 
BBB_SECURITY_SALT=bbb_secret_key   
BBB_SERVER_BASE_URL=https://example.com/bigbluebutton/
``` 
 
- Get all meetings  
```
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

Bigbluebutton::all()
```



- Create meeting
```
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

Bigbluebutton::create([
    'meetingID' => 'tamku',
    'meetingName' => 'test meeting',
    'attendeePW' => 'attendee',
    'moderatorPW' => 'moderator'
]);
```

- Join meeting
```
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

Bigbluebutton::join([
    'meetingID' => 'tamku',
    'userName' => 'disa',
    'password' => 'attendee'
]);
```
### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jigneshjoisar@gmail.com instead of using the issue tracker.

## Credits

- [Jignesh Joisar](https://github.com/joisarjignesh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

