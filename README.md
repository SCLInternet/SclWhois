WHOIS Server Lookup Library
===========================

Performs a whois lookup on a given domain name.

[![Build Status](https://travis-ci.org/tomphp/WhoisLookup.png?branch=master)](https://travis-ci.org/tomphp/WhoisLookup)

Requires
--------

[tomphp/BasicSocket](https://github.com/tomphp/BasicSocket "tomphp/BasicSocket on GitHub")

Intallation
-----------

Add the following to your `composer.json` file

```json
{
    "require": {
        "tomphp/whoislookup": "dev-master"
    }
}
```

Usage
-----

Create an instance of the DomainLookup object passing in a `BasicSocket\SocketInterface` and call lookup().

```php
$whois = new \WhoisLookup\DomainLookup(new \BasicSocket\Socket);

echo $whois->lookup('google.com');
```
