<?php

namespace App\Services;


use App\Exceptions\UnsafeURLException;

class URLSafeChecker
{

    /**
     * URL that need to check for safety
     *
     * @var string
     */
    private string $url;


    /**
     * google lookup api with api key
     *
     * @var string
     */
    private string $googleApiURL = '';


    /**
     * The last curl response
     * @var string
     */
    private string $curlResponse = "";


    /**
     * lookup the URL in google safe and return result accordingly
     *
     * @param string $url
     * @return void
     * @throws UnsafeURLException
     */
    public function urlLookup(string $url): void
    {
        $this->url = $url;
        $this->googleApiURL = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=" . env('GOOGLE_API_KEY') . "";

        $this->sendPostData();
    }

    /**
     * format the response before return
     *
     * @return void
     * @throws UnsafeURLException
     */
    private function throwUnsafeResponse(): void
    {
        $response = json_decode($this->curlResponse);
        if (isset($response->matches)) {
            throw match ($response->matches[0]->threatType) {
                "MALWARE" => new UnsafeURLException("This site is a malware, not safe"),
                "SOCIAL_ENGINEERING" => new UnsafeURLException("This site is for social engineering, not safe"),
                "UNWANTED_SOFTWARE" => new UnsafeURLException("This site is a unwanted software, not safe"),
                "POTENTIALLY_HARMFUL_APPLICATION" => new UnsafeURLException("This site is a potentially harmful application, not safe"),
                default => new UnsafeURLException("This site is not safe"),
            };
        }
    }


    /**
     * Get post body data for CURL
     *
     * @return string
     */
    private function getData(): string
    {
        return '{
                    "client": {
                      "clientId": "' . env('CLIENT_ID', 'url-shortener-checker') . '",
                      "clientVersion": "' . env('CLIENT_VERSION', '1.5.2') . '",
                    },
                    "threatInfo": {
                      "threatTypes":       ["MALWARE", "SOCIAL_ENGINEERING", "UNWANTED_SOFTWARE", "POTENTIALLY_HARMFUL_APPLICATION"],
                      "platformTypes":    ["ALL_PLATFORMS"],
                      "threatEntryTypes": ["URL"],
                      "threatEntries": [
                        {"url": "' . $this->url . '"}
                      ]
                    }
            }';
    }


    /**
     * @return void
     * @throws UnsafeURLException
     */
    private function sendPostData(): void
    {
        $data = $this->getData();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->googleApiURL);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $this->curlResponse = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new UnsafeURLException("Something is wrong with the site. Please try again");
        } else {
            $this->throwUnsafeResponse();
        }
    }
}
