<?php

namespace App\Http\Controllers;

use App\Facade\URLSafeLookup;
use App\Helpers\HttpHandler;
use App\Http\Requests\UrlAddRequest;
use App\Http\Resources\OriginalUrlResource;
use App\Http\Resources\UrlShortenerResource as ShortUrl;
use App\Http\Resources\UrlShortenerResourceCollection;
use App\Repositories\URLShortenerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class URLShortenerController extends Controller
{
    /**
     * resource class for API data return format
     *
     * @var string
     */
    protected string $resource;


    /**
     * resource collection class for API data return format
     *
     * @var string
     */
    protected string $resourceCollection;

    public function __construct(
        protected URLShortenerRepositoryInterface $repository,
    )
    {
        $this->resource = ShortUrl::class;
        $this->resourceCollection = UrlShortenerResourceCollection::class;
    }


    /**
     * Get all / paginated data
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $pageOffset = (isset($request->pageOffset)) ? (int) $request->pageOffset : null;
        $orderBy = $request->orderBy ??  'DESC';
        $sortBy = $request->sortBy ?? 'id';

        return new $this->resourceCollection($this->repository->getAll($pageOffset, $orderBy, $sortBy));
    }

    /**
     * create new shorten URL and save
     *
     * @param UrlAddRequest $request
     * request body data: original_url
     *
     * @return JsonResponse
     */
    public function store(UrlAddRequest $request): JsonResponse
    {
        $requestData = $request->all();
        $response = $this->repository->createResource($requestData);

        if ($response) {
            // if data already exist then status code is not 201
            return isset($response['status']) ? HttpHandler::successResponse(new $this->resource($response['data']))
                : HttpHandler::successResponse(new $this->resource($response), 201);
        }

        return HttpHandler::errorMessage('Something went wrong', 400);
    }


    /**
     * Get the original URL from shorten URL to redirect
     *
     * @param string $hash
     * @return JsonResponse
     */
    public function getOriginalUrl(string $hash): JsonResponse
    {
        $response = $this->repository->getByColumn('url_hash', $hash);

        if ($response) {
            return HttpHandler::successResponse(new OriginalUrlResource($response));
        }

        return HttpHandler::errorMessage();
    }

    /**
     * test google safe browsing lookup API
     * ONLY FOR TEST PURPOSE
     *
     * @return void
     */
    public function check()
    {
        // TEST from here for malicious site: https://testsafebrowsing.appspot.com/
         $url = "https://laravel.com/docs/8.x/facades";
//        $url = "testsafebrowsing.appspot.com/s/phishing.html";
        $result = URLSafeLookup::urlLookup($url); // URLSafeLookup
        return $result['status'] === 200 ? [ "status" => $result['status'], "message" => "Safe"] :
            [ "status" => $result['status'], "message" => $result['message']];
    }
}
