<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Model\Referensi\BrandBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Referensi\StoreBrandBlock;

class BrandBlockController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:read-setting-beranda', ['only' => ['index']]);
        // $this->middleware('permission:create-setting-beranda|update-setting-beranda', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-setting-beranda', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['title', 'content', 'reorder'];
        $data = BrandBlock::where(function ($query) use ($request, $fields) {
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
        $data = $data->where('brand_id', $request->brand_id)->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreBrandBlock $request)
    {
        $request->validated();
        $idBlock = BrandBlock::where('brand_id', $request->brand_id)->orderBy('id', 'DESC')->pluck('id')->first();
        $id = $request->id ? $request->id : $idBlock + 1;
        $_id = $request->_id ? $request->_id : Str::uuid();
        $data = array_merge($request->except('_id'), ['id' => $id, '_id' => $_id, 'brand_id' => $request->brand_id]);
        $blok = BrandBlock::updateOrCreate($request->only('id', 'brand_id'), $data);

        if ($blok) {
            $status = true;
            $response = $blok->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }


        return response()->json([
            'status' => $status,
            'response' => $response
        ]);
    }

    public function destroy(Request $request)
    {
        $blok = BrandBlock::where('brand_id', $request->brand_id)->where('id', $request->id)->delete();

        if ($blok) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }

    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $blok = BrandBlock::where('brand_id', $request->brand_id)->where('id', $value['id'])->delete();

            if ($blok) {
                $success++;
                $status = true;
            } else {
                $fail++;
                $status = false;
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
        $hide = BrandBlock::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('ishide')->first();
        $value = BrandBlock::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($value) {
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
        $value = BrandBlock::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($value) {
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
        $data = BrandBlock::select('id as value', 'title as text')->where('brand_id', $request->brand_id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
