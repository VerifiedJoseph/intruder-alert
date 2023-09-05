# Intruder Alert
![screenshot](screenshot.png)

Intruder Alert is an incident dashboard for Fail2ban.

## Demo

A demo is [available](https://verifiedjoseph.github.io/intruder-alert/demo/). It is built from the latest commit on the `main` brach.

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
| `IA_LOG_FOLDER`          | Path of the Fail2ban logs folder. Ignored if `IA_LOG_PATHS` is set.           |
| `IA_MAXMIND_LICENSE_KEY` | MaxMind license key for automatic GeoLite2 database downloads.                |
| `IA_ASN_DATABASE`        | Path of the GeoLite2 ASN database file.                                       |
| `IA_COUNTRY_DATABASE`    | Path of the GeoLite2 Country database file.                                   |
| `IA_TIMEZONE`            | Timezone ([php docs](https://www.php.net/manual/en/timezones.php))            |
| `IA_SYSTEM_LOG_TIMEZONE` | Timezone of fail2ban logs (optional, default is UTC)                          |
| `IA_DISABLE_CHARTS`      | Disable dashboard charts.                                                     |
| `IA_DISABLE_DASH_UPDATES`| Disable automatic dashboard updates.                                          |

### GeoLite2 databases

GeoLite2 databases will be automatically downloaded and updated if a [MaxMind license key](https://support.maxmind.com/hc/en-us/articles/4407111582235-Generate-a-License-Key) is set with `IA_MAXMIND_LICENSE_KEY`. 

Alternatively, the databases can be manually [downloaded](https://dev.maxmind.com/geoip/geolite2-free-geolocation-data?lang=en) and set using the environment variables `IA_ASN_DATABASE` and `IA_COUNTRY_DATABASE`.

## Running

The backend script `backend\script.php` is designed to be used with a task scheduler like cron.

Cron example:

`1 * * * * php path/to/intruder-alert/backend/script.php`

## Dependencies

- PHP
	- [`geoip2/geoip2`](https://github.com/maxmind/GeoIP2-php)
	- [`tronovav/geoip2-update`](https://github.com/tronovav/geoip2-update)
- JavaScript
	- [Chart.js](https://github.com/chartjs/Chart.js/)
	- [Spacetime](https://github.com/spencermountain/spacetime)

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 18.0 (development only)
