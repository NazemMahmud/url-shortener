<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\URLShortener;

class OriginalUrlResource extends JsonResource
{
    public function __construct(protected URLShortener $model)
    {
        parent::__construct($model);
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'original_url' => $this->original_url,
        ];
    }
}
