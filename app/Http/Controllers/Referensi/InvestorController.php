<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreInvestor;
use App\Model\Referensi\Investor;
use App\Model\Transaksi\Image;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-investor', ['only' => ['index']]);
        // $this->middleware('permission:create-investor|update-investor', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        // $this->middleware('permission:delete-investor', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['investor_name', 'image_id', 'url', 'reorder'];
        $data = Investor::where(function ($query) use ($request, $fields) {
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

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreInvestor $request)
    {
        $image_id = Image::where('image', $request->cover_image)->pluck('id')->first();
        $merge = array_merge($request->except('image'), ['image_id' => $image_id]);
        $mitra = Investor::updateOrCreate($request->only('id'), $merge);

        if ($mitra) {
            $status = true;
            $response = $mitra->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'response' => $response,
        ]);
    }

    public function destroy(Request $request)
    {
        $mitra = Investor::destroy($request->id);

        if ($mitra) {
            $status = true;
        } else {
            $status = false;
        }
        return response()->json([
            'status' => $status,
        ]);
    }

    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $data = Investor::destroy($value['id']);
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
            'fail' => $fail,
        ]);
    }

    public function hide(Request $request)
    {
        $hide = Investor::where('id', $request->id)->pluck('ishide')->first();
        $bank = Investor::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Investor::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
        ]);
    }
}
