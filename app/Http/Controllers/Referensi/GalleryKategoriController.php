<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreGalleryKategori;
use App\Model\Referensi\GalleryKategori;

class GalleryKategoriController extends Controller
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
        $fields = ['nama', 'nama_en', 'reorder'];
        $data = GalleryKategori::where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('gallery')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreGalleryKategori $request)
    {
        $request->validated();

        $val = GalleryKategori::updateOrCreate($request->only('id'), $request->all());

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
        $data = GalleryKategori::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $val = GalleryKategori::destroy($request->id);
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
            $data = GalleryKategori::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $val = GalleryKategori::destroy($value['id']);
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
        $hide = GalleryKategori::where('id', $request->id)->pluck('ishide')->first();
        $val = GalleryKategori::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $val = GalleryKategori::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = GalleryKategori::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
