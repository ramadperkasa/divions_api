<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\BrandProduk;
use App\Model\Referensi\BrandProdukDetail;
use App\Http\Requests\Referensi\StoreBrandProduk;
use App\Model\Referensi\BrandImage;
use Illuminate\Support\Str;

class BrandProdukController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-daftar-brand', ['only' => ['index']]);
        // $this->middleware('permission:create-daftar-brand|edit-daftar-brand', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-daftar-brand', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => true];
        $fields = ['nama', 'deskripsi', 'harga', 'ishide'];
        $data = BrandProduk::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
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
        $data = $data->with('brandKategoriProduk', 'brandProdukDetail')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreBrandProduk $request)
    {
        $request->validated();

        if ($request->_id) {
            BrandProdukDetail::where('brand_id',$request->brand_id)->where('brand_product_id_key', $request->id)->delete();
        }

        if ($request->id == null || $request->id == '') {
            $getId = BrandProduk::orderBy('id', 'DESC')->pluck('id')->first();

            $id = $getId + 1;
        } else {
            $id = $request->id;
        }
        $image_id = BrandImage::where('image', $request->cover_image)->pluck('_id')->first();
        if ($request->duplicate) {
            $_id = Str::uuid();
        } else {
            $_id = $request->_id ? $request->_id : Str::uuid();
        }
        $merge = array_merge(['id' => $id, 'image_id' => $image_id, '_id' => $_id, 'slug' => Str::slug($request->nama, '-')], $request->except('logo_id', '_id', 'id'));
        
        $brand = BrandProduk::updateOrCreate(['id' => $id, 'brand_id' => $request->brand_id], $merge);
        if ($request->duplicate) {
            foreach ($request->brand_produk_detail as $key => $value) {
                $mergeComp = array_merge($value, ['id' => $key + 1, '_id' => Str::uuid(), 'brand_product_id' => $_id, 'brand_id' => $request->brand_id, 'brand_product_id_key' => $id]);
                // dd($_id);
                BrandProdukDetail::insert($mergeComp);
            }
        } else {
            foreach ($request->detail as $key => $value) {
                $mergeComp = array_merge(['id' => $key + 1, '_id' => Str::uuid(), 'brand_product_id' => $_id, 'brand_id' => $request->brand_id, 'brand_product_id_key' => $id], $value);
                BrandProdukDetail::insert($mergeComp);
            }
        }


        if ($brand) {
            $status = true;
            $response = $brand->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'response' => $response,
            'response2' => $image_id,
        ]);
    }

    public function destroy(Request $request)
    {
        $brand_id = (string) $request->brand_id;
        // dd(BrandProdukDetail::where('brand_product_id_key',$request->id)->where('brand_id',$request->brand_id)->first());
        $dd = ["id" => $request->id, "brand_id" => $brand_id];

        //$data = BrandProduk::where('id', $request->id)->where('brand_id', $brand_id)->first();
        $data = BrandProduk::where($dd)->first();

        if ($data) {
            $data->delete();
            BrandProdukDetail::where('brand_product_id_key', $request->id)->where('brand_id', $request->brand_id)->delete();
            $status = true;
        } else {
            $status = false;
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
            $data = BrandProduk::where('id', $value['id'])->first();

            if ($data) {
                BrandProduk::destroy($value['id']);
                BrandProdukDetail::where('brand_product_id_key', $value['id'])->where('brand_id', (string)$value['brand_id'])->delete();
                $status = true;
                $success++;
            } else {
                $status = false;
                $fail++;
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
        $hide = BrandProduk::where('id', $request->id)->pluck('ishide')->first();
        $check = BrandProduk::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($check) {
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
        $check = BrandProduk::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($check) {
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
        $data = BrandProduk::select('id as value', 'nama as text')->with('brandProdukDetail')->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function ajaxId(Request $request)
    {
        $data = BrandProduk::where('id', $request->id)->with('brandProdukDetail')->first();

        return response()->json([
            'data' => $data,
        ]);
    }
}
