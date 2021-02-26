<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreWarna;
use App\Model\Referensi\Warna;
use Illuminate\Http\Request;

class WarnaController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:read-kontak', ['only' => ['index']]);
        // $this->middleware('permission:create-kontak|update-kontak', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        // $this->middleware('permission:delete-kontak', ['only' => ['destroy',  'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama', 'nama_singkat', 'code_hex', 'code_rgb'];
        $data = Warna::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreWarna $request)
    {
        $request->validated();

        $warna = Warna::updateOrCreate($request->only('id'), $request->all());

        if ($warna) {
            $status = true;
            $response = $warna->wasRecentlyCreated;
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
        $data = Warna::destroy($request->id);

        if (!$data) {
            $status = false;
        } else {
            $status = true;
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
            $data = Warna::destroy($value['id']);

            if (!$data) {
                $status = false;
                $fail++;
            } else {
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
        $hide = Warna::where('id', $request->id)->pluck('ishide')->first();
        $warna = Warna::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($warna) {
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
        $warna = Warna::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($warna) {
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
        $data = Warna::select('id as value', 'nama as text', 'nama_singkat', 'code_hex')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
