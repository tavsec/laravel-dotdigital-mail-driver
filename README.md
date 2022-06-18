# Laravel DotDigital mail driver
This package provides Laravel email driver which uses DotDigital transactional emails via API.

## Config
Add the following code snippet under `config\mail.php`:
```php
    'mailers' => [
        # ...
        "dotdigital" => [
            "transport" => "dotdigital"
        ]
    ]
```

And change/add the following variables to your .env file:
```dotenv
MAIL_MAILER=dotdigital
# ...
DOTDIGITAL_REGION="r1"          # r1, r2 or r3
DOTDIGITAL_USERNAME="username"  # DotDigital API username
DOTDIGITAL_PASSWORD="password"  # DotDigital API password
```
