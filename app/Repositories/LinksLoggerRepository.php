<?php

namespace App\Repositories;

use App\Models\LinksLogger;
use App\Repositories\BaseRepository;

class LinksLoggerRepository extends BaseRepository
{
    public function setModel()
    {
        return LinksLogger::class;
    }
}
