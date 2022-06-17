# About
**Create URL shortener after checking the URL with Google Safe Browsing Lookup API**

# Table of Contents

| No. | Title                                                               |
|-----|---------------------------------------------------------------------|
| 1   | [Todos](#todos)                                                     |
| 2   | [Installation](#installation)                                       |
| 3   | [Google Safe Browsing Lookup API](#google-safe-browsing-lookup-api) |
| 4   | [Unit Test](#unit-test)                                             |
| 5   | [MySQL Data](#mysql-data)                                           |
| 6   | [Postman](#postman)                                                 |


# Task Implemented
1. Create a short symbol for a safe & valid URL.
   1. URL is taken from a form, form validation is added.
   2. Shortened till 6 symbols hash, which contains alphanumeric symbols
   3. The format of generated URL: `example.com/[hash]`
2. Algorithm can recognize duplicate URL. If duplicate, no new short URL is generated, returning previous data. 
   1. Also, http status code is maintained here. That is, is new short symbol is generated, status code = 201. 
   2. For existing data, status code = 200
3. **++Additional:** Also checked for unique symbol, in case same symbol already exist in the DB for another URL, tried again until generate a new one.
4. Upon submit, the URL is checked using the [**Google Safe Browsing API**](https://developers.google.com/safe-browsing/v4/lookup-api).
5. In front, from the URLs list, upon opening the short URL, page is redirected to the original URL. 
   1. Hash Symbol is checked from back-end for redirection. Such as, `http:localhost:port/url-shorten/get-url/{hash}`
6. Initially, if there is no data, there will be an empty card with text message, instead of a data table.
7. Loader and Toast message is integrated.

**Note:**
- _Pagination is implemented only in back-end API._


# Installation
- For implementation used: **Laravel** and **Vue.js**
- Laravel version `^8.0` is used. So, respective PHP version is pre-required to install
- after git clone, run `composer install`
- Install npm packages: run `npm install`
- Key generate: `php artisan key:generate`
- Run migration: `php artisan migrate`
- Run server: `php artisan serve`
- Run Vue: `npm run hot`


# Google Safe Browsing Lookup API
- Created an API key using my gmail
- Required data, like, **CLIENT_ID**, **CLIENT_VERSION** and **API_KEY** for lookup API call is provided in `env.example` file
- There is **an extra route added**, in order to test the lookup API is correctly implemented or not: Route path:: `http://localhost:port/api/check-url`\
You will find more unsafe site link here: `https://testsafebrowsing.appspot.com/`


# Unit Test
- Run Tests: `php artisan test`
- Check the test file in `tests/Unit/UrlShortenTest.php`. You may add / update any test method if you feel necessary.

### Note:
- _Normally unit tests code is written before the actual functions. But, here I wrote the tests code later_.

# MySQL Data
You can use existing data from here: 
```
short_urls.sql
```

# Postman
Postman API collection is provided here: 
```
URL shortener.postman_collection.json
```
