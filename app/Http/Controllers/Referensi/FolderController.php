<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Referensi\Folder;
use App\Http\Requests\Referensi\StoreFolder;

class FolderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-uploads-folder', ['only' => ['index']]);
        $this->middleware('permission:create-uploads-folder|update-uploads-folder', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-uploads-folder', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama_folder', 'reorder'];
        $data = Folder::where('isedit', '!=', 1)->where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('gambar')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreFolder $request)
    {
        $request->validated();

        $folder = Folder::updateOrCreate($request->only('id'), $request->all());

        if ($folder) {
            $status = true;
            $response = $folder->wasRecentlyCreated;
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

        $status = false;
        $folder = Folder::find($request->id);

        if ($folder) {
            $folder->delete();
            $status = true;
        } else {
            $status = false;
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
            $data = Folder::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $Folder = Folder::destroy($value['id']);
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
        $hide = Folder::where('id', $request->id)->pluck('ishide')->first();
        $folder = Folder::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($folder) {
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
        $folder = Folder::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($folder) {
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
        $data = Folder::select('id as value', 'nama_folder as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
