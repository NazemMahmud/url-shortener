<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URLShortener extends Model
{
    use HasFactory;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = "short_urls";


    /**
     * @var string[]
     */
    protected $fillable = [
        'original_url',
        'url_hash'
    ];
}
