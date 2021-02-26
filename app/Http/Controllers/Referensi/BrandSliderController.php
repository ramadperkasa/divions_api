<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreBrandSlider;
use Illuminate\Http\Request;
use App\Model\Referensi\BrandSlider;
use App\Model\Referensi\BrandImage;
use Illuminate\Support\Str;


class BrandSliderController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:read-slider', ['only' => ['index']]);
    //     $this->middleware('permission:create-slider|update-slider', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
    //     $this->middleware('permission:delete-slider', ['only' => ['destroy', 'destroys']]);
    // }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['title', 'title_sub', 'description', 'title_en', 'title_sub_en', 'description_en', 'image_id', 'url', 'target', 'style', 'reorder', 'tipe_link'];
        $data = BrandSlider::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
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
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreBrandSlider $request)
    {
        $_id = $request->_id ? $request->_id : Str::uuid();
        $image_id = BrandImage::where('image', $request->image_url)->pluck('_id')->first();
        $merge = array_merge($request->except('duplicate', 'created_at', 'updated_at'), ['image_id' => $image_id, '_id' => $_id, 'brand_id' => $request->brand_id]);
        $data = BrandSlider::updateOrCreate($request->only('id', 'brand_id'), $merge);
        if ($data) {
            $status = true;
            $response = $data->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => true,
            'response' => $response,
        ]);
    }

    public function destroy(Request $request)
    {
        BrandSlider::destroy($request->id);

        return response()->json([
            'status' => true
        ]);
    }
    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $data = BrandSlider::where('id', $value['id'])->first();

            if ($data) {
                $Slider = BrandSlider::destroy($value['id']);
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
            'fail' => $fail
        ]);
    }

    public function hide(Request $request)
    {
        $hide = BrandSlider::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('ishide')->first();
        $Check = BrandSlider::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($Check) {
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
        $Check = BrandSlider::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($Check) {
            $status = true;
        } else {
            $status = false;
        }


        return response()->json([
            'status' => $status
        ]);
    }

    public function ajax(Request $request)
    {
        $data = BrandSlider::where('brand_id', $request->brand_id)->select('id as value', 'title as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function ajaxId(Request $request)
    {
        $data = BrandSlider::where('brand_id', $request->brand_id)->where('id', $request->id)->first();

        return response()->json([
            'data' => $data
        ]);
    }
}
