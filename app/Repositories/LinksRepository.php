<?php

namespace App\Repositories;

use App\Models\Links;
use App\Repositories\BaseRepository;

class LinksRepository extends BaseRepository
{
    public function setModel()
    {
        return Links::class;
    }
}
