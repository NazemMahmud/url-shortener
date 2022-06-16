<?php

namespace Tests\Unit;

use Tests\TestCase;

class UrlShortenTest extends TestCase
{

    // TODO: Test 5: create new short symbol: Add Success
    // TODO: Test 6: create new short symbol: URL Already Exist
    // TODO: Test 7: create new short symbol: Symbol Already Exist
    // TODO: Test 8: create new short symbol: Validation error: single ( empty field sent)
    // TODO: Test 9: create new short symbol: Validation error: multiple ( empty field sent + wrong URL format)
    // TODO: Test 10: create new short symbol: Unsafe URL


//    '/url-shorten',
//    '/url-shorten/'

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

    /**
     * Test 3: get original URL from short symbol: Correct
     * To test it correctly, insert some data, and pick one symbol from there, then update the $hash
     * Match: status code; response data structure; value of status == success
     * @return void
     */
    public function test_get_original_url_success()
    {
        $hash = '2iLtB7';
        $response = $this->get('api/url-shorten/get-url/' . $hash);
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
    
}
