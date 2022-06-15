<?php


namespace App\Repositories;

use App\Models\URLShortener;
use Illuminate\Support\Str;

class URLShortenerRepositoryEloquent implements URLShortenerRepositoryInterface
{
    public function __construct(protected URLShortener $model)
    {
    }

    /**
     * Create a hash/unique short code (length 6)
     * if this code already exist in DB, try again
     * else return
     *
     * @return string
     */
    public function createHashCode($idx = 0): string
    {
        $urlCode = Str::random(6);

        if ($this->checkUrlHash($urlCode)) {
            return $this->createHashCode($idx);
        }

        return $urlCode;
    }

    /**
     * New data entry
     * IF original URL exists, no need to save, just return
     * IF short code symbol match with another, try again
     * Check with google safe browsing lookup [REMAINS]
     *
     * @param array $data
     * @return mixed
     */
    public function createResource(array $data): mixed
    {
        if ($existData = $this->getByColumn('original_url', $data['original_url'])) {
            return [
                'data'=> $existData,
                'status' => 200 // because if data exist, then it is not created, just found
            ];
        }

        $data['url_hash'] = $this->createHashCode();

        return $this->model::create($data) ?? false;
    }

    /**
     * Get all/ paginated data
     *
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function getAll(string $orderBy = 'DESC', string $sortBy = 'id'): mixed
    {
        // TODO:
        return "";
    }



    /**
     * check the generated code exist already or not
     *
     * @param string $code
     * @return bool
     */
    public function checkUrlHash(string $code): bool
    {
        return (bool)$this->getByColumn('url_hash', $code);
    }


    /**
     * specifically needed for finding the original URL, but can be used any column search
     *
     * @param string $columnName
     * @param string $value
     * @return mixed
     */
    public function getByColumn(string $columnName, string $value): mixed
    {
        return $this->model::where($columnName, $value)->first();
    }
}