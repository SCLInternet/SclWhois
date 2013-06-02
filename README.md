WHOIS Server Lookup Library
===========================

[![Build Status](https://travis-ci.org/SCLInternet/SclWhois.png?branch=master)](https://travis-ci.org/SCLInternet/SclWhois)
[![Coverage Status](https://coveralls.io/repos/SCLInternet/SclWhois/badge.png)](https://coveralls.io/r/SCLInternet/SclWhois)

Performs a whois lookup on a given domain name.

Requires
--------

[sclinternet/SclSocket](https://github.com/SCLInternet/SclSocket "sclinternet/SclSocket on GitHub")

Intallation
-----------

Add the following to your `composer.json` file

```json
{
    "require": {
        "sclinternet/scl-whois": "dev-master"
    }
}
```

Usage
-----

Create an instance of the DomainLookup object passing in a `SclSocket\SocketInterface` and call lookup().

```php
$whois = new \SclWhois\DomainLookup(new \SclSocket\Socket);

echo $whois->lookup('google.com');
```
