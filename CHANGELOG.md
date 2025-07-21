# Changelog

All notable changes to this project are documented in this file.

## [1.22.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.22.2) - 2025-07-21

- Updated chart.js from 4.4.9 to 4.5.0 ([#847](https://github.com/VerifiedJoseph/intruder-alert/pull/847), [`01dbdcf`](https://github.com/VerifiedJoseph/intruder-alert/commit/01dbdcfe5e6d199dd7e55b8cd9a0f19796806912))
- Dockerfile: Updated php from 8.3.22-fpm-alpine3.20 to 8.3.23-fpm-alpine3.22 ([#862](https://github.com/VerifiedJoseph/intruder-alert/pull/862), [`feb3a36`](https://github.com/VerifiedJoseph/intruder-alert/commit/feb3a364dde5059a32343d23ed7ac130fc8d245e))
- Dockerfile: Updated node from 22.16-alpine3.22 to 22.17-alpine3.22 ([#864](https://github.com/VerifiedJoseph/intruder-alert/pull/864), [`95cc709`](https://github.com/VerifiedJoseph/intruder-alert/commit/95cc709e9f93e8558cb96d4416a4f4d54409b8ba))
- Dockerfile: Update alpine version of node image from 3.20 to 3.22 ([#861](https://github.com/VerifiedJoseph/intruder-alert/pull/861), [`afff874`](https://github.com/VerifiedJoseph/intruder-alert/commit/afff874d3d94e68061773281ba54ad95e6671790))

## [1.22.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.22.1) - 2025-06-11

- Dockerfile: Updated php from 8.3.21-fpm-alpine3.20 to 8.3.22-fpm-alpine3.20 ([#843](https://github.com/VerifiedJoseph/intruder-alert/pull/843), [`43e7f44`](https://github.com/VerifiedJoseph/intruder-alert/commit/43e7f447281d84eabbb86eae95bdc749b65d0701))
- Dockerfile: Updated node from 22.15-alpine3.20 to 22.16-alpine3.20 ([#835](https://github.com/VerifiedJoseph/intruder-alert/pull/835), [`b388178`](https://github.com/VerifiedJoseph/intruder-alert/commit/b388178a5c872af0626f74e171ee1a17403b1730))

## [1.22.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.22.0) - 2025-05-12

- Dropped support for php 8.2 ([#804](https://github.com/VerifiedJoseph/intruder-alert/pull/804), [`dbbc464`](https://github.com/VerifiedJoseph/intruder-alert/commit/dbbc46434e363866b939998e1d5d27900b229251))
- Docker: Reduced interval between backend tasks from 600 to 300 seconds. ([#827](https://github.com/VerifiedJoseph/intruder-alert/pull/827), [`d43490a`](https://github.com/VerifiedJoseph/intruder-alert/commit/d43490ad7b45d5414308fc275802199d2e628b69))
- Updated geoip2/geoip2 from 3.1.0 to 3.2.0 ([#823](https://github.com/VerifiedJoseph/intruder-alert/pull/823), [`cac8611`](https://github.com/VerifiedJoseph/intruder-alert/commit/cac86113a2193ca1e2ab6b9011cf59f162b1e636))
- Dockerfile: Updated php from 8.3.20-fpm-alpine3.20 to 8.3.21-fpm-alpine3.20 ([#821](https://github.com/VerifiedJoseph/intruder-alert/pull/821), [`dfa7ad6`](https://github.com/VerifiedJoseph/intruder-alert/commit/dfa7ad67ee2cc1e3136351e94765b4a4893e1189))
- Dockerfile: Updated node from 22.14-alpine3.20 to 22.15-alpine3.20 ([#811](https://github.com/VerifiedJoseph/intruder-alert/pull/811), [`602d81d`](https://github.com/VerifiedJoseph/intruder-alert/commit/602d81dda075e068939dc5d7cf493ffd5187f170))

## [1.21.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.21.1) - 2025-04-21

- Updated chart.js from 4.4.8 to 4.4.9 ([#800](https://github.com/VerifiedJoseph/intruder-alert/pull/800), [`5608410`](https://github.com/VerifiedJoseph/intruder-alert/commit/56084101c20c7182c8449559a59a780d99d66009))
- Updated spacetime from 7.8.0 to 7.9.0 ([#798](https://github.com/VerifiedJoseph/intruder-alert/pull/798), [`4811126`](https://github.com/VerifiedJoseph/intruder-alert/commit/4811126e8e36eed13a5fe00fc5c2b1571afd5ac7))
- Updated spacetime from 7.9.0 to 7.10.0 ([#802](https://github.com/VerifiedJoseph/intruder-alert/pull/802), [`a5fa42e`](https://github.com/VerifiedJoseph/intruder-alert/commit/a5fa42e32d992b160ef2aea2a5d792aff7f3674a))

## [1.21.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.21.0) - 2025-03-23

- Frontend: Reworked filtering ([#785](https://github.com/VerifiedJoseph/intruder-alert/pull/785), [`907db9d`](https://github.com/VerifiedJoseph/intruder-alert/commit/907db9d41b5d8d3fd0396eeafd4d7e78227c7015))
- Frontend: Replaced IaData class with Dataset and Settings classes. ([#786](https://github.com/VerifiedJoseph/intruder-alert/pull/786), [`ebecd93`](https://github.com/VerifiedJoseph/intruder-alert/commit/ebecd939edb19efcba681fb323c79373b11db3e1))
- Frontend: Fixed incorrect amount of minutes and seconds displayed in filter dialog. ([#784](https://github.com/VerifiedJoseph/intruder-alert/pull/784), [`1ab2ae3`](https://github.com/VerifiedJoseph/intruder-alert/commit/1ab2ae328100472e4865012b31f5684d2a400679))
- Updated spacetime from 7.7.0 to 7.8.0 ([#780](https://github.com/VerifiedJoseph/intruder-alert/pull/780), [`5342972`](https://github.com/VerifiedJoseph/intruder-alert/commit/5342972426de90e75822850bfd5e662df3bb5357))
- Dockerfile: Updated php from 8.2.27-fpm-alpine3.20 to 8.2.28-fpm-alpine3.20 ([#783](https://github.com/VerifiedJoseph/intruder-alert/pull/783), [`7287f77`](https://github.com/VerifiedJoseph/intruder-alert/commit/7287f7732fdf2eb5cf2d9550c2a9a54d9bc425d4))

## [1.20.4](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.20.4) - 2025-03-04

- Reworked front-end to remove use of `crypto.randomUUID()` ([#778](https://github.com/VerifiedJoseph/intruder-alert/pull/778), [`8028bb9`](https://github.com/VerifiedJoseph/intruder-alert/commit/8028bb9f0a1cc322fb806607864445a912c01666))

## [1.20.3](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.20.3) - 2025-03-03

- Updated chart.js from 4.4.7 to 4.4.8 ([#763](https://github.com/VerifiedJoseph/intruder-alert/pull/763), [`225abaa`](https://github.com/VerifiedJoseph/intruder-alert/commit/225abaa68f78e2324e08feb9d200f4482b8ddfdb))
- Updated node from 20.18 to 22.14 ([#772](https://github.com/VerifiedJoseph/intruder-alert/pull/772), [`31fdecb`](https://github.com/VerifiedJoseph/intruder-alert/commit/6f1ae08c0e68f168213ad09f7037f1109a99c8e1))

## [1.20.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.20.2) - 2025-02-17

- Dockerfile: Updated composer from 2.8.4 to 2.8.5 ([#741](https://github.com/VerifiedJoseph/intruder-alert/pull/741), [`31fdecb`](https://github.com/VerifiedJoseph/intruder-alert/commit/31fdecb309ed85a96dedb61081af4e0e7d5e65af))
- Dockerfile: Update node from 20.18.2-alpine3.20 to 20.18.3-alpine3.20 ([#759](https://github.com/VerifiedJoseph/intruder-alert/pull/759), [`c81771c`](https://github.com/VerifiedJoseph/intruder-alert/commit/c81771c6c4ed5fb5bade877c877e7466827e1b6b))
- Dockerfile: Update node from 20.18.1-alpine3.20 to 20.18.2-alpine3.20 ([#742](https://github.com/VerifiedJoseph/intruder-alert/pull/742), [`57dbd13`](https://github.com/VerifiedJoseph/intruder-alert/commit/57dbd134dc4b5cf99f5fa23896dd06970a70db48))

## [1.20.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.20.1) - 2024-12-30

- Dockerfile: Updated composer from 2.8.3 to 2.8.4 ([#723](https://github.com/VerifiedJoseph/intruder-alert/pull/723), [`fa0ab4d`](https://github.com/VerifiedJoseph/intruder-alert/commit/fa0ab4d50eddcdd9f7bb1a43d6c16f505dfd9111))
- Dockerfile: Updated php from 8.2.26-fpm-alpine3.20 to 8.2.27-fpm-alpine3.20 ([#727](https://github.com/VerifiedJoseph/intruder-alert/pull/727), [`6fb7497`](https://github.com/VerifiedJoseph/intruder-alert/commit/6fb749785dd9f3f7bb0ab1b200a4a922bf24ccd4))

## [1.20.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.20.0) - 2024-12-09

- Added verbose logging option ([#707](https://github.com/VerifiedJoseph/intruder-alert/pull/707), [`b5ee53e`](https://github.com/VerifiedJoseph/intruder-alert/commit/b5ee53e1b606d98e46db66ad32d91fdd296d5df3))
- Updated chart.js from 4.4.6 to 4.4.7 ([#717](https://github.com/VerifiedJoseph/intruder-alert/pull/717), [`585703f`](https://github.com/VerifiedJoseph/intruder-alert/commit/585703f4ca2cf23c52194b1e1ffac6f6a437a578))
- Updated spacetime from 7.6.2 to 7.7.0 ([#716](https://github.com/VerifiedJoseph/intruder-alert/pull/716), [`ac19eb4`](https://github.com/VerifiedJoseph/intruder-alert/commit/ac19eb44deb9cf18db391a0a5103a92ef15364e8))
- Dockerfile: Updated composer from 2.8.2 to 2.8.3 ([#706](https://github.com/VerifiedJoseph/intruder-alert/pull/706), [`391fc56`](https://github.com/VerifiedJoseph/intruder-alert/commit/391fc56acddf1e9a07f7e476ede725135a996a32))

## [1.19.8](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.8) - 2024-10-31

- Updated spacetime from 7.6.1 to 7.6.2 ([#679](https://github.com/VerifiedJoseph/intruder-alert/pull/679), [`aab8a3b`](https://github.com/VerifiedJoseph/intruder-alert/commit/aab8a3b1f558fddda94d816c655c3346776c1548))
- Updated chart.js from 4.4.4 to 4.4.5 ([#680](https://github.com/VerifiedJoseph/intruder-alert/pull/680), [`74a5b6d`](https://github.com/VerifiedJoseph/intruder-alert/commit/74a5b6d18b5625c29f3a73e92dbeb772ed720c09))
- Updated chart.js from 4.4.5 to 4.4.6 ([#686](https://github.com/VerifiedJoseph/intruder-alert/pull/686), [`f1adf74`](https://github.com/VerifiedJoseph/intruder-alert/commit/f1adf741e1de56c4f41d978043baa8b2a07f8e1f))
- Dockerfile: Updated composer from 2.8.1 to 2.8.2 ([#687](https://github.com/VerifiedJoseph/intruder-alert/pull/687), [`c343a1a`](https://github.com/VerifiedJoseph/intruder-alert/commit/c343a1a13cebfa2e87cd0a8c52e560efd0387ce7))
- Dockerfile: Updated php from 8.2.24-fpm-alpine3.19 to 8.2.25-fpm-alpine3.19 ([#684](https://github.com/VerifiedJoseph/intruder-alert/pull/684), [`2229e5d`](https://github.com/VerifiedJoseph/intruder-alert/commit/2229e5de31ed50f815ffb75d6eaec2dcba5e09fb))

## [1.19.7](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.7) - 2024-10-08

- Dockerfile: Updated php from 8.2.23-fpm-alpine3.19 to 8.2.24-fpm-alpine3.19 ([#662](https://github.com/VerifiedJoseph/intruder-alert/pull/662), [`200185d`](https://github.com/VerifiedJoseph/intruder-alert/commit/200185dd71db774e9271f51e9e68cdc5febccfc3))
- Dockerfile: Updated node from 20.17.0-alpine3.19 to 20.18.0-alpine3.19 ([#670](https://github.com/VerifiedJoseph/intruder-alert/pull/670), [`01b7a26`](https://github.com/VerifiedJoseph/intruder-alert/commit/01b7a2670a6e26f4d91234fb4cc0d620859e8a19))
- Dockerfile: Updated composer from 2.7.9 to 2.8.0 ([#671](https://github.com/VerifiedJoseph/intruder-alert/pull/671), [`3480baa`](https://github.com/VerifiedJoseph/intruder-alert/commit/3480baa50c01c52eca56779c803caf802be09eee))
- Dockerfile: Updated composer from 2.8.0 to 2.8.1 ([#674](https://github.com/VerifiedJoseph/intruder-alert/pull/674), [`978a4ad`](https://github.com/VerifiedJoseph/intruder-alert/commit/978a4adef2f489258863b42cfaf9f160d6fbca94))

## [1.19.6](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.6) - 2024-09-20

- css: Fixed sticky table header ([#653](https://github.com/VerifiedJoseph/intruder-alert/pull/653), [`24bda04`](https://github.com/VerifiedJoseph/intruder-alert/commit/24bda04e21dca848caaf77de0e17be81c6eb20bc))

## [1.19.5](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.5) - 2024-09-17

- Added ellipsis to text in most banned IP address box. ([#649](https://github.com/VerifiedJoseph/intruder-alert/pull/649), [`912df63`](https://github.com/VerifiedJoseph/intruder-alert/commit/912df6378eccccc317f6991e28484657deb249b1))
- Dockerfile: Updated composer from 2.7.8 to 2.7.9 ([#643](https://github.com/VerifiedJoseph/intruder-alert/pull/643), [`d58fe93`](https://github.com/VerifiedJoseph/intruder-alert/commit/d58fe93864a340af2b5adaf2bbf9ef0bafa982dc))

## [1.19.4](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.4) - 2024-09-03

- css: Sticky table header ([#642](https://github.com/VerifiedJoseph/intruder-alert/pull/642), [`38e3b97`](https://github.com/VerifiedJoseph/intruder-alert/commit/38e3b97b234832964142109cef62316f54ce2d37))
- Dockerfile: Updated composer from 2.7.7 to 2.7.8 ([#632](https://github.com/VerifiedJoseph/intruder-alert/pull/632), [`3e4d2cb`](https://github.com/VerifiedJoseph/intruder-alert/commit/3e4d2cb42264dc78dae6c7fb1831dd640f92a3ec))
- Dockerfile: Updated node from 20.16.0-alpine3.19 to 20.17.0-alpine3.19 ([#631](https://github.com/VerifiedJoseph/intruder-alert/pull/631), [`6621f3f`](https://github.com/VerifiedJoseph/intruder-alert/commit/6621f3f5a0de11bc43fc49b8014febfa5935babf))
- Dockerfile: Updated php from 8.2.22-fpm-alpine3.19 to 8.2.23-fpm-alpine3.19 ([#639](https://github.com/VerifiedJoseph/intruder-alert/pull/639), [`66db7f0`](https://github.com/VerifiedJoseph/intruder-alert/commit/66db7f03f222a3628330e9a165a46c8fec907abf))

## [1.19.3](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.3) - 2024-08-21

- Updated chart.js from 4.4.3 to 4.4.4 ([#627](https://github.com/VerifiedJoseph/intruder-alert/pull/627), [`15e03e9`](https://github.com/VerifiedJoseph/intruder-alert/commit/15e03e98c82846a9b58f6690694ada6e31f51440))
- Minor change to table css. ([#629](https://github.com/VerifiedJoseph/intruder-alert/pull/629), [`c83aa1f`](https://github.com/VerifiedJoseph/intruder-alert/commit/c83aa1fd6e5fc16c31be39bd0392d3c7a2a05c2e))

## [1.19.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.2) - 2024-08-02

- Updated spacetime from 7.6.0 to 7.6.1 ([#614](https://github.com/VerifiedJoseph/intruder-alert/pull/614), [`62fb54f`](https://github.com/VerifiedJoseph/intruder-alert/commit/62fb54f55aefbb3c0d6a6d83bdca08e73c20d413))
- Dockerfile: Updated php from 8.2.21-fpm-alpine3.19 to 8.2.22-fpm-alpine3.19 ([#619](https://github.com/VerifiedJoseph/intruder-alert/pull/619), [`9cdea3c`](https://github.com/VerifiedJoseph/intruder-alert/commit/9cdea3c3e1e5ce1a50e98e7795ac509f1fa9e589))

## [1.19.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.1) - 2024-07-29

- Dockerfile: Updated node from 20.15.1-alpine3.19 to 20.16.0-alpine3.19 ([#603](https://github.com/VerifiedJoseph/intruder-alert/pull/603), [`832a97c`](https://github.com/VerifiedJoseph/intruder-alert/commit/832a97c6f0998d4f1a0f84bc108917b785ef11e4))

## [1.19.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.19.0) - 2024-07-17

- Dropped support for php 8.1 ([#598](https://github.com/VerifiedJoseph/intruder-alert/pull/598), [`5df5941`](https://github.com/VerifiedJoseph/intruder-alert/commit/5df59417f60ab12e9e97a9eb0a86a6d4480860f4))
- Minor table css improvements. ([#599](https://github.com/VerifiedJoseph/intruder-alert/pull/599), [`ae70f4e`](https://github.com/VerifiedJoseph/intruder-alert/commit/ae70f4e1f71464bf8d6d938981bb80ee1c27e598))
- Dockerfile: Updated node from 20.15.0-alpine3.19 to 20.15.1-alpine3.19 ([#595](https://github.com/VerifiedJoseph/intruder-alert/pull/595), [`79640dc`](https://github.com/VerifiedJoseph/intruder-alert/commit/79640dc4ad3e2d49235f7a0f234096a2999f76b2))

## [1.18.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.18.0) - 2024-07-08

- Fixed action text on filter chips not changing when filters are reversed. ([#575](https://github.com/VerifiedJoseph/intruder-alert/pull/575), [`41cc278`](https://github.com/VerifiedJoseph/intruder-alert/commit/41cc27898bdb75c05dcc5d985f884dbb15f24869))
- Moved checking GeoIP archive file integrity to database downloader class. ([#590](https://github.com/VerifiedJoseph/intruder-alert/pull/590), [`987478d`](https://github.com/VerifiedJoseph/intruder-alert/commit/987478de6f0587a23852649a0376b8ae06191279))
- Dockerfile: Updated node from 20.14.0-alpine3.19 to 20.15.0-alpine3.19 ([#584](https://github.com/VerifiedJoseph/intruder-alert/pull/584), [`6529e61`](https://github.com/VerifiedJoseph/intruder-alert/commit/6529e61924a29baade966eb2131b391b711cd164))
- Dockerfile: Updated php from 8.2.20-fpm-alpine3.19 to 8.2.21-fpm-alpine3.19 ([#585](https://github.com/VerifiedJoseph/intruder-alert/pull/585), [`faff4c2`](https://github.com/VerifiedJoseph/intruder-alert/commit/faff4c242606d01431f05ea5b494442de66305bf))

## [1.17.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.17.2) - 2024-06-18

- Dockerfile: Updated composer from 2.7.6 to 2.7.7 ([#563](https://github.com/VerifiedJoseph/intruder-alert/pull/563), [`c0935c2`](https://github.com/VerifiedJoseph/intruder-alert/commit/c0935c2dce7995a0a8c7f5af708cebcade4cdcbf))
- Dockerfile: Updated node from 20.13.1-alpine3.19 to 20.14.0-alpine3.19 ([#553](https://github.com/VerifiedJoseph/intruder-alert/pull/553), [`90134fa`](https://github.com/VerifiedJoseph/intruder-alert/commit/90134fac63fd54c2858d7dcecb4d2c692ee32ba4))
- Dockerfile: Updated php from 8.2.19-fpm-alpine3.19 to 8.2.20-fpm3.19 ([#561](https://github.com/VerifiedJoseph/intruder-alert/pull/561), [`86122a0`](https://github.com/VerifiedJoseph/intruder-alert/commit/86122a097febe435f0b21d53ce00083969b09a56))

## [1.17.1](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.17.1) - 2024-05-20

- Improved code comments & minor code changes. ([#530](https://github.com/VerifiedJoseph/intruder-alert/pull/530), [`b9a54aa`](https://github.com/VerifiedJoseph/intruder-alert/commit/b9a54aabb676fde4a7db6ef487c75eddbb5d53c9))
- Npm: Updated chart.js from 4.4.2 to 4.4.3 ([#545](https://github.com/VerifiedJoseph/intruder-alert/pull/545), [`8c4fe09`](https://github.com/VerifiedJoseph/intruder-alert/commit/8c4fe09582cf30201f91aaf59bd1b10f3d0ea41e))
- Dockerfile: Updated composer from 2.7.4 to 2.7.6 ([#528](https://github.com/VerifiedJoseph/intruder-alert/pull/528), [`c354fb2`](https://github.com/VerifiedJoseph/intruder-alert/commit/c354fb233d2c366e641c08fdb88c179adf4d2147))
- Dockerfile: Updated node from 20.12.2-alpine3.19 to 20.13.1-alpine3.19 ([#535](https://github.com/VerifiedJoseph/intruder-alert/pull/535), [`e22fad5`](https://github.com/VerifiedJoseph/intruder-alert/commit/e22fad50722ffbad22b04dfc1b85ea989f6d2bb5))
- Dockerfile: Updated php from 8.2.18-fpm-alpine3.19 to 8.2.19-fpm3.19 ([#534](https://github.com/VerifiedJoseph/intruder-alert/pull/534), [`3e7fc45`](https://github.com/VerifiedJoseph/intruder-alert/commit/3e7fc45fdf6556b0c596fd1fc555282dc6850413))

## [1.17.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.17.0) - 2024-04-30

- Docker: Replaced php daemon script with bash script. ([#521](https://github.com/VerifiedJoseph/intruder-alert/pull/521), [`9f97d04`](https://github.com/VerifiedJoseph/intruder-alert/commit/9f97d04f6e074efbbe348abd708cebc87dcf7d2c))
- Dockerfile: Updated composer from 2.7.2 to 2.7.4 ([#513](https://github.com/VerifiedJoseph/intruder-alert/pull/513), [`c222b9d`](https://github.com/VerifiedJoseph/intruder-alert/commit/c222b9dc1da3e25e9c20f680af3bab3861231fcb))

## [1.16.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.16.0) - 2024-04-22

- Require environment variable `IA_SYSTEM_LOG_TIMEZONE` when running in docker. ([#500](https://github.com/VerifiedJoseph/intruder-alert/pull/500), [`50f2776`](https://github.com/VerifiedJoseph/intruder-alert/commit/50f2776378ca8672d88e9e669c5b92d70df1eb4f))
- Added dashboard chart environment variable `IA_DASH_DEFAULT_CHART`. ([#507](https://github.com/VerifiedJoseph/intruder-alert/pull/507), [`d655be4`](https://github.com/VerifiedJoseph/intruder-alert/commit/d655be46703997f859be58e11091ff7b51565c30))
- Added dashboard table page size environment variable `IA_DASH_PAGE_SIZE`. ([#510](https://github.com/VerifiedJoseph/intruder-alert/pull/510), [`0260263`](https://github.com/VerifiedJoseph/intruder-alert/commit/02602633cffffe1b854f10d80b2c16421bf82080))
- Added CSS text ellipsis to jail column in recent bans table. ([#499](https://github.com/VerifiedJoseph/intruder-alert/pull/499), [`dd0f5f5`](https://github.com/VerifiedJoseph/intruder-alert/commit/dd0f5f594fe3fb18a31aefe32580f8cb5a51ad3b))

## [1.15.0](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.15.0) - 2024-04-15

- Added backend class `Convert` ([#483](https://github.com/VerifiedJoseph/intruder-alert/pull/483), [`36c7ccf`](https://github.com/VerifiedJoseph/intruder-alert/commit/36c7ccfffd5f2d27534a4d14ac7e2717777cf451))
- Reworked backend date and time handling. ([#494](https://github.com/VerifiedJoseph/intruder-alert/pull/494), [`efb4172`](https://github.com/VerifiedJoseph/intruder-alert/commit/efb41725d0e71da36e94c47071c00155b3fc4180), [#495](https://github.com/VerifiedJoseph/intruder-alert/pull/495), [`20d99e1`](https://github.com/VerifiedJoseph/intruder-alert/commit/20d99e1632492673715b89e60beafa4e9ea2a57c))
- Split methods `timezones()` and `dashboard()` into multiple methods in backend class `Config\Check`. ([#484](https://github.com/VerifiedJoseph/intruder-alert/pull/484), [`36f51ca`](https://github.com/VerifiedJoseph/intruder-alert/commit/36f51cad9a4d81d28bd8b47db59ffe04c80cc6ec))
- Fixed log line regex failing on jails with non-alphameric characters. ([#493](https://github.com/VerifiedJoseph/intruder-alert/pull/493), [`6598bfc`](https://github.com/VerifiedJoseph/intruder-alert/commit/6598bfce3cce5cd4774af4574ae11974b1cfb480))

## [1.14.3](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.14.3) - 2024-04-13

- Dockerfile: Updated php from 8.2.17-fpm-alpine3.19 to 8.2.18-fpm3.19 ([#479](https://github.com/VerifiedJoseph/intruder-alert/pull/479), [`61f62cd`](https://github.com/VerifiedJoseph/intruder-alert/commit/61f62cdeeca3a44492bbc0ea2982a3798d79a7ae))
- Dockerfile: Updated node from 20.12.1-alpine3.19 to 20.12.2-alpine3.19 ([#477](https://github.com/VerifiedJoseph/intruder-alert/pull/477), [`a59a801`](https://github.com/VerifiedJoseph/intruder-alert/commit/a59a8019111eebc2c35de6498439f19d45cff324))

## [1.14.2](https://github.com/VerifiedJoseph/intruder-alert/releases/tag/v1.14.2) - 2024-04-05

- Dockerfile: Updated node from 20.12.0-alpine3.19 to 20.12.1-alpine3.19 ([#470](https://github.com/VerifiedJoseph/intruder-alert/pull/470), [`2867372`](https://github.com/VerifiedJoseph/intruder-alert/commit/28673724aa1f7e3fddad6ee608a933b0f8b45b54))
- Dockerfile: Updated node from 20.11.1-alpine3.19 to 20.12.0-alpine3.19 ([#464](https://github.com/VerifiedJoseph/intruder-alert/pull/464), [`aa4e5a7`](https://github.com/VerifiedJoseph/intruder-alert/commit/aa4e5a798b57812bb58341da402852773451715e))
- Backend: Removed permissions `0660` from `mkdir()` in class `Config\Check`. ([#456](https://github.com/VerifiedJoseph/intruder-alert/pull/456), [`7b072f8`](https://github.com/VerifiedJoseph/intruder-alert/commit/7b072f8c714fed8d107a8bf901ddae4c05c84366))
- Frontend: Updated filter method `remove()` to use unique filter identifiers. ([#460](https://github.com/VerifiedJoseph/intruder-alert/pull/460), [`7eaa189`](https://github.com/VerifiedJoseph/intruder-alert/commit/7eaa1897cf533dbc9be103f6b4149d8d02801b53))

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
