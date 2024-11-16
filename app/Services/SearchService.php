<?php

namespace App\Services;
use App\Repositories\SearchRepository;

class SearchService
{
    protected $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function searchAll($query)
    {
        return $this->searchRepository->searchAll($query);
    }
}
