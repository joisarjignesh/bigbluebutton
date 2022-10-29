![laravel-bigbluebutton](https://user-images.githubusercontent.com/17031402/87796124-93327b80-c866-11ea-8a77-cdfa844b3d63.jpg)
# BigBlueButton Server API Library for Laravel 
[![License](https://img.shields.io/packagist/l/joisarjignesh/bigbluebutton.svg)](https://github.com/joisarjignesh/bigbluebutton/LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/joisarjignesh/bigbluebutton.svg?style=flat-square)](https://packagist.org/packages/joisarjignesh/bigbluebutton)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://travis-ci.org/joisarjignesh/bigbluebutton)
[![Quality Score](https://img.shields.io/scrutinizer/g/joisarjignesh/bigbluebutton.svg?style=flat-square)](https://scrutinizer-ci.com/g/joisarjignesh/bigbluebutton)
[![Total Downloads](https://img.shields.io/packagist/dt/joisarjignesh/bigbluebutton.svg?style=flat-square)](https://packagist.org/packages/joisarjignesh/bigbluebutton)
![Laravel Framework](https://img.shields.io/badge/laravel-%3E%3D5.5-blue)


Package that provides easily communicate between BigBlueButton server and laravel framework

* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [Api](#Api)
    * [Check a url and secret working](#check-a-url-and-secret-working)
    * [Meeting](#meeting)
        * [Create a meeting](#create-a-meeting)
          * [Upload slides](#upload-slides)
          * [End meeting callback url](#end-meeting-callback-url)
          * [Recording ready callback URL](#recording-ready-callback-url)
        * [Join a meeting](#join-a-meeting)
        * [Get a list of meetings](#get-a-list-of-meetings)
        * [Get meeting info](#get-meeting-info)
        * [Is a meeting running?](#is-a-meeting-running)
        * [Close a meeting](#close-a-meeting)
     * [Recording](#recording)
        * [Get recordings](#get-recordings)
        * [Publish recordings](#publish-recordings)
        * [Delete recordings](#delete-recordings)
        * [Update recordings](#update-recordings)
     * [Hooks](#hooks)
       * [Create Hooks](#hooks-create)
       * [Destroy Hooks](#hooks-destroy)
     * [Other](#other)
        * [Get API version](#get-api-version)   
     * [Unofficial](#unofficial)
        * [Start a meeting](#start-a-meeting)   



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
 
 - For Specific server configuration (only for multiple server by default is optional)
  ```
  'servers' => [
         'server1' => [
             'BBB_SECURITY_SALT'    => '',
             'BBB_SERVER_BASE_URL'  => '',
         ],
   ]
```

After Define salt and url clear old configurations 
 ```
php artisan config:clear
```
 ## Api
 
 ### Check a url and secret working
```php
dd(\Bigbluebutton::isConnect()); //default 
dd(\Bigbluebutton::server('server1')->isConnect()); //for specific server 
dd(bigbluebutton()->isConnect()); //using helper method 
```
 
 ### Meeting
 #### Create a meeting
- You can create meeting in three ways [document](https://docs.bigbluebutton.org/dev/api.html#create)

1.By Passing Array
```php
\Bigbluebutton::create([
    'meetingID' => 'tamku',
    'meetingName' => 'test meeting',
    'attendeePW' => 'attendee',
    'moderatorPW' => 'moderator'
]); 
```

2.By passing CreateMeetingParameters object for customize create meeting 
```php
use BigBlueButton\Parameters\CreateMeetingParameters;

$meetingParams = new CreateMeetingParameters($meetingID, $meetingName);
$meetingParams->setModeratorPW('moderatorPassword');
$meetingParams->setAttendeePW('attendeePassword');

\Bigblubutton::create($meetingParams);
```

3.By passing array it will return CreateMeetingParameters object for overwrite methods
```php
$createMeeting = \Bigbluebutton::initCreateMeeting([
    'meetingID' => 'tamku',
    'meetingName' => 'test meeting',
    'attendeePW' => 'attendee',
    'moderatorPW' => 'moderator',
]);

$createMeeting->setDuration(100); //overwrite default configuration
\Bigbluebutton::create($createMeeting);
``` 

##### Upload slides 
- You can upload slides within the create a meeting call. If you do this, the BigBlueButton server will immediately
 download  and process the [slides](https://docs.bigbluebutton.org/dev/api.html#pre-upload-slides) 
  ```php
  \Bigbluebutton::create([
      'meetingID' => 'tamku',
      'meetingName' => 'test meeting',
      'attendeePW' => 'attendee',
      'moderatorPW' => 'moderator',
      'presentation'  => [ //must be array
          ['link' => 'https://www.example.com/doc.pdf', 'fileName' => 'doc.pdf'], //first will be default and current slide in meeting
          ['link' => 'https://www.example.com/php_tutorial.pptx', 'fileName' => 'php_tutorial.pptx'],
      ],
  ]); 
  ```  
  
##### End meeting callback [URL](https://docs.bigbluebutton.org/dev/api.html#end-meeting-callback-url)
- You can ask the BigBlueButton server to make a callback to your application when the meeting ends. Upon receiving
 the callback your application could, for example, change the interface for the user to hide the ‘join’ button.
 
  ##### Note : End meeting callback URL will notify silently, User won't redirect to that page.
   - For testing endCallbackUrl see [webhook site](https://webhook.site)
  
  If you want to redirect users to that page after meeting end then can use logoutURL
 ```php
\Bigbluebutton::create([
    'meetingID' => 'tamku',
    'meetingName' => 'test meeting',
    'attendeePW' => 'attendee',
    'moderatorPW' => 'moderator',
    'endCallbackUrl'  => 'www.example.com/callback',
    'logoutUrl' => 'www.example.com/logout',
]); 
```

##### Recording ready callback [URL](https://docs.bigbluebutton.org/dev/api.html#recording-ready-callback-url) 
- You can ask the BigBlueButton server to make a callback to your application when the recording for a meeting is ready for viewing. Upon receiving the callback your application could, for example, send the presenter an e-mail to notify them that their recording is ready

   ##### Note :  Recording ready callback URL will notify silently, User won't redirect to that page.
    - For testing Recording ready callback see [webhook site](https://webhook.site)
```php
\Bigbluebutton::create([
    'meetingID' => 'tamku',
    'meetingName' => 'test meeting',
    'attendeePW' => 'attendee',
    'moderatorPW' => 'moderator',
    'bbb-recording-ready-url'  => 'https://example.com/api/v1/recording_status',
]); 
```

#### Join a meeting    
 - Join meeting ( by default it will redirect into BigBlueButton Server And Join Meeting) [document](https://docs.bigbluebutton.org/dev/api.html#join)
```php
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

return redirect()->to(
 Bigbluebutton::join([
    'meetingID' => 'tamku',
    'userName' => 'disa',
    'password' => 'attendee' //which user role want to join set password here
 ])
);
```

- Join meeting but does want to redirect into BigBlueButton server and pass other parameters
```php
\Bigbluebutton::join([
    'meetingID' => 'tamku',
    'userName' => 'disa',
    'password' => 'attendee', //which user role want to join set password here
    'redirect' => false, //it will not redirect into bigblueserver
    'userId' =>  "54575",
    'customParameters' => [  
       'foo' => 'bar',
       'key' => 'value'
    ]
]);
```

#### Get a list of meetings
- Get all meetings  [document](https://docs.bigbluebutton.org/dev/api.html#getmeetings)
```php
\Bigbluebutton::all(); //using facade
bigbluebutton()->all(); //using helper method 
```

#### Get meeting info
- Get meeting info [document](https://docs.bigbluebutton.org/dev/api.html#getmeetinginfo)
```php
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

Bigbluebutton::getMeetingInfo([
    'meetingID' => 'tamku',
    'moderatorPW' => 'moderator' //moderator password set here
]);
```

#### Is a meeting running
- Is meeting running [document](https://docs.bigbluebutton.org/dev/api.html#ismeetingrunning)
```php
Bigbluebutton::isMeetingRunning([
    'meetingID' => 'tamku',
]);

Bigbluebutton::isMeetingRunning('tamku'); //second way 
```

#### Close a meeting
- Close meeting [document](https://docs.bigbluebutton.org/dev/api.html#end)
```php
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

Bigbluebutton::close([
    'meetingID' => 'tamku',
    'moderatorPW' => 'moderator' //moderator password set here
]);
```

### Recording
#### Get recordings
- Get recordings [document](https://docs.bigbluebutton.org/dev/api.html#getrecordings)
```php
\Bigbluebutton::getRecordings([
    'meetingID' => 'tamku',
    //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings 
    //'recordID' => 'a3f1s',
    //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
    // 'state' => 'any' // It can be a set of states separate by commas  
]);
```

#### Publish recordings
- Publish Recordings [document](https://docs.bigbluebutton.org/dev/api.html#publishrecordings)
```php
\Bigbluebutton::publishRecordings([
   'recordID' => 'a3f1s',
    //'recordID' => ['xyz.1','pqr.1'] //pass as array if publish multiple recordings
   'state' => true //default is true  
]);
```

#### Delete recordings
- Delete recordings [document](https://docs.bigbluebutton.org/dev/api.html#deleterecordings)
```php
\Bigbluebutton::deleteRecordings([
    //'recordID' => 'a3f1s',
    'recordID' => ['a3f1s','a4ff2'] //pass array if multiple delete recordings
]);
```
#### Update recordings
- Update recordings [document](https://docs.bigbluebutton.org/dev/api.html#updaterecordings)
```php
\Bigbluebutton::updateRecordings([
    //'recordID' => 'a3f1s',
    'recordID' => ['a3f1s','a4ff2'] //pass array if multiple delete recordings
]);
```

### Hooks
#### Hooks create
- Hooks Create [document](https://docs.bigbluebutton.org/dev/webhooks.html#hookscreate)
```php 
dd(Bigbluebutton::hooksCreate([
      'callbackURL' => 'example.test', //required
      'meetingID' => 'tamku', //optional  if not set then hooks set for all meeting id
      'getRaw' => true //optional
]));
```

#### Hooks destroy
- Hooks Destroy [document](https://docs.bigbluebutton.org/dev/webhooks.html#hooksdestroy)
```php 
dd(Bigbluebutton::hooksDestroy([
     'hooksID' => 33
]));

dd(Bigbluebutton::hooksDestroy('53')); //second way
```

### Other
#### Get api version
- Get api version
```php
dd(\Bigbluebutton::getApiVersion()); //return as collection 
```

### Unofficial
#### Start a meeting 
- Start meeting (first check meeting is exists or not if not then create a meeting and join a meeting otherwise
  meeting is exists then it will directly join a meeting) (by default user join as moderator)
 ```php
 $url = \Bigbluebutton::start([
     'meetingID' => 'tamku',
     'meetingName' => 'test meeting name',
     'moderatorPW' => 'moderator', //moderator password set here
     'attendeePW' => 'attendee', //attendee password here
     'userName' => 'John Deo',//for join meeting 
     //'redirect' => false // only want to create and meeting and get join url then use this parameter 
 ]);

return redirect()->to($url);
 ```

### More Information Read This [wiki](https://github.com/bigbluebutton/bigbluebutton-api-php/wiki) 
### For Bigbluebutton Api Testing See This [ApiMate](https://mconf.github.io/api-mate/) 
### See Bigbluebutton Official dev Api   [Bigbluebutton](https://docs.bigbluebutton.org/dev/api.html) 

### Support

<a href="https://www.buymeacoffee.com/joisarjignesh" target="_blank">
  <img src="https://cdn.buymeacoffee.com/buttons/default-orange.png" alt="Buy Me A Coffee" height="41" width="174"></a>

<a href="https://www.paypal.me/joisarjignesh" target="_blank">
  <img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="Donate" ></a>

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



