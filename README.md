# Intruder Alert
![screenshot](screenshot.png)

Intruder Alert is a browser-based incident dashboard for Fail2ban.

## Installation

Clone the repository.

```
git clone https://github.com/VerifiedJoseph/intruder-alert
```

Install dependencies with composer.

```
composer install --no-dev
```

## Configuration

The preferred method to adjust the configuration is with environment variables.

Alternatively, you can use `backend/config.php` (copied from [`backend/config.example.php`](backend/config.example.php)) to set the variables.

| Name                     | Description                                                                   |
| ------------------------ | ----------------------------------------------------------------------------- |
| `IA_LOG_PATHS`           | Comma separated list of Fail2ban log files.                                   |
| `IA_LOG_FOLDER`          | Path of the Fail2ban logs folder. Ignored if `IA_LOG_PATHS` is set            |
| `IA_ASN_DATABASE`        | Path of the GeoLite2 ASN database file.                                       |
| `IA_COUNTRY_DATABASE`    | Path of the GeoLite2 Country database file.                                   |
| `IA_TIMEZONE`            | Timezone (optional) ([php docs](https://www.php.net/manual/en/timezones.php)) |
| `IA_SYSTEM_LOG_TIMEZONE` | Timezone of fail2ban logs (optional, default is UTC)                          |

GeoLite2 databases can be [downloaded](https://dev.maxmind.com/geoip/geolite2-free-geolocation-data?lang=en) from MaxMind.

## Running

The backend script `backend\script.php` is designed to be used with a task scheduler like cron.

Cron example:

`1 * * * * php path/to/intruder-alert/backend/script.php`

## Dependencies
- [`geoip2/geoip2`](https://github.com/maxmind/GeoIP2-php)

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 18.0 (development only)
