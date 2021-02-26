<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreBlok;
use App\Model\Referensi\Blok;

class BlokController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-setting-beranda', ['only' => ['index']]);
        $this->middleware('permission:create-setting-beranda|update-setting-beranda', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-setting-beranda', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['title', 'content', 'reorder'];
        $data = Blok::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreBlok $request)
    {
        $request->validated();

        $blok = Blok::updateOrCreate($request->only('id'), $request->all());

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
        $blok = Blok::destroy($request->id);

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
            $blok = Blok::destroy($value['id']);

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
        $hide = Blok::where('id', $request->id)->pluck('ishide')->first();
        $value = Blok::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $value = Blok::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Blok::select('id as value', 'title as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
