# Changelog

All notable changes to this project are documented in this file.

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
