<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreKategoriSubVacancy;
use App\Model\Referensi\KategoriSubVacancy;
use App\Model\Transaksi\Image;
use Illuminate\Http\Request;

class KategoriSubVacancyController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-vacancy-sub', ['only' => ['index']]);
        // $this->middleware('permission:create-vacancy-sub|update-vacancy-sub', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        // $this->middleware('permission:delete-vacancy-sub', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama', 'keterangan'];
        $data = KategoriSubVacancy::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('kategoriVacancy')->withCount('vacancy')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreKategoriSubVacancy $request)
    {
        $image_id = Image::where('image', $request->image_id)->pluck('id')->first();
        $merge = array_merge($request->except('image'), ['image_id' => $image_id]);
        $mitra = KategoriSubVacancy::updateOrCreate($request->only('id'), $merge);

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
        $data = KategoriSubVacancy::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $Kategori = KategoriSubVacancy::destroy($request->id);
            $status = true;
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
            $data = KategoriSubVacancy::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $Kategori = KategoriSubVacancy::destroy($value['id']);
                $status = true;
                $success++;
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
        $hide = KategoriSubVacancy::where('id', $request->id)->pluck('ishide')->first();
        $bank = KategoriSubVacancy::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = KategoriSubVacancy::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    public function ajax(Request $request)
    {
        $data = KategoriSubVacancy::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function ajaxId(Request $request)
    {
        $data = KategoriSubVacancy::select('id as value', 'nama as text')->where('parent_id', $request->id)->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
