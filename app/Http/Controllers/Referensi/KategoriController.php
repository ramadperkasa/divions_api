<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreKategori;
use App\Model\Referensi\Kategori;
use App\Model\Referensi\MenuSub;
use DB;

class KategoriController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-berita-kategori', ['only' => ['index']]);
        $this->middleware('permission:create-berita-kategori|update-berita-kategori', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-berita-kategori', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama', 'keterangan', 'nama_en', 'keterangan_en', 'reorder'];
        $data = Kategori::where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('berita')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreKategori $request)
    {
        // $request->validated();

        $Kategori = Kategori::updateOrCreate($request->only('id'), $request->all());

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
            'response' => $response
        ]);
    }

    public function destroy(Request $request)
    {
        $data = Kategori::where('id', $request->id)->withCount('berita')->first();

        if ($data->page_count > 0 || $data->berita_count > 0) {
            $status = false;
        } else {
            $Kategori = Kategori::destroy($request->id);
            MenuSub::where('kategori_id', '=', $request->id)->delete();
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
            $data = Kategori::where('id', $value['id'])->withCount('berita')->first();

            if ($data['page_count'] > 0 || $data['berita_count'] > 0) {
                $status = false;
                $fail++;
            } else {
                $Kategori = Kategori::destroy($value['id']);
                MenuSub::where('kategori_id', '=', $value['id'])->delete();
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
        $hide = Kategori::where('id', $request->id)->pluck('ishide')->first();
        $bank = Kategori::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Kategori::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Kategori::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
