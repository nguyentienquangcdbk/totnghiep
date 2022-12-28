<?php

namespace App\Http\Controllers;

use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\PropertyProduct;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator as PaginationPaginator;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd($request->size);
        if ($request->color || $request->size) {
            if ($request->color && $request->size) {

                $arrFilter = array_merge($request->color, $request->size);
            } else {
                if ($request->color) {
                    $arrFilter = $request->color;
                } else {

                    $arrFilter = $request->size;
                    // dd($arrFilter);
                }
            }
            if (is_array($arrFilter)) {

                $product = Product::whereHas('property', function ($query) use ($arrFilter) {
                    $query->whereIn('value', $arrFilter);
                })->get();
                // dd($product);
                return $product;
            } else {
                $product = Product::has('property.value', '=', $arrFilter)->get();
            }
        } else {

            $product = product::all();
        }
        if ($request->category) {
            $product = $product->whereIn('categoryName', $request->category);
        }


        $product = $this->paginate($product, 10);


        // return new ProductCollection($product);
        return $product;;
    }
    public function fliter(Request $request)
    {
        $product = DB::table('products')->inRandomOrder()->limit(20)->get();

        return $product;
    }

    public static function paginate(Collection $results, $pageSize)
    {
        $page = PaginationPaginator::resolveCurrentPage('page');

        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }


    public function getId($id)
    {
        $size = PropertyProduct::where('productId', $id);

        $size = $size->where('key', '=', 'size')->get();

        $color = PropertyProduct::where('productId', $id);
        $color = $color->where('key', '=', 'color')->get();
        $img = product::find($id)->images;

        $product =  product::find($id);
        return [
            "color" => $color,
            'size' => $size,
            'img' => $img,
            "product" => $product,
        ];
    }
    public function add(Request $request)
    {
        $product = new product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->avatar = $request->avatar;
        $product->price = $request->price;
        $product->categoryName = $request->categoryName;

        $product->save();

        if (!empty($request->color)) {
            $color = $request->color;
            if (is_array($color)) {
                foreach ($request->color as $itemColor) {

                    $product->property()->create([
                        'key' => 'color',
                        'value' => $itemColor
                    ]);
                }
            } else {
                $product->property()->create([
                    'key' => 'color',
                    'value' => $color
                ]);
            }
        }


        if (!empty($request->size)) {
            $size = $request->size;
            if (is_array($size)) {

                foreach ($request->size as $itemSize) {

                    $product->property()->create([
                        'key' => 'size',
                        'value' => $itemSize
                    ]);
                }
            } else {
                $product->property()->create([
                    'key' => 'size',
                    'value' => $size
                ]);
            }
        }

        if (!empty($request->img)) {
            $img = $request->img;
            if (is_array($img)) {

                foreach ($img as $item) {

                    $product->Images()->create([
                        'name' => $item,
                        'path' => $item
                    ]);
                }
            } else {
                $product->Images()->create([
                    'name' => $img,
                    'path' => $img
                ]);
            }
        }
        return $product;
    }

    public function edit(Request $request, $id)
    {

        $product = product::find($id);

        if ($request->name) {

            $product->name = $request->name;
        }
        if ($request->description) {

            $product->description = $request->description;
        }
        if ($request->price) {

            $product->price = $request->price;
        }
        if ($request->categoryName) {

            $product->categoryName = $request->categoryName;
        }
        if ($request->avatar) {

            $product->avatar = $request->avatar;
        }

        $product->save();


        if (!empty($request->img)) {
            $img = $request->img;
            if (is_array($img)) {

                foreach ($img as $item) {

                    $product->Images()->create([
                        'name' => $item,
                        'path' => $item
                    ]);
                }
            } else {
                $product->Images()->create([
                    'name' => $img,
                    'path' => $img
                ]);
            }
        }

        if ($request->color) {
            $product->property()->where('key', 'color')->delete();
            $color = $request->color;
            if (is_array($color)) {

                foreach ($request->color as $itemColor) {

                    $product->property()->create([
                        'key' => 'color',
                        'value' => $itemColor
                    ]);
                }
            } else {
                $product->property()->create([
                    'key' => 'color',
                    'value' => $color
                ]);
            }
        }
        if (!empty($request->size)) {
            $product->property()->where('key', 'size')->delete();
            $size = $request->size;
            if (is_array($size)) {

                foreach ($request->size as $itemSize) {

                    $product->property()->create([
                        'key' => 'size',
                        'value' => $itemSize
                    ]);
                }
            } else {
                $product->property()->create([
                    'key' => 'size',
                    'value' => $size
                ]);
            }
        }

        return $product;
    }
    public function delete($id)
    {
        $deleteImg = ImageProduct::where('productId', '=', $id)->get();

        // dd($deleteImg);
        if (!empty($deleteImg)) {

            foreach ($deleteImg as $item) {
                // dd($item->path);
                File::delete(public_path($item->path));
                ImageProduct::destroy($item->id);
            }
        }

        $deleteproperty = PropertyProduct::where('productId', '=', $id)->delete();

        return product::destroy($id);
    }
}
