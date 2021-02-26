<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreImam;
use App\Model\Referensi\Imam;
use Illuminate\Http\Request;

class ImamController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-bank', ['only' => ['index']]);
        $this->middleware('permission:create-bank|update-bank', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-bank', ['only' => ['destroy', 'destroys']]);
    }
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields =
            ['id', 'nama'];
        $data = Imam::where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('agendaDetail')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreImam $request)
    {
        $request->validated();

        $val = Imam::updateOrCreate($request->only('id'), $request->all());

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
        $data = Imam::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $val = Imam::destroy($request->id);
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
            $data = Imam::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $val = Imam::destroy($value['id']);
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
        $hide = Imam::where('id', $request->id)->pluck('ishide')->first();
        $val = Imam::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($val) {
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
        $val = Imam::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($val) {
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
        $data = Imam::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
