<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\BrandKategoriProduk;
use Illuminate\Support\Str;
use App\Http\Requests\Referensi\StoreBrandKategoriProduk;
use App\Model\Referensi\BrandImage;

class BrandKategoriProdukController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-kategori-brand', ['only' => ['index']]);
        // $this->middleware('permission:create-kategori-brand|edit-kategori-brand', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-kategori-brand', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['id', 'nama', 'name_en', 'ishide', 'reorder'];
        $data = BrandKategoriProduk::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
            foreach ($fields as $item) {
                $query->orWhere($item, 'LIKE', "%" . $request->search . "%");
            }
        });
        if (!is_null($request->sortBy)) {
            if (count($request->sortBy) > 0) {
                for ($i = 0; $i < count($request->sortBy); $i++) {
                    $data = $data->orderBy($request->sortBy[$i], $request->sortDesc[$i] == 'false' ? 'asc' : 'desc');
                }
            }
        } else {
            $data = $data->orderBy($default->sortBy, $default->sortDesc ? 'desc' : 'asc');
        }
        $data = $data->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreBrandKategoriProduk $request)
    {
        // $request->validated();
        $_id = $request->_id ? $request->_id : Str::uuid();
        $image_id = BrandImage::where('image', $request->cover_image)->pluck('_id')->first();
        $data = array_merge($request->except('_id', 'brand_id'), ['_id' => $_id, 'brand_id' => $request->brand_id, 'image_id' => $image_id]);
        $Kategori = BrandKategoriProduk::updateOrCreate($request->only('id', 'brand_id'), $data);

        if ($Kategori) {
            $status = true;
            $response = $Kategori->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'response' => $response,
        ]);
    }

    public function destroy(Request $request)
    {
        $data = BrandKategoriProduk::where('id', $request->id)->withCount('produk')->first();

        if ($data->produk_count > 0) {
            $status = false;
        } else {
            $Kategori = BrandKategoriProduk::destroy($request->id);
            $status = true;
        }
        return response()->json([
            'status' => $status,
        ]);
    }

    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $data = BrandKategoriProduk::where('id', $value['id'])->withCount('produk')->first();

            if ($data['produk_count'] > 0) {
                $status = false;
                $fail++;
            } else {
                $Kategori = BrandKategoriProduk::destroy($value['id']);
                $status = true;
                $success++;
            }
        }

        return response()->json([
            'status' => $status,
            'success' => $success,
            'fail' => $fail,
        ]);
    }

    public function hide(Request $request)
    {
        $hide = BrandKategoriProduk::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('ishide')->first();
        $bank = BrandKategoriProduk::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'hide' => $hide,
            'status' => $status,
        ]);
    }

    public function reorder(Request $request)
    {
        $bank = BrandKategoriProduk::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    public function ajax(Request $request)
    {
        $data = BrandKategoriProduk::where('brand_id', $request->brand_id)->select('_id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
