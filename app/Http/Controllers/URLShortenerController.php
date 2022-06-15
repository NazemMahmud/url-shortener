<?php

namespace App\Http\Controllers;

use App\Helpers\HttpHandler;
use App\Http\Requests\UrlAddRequest;
use App\Http\Resources\UrlShortenerResource as ShortUrl;
use App\Http\Resources\UrlShortenerResourceCollection;
use App\Repositories\URLShortenerRepositoryInterface;
use Illuminate\Http\JsonResponse;

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
     * Get all now, later will make pagination if get time
     *
     * @return mixed
     */
    public function index(): mixed
    {
        // TODO:
        return "";
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
     * @return mixed
     */
    public function getOriginalUrl(string $hash): mixed
    {
        // TODO:
        return "";
    }

    /**
     * test google safe browsing lookup API
     * ONLY FOR TEST PURPOSE
     *
     * @return void
     */
    public function check(): void
    {
        // TODO:
    }
}
