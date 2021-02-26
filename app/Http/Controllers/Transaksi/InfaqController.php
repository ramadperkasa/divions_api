<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\StoreInfaq;
use App\Model\Transaksi\Infaq;
use Illuminate\Http\Request;

class InfaqController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-infaq', ['only' => ['index']]);
        $this->middleware('permission:create-infaq|update-infaq', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-infaq', ['only' => ['destroy', 'destroys']]);
    }
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields =
            ['id', 'tgl', 'nama', 'ket', 'debit', 'credit', 'type_id', 'rekening_id', 'ishide'];
        $data = Infaq::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreInfaq $request)
    {
        $request->validated();

        $val = Infaq::updateOrCreate($request->only('id'), $request->all());

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
        $data = Infaq::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $val = Infaq::destroy($request->id);
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
            $data = Infaq::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $val = Infaq::destroy($value['id']);
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
        $hide = Infaq::where('id', $request->id)->pluck('ishide')->first();
        $bank = Infaq::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Infaq::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Infaq::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
