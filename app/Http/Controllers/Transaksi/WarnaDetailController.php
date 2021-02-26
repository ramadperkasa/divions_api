<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\StoreWarnaDetail;
use App\Model\Transaksi\WarnaDetail;
use Illuminate\Http\Request;

class WarnaDetailController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-daftar-brand', ['only' => ['index']]);
        // $this->middleware('permission:create-daftar-brand|edit-daftar-brand', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-daftar-brand', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => true];
        $fields = ['warna_id', 'produk_id', 'brand_id', 'image_id'];
        $data = WarnaDetail::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
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
        $data = $data->with('warna', 'brandProduk', 'brand', 'image')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreWarnaDetail $request)
    {

    }

    public function destroy(Request $request)
    {
      $data =  WarnaDetail::where('id', $request->id)->where('produk_id', $request->produk_id)->where('brand_id', $request->brand_id)->delete();

        if ($data) {
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
            $data = WarnaDetail::where('id', $value['id'])->where('produk_id', $value['produk_id'])->where('brand_id', (string)$value['brand_id'])->delete();
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
        $hide = WarnaDetail::where('id', $request->id)->pluck('ishide')->first();
        $check = WarnaDetail::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($check) {
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
        $check = WarnaDetail::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($check) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
        ]);
    }   
}
