# php-email-validator

### How it works:
- validate using FILTER_VALIDATE_EMAIL
- validate using getHostByName
- validate using FILTER_VALIDATE_IP
- validate using checkdnsrr
- validate using dig
- validate using nslookup

### Provide Methods:

```php
_is_email_valid($email); // bool
```

### References:
- https://github.com/egulias/EmailValidator/blob/c81f18a3efb941d8c4d2e025f6183b5c6d697307/src/Validation/DNSCheckValidation.php#L58
- https://www.php.net/manual/en/function.getmxrr.php#42396
