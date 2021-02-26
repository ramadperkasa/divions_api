<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Referensi\Quotes;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Referensi\StoreQuotes;
use App\Model\Transaksi\Image;

class QuotesController extends Controller
{

    function __construct()
    {
        // $this->middleware('permission:read-quotes', ['only' => ['index']]);
        // $this->middleware('permission:create-quotes|update-quotes', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-quotes', ['only' => ['destroy', 'destroys']]);
    }
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['deskripsi', 'image_id', 'oleh', 'jabatan', 'reorder'];
        $data = Quotes::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreQuotes $request)
    {
        $request->validated();
        $image_id = Image::where('image', $request->foto)->pluck('id')->first();
        $merge = array_merge($request->except('foto'), ['image_id' => $image_id]);
        $Quotes = Quotes::updateOrCreate($request->only('id'), $merge);

        if ($Quotes) {
            $status = true;
            $response = $Quotes->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'status1' => $merge,
            'response' => $response
        ]);
    }

    public function destroy(Request $request)
    {
        $data = Quotes::destroy($request->id);

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
            $data = Quotes::destroy($value['id']);

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
        // $hide = Quotes::where('id', $request->id)->pluck('ishide')->first();
        // $switch = Quotes::where('ishide', 0)->update(['ishide' => 1]);
        // $tahunajaran = Quotes::where('id', $request->id)->update(['ishide' => 0]);

        // if ($tahunajaran) {
        //     $status = true;
        // } else {
        //     $status = false;
        // }

        // return response()->json([
        //     'status' => $status,
        // ]);

        $hide = Quotes::where('id', $request->id)->pluck('ishide')->first();
        $bank = Quotes::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Quotes::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Quotes::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
