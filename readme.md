# SimpleCURL

SimpleCURL is a basic cURL client that can be used to consume REST services exposed by other apps.

## Requisites
- PHP 7.2 or higher
- curl extension enabled



## Install
Add SimpleCURL to your project vía composer

`composer require Underdog1987/SimpleCURL`

## Basic Usage

### Import class
To import SimpleCURL class use

```php
use Underdog1987\SimpleCURL\SimpleCURL
```

Linux systems are case-sensitive, that means, `SimpleCurl` and `SimpleCURL` are different class names. Be carreful.

### Simple GET request
This example shows how to make a simple request to Google.

```php
if(!SimpleCURL::isRunnable()){
   die('cURL extension is not loaded');
}
$client = new SimpleCURL();
$client->isGet();
$client->setUrl('https://google.com');
$client->ignoreCerts(TRUE);
$client->prepare();
$result = $client->execute();
print_r($result->getHttpCode()); // HTTP Response
print_r($result->getResponseHeaders()); // Response Headers
print_r($result->getResponseBody()); // Response Body
```

The `$client->isGet()` statement specifies GET as request method.
The `$client->ignoreCerts(TRUE)` statement specifies SSL certificates will not be required in SSL connections (https).

### Simple POST request (form-data)
This example shows how to make a simple POST request.

```php
if(!SimpleCURL::isRunnable()){
   die('cURL extension is not loaded');
}
$client = new SimpleCURL();
$client->setData('name=John&surname=Doe');
$client->setUrl('http://example.com');
$client->prepare();
$result = $client->execute();
print_r($result->getHttpCode()); // HTTP Response
print_r($result->getResponseHeaders()); // Response Headers
print_r($result->getResponseBody()); // Response Body
```

By default, `SimpleCURL` request method is set to POST.

### Simple POST request (json)
To send JSON as request body, just change the content of `setData()` and add header `Content-Type: application/json` as described below:

```php
$client->setData('{"name":"Jhon", "surname":"Doe"}');
$client->addHeader(['name' => 'Content-Type', 'value' => 'appication/json']);
```






