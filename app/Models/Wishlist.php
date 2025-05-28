<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Wishlist",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="product_id", type="integer", example=2),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-28T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-28T12:34:56Z"),
 *     @OA\Property(property="product", ref="#/components/schemas/Product")
 * )
 */
class Wishlist extends Model
{  
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var list<string>
    */
   protected $fillable = [
       'user_id',
       'product_id'
   ];

   /**
    * Define the relationship with the Product model.
    */
   public function product()
   {
       return $this->belongsTo(Product::class, 'product_id');
   }
}
