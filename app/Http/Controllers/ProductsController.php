<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller {

    /**
     * Show all products
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, HttpStatusCodes::SUCCESS_OK);
    }

    /**
     * View product (multiple can be requested with comma's)
     *
     * @param string
     *
     * @return JsonResponse
     */
    public function show($id) {
        $ids = array_map('intval', explode(',', $id));

        return response()->json(
            Product::findOrFail($ids),
            HttpStatusCodes::SUCCESS_OK
        );
    }

    public function create($id) {
        //
    }

    public function update($id) {
        //
    }

    /**
     * Delete product
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function delete(Request $request, $id) {
        // TODO: add access_level system

        if (!$request->user->active) {
            return response()->json(
                'access_denied',
                HttpStatusCodes::CLIENT_ERROR_UNAUTHORIZED
            );
        }

        Product::findOrFail($id)->delete();

        return response()->json(
            null,
            HttpStatusCodes::SUCCESS_NO_CONTENT
        );
    }
}