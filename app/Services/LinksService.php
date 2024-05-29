<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Repositories\LinksRepository;
use App\Services\Traits\ShowServiceTrait;
use App\Repositories\LinksLoggerRepository;


class LinksService
{
    use ShowServiceTrait;
    public $links_repository;
    public $links_logger_repository;

    private $generate_try = 0;
    function __construct(LinksRepository $links_repository, LinksLoggerRepository $links_logger_repository)
    {
        $this->links_repository = $links_repository;
        $this->links_logger_repository = $links_logger_repository;
    }

    function isExist($inputs)
    {
        $search_inputs = [
            'where' => [
                'title' => $inputs['title'],
                'link' => $inputs['link'],
                'user_id' => Auth::user()->id
            ]
        ];

        return  $this->links_repository->search($search_inputs);
    }

    function shouldNotExist($inputs)
    {
        $link = $this->isExist($inputs);

        if ($link->count() > 0) {

            throw new Exception(__(
                'link_and_title_already_defined',
                [
                    'link' => $inputs['link'],
                    'title' => $inputs['title'],
                ]
            ), 422);
        }
        return true;
    }


    function generatShortLink()
    {
        $this->generate_try++;
        $short_link = Str::random(5);

        $link = $this->links_repository->search([
            'where' => [
                'shortener_link' => $short_link
            ]
        ]);

        if ($link->count() > 0) {
            if ($this->generate_try > 5) {
                throw new Exception(__('can_not_generate_link'), 500);
            }
            return $this->generatShortLink();
        }

        return $short_link;
    }
    function insert($inputs)
    {
        $this->shouldNotExist($inputs);
        $insert_data = [
            'title' => Str::lower($inputs['title']),
            'link' => $inputs['link'],
            'shortener_link' => $this->generatShortLink(),
            'user_id' => Auth::user()->id
        ];
        $link = $this->links_repository->insert(
            $insert_data
        );
        return $link;
    }

    function log($link)
    {
        $insert_logger_data = [
            'ip' => request()->ip(),
            'link_id' => $link->id
        ];

        try {

            $link_log = $this->links_logger_repository->insert($insert_logger_data);
        } catch (\Throwable $th) {
            //throw $th;
            //log if error 
            //do not stop the process
        }

        return true;
    }



    function viewLinksReport($index_data)
    {

        $search_data = $this->getDefaultSearchParams($index_data);
        $date_clause_columns = $this->getDefaultDateParams($index_data);

        $search_data["count_relations"] = [
            "logs",
            "user"
        ];
        $search_data["relations"] = [
            "user"
        ];

        $where_clause_columns = [
            "id",
            "user_id"
        ];

        $like_clause_columns = [
            "link",
            "shortner_link"
        ];


        $index_data['date_field'] = $index_data['date_field'] ?? "created_at";
        $index_data['date_field'] = in_array($index_data['date_field'], ['created_at', 'updated_at']) ? $index_data['date_field'] : "created_at";
        $where_clause = array_filter(arrayOnly($index_data, $where_clause_columns));
        $date_clause = array_filter(arrayOnly($index_data, $date_clause_columns));
        $like_clause = array_filter(arrayOnly($index_data, $like_clause_columns));
        $search_data['where'] = $where_clause;
        $search_data['date'] = $date_clause;
        $search_data['like'] = $like_clause;

        $links = $this->links_repository->index($search_data);

        return $links;
    }
}
