# Intruder Alert
![screenshot](screenshot.png)

Intruder Alert is an incident dashboard for Fail2ban.

## Demo

A demo is [available](https://verifiedjoseph.github.io/intruder-alert/demo/). It is built from the latest commit on the `main` brach.

## Installation

### docker-compose (recommended)

```yaml
version: '3'

services:
  app:
    image: ghcr.io/verifiedjoseph/intruder-alert:1.0.0
    container_name: intruder-alert
    environment:
      - IA_TIMEZONE=Europe/London
      - IA_MAXMIND_LICENSE_KEY=
      - IA_LOG_FOLDER=/app/backend/logs
    volumes:
      - <path/to/fail2ban.log>:/app/backend/logs/fail2ban.log:ro
      - path/to/fail2ban.log.1:/app/backend/logs/fail2ban.log.1:ro
      - path/to/fail2ban.log.2.gz:/app/backend/logs/fail2ban.log.2.gz:ro
      - path/to/fail2ban.log.3.gz:/app/backend/logs/fail2ban.log.3.gz:ro
      - path/to/fail2ban.log.4.gz:/app/backend/logs/fail2ban.log.4.gz:ro
    ports:
      - '127.0.0.1:8080:8080'
    cap_drop:
      - ALL
    security_opt:
      - no-new-privileges:true
```

### Manual

<details>
<summary>Show/hide install details</summary>

1) Download the latest release to your web server.

	```
	wget https://github.com/VerifiedJoseph/intruder-alert/releases/download/v1.0.0/intruder-alert-v1.0.0.zip
	```

2) Extract the zip archive.

	```
	unzip intruder-alert-v1.0.0.zip
	```

3) Configure the application using `backend/config.php` copied from [`backend/config.example.php`](backend/config.example.php).
	
	```
	cp backend/config.example.php backend/config.php
	```

4) Create a scheduled task with cron (below) or similar that runs `backend\script.php` at least once an hour.

	```
	1 * * * * php path/to/intruder-alert/backend/script.php
	```

**Notes**

The backend folder does not need to be reachable in the browser and access should blocked with a reverse proxy rule.
</details>

## Configuration

Environment variables are used to adjust the configuration.

| Name                    | Type      | Description                                                                   |
| ------------------------| --------- | ----------------------------------------------------------------------------- |
| `IA_LOG_PATHS`          | `string`  | Comma separated list of Fail2ban log files.                                   |
| `IA_LOG_FOLDER`         | `string`  | Path of the Fail2ban logs folder. Ignored if `IA_LOG_PATHS` is set.           |
| `IA_MAXMIND_LICENSE_KEY`| `string`  | MaxMind license key for automatic GeoLite2 database downloads.                |
| `IA_ASN_DATABASE`       | `string`  | Path of the GeoLite2 ASN database file.                                       |
| `IA_COUNTRY_DATABASE`   | `string`  | Path of the GeoLite2 Country database file.                                   |
| `IA_TIMEZONE`           | `string`  | Timezone ([php docs](https://www.php.net/manual/en/timezones.php))            |
| `IA_SYSTEM_LOG_TIMEZONE`| `string`  | Timezone of fail2ban logs (optional, default is UTC)                          |
| `IA_DASH_CHARTS`        | `boolean` | Enable/disable dashboard charts (optional, enabled by default).               |
| `IA_DASH_UPDATES`       | `boolean` | Enable/disable automatic dashboard updates (optional, enabled by default).    |

### GeoLite2 databases

GeoLite2 databases will be automatically downloaded and updated if a [MaxMind license key](https://support.maxmind.com/hc/en-us/articles/4407111582235-Generate-a-License-Key) is set with `IA_MAXMIND_LICENSE_KEY`. 

Alternatively, the databases can be manually [downloaded](https://dev.maxmind.com/geoip/geolite2-free-geolocation-data?lang=en) and set using the environment variables `IA_ASN_DATABASE` and `IA_COUNTRY_DATABASE`.

## Development

Clone the repository.

```
git clone https://github.com/VerifiedJoseph/intruder-alert
```

Install dependencies with composer.

```
composer install --no-dev
```

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
