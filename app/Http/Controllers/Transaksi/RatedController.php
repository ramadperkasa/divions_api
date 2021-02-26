<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaksi\Rated;
use App\Http\Requests\Transaksi\StoreRated;

class RatedController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-gallery-kategori', ['only' => ['index']]);
        $this->middleware('permission:create-gallery-kategori|update-gallery-kategori', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-gallery-kategori', ['only' => ['destroy', 'destroys']]);
    }
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['rated_nilai'];
        $data = Rated::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('user')->where('berita_id', $request->parent_id)->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function sumAvg(Request $request)
    {
        $count = Rated::where('berita_id', $request->id)->count();
        $sum = Rated::where('berita_id', $request->id)->sum('rated_nilai');
        $avg = $sum / $count;

        return response()->json([
            'data' => $avg
        ]);
    }
    public function hide(Request $request)
    {
        $hide = Rated::where('id', $request->id)->pluck('ishide')->first();
        $bank = Rated::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
}
