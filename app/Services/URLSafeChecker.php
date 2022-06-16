<?php

namespace App\Services;


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
     * POST data that will be sent to google safe lookup API
     *
     * @var string
     */
    private string $data;


    /**
     * lookup the URL in google safe and return result accordingly
     *
     * @param string $url
     * @return array
     */
    public function urlLookup(string $url): array
    {
        $this->url = $url;
        $this->googleApiURL = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=" . env('GOOGLE_API_KEY') . "";
        $this->setData();

        $this->sendPostData();

        return !$this->curlResponse ? [
            "message" => "Something is wrong with the site. Please try again",
            "status" => 404 ] :
            $this->formatResponse();
    }

    /**
     * format the response before return
     *
     * @return array
     */
    private function formatResponse(): array
    {
        $response = json_decode($this->curlResponse);
        if (isset($response->matches))
        {
            foreach ($response->matches as $site)
            {
                return match ($site->threatType) {
                    "MALWARE" => [
                        "message" => "This site is a malware, not safe",
                        "status" => 404
                    ],
                    "SOCIAL_ENGINEERING" => [
                        "message" => "This site is for social engineering, not safe",
                        "status" => 404
                    ],
                    "UNWANTED_SOFTWARE" => [
                        "message" => "This site is a unwanted software, not safe",
                        "status" => 404
                    ],
                    "POTENTIALLY_HARMFUL_APPLICATION" => [
                        "message" => "This site is a potentially harmful application, not safe",
                        "status" => 404
                    ],
                    default => [
                        "message" => "This site is not safe",
                        "status" => 404
                    ],
                };
            }
        }

        return [
            "status" => 200
        ];
    }


    /**
     * Set post body data for CURL
     *
     * @return void
     */
    private function setData(): void
    {
        $this->data = '{
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
     */
    private function sendPostData(): void
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->googleApiURL);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $this->curlResponse = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->curlResponse = false;
        }
    }
}
