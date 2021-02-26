<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreInventaris;
use App\Model\Referensi\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-inventaris', ['only' => ['index']]);
        $this->middleware('permission:create-inventaris|update-inventaris', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-inventaris', ['only' => ['destroy', 'destroys']]);
    }
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields =
            ['id', 'nama', 'jumlah', 'satuan', 'kondisi', 'jenis_inventaris_id', 'keterangan'];
        $data = Inventaris::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreInventaris $request)
    {
        $request->validated();

        $val = Inventaris::updateOrCreate($request->only('id'), $request->all());

        if ($val) {
            $status = true;
            $response = $val->wasRecentlyCreated;
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
        $data = Inventaris::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $val = Inventaris::destroy($request->id);
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
            $data = Inventaris::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $val = Inventaris::destroy($value['id']);
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
        $hide = Inventaris::where('id', $request->id)->pluck('ishide')->first();
        $bank = Inventaris::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Inventaris::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($bank) {
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
        $data = Inventaris::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
