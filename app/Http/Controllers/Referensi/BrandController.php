<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreBrand;
use App\Model\Referensi\Brand;
use App\Model\Referensi\BrandBlock;
use App\Model\Transaksi\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-daftar-brand', ['only' => ['index']]);
        // $this->middleware('permission:create-daftar-brand|edit-daftar-brand', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-daftar-brand', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['brand_kategori_id', 'nama_brand', 'nama_brand_en', 'description', 'logo_id', 'ishide', 'url'];
        $data = Brand::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('brandKategori')->withCount('vacancy', 'brandFolder', 'brandImage',  'brandKategoriProduk', 'brandKontak', 'brandProduk', 'brandSlider')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    // public function updateDetailGallery(Request $request)
    // {
    //     $delete = Brand::where('gallery_id', $request->id)->delete();
    //     if (count($request->image) > 0) {
    //         foreach ($request->image as $a) {
    //             $soalTemplateDetail = Brand::create(['gallery_id' => $request->id, 'image_id' => $a]);
    //         }
    //         if ($soalTemplateDetail) {
    //             $status = true;
    //             $response = $soalTemplateDetail->wasRecentlyCreated;
    //         } else {
    //             $status = false;
    //             $response = null;
    //         }
    //     } else if ($delete) {
    //         $status = true;
    //     }
    //     return response()->json([
    //         'status' => $status
    //     ]);
    // }
    public function updateOrCreate(StoreBrand $request)
    {
        $request->validated();
        $block = array('{slider}', '{product}');
        

        if ($request->id == null || $request->id == '') {
            $getId = Brand::orderBy('id', 'DESC')->pluck('id')->first();

            $id = $getId + 1;
        } else {
            $id = $request->id;
        }

        $idBlock = BrandBlock::where('brand_id', $request->_id)->orderBy('id', 'DESC')->pluck('id')->first();
        $image_id = Image::where('image', $request->cover_image)->pluck('id')->first();
        
        $_id = $request->_id ? $request->_id : Str::uuid();
        $merge = array_merge($request->except('logo_id', 'slug'), ['logo_id' => $image_id, '_id' => $_id, 'slug' => Str::slug($request->nama_brand, '-'),]);
        $brand = Brand::updateOrCreate(['id' => $id], $merge);

        foreach ($block as $key => $value) {
            BrandBlock::where('title', $value)->where('brand_id', $_id)->delete();
            BrandBlock::updateOrCreate(['title' => $value, 'brand_id' => $_id], ['id' => $idBlock + ($key + 1),  'brand_id' => $_id, 'title' => $value, 'is_edit' => 1]);
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
        ]);
    }

    public function destroy(Request $request)
    {
        $data = Brand::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $data->delete();
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
            $data = Brand::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $data->delete();
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
        $hide = Brand::where('id', $request->id)->pluck('ishide')->first();
        $check = Brand::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $check = Brand::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Brand::select('_id as value', 'nama_brand as text', 'slug')->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function ajaxId(Request $request)
    {
        $data = Brand::where('id', $request->id)->with('brandKategori')->first();

        return response()->json([
            'data' => $data,
        ]);
    }
    public function ajaxUuid(Request $request)
    {
        $data = Brand::select('nama_brand')->where('_id', $request->id)->first();

        return response()->json([
            'data' => $data,
        ]);
    }
}
