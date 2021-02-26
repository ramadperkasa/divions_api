<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreMitra;
use App\Model\Referensi\Mitra;
use App\Model\Transaksi\Image;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-mitra', ['only' => ['index']]);
        $this->middleware('permission:create-mitra|update-mitra', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        $this->middleware('permission:delete-mitra', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['image_id', 'nama', 'url', 'reorder'];
        $data = Mitra::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreMitra $request)
    {
        $request->validated();

        $image_id = Image::where('image', $request->image)->pluck('id')->first();
        $merge = array_merge($request->except('image', 'type_img'), ['image_id' => $image_id]);
        $mitra = Mitra::updateOrCreate($request->only('id'), $merge);

        if ($mitra) {
            $status = true;
            $response = $mitra->wasRecentlyCreated;
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
        $mitra = Mitra::destroy($request->id);

        if ($mitra) {
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
            $data = Mitra::destroy($value['id']);
            if ($data) {
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
        $hide = Mitra::where('id', $request->id)->pluck('ishide')->first();
        $bank = Mitra::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Mitra::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }


        return response()->json([
            'status' => $status
        ]);
    }
}
