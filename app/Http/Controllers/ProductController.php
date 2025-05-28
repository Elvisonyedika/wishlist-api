<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Fetch all products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        try {
            $products = Product::all();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found'], 404);
            }

            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching products.'], 500);
        }
    }
}
