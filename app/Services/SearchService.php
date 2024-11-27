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

    public function searchAll($query,$page)
    {
        return $this->searchRepository->searchAll($query, $page);
    }

    public function searchPostswithphoto($query, $page)
    {
        return $this->searchRepository->searchPostswithphoto($query, $page);
    }

    public function searchPostswithvideo($query, $page)
    {
        return $this->searchRepository->searchPostswithvideo($query, $page);
    }

    public function searchAllPosts($query, $page)
    {
        return $this->searchRepository->searchAllPosts($query, $page);
    }

    public function searchUsers($query, $page)
    {
        return $this->searchRepository->searchUsers($query, $page);
    }

    public function searchCategory($category_id, $page)
    {
        return $this->searchRepository->searchCategory($category_id, $page);
    }


}
