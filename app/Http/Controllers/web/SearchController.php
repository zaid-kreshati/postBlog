<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Traits\JsonResponseTrait;

class SearchController extends Controller
{
    use JsonResponseTrait;
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function searchAll(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchAll($query);
        //return $resaults;
        return $this->successResponse($resaults, 'Search results fetched successfully');
    }
}
