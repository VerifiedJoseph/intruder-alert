# Changelog

All notable changes to this project are documented in this file.

## [1.14.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.14.1) - 2024-03-20

- Fixed checking environment variable `IA_MAXMIND_LICENSE_KEY`. ([#453](https://github.com/VerifiedJoseph/intruder-alert/pull/453), [`65184b0`](https://github.com/VerifiedJoseph/intruder-alert/commit/65184b07ab9039753951af72fe8c16779074d3be))

## [1.14.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.14.0) - 2024-03-19

- Dockerfile: Updated composer from 2.7.1 to 2.7.2 ([#430](https://github.com/VerifiedJoseph/intruder-alert/pull/430), [`acde901`](https://github.com/VerifiedJoseph/intruder-alert/commit/acde90196ad97479b1880b0ed389bc58c441eea1))
- Dockerfile: Updated php from 8.2.16-fpm-alpine3.19 to 8.2.17-fpm-alpine3.19 ([#436](https://github.com/VerifiedJoseph/intruder-alert/pull/436), [`3972b61`](https://github.com/VerifiedJoseph/intruder-alert/commit/3972b615f27d08a7b8f843541dadaeb300637bc7))
- Added `App\Backend` and `App\Frontend` classes. ([#411](https://github.com/VerifiedJoseph/intruder-alert/pull/411), [`16765ac`](https://github.com/VerifiedJoseph/intruder-alert/commit/16765ac80574bae7147fac51017c94e18b1d2018))
- Added `Config\Check` class. ([#444](https://github.com/VerifiedJoseph/intruder-alert/pull/444), [`0db9990`](https://github.com/VerifiedJoseph/intruder-alert/commit/0db9990536da692ce3843135a5413a1737f02249))
- Added `Version` class. ([#433](https://github.com/VerifiedJoseph/intruder-alert/pull/433), [`09c4c71`](https://github.com/VerifiedJoseph/intruder-alert/commit/09c4c7123f62a3afdf4f2def95b60be100c332ab))
- Rewrote MaxMind GeoIP database updating. ([#420](https://github.com/VerifiedJoseph/intruder-alert/pull/420), [`27ea742`](https://github.com/VerifiedJoseph/intruder-alert/commit/27ea742aa45ded8a0b7f0f62e2f03584fe540f30))
- Reworked logging. ([#423](https://github.com/VerifiedJoseph/intruder-alert/pull/423), [`5c23b03`](https://github.com/VerifiedJoseph/intruder-alert/commit/5c23b03732fbcae702b03d504a65794ffd8e19ae))
- Reworked frontend and backend report update checking. ([#426](https://github.com/VerifiedJoseph/intruder-alert/pull/426), [`4c711d6`](https://github.com/VerifiedJoseph/intruder-alert/commit/4c711d6280645c8a02e029ba5494cb77aab89e70))

## [1.13.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.13.1) - 2024-03-01

- Npm: Updated chart.js from 4.4.1 to 4.4.2 ([#393](https://github.com/VerifiedJoseph/intruder-alert/pull/393), [`0bd2b3c`](https://github.com/VerifiedJoseph/intruder-alert/commit/0bd2b3cebf9c2e4aba5fae6f73f1327ad0392eb5))

## [1.13.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.13.0) - 2024-02-24

- frontend: Added last 48 hours chart view. ([#385](https://github.com/VerifiedJoseph/intruder-alert/pull/385), [`580309b`](https://github.com/VerifiedJoseph/intruder-alert/commit/580309b7be08b256820331c06a30da2679d1ad98))
- frontend: Replaced last 7 days chart with last 14 days. ([#386](https://github.com/VerifiedJoseph/intruder-alert/pull/386), [`bf8b093`](https://github.com/VerifiedJoseph/intruder-alert/commit/bf8b0933a6cc04286f7e3ce574efe9ed71344545))
- app(js): Added user friendly error message for when update checks fail. ([#388](https://github.com/VerifiedJoseph/intruder-alert/pull/388), [`4941e09`](https://github.com/VerifiedJoseph/intruder-alert/commit/4941e0961ba69d37a25841c415d102d87ff28f95))

## [1.12.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.12.0) - 2024-02-19

- Config: Changed behavior of `IA_SYSTEM_LOG_TIMEZONE`. System timezone is used instead of UTC if environment variable not given. ([#376](https://github.com/VerifiedJoseph/intruder-alert/pull/376), [`99fa389`](https://github.com/VerifiedJoseph/intruder-alert/commit/f50fed21c9b7bc58411edb30966a46b414a5a871))
- Reworked dark mode and reduced chart height. ([#374](https://github.com/VerifiedJoseph/intruder-alert/pull/374), [#377](https://github.com/VerifiedJoseph/intruder-alert/pull/377), [`5b611bd`](https://github.com/VerifiedJoseph/intruder-alert/commit/5b611bd767439aa551b5e6fe89184409fa34d9ab), [`99e1365`](https://github.com/VerifiedJoseph/intruder-alert/commit/99e13651dc31f948ac9019698f3f375eb7a7baa2))
- Dockerfile: Updated php from 8.2.15-fpm-alpine3.19 to 8.2.16-fpm-alpine3.19 ([#379](https://github.com/VerifiedJoseph/intruder-alert/pull/379), [`99fa389`](https://github.com/VerifiedJoseph/intruder-alert/commit/99fa389f4d79dab8778509862dcda2a00c911322))
- Dockerfile: Updated node from 20.11.0-alpine3.19 to 20.11.1-alpine3.19 ([#380](https://github.com/VerifiedJoseph/intruder-alert/pull/380), [`9a624d0`](https://github.com/VerifiedJoseph/intruder-alert/commit/9a624d0bbe517b02ffffb4d550ee5fb1a1fe8016))

## [1.11.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.11.2) - 2024-02-13

- IaData(js): Fixed `isUpdatingEnabled()` returning wrong feature status. ([#372](https://github.com/VerifiedJoseph/intruder-alert/pull/372), [`4075ae1`](https://github.com/VerifiedJoseph/intruder-alert/commit/4075ae156dbaf11dc2bb8dab955934c9278f5595))
- Dockerfile: Updated composer from 2.6.6 to 2.7.1 ([#370](https://github.com/VerifiedJoseph/intruder-alert/pull/370), [`54c3f96`](https://github.com/VerifiedJoseph/intruder-alert/commit/54c3f96501ee55cc0571cafe866923e1c17bcf60))
- npm: Updated spacetime from 7.5.0 to 7.6.0 ([#367](https://github.com/VerifiedJoseph/intruder-alert/pull/367), [`132941c`](https://github.com/VerifiedJoseph/intruder-alert/commit/132941c2b9eccf0c267c6ee3fba3e1ef296de7b0))

## [1.11.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.11.1) - 2024-02-12

- App: Fixed passing wrong database path to `Database\Country` in `processLogs()`. ([#358](https://github.com/VerifiedJoseph/intruder-alert/pull/358), [`7ffc6ac`](https://github.com/VerifiedJoseph/intruder-alert/commit/7ffc6acedd0888a89adf0200690f577c1c6e26c2))

## [1.11.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.11.0) - 2024-01-23

- Dockerfile: Added health check for php-fpm. ([#344](https://github.com/VerifiedJoseph/intruder-alert/pull/344), [`f93244e`](https://github.com/VerifiedJoseph/intruder-alert/commit/f93244e96f14f347c37a261308b36bb04b5cf8f3))

## [1.10.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.10.0) - 2024-01-20

- Added Fetch class. ([#336](https://github.com/VerifiedJoseph/intruder-alert/pull/336), [`1e3f3cf`](https://github.com/VerifiedJoseph/intruder-alert/commit/1e3f3cf79cdf1f79dccdfa60024025e4bbbc4694))
- Added Logger class method `removeEntries()`. ([#335](https://github.com/VerifiedJoseph/intruder-alert/pull/335), [`6c47b60`](https://github.com/VerifiedJoseph/intruder-alert/commit/6c47b60e825e309afaa1c5e8a0f2318867870509))
- Added Config class methods `getMaxMindDownloadUrl()` & `getGeoIpDatabaseFolder()`. ([#334](https://github.com/VerifiedJoseph/intruder-alert/pull/334), [`452c44d`](https://github.com/VerifiedJoseph/intruder-alert/commit/452c44dcfa0d0f10def3ef7863c1a7a9bab7a3e4))
- Reworked GeoIP database lookup class. ([#337](https://github.com/VerifiedJoseph/intruder-alert/pull/337), [`70bffcd`](https://github.com/VerifiedJoseph/intruder-alert/commit/70bffcd573c95f713486f45253196dc525049074))
- Dockerfile: Updated php from 8.2.14 to 8.2.15 ([#341](https://github.com/VerifiedJoseph/intruder-alert/pull/341), [`1c88cf7`](https://github.com/VerifiedJoseph/intruder-alert/commit/1c88cf7d7c2b90be80a7cc671d538cf2e35a81db))
- Dockerfile: Updated alpine from 3.18 to 3.19 ([#333](https://github.com/VerifiedJoseph/intruder-alert/pull/333), [`248cd9f`](https://github.com/VerifiedJoseph/intruder-alert/commit/248cd9f852c44387c92b2978faffd37da02c8563))
- Updated node from 18.18.1 to 20.11 ([#332](https://github.com/VerifiedJoseph/intruder-alert/pull/332), [`dbdb61a`](https://github.com/VerifiedJoseph/intruder-alert/commit/dbdb61adb58369e6d71377d81c72fbbf05d8a844))

## [1.9.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.9.1) - 2023-12-30

- Dockerfile: Updated php from 8.2.13 to 8.2.14 ([#318](https://github.com/VerifiedJoseph/intruder-alert/pull/318), [`5a59f48`](https://github.com/VerifiedJoseph/intruder-alert/commit/5a59f487b3e8c7de12e574208d58ddc08ca8d106))

## [1.9.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.9.0) - 2023-12-11

- Reworked Dockerfile. ([#291](https://github.com/VerifiedJoseph/intruder-alert/pull/291), [`9985282`](https://github.com/VerifiedJoseph/intruder-alert/commit/9985282f6e7c9b82287b15b9e09a8b947b7a3b5b))
- Dockerfile: Updated composer from 2.6.5 to 2.6.6 ([#301](https://github.com/VerifiedJoseph/intruder-alert/pull/301), [`06af435`](https://github.com/VerifiedJoseph/intruder-alert/commit/06af435ebd5e065f10896415e11d5ac8b1300701))
- Npm: Updated spacetime from 7.4.8 to 7.5.0 ([#299](https://github.com/VerifiedJoseph/intruder-alert/pull/299), [`e493db4`](https://github.com/VerifiedJoseph/intruder-alert/commit/e493db4f2142bfbc557f69f42d607bf5335cbb1e))

## [1.8.6](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.6) - 2023-12-05

- Composer: Updated geoip2/geoip2 from 2.13.0 to 3.0.0 ([#284](https://github.com/VerifiedJoseph/intruder-alert/pull/284), [`c8cde4e`](https://github.com/VerifiedJoseph/intruder-alert/commit/c8cde4e1e01fac836c250080296f98031fae9a42))
- Npm: Updated chart.js from 4.4.0 to 4.4.1 ([#289](https://github.com/VerifiedJoseph/intruder-alert/pull/289), [`24e9677`](https://github.com/VerifiedJoseph/intruder-alert/commit/24e9677b3d311c1146a0de0101261a47b1c363e1))

## [1.8.5](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.5) - 2023-12-02

- Dockerfile: Updated alpine from 3.18.4 to 3.18.5 ([#272](https://github.com/VerifiedJoseph/intruder-alert/pull/272), [`b65e9d3`](https://github.com/VerifiedJoseph/intruder-alert/commit/b65e9d3fdfb4ceb85df1482b39f93dc079f4c1af))
- Filter(js): Reworked removing filters by type. ([#279](https://github.com/VerifiedJoseph/intruder-alert/pull/279), [`0581170`](https://github.com/VerifiedJoseph/intruder-alert/commit/0581170c06a6ab7f0f9c61440e2777d69ef72c92))
- App(js): Set default table & chart view types. ([#280](https://github.com/VerifiedJoseph/intruder-alert/pull/280), [`6a55a24`](https://github.com/VerifiedJoseph/intruder-alert/commit/6a55a241edd4f311314744809b0bf67ce8221777))

## [1.8.4](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.4) - 2023-11-06

- CSS: Fixed height bug with filter chip close buttons. ([#256](https://github.com/VerifiedJoseph/intruder-alert/pull/256), [`4bdd81b`](https://github.com/VerifiedJoseph/intruder-alert/commit/4bdd81b5a5568dd5005e0e5cbc7435284942a4e1))

## [1.8.3](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.3) - 2023-11-01

- CSS: Minor changes to filter chip margins. ([#248](https://github.com/VerifiedJoseph/intruder-alert/pull/248), [`e5bdb13`](https://github.com/VerifiedJoseph/intruder-alert/commit/e5bdb13dcad012d2f38acb629a59ac51bac15da8))
- List\Addresses: Removed unused method `orderByDate()`. ([#247](https://github.com/VerifiedJoseph/intruder-alert/pull/247), [`6230e14`](https://github.com/VerifiedJoseph/intruder-alert/commit/6230e143856d14a8aab20586a9e645234fc5de86))
- Logs: Added method `getLines()`. ([#243](https://github.com/VerifiedJoseph/intruder-alert/pull/243), [`79d385d`](https://github.com/VerifiedJoseph/intruder-alert/commit/79d385d1bcf7d1b78a3d1d7270da4981ddfcea82))
- build: Optimized composer autoloader. ([#241](https://github.com/VerifiedJoseph/intruder-alert/pull/241), [`9bd9378`](https://github.com/VerifiedJoseph/intruder-alert/commit/9bd93786c23a386cce8cd670227df1f9832a3f15))

## [1.8.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.2) - 2023-10-18

- Logs: Fixed fatal error when log file is empty. ([#238](https://github.com/VerifiedJoseph/intruder-alert/pull/238), [`87974ad`](https://github.com/VerifiedJoseph/intruder-alert/commit/87974ad8adeb12627270dc0676d209643e48ad33))
- Dockerfile: Updated node from 18.18.1-alpine3.18 to 18.18.2-alpine3.18. ([#235](https://github.com/VerifiedJoseph/intruder-alert/pull/235), [`f252221`](https://github.com/VerifiedJoseph/intruder-alert/commit/f252221c4214cc3e1c41e53e56412d33052666ec))

## [1.8.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.1) - 2023-10-16

- Updated spacetime from 7.4.7 to 7.4.8. ([#229](https://github.com/VerifiedJoseph/intruder-alert/pull/229), [`19a684e`](https://github.com/VerifiedJoseph/intruder-alert/commit/19a684e77b9664dc594ea250f1d6f1d181436a67))
- FilterAddDialog(js): Added `#getTimeList()` and reworked `#setupElements()`. ([#226](https://github.com/VerifiedJoseph/intruder-alert/pull/226), [`ce0ee3a`](https://github.com/VerifiedJoseph/intruder-alert/commit/ce0ee3a13eef841b10c01dc8688d19269d356aca))
- Dockerfile: Updated node from 18.18.0-alpine3.18 to 18.18.1-alpine3.18. ([#227](https://github.com/VerifiedJoseph/intruder-alert/pull/227), [`701c3e6`](https://github.com/VerifiedJoseph/intruder-alert/commit/701c3e69cb4b75d87334d3412dc96952db780103))

## [1.8.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.8.0) - 2023-10-13

- Dialog(js): Refactored to allow for dialogs not tied to data groups. ([#220](https://github.com/VerifiedJoseph/intruder-alert/pull/220), [`b76279a`](https://github.com/VerifiedJoseph/intruder-alert/commit/b76279a95ea66a3bd3b623dcfba4343195cc3eec))
- Dialog(js) Renamed and moved dialog sub classes. ([#223](https://github.com/VerifiedJoseph/intruder-alert/pull/223), [`43b8884`](https://github.com/VerifiedJoseph/intruder-alert/commit/43b888486ed501d1602ee18e15b729acf411bf46))
- FilterAddDialog(js): Fixed jail filter name. ([#222](https://github.com/VerifiedJoseph/intruder-alert/pull/222), [`d55afaa`](https://github.com/VerifiedJoseph/intruder-alert/commit/d55afaa3500b20456ecc0564bfc87327cc29c82d))
- FilterAddDialog(js): Fixed filter list always showing every filter. ([#224](https://github.com/VerifiedJoseph/intruder-alert/pull/224), [`1b5fd38`](https://github.com/VerifiedJoseph/intruder-alert/commit/1b5fd38f6951c59f1ad893d868eadf5c2a33ff50))

## [1.7.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.7.0) - 2023-10-11

- App: Added features array to settings in `getJsonReport()` ([#217](https://github.com/VerifiedJoseph/intruder-alert/pull/217), [`a1d7deb`](https://github.com/VerifiedJoseph/intruder-alert/commit/a1d7deb6d2e0af74fa7bc544c1d8f8447e8bfcba))
- FilterChip(js): Improved accessibility of filter remove buttons. ([#215](https://github.com/VerifiedJoseph/intruder-alert/pull/215), [`2304114`](https://github.com/VerifiedJoseph/intruder-alert/commit/23041144aa023620a49f9e7c970b225e57c927a5))
- Table(js): Added scope attribute to header cells. ([#214](https://github.com/VerifiedJoseph/intruder-alert/pull/214), [`84b5f6d`](https://github.com/VerifiedJoseph/intruder-alert/commit/96d8b84c092f6ff534813ba536cb9199461f5ab3))
- Added '+' to dialog open button for adding a filter using CSS. ([#212](https://github.com/VerifiedJoseph/intruder-alert/pull/212), [`84b5f6d`](https://github.com/VerifiedJoseph/intruder-alert/commit/84b5f6ddd5baa68f2fd38e6ebb796304814bc09c))
- CSS: Changed font-family. ([#197](https://github.com/VerifiedJoseph/intruder-alert/pull/197), [`a67771b`](https://github.com/VerifiedJoseph/intruder-alert/commit/a67771b441747718af77ba7978ee99dabeb4cd46))
- Dockerfile: Build on alpine3.18 base image. ([#211](https://github.com/VerifiedJoseph/intruder-alert/pull/211), [`e139294`](https://github.com/VerifiedJoseph/intruder-alert/commit/e1392948cc9eb21754f49a5761fa9441781e0b83))
- Removed `array_search()` from List classes. ([#209](https://github.com/VerifiedJoseph/intruder-alert/pull/209), [`18746ea`](https://github.com/VerifiedJoseph/intruder-alert/commit/18746eacafb515d5f8db6911b36e5dee2d2dadfa))
- composer: Specify php version. ([#207](https://github.com/VerifiedJoseph/intruder-alert/pull/207), [`06b45cf`](https://github.com/VerifiedJoseph/intruder-alert/commit/06b45cf073f44d68cae27bee89709f02de5e12f3))
- Config: Added option to control displaying daemon log in dashboard. ([#216](https://github.com/VerifiedJoseph/intruder-alert/pull/216), [`6d8b289`](https://github.com/VerifiedJoseph/intruder-alert/commit/6d8b28983dc833473fd3174ce2711d7e30723481))
- Config: Added checking for required PHP extensions. ([#205](https://github.com/VerifiedJoseph/intruder-alert/pull/205), [`6927734`](https://github.com/VerifiedJoseph/intruder-alert/commit/692773413b89db7ae2163459852d1880af02d2be))
- Added Dev Container. ([#194](https://github.com/VerifiedJoseph/intruder-alert/pull/194), [`e9be39d`](https://github.com/VerifiedJoseph/intruder-alert/commit/e9be39d5429fe2fe07def87b64a6dd7b65792579))
- Downgraded node.js version to 18 (LTS). ([#193](https://github.com/VerifiedJoseph/intruder-alert/pull/193), [`5868dfd`](https://github.com/VerifiedJoseph/intruder-alert/commit/5868dfdb0f50f86fe8a61ec8ea6dd6349e7ce661))
- Database\Update: Fixed geoip folder path. ([#204](https://github.com/VerifiedJoseph/intruder-alert/pull/204), [`2ffdffe`](https://github.com/VerifiedJoseph/intruder-alert/commit/2ffdffebb9378b0dab41711381ef3777348c1f79))
- Database\Update: Improved error handling & add `buildUrl()`. ([#192](https://github.com/VerifiedJoseph/intruder-alert/pull/192), [`d1e5469`](https://github.com/VerifiedJoseph/intruder-alert/commit/d1e5469b4531c48ce140516c8275ec860339e407))

## [1.6.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.6.0) - 2023-10-02

* Replaced `tronovav/geoip2-update` with custom update logic. ([#188](https://github.com/VerifiedJoseph/intruder-alert/pull/188), [`d7e3aca`](https://github.com/VerifiedJoseph/intruder-alert/commit/d7e3acaa96ccd649027a5581eca129ae2b001544))

## [1.5.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.5.0) - 2023-10-01

* css: Fixed dark mode. ([#185](https://github.com/VerifiedJoseph/intruder-alert/pull/185), [`c378f33`](https://github.com/VerifiedJoseph/intruder-alert/commit/c378f330b42c99dc6022ec3d8f097f6af577954d))
* docs: Added screenshots. ([#183](https://github.com/VerifiedJoseph/intruder-alert/pull/183), [`515ed40`](https://github.com/VerifiedJoseph/intruder-alert/commit/515ed40a6c2744565f3fe72a2e8099bf64c73b27))
* Filter(js): Added time filters. ([#174](https://github.com/VerifiedJoseph/intruder-alert/pull/174), [`a8b1219`](https://github.com/VerifiedJoseph/intruder-alert/commit/a8b1219bb684418153a98d3852a4b79efc45ee7b))
* Added LogLine class. ([#169](https://github.com/VerifiedJoseph/intruder-alert/pull/169), [`b9df6f4`](https://github.com/VerifiedJoseph/intruder-alert/commit/b9df6f4706492a10b0b77b4a7819728870e23ae3))
* Dockerfile: Updated node from 20.7.0-alpine3.18 to 20.8.0-alpine3.18. ([#182](https://github.com/VerifiedJoseph/intruder-alert/pull/182), [`fad1160`](https://github.com/VerifiedJoseph/intruder-alert/commit/fad11606a7df0b4aeda2d31f6f4565c859fe9003))
* Dockerfile: Updated php from 8.2.10-fpm-alpine3.18 to 8.2.11-fpm-alpine3.18. ([#181](https://github.com/VerifiedJoseph/intruder-alert/pull/181), [`a4bc80d`](https://github.com/VerifiedJoseph/intruder-alert/commit/a4bc80d6566f4d1d617818e628e189c1eb278d95))
* Dockerfile: Updated composer from 2.6.3 to 2.6.4. ([#180](https://github.com/VerifiedJoseph/intruder-alert/pull/180), [`b95a0ed`](https://github.com/VerifiedJoseph/intruder-alert/commit/b95a0eddfbd7f91b2e0291c25fdca62316f73b71))

## [1.4.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.4.0) - 2023-09-29

* Added esbuild ([#162](https://github.com/VerifiedJoseph/intruder-alert/pull/162), [`7e571b6`](https://github.com/VerifiedJoseph/intruder-alert/commit/7e571b6d49dd6b3149810794d082be7e44e36d32))

## [1.3.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.3.1) - 2023-09-28

* Config: Added method `checkDataFolder()`. ([#159](https://github.com/VerifiedJoseph/intruder-alert/pull/159), [`acde564`](https://github.com/VerifiedJoseph/intruder-alert/commit/acde56472941f7386e5ec6ec73e69cb3c6e470dc))

## [1.3.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.3.0) - 2023-09-28

* Button(js): Removed class. ([#157](https://github.com/VerifiedJoseph/intruder-alert/pull/157), [`ebf22a5`](https://github.com/VerifiedJoseph/intruder-alert/commit/ebf22a54749281b249459138e659a434c2fd546e))
* Dialog(js): Minor changes to sub classes `FilterAdd` & `FilterOptions`. ([#155](https://github.com/VerifiedJoseph/intruder-alert/pull/155), [`1f67579`](https://github.com/VerifiedJoseph/intruder-alert/commit/1f675790b9a9e0afb079d108c2c9cf2c56d38020))
* Table(js): Use private class properties in `Table` & `Cell`. ([#154](https://github.com/VerifiedJoseph/intruder-alert/pull/154), [`001f1e1`](https://github.com/VerifiedJoseph/intruder-alert/commit/001f1e18e1f3dfd362d767c149fbcae9c2f3eee0))
* Dialog(js): Dynamically create dialog contents. ([#153](https://github.com/VerifiedJoseph/intruder-alert/pull/153), [`6cbff3d`](https://github.com/VerifiedJoseph/intruder-alert/commit/6cbff3d8be4892f18a17fe94cd583bf0befd7a1d))
* CreateTable(js): Removed unused parameter from `#createGenericRow()`. ([#152](https://github.com/VerifiedJoseph/intruder-alert/pull/152), [`f1a3d26`](https://github.com/VerifiedJoseph/intruder-alert/commit/f1a3d26b0506d798f7e98676edb79ab66781db80))
* FilterChip(js): Use Unicode character as close icon. ([#151](https://github.com/VerifiedJoseph/intruder-alert/pull/151), [`74d2028`](https://github.com/VerifiedJoseph/intruder-alert/commit/74d202843fdd46f55e89c55817c2de3f70232a17))
* css: Changed width for table column country, bans & IP. ([#150](https://github.com/VerifiedJoseph/intruder-alert/pull/150), [`5cd3394`](https://github.com/VerifiedJoseph/intruder-alert/commit/5cd3394c4888fba0f0d8ed535d17ee0c27ee37a9))
* css: Updated header version text position. ([#148](https://github.com/VerifiedJoseph/intruder-alert/pull/148), [`ab808d0`](https://github.com/VerifiedJoseph/intruder-alert/commit/ab808d0d3bd674d0ab257bb54a341ae3e5337326))
* Dockerfile: Removed installing curl package. ([#149](https://github.com/VerifiedJoseph/intruder-alert/pull/149), [`c8f847c`](https://github.com/VerifiedJoseph/intruder-alert/commit/c8f847c58b87a261350a68fe596e06cb4176470c))

## [1.2.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.2.0) - 2023-09-24

* FilterChip(js): Fixed updating chip action text. ([#141](https://github.com/VerifiedJoseph/intruder-alert/pull/141), [`83e2f94`](https://github.com/VerifiedJoseph/intruder-alert/commit/83e2f94f59b2f7c27af2032f6f94769c80b7e4b0))
* Updated `tronovav/geoip2-update` from 2.2.3 to 2.2.4. ([#137](https://github.com/VerifiedJoseph/intruder-alert/pull/137), [`f48dc17`](https://github.com/VerifiedJoseph/intruder-alert/commit/f48dc1722d652bd30d5bdd342fb05cbdabed1c58))
* CSS: Added text ellipsis to HTML select elements. ([#135](https://github.com/VerifiedJoseph/intruder-alert/pull/135), [`1d2ac6a`](https://github.com/VerifiedJoseph/intruder-alert/commit/1d2ac6ad16543265fd2be5b8443a4354ac8e20d3))

## [1.1.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.1.0) - 2023-09-23

* FilterChip(js): Added text ellipsis to filter value. ([#133](https://github.com/VerifiedJoseph/intruder-alert/pull/133), [`0bb27bf`](https://github.com/VerifiedJoseph/intruder-alert/commit/0bb27bfdccfd2330fe653d31e2e775195417abd3))
* TableFilter(js): Cloned data array in `getData()`. ([#132](https://github.com/VerifiedJoseph/intruder-alert/pull/132), [`4533ade`](https://github.com/VerifiedJoseph/intruder-alert/commit/4533ade058b8565651396412397e8f6d5287cd57))
* List\Dates: Ordered items by date. ([#131](https://github.com/VerifiedJoseph/intruder-alert/pull/131), [`6be3dea`](https://github.com/VerifiedJoseph/intruder-alert/commit/6be3dea4d5b4d9a9c108c619055821e23a8e7b14))

## [1.0.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.0.0) - 2023-09-20
Initial release
