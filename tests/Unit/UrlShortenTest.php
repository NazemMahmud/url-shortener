<?php

namespace Tests\Unit;

use Tests\TestCase;

class UrlShortenTest extends TestCase
{
    /**
     * this to test: unsafe URL using google safe browsing lookup api
     * @var array
     */
    private array $unsafeUrlData;

    /**
     * this to test: empty validation
     * @var array
     */
    private array $emptyData;

    /**
     * this to test: data must a valid url (multiple validation)
     * @var array|string[]
     */
    private array $notValidateData;

    /**
     * this to test: success on creating new shorten url
     * @var array|string[]
     */
    private array $successData;

    /**
     * to get the original url
     * @var string
     */
    private string $hash;


    public function setUp(): void
    {
        parent::setUp();
        $this->hash = '2iLtB7';
        $this->unsafeUrlData = [ "original_url" => "https://testsafebrowsing.appspot.com/s/malware.html"];
        $this->emptyData = [ "original_url" => ""];
        $this->notValidateData = [ "original_url" => "github"];
        $this->successData = [ "original_url" => "https://github.com/NazemMahmud/strategy-pattern"];
    }

    /**************************************** GET All / Paginated: api/url-shorten  ***********************************/

    /**
     * Test 1: get all
     * Match: status code; response data structure; value of status == success
     *
     * @return void
     */
    public function test_get_all()
    {
        $response = $this->get('api/url-shorten');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'original_url', 'url_hash']
                ],
                'status'
            ])->assertJsonPath('status', 'success');
    }

    /**
     * Test 2: get paginated: will get latest 10 data
     * Match: status code; response data structure; value of status == success; data count == pageOffset
     * To test it: insert at least 10 data, OR, change the $offset value accordingly
     *
     * @return void
     */
    public function test_get_paginated()
    {
        $offset = 10;
        $response = $this->get('api/url-shorten?pageOffset='.$offset);
        //  $response->dump();
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'original_url', 'url_hash']
                ],
                'status',
                'links' => [ 'first', 'last', 'prev', 'next']
            ])->assertJsonPath('status', 'success')
            ->assertJsonCount($offset, 'data');
    }

    /************************ GET Original URL from Short Symbol: api/url-shorten/get-url/{hash}  *********************/
    /**
     * Test 3: get original URL from short symbol: Correct
     * To test it correctly, insert some data, and pick one symbol from there, then update the $hash
     * Match: status code; response data structure; value of status == success
     * @return void
     */
    public function test_get_original_url_success()
    {
        $response = $this->get('api/url-shorten/get-url/' . $this->hash);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['original_url'],
                'status'
            ])->assertJsonPath('status', 'success');
    }


    /**
     * Test 4: get original URL from short symbol: Incorrect | Not found
     * insert symbol length should be 6, more or less length is wrong symbol
     * Match: status code; response data structure; value of status == failed && error message
     *
     * @return void
     */
    public function test_get_original_url_failed()
    {
        $hash = '2iLtB';
        $response = $this->get('api/url-shorten/get-url/' . $hash);

        $response->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'status'
            ])->assertJsonPath('status', 'failed')
            ->assertJsonPath('error', 'Something went wrong');
    }

    /********************************* POST Add New Short Symbol: api/url-shorten  ************************************/
    /**
     * Test 5: create new short symbol: Unsafe URL
     * Match: status code; response data structure; value of status == failed && error message
     *
     * @return void
     */
    public function test_post_unsafe_url()
    {
        $response = $this->postJson('api/url-shorten', $this->unsafeUrlData);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'error',
                'status'
            ])->assertJsonPath('status', 'failed')
            ->assertJsonPath('error', 'This site is a malware, not safe');
    }


    /**
     * Test 6: create new short symbol: Validation error: single ( empty field sent)
     * Match: status code; response data structure; value of status == failed && error message
     *
     * @return void
     */
    public function test_post_validation_error()
    {
        $response = $this->postJson('api/url-shorten', $this->emptyData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
                'status'
            ])->assertJsonPath('status', 'failed')
            ->assertJsonPath('error', ['The original url field is required.']);
    }

    /**
     * Test 7: create new short symbol: Validation error: multiple ( wrong url length + wrong URL format)
     * Match: status code; response data structure; value of status == failed && error messages
     *
     * @return void
     */
    public function test_post_multi_validation_error()
    {
        $response = $this->postJson('api/url-shorten', $this->notValidateData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
                'status'
            ])->assertJsonPath('status', 'failed')
            ->assertJsonPath('error', [
                "The original url must be at least 9 characters.",
                "The original url format is invalid."
            ]);
    }


    /**
     * Test 8: create new short symbol: Add Success
     * Match: status code; response data structure; value of status == success && data.original_url
     *
     * @return void
     */
    public function test_post_add_new()
    {
        $response = $this->postJson('api/url-shorten', $this->successData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [ 'id', 'original_url', 'url_hash' ],
                'status'
            ])->assertJsonPath('status', 'success')
            ->assertJsonPath('data.original_url', $this->successData['original_url']);
    }

    /**
     * Test 9: create new short symbol: URL Already Exist
     * Match: status code; response data structure; value of status == success && some data value
     *
     * @return void
     */
    public function test_post_exist_url()
    {
        $response = $this->postJson('api/url-shorten', $this->successData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [ 'id', 'original_url', 'url_hash', 'message' ],
                'status'
            ])->assertJsonPath('status', 'success')
            ->assertJsonPath('data.original_url', $this->successData['original_url'])
            ->assertJsonPath('data.message', 'URL already exist');
    }

}
