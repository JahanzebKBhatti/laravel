<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreProduct;
use App\Models\Section;
use App\store_products;
use Illuminate\Session\Store;

class ProductsController extends Controller
{
    public $storeId;

    protected $perPage = 5;

    protected $page = 1;

    protected $sort         = 'az';
    protected $sort_field   = 'id';
    protected $sort_order   = 'asc';

    protected $country      = 'GB';

    public function __construct(Request $request)
    {
        /* As the system manages multiple stores a storeBuilder instance would
        normally be passed here with a store object. The id of the example
        store is being set here for the purpose of the test */
        $this->storeId = 3;

        $this->__setPerPage($request);
        $this->__setPageNumber($request);
        $this->__setCountry($request);
        $this->__setSort($request);
    }

    public function index() {
        $products = StoreProduct::with('sections');

        return $this->__returnResponse($products, "/products");
    }

    public function getSectionProductions($section) {
        $endpoint   = $section;

        if (ctype_digit($section)) {
            $section    = (int) $section;
            $endpoint   = $section;
        } else {
            $section = Section::where('description', $section)->get()->first();
            if(!empty($section)) {
                $section    = $section->id;
            }
        }

        $products = StoreProduct::with('sections', 'artist')->whereHas('sections', function ($q) use ($section) {
            $q->where('section_id', '=', $section);
        });

        return $this->__returnResponse($products, "/products/$endpoint");
    }

    public function getOlder () {
        $store_products = new store_products();

        return response()->json([
            "data"      => $store_products->sectionProducts(3, 8414, 2, 1, 'az'),
        ], 200);
    }

    protected function __setSort (Request $request) {
        if($request->has('sort')) {
            switch ($request->get('sort')) {
                case 'az':
                case 'za':
                    $this->sort_field = 'name';
                break;

                case 'low':
                case 'high':
                    $this->sort_field = 'price';
                break;

                case 'old':
                case 'new':
                    $this->sort_field = 'release_date';
                break;
            }

            switch ($request->get('sort')) {
                case 'za':
                case 'high':
                case 'new':
                    $this->sort_order = 'desc';
                break;
            }
        }
    }

    protected function __setPageNumber (Request $request) {
        if($request->has('page')) {
            if (ctype_digit($request->get('page'))) {
                $this->page = $request->get('page');
            }
        }
    }

    protected function __setPerPage (Request $request) {
        if($request->has('perpage')) {
            if (ctype_digit($request->get('perpage'))) {
                $this->perPage = $request->get('perpage');
            }
        }
    }

    protected function __setCountry (Request $request) {
        if($request->has('country')) {
            $this->country = $request->get('country');
        }
    }

    protected function __returnResponse ($query, $endpoint) {

        // Get Current Store
        $query          = $query
                                ->where('store_id', '=', $this->storeId);
        // Geo Gating
        $query          = $query
                                ->where('disabled_countries', 'NOT LIKE', $this->country);

        // Count products
        $products_total = $query->count();

        // Pagination
        $query          = $query
                                ->skip((($this->perPage) * ($this->page - 1)))
                                ->take($this->perPage);

        // Sorting
        $query          = $query
                                ->orderBy($this->sort_field, $this->sort_order);


        // Get Results
        // $products_list  = $query->get(['id', 'name', 'description', 'price', 'type', 'release_date']);
        $products_list  = $query->get();

        return response()->json([
            "data"      => $products_list,
            "count"     => $products_total,
            "endpoint"  => "$endpoint",
            "perpage"   => (int) $this->perPage,
            "page"      => (int) $this->page,
        ], 200);

    }
}
