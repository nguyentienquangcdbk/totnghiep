<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    //
    public function index()
    {
        return Category::all();
    }

    public function getId($id)
    {

        return Category::find($id);
    }
    public function add(Request $request)
    {
        $category = new Category();

        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');

        $category->save();

        return $category;
    }

    public function edit(Request $request, $id)
    {
        // dd($request->name);
        $category = Category::find($id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-')
        ]);

        return $category;
    }
    public function delete($id)
    {

        return Category::destroy($id);
    }
}
