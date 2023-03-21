# Intruder Alert
![screenshot](screenshot-2023-03-21.png)

Intruder Alert is an incident dashboard for Fail2ban.

## Frontend

Browser-based dashboard for viewing the data report created by the backend.

## Backend

PHP script for parsing Fail2ban logs and generating the report.

### Configuration

Use ` backend/config.php` (copied from [`backend/config.example.php`](backend/config.example.php)) to set the configuration.

| Name             | Description                             |
| ---------------- | --------------------------------------- |
| `LOG_FOLDER`     | Path of Fail2ban log files.             |
| `GEO_IP_ASN`     | Path of GeoLite2 ASN database file.     |
| `GEO_IP_COUNTRY` | Path of GeoLite2 Country database file. |

GeoLite2 databases can be [downloaded](https://dev.maxmind.com/geoip/geolite2-free-geolocation-data?lang=en) from MaxMind.

## Dependencies
- [`geoip2/geoip2`](https://github.com/maxmind/GeoIP2-php)

## Requirements

- Node.js >= 18.0
- PHP >= 8.1
- Composer

