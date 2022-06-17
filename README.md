# About
**Create URL shortener after checking the URL with Google Safe Browsing Lookup API**

# Table of Contents

| No. | Title                                                               |
|-----|---------------------------------------------------------------------|
| 1   | [Todos](#todos)                                                     |
| 2   | [Installation](#installation)                                       |
| 3   | [Google Safe Browsing Lookup API](#google-safe-browsing-lookup-api) |
| 4   | [Unit Test](#unit-test)                                             |


# Todos

1. Take a URL from a form, generate shorten URL from that URL.
   1. The format of generated URL: example.com/[hash]
   2. The short URL must be a valid URL [**DONE**]
   3. shortened till 6 symbols hash, which contains alphanumeric symbols [**DONE**]
   4. **++Additional:** I also checked for unique symbol, in case same symbol already exist in the DB for another URL [**DONE**]  
2. Algorithm must recognize duplicate URL. If duplicate, no need to generate new short URL, show previously
  created [**DONE**]
3. Upon submit, the URL should be checked using the **Google Safe Browsing API**
  (https://developers.google.com/safe-browsing/v4/lookup-api). Or any other API with the same
  function.
4. After implementation, upon opening the short URL, the user must be redirected to the original URL.

- Advantage, if functionality could work from folder (e.g.: example.com/something/[hash]). 
- For implementation use Laravel and Vue.js.

# Installation
- Laravel version `^8.0` is used. So, respective PHP version is pre-required to install
- after git clone, run `composer install`
- Install npm packages: run `npm install`
- Key generate: `php artisan key:generate`
- Run migration: `php artisan migrate`


# Google Safe Browsing Lookup API
- Created an API key using my gmail
- Required data, like, **CLIENT_ID**, **CLIENT_VERSION** and **API_KEY** for lookup API call is provided in `env.example` file
- There is **an extra route added**, in order to test the lookup API is correctly implemented or not: Route path:: `http://localhost:port/api/check-url`\
You will find more unsafe site link here: `https://testsafebrowsing.appspot.com/`


# Unit Test
- Run Tests: `php artisan test`
- Check the test file in `tests/Unit/UrlShortenTest.php`. Add / Update any test method if you feel necessary.

### Note:
- _Normally unit tests code is written before the actual functions. But, here I wrote the tests code later_.
