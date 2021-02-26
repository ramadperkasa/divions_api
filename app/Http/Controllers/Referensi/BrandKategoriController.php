<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Model\Referensi\BrandKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Referensi\StoreBrandKategori;

class BrandKategoriController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-kategori-brand', ['only' => ['index']]);
        // $this->middleware('permission:create-kategori-brand|edit-kategori-brand', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-kategori-brand', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['id', 'nama', 'nama_en', 'ishide', 'reorder'];
        $data = BrandKategori::where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('brand')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOrCreate(StoreBrandKategori $request)
    {
        // $request->validated();
        $_id = $request->_id ? $request->_id : Str::uuid();
        $data = array_merge($request->except('_id','brand_id'), ['_id' => $_id]);
        $Kategori = BrandKategori::updateOrCreate($request->only('id'), $data);

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
        $data = BrandKategori::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $Kategori = BrandKategori::destroy($request->id);
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
            $data = BrandKategori::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $Kategori = BrandKategori::destroy($value['id']);
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
        $hide = BrandKategori::where('id', $request->id)->pluck('ishide')->first();
        $bank = BrandKategori::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = BrandKategori::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = BrandKategori::select('_id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
