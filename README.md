WHOIS Server Lookup Library
===========================

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
