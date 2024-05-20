<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Mockery\Generator\StringManipulation\Pass\RemoveDestructorPass;

class CategoryCT extends Controller
{
    public function store(Request $request)
    {

        $validator = validator::make($request->all(), ['name' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->massages(), 400);
        }
        $validated = $validator->validated();

        if (Category::where('name', $validated['name'])->exists()) {
            return response()->json(['msg' => 'Category Sudah ada'], 400);
        }
        $category = Category::create(['name' => $validated['name']]);

        return response()->json(["data" => ['msg' => 'Category berhasil dibuat', 'data' => $category]]);
    }

    public function show()
    {
        $categories = Category::all();

        if ($categories->count() <= 0) {
            return response()->json(['msg' => 'Category tidak ditemukan'], 404);
        }
        return response()->json(["data" => ['msg' => 'Category Ditemukan', 'data' => $categories]], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['msg' => 'Category tidak ditemukan'], 404);
        }

        // Melakukan validasi inputan
        $validator = validator::make($request->all(), [
            'name' => 'required|max:50'
        ]);

        // Kondisi Inputan Salah
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // Inputan Yang sudah Benar
        $validated = $validator->validated();

        if (Category::where('name', $validated['name'])->where('id', '!=', $id)->exists()) {
            return response()->json(['msg' => 'Category Sudah ada']);
        }

        Category::where('id', $id)
            ->update([
                'name' => $validated['name'],
            ]);

        return response()->json(['msg' => 'Category Berhasil disunting'], 201);

    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['msg' => 'Category tidak ditemukan'], 404);
        }
        $category->delete();
        return response()->json(['msg' => 'Category Berhasil hapus'], 201);
    }
}
