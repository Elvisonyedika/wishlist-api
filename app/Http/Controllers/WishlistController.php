<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *     title="Wishlist API",
 *     version="1.0.0",
 *     description="API for managing user wishlists"
 * )
 */
class WishlistController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/wishlist",
     *     summary="Add a product to the wishlist",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID of the product to add")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product added to wishlist",
     *         @OA\JsonContent(ref="#/components/schemas/Wishlist")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $wishlist = Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);

            return response()->json($wishlist, 201);
        }catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while adding the product to the wishlist.'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/wishlist",
     *     summary="Remove a product from the wishlist",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID of the product to remove")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from wishlist"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in wishlist"
     *     )
     * )
     */
    public function remove(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $wishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                return response()->json(['message' => 'Product removed from wishlist'], 200);
            }

            return response()->json(['message' => 'Product not found in wishlist'], 404);
        }
    catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while removing the product from the wishlist.'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/wishlist/clear",
     *     summary="Clear the wishlist",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wishlist cleared"
     *     )
     * )
     */
    public function clear()
    {
        try {
            Wishlist::where('user_id', Auth::id())->delete();
            return response()->json(['message' => 'Wishlist cleared'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while clearing the wishlist.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/wishlist",
     *     summary="View the wishlist",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of wishlist items",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Wishlist"))
     *     )
     * )
     */
    public function view()
    {
        try {
            $wishlists = Wishlist::where('user_id', Auth::id())
                ->with('product') // Assuming you have a relationship defined in the Wishlist model
                ->get();

            if ($wishlists->isEmpty()) {
                return response()->json(['message' => 'No items found in the wishlist'], 404);
            }

            return response()->json($wishlists, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the wishlist.'], 500);
        }
    }
}