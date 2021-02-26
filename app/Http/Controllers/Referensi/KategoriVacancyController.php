<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreKategoriVacancy;
use App\Model\Referensi\KategoriVacancy;
use Illuminate\Http\Request;

class KategoriVacancyController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-vacancy-kategori', ['only' => ['index']]);
        // $this->middleware('permission:create-vacancy-kategori|edit-vacancy-kategori', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-vacancy-kategori', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['id', 'nama', 'nama_en', 'keterangan', 'keterangan_en', 'ishide', 'reorder'];
        $data = KategoriVacancy::where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('subKategoriVacancy')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreKategoriVacancy $request)
    {
        // $request->validated();
        $Kategori = KategoriVacancy::updateOrCreate($request->only('id'), $request->all());

        if ($Kategori) {
            $status = true;
            $response = $Kategori->wasRecentlyCreated;

            // if ($response) {
            //     MenuSub::create(['parent_id' => 5, 'title' => $request->nama, 'title_en' => $request->nama_en, 'url' => '/news/' . str_slug($request->nama_en), 'kategori_id' => $Kategori->id, 'tipe_link' => 1]);
            // }
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
        $data = KategoriVacancy::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $Kategori = KategoriVacancy::destroy($request->id);
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
            $siap = $value['id'];
            $data = KategoriVacancy::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $Kategori = KategoriVacancy::destroy($value['id']);
                $status = true;
                $success++;
            }
        }

        return response()->json([
            'status' => $status,
            'success' => $success,
            'fail' => $fail,
            'data' => $data
        ]);
    }

    public function hide(Request $request)
    {
        $hide = KategoriVacancy::where('id', $request->id)->pluck('ishide')->first();
        $bank = KategoriVacancy::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = KategoriVacancy::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = KategoriVacancy::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
