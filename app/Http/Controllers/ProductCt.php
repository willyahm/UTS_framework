<?php

namespace App\Http\Controllers;

use App\Models\Product; //Memanggil Product
use App\Models\Category; //Memanggil Category

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ProductCt extends Controller
{
    protected function getEmailFromToken($token)
    {
        $decoded = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'));
        return $decoded->email;
    }
    public function create(Request $request)
    {

        // Melakukan validasi inputan
        $validator = validator::make($request->all(), [
            'name'        => 'required|max:50',
            'description' => 'required|max:500',
            'price'       => 'required|numeric',
            'image'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'expired_at'  => 'required|date',
            'category_id' => 'required'
        ]);

        // Kondisi Inputan Salah
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }


        // Inputan Yang sudah Benar
        $validated = $validator->validated();

        // validasi jika Produk sudah ada
        if (Product::where('name', $validated['name'])->exists()) {
            return response()->json(['msg' => 'Produk Sudah ada']);
        }

        // validasi jika categorytidak ada
        $category = Category::where('name', $validated['category_id'])->first();
        if (!$category) {
            return response()->json(['msg' => 'Category tidak ditemukan'], 404);
        }

        $image    = $request->file('image');
        $fileName = now()->timestamp . '_' . $image->getClientOriginalName();
        $image->move('Uploads/', $fileName);

        $email = $this->getEmailFromToken($request->bearerToken());

        // input ke tabel_products
        $product = Product::create(
            [
                'name'        => $validated['name'],
                'description' => $validated['description'],
                'price'       => $validated['price'],
                'image'       => 'Uploads/' . $fileName,
                'category_id' => $category->id,
                'expired_at'  => $validated['expired_at'],
                'modified_by' => $email  //$request->user_email
            ]
        );
        return response()->json(["data" => ['msg' => 'Data produk berhasil ditambahkan', 'data' => $product]], 201);
    }

    public function show()
    {
        $products = Product::all();

        if ($products->count() <= 0) {
            return response()->json(['msg' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json([
            "data" => [
                'msg'  => "{$products->count()} produk ditemukan",
                'data' => $products
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['msg' => 'Produk tidak ditemukan'], 404);
        }

        $validator = validator::make($request->all(), [
            'name'        => 'required|max:50',
            'description' => 'required|max:500',
            'price'       => 'required|numeric',
            'image'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'expired_at'  => 'required|date',
            'category_id' => 'required'
        ]);

        // Kondisi Inputan Salah
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }


        // Inputan Yang sudah Benar
        $validated = $validator->validated();

        // validasi jika Produk sudah ada
        if (
            Product::where('name', $validated['name'])
                ->where('id', '!=', $id)
                ->exists()
        ) {
            return response()->json(['msg' => 'Produk Sudah ada']);
        }

        // validasi jika categorytidak ada
        $category = Category::where('name', $validated['category_id'])->first();
        if (!$category) {
            return response()->json(['msg' => 'Category tidak ditemukan'], 404);
        }

        $image    = $request->file('image');
        $fileName = now()->timestamp . '_' . $image->getClientOriginalName();
        $image->move('Uploads/', $fileName);


        $email = $this->getEmailFromToken($request->bearerToken());

        // input ke tabel_products
        $product->update(
            [
                'name'        => $validated['name'],
                'description' => $validated['description'],
                'price'       => $validated['price'],
                'image'       => 'Uploads/' . $fileName,
                'category_id' => $category->id,
                'expired_at'  => $validated['expired_at'],
                'modified_by' => $email //$request->user_email 
            ]
        );
        return response()->json(["data" => ['msg' => 'Data produk berhasil diedit', 'data' => $product]], 201);
    }
    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['messages' => 'Data Produk tidak ditemukan'])->setStatusCode(404);
        }
        $product->delete();
        return response()->json(['messages' => "Produk dengan id {$id} Data Berhasil Dihapus"], 200);
    }
}