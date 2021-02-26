<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\BrandFolder;
use Illuminate\Support\Str;
use App\Http\Requests\Referensi\StoreBrandFolder;

class BrandFolderController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:read-uploads-folder', ['only' => ['index']]);
    //     $this->middleware('permission:create-uploads-folder|update-uploads-folder', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
    //     $this->middleware('permission:delete-uploads-folder', ['only' => ['destroy', 'destroys']]);
    // }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama_folder', 'reorder'];
        $data = BrandFolder::where('brand_id', $request->brand_id)->where('isedit', '!=', 1)->where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreBrandFolder $request)
    {
        // $request->validated();
        $_id = $request->_id ? $request->_id : Str::uuid();
        $data = array_merge($request->except('_id', 'brand_id'), ['_id' => $_id, 'brand_id' => $request->brand_id]);
        $folder = BrandFolder::where('brand_id', $request->brand_id)->updateOrCreate($request->only('id', 'brand_id'), $data);

        if ($folder) {
            $status = true;
            $response = $folder->wasRecentlyCreated;
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

        $status = false;
        $brands = BrandFolder::where('brand_id', $request->brand_id)->withCount('brandImage')->find($request->id);

        if ($brands->brand_image_count == 0) {
            $brands->delete();
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
            $data = BrandFolder::where('brand_id', $request->brand_id)->where('id', $value['id'])->withCount('brandImage')->first();

            if ($data['brand_image_count'] > 0) {
                $status = false;
                $fail++;
            } else {
                $Folder = BrandFolder::destroy($value['id']);
                $status = true;
                $success++;
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
        $hide = BrandFolder::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('ishide')->first();
        $folder = BrandFolder::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($folder) {
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
        $folder = BrandFolder::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($folder) {
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
        $data = BrandFolder::where('brand_id', $request->brand_id)->select('_id as value', 'nama_folder as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
