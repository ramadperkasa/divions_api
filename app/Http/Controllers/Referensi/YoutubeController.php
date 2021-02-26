<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreYoutube;
use App\Model\Referensi\Youtube;

class YoutubeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:video', ['only' => ['index']]);
        $this->middleware('permission:create-video|update-video', ['only' => ['updateOrCreate', 'hide']]);
        $this->middleware('permission:delete-video', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['image', 'judul', 'sinopsis', 'url'];
        $data = Youtube::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreYoutube $request)
    {
        try {
            $request->validated();

            $youtube = Youtube::updateOrCreate($request->only('id'), $request->all());

            if ($youtube) {
                $status = true;
                $response = $youtube->wasRecentlyCreated;
            } else {
                $status = false;
                $response = null;
            }
        } catch (\Exception $e) {
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

        try {

            $youtube = Youtube::destroy($request->id);

            if ($youtube) {
                $status = true;
            } else {
                $status = false;
            }
        } catch (\Exception $e) {
            $status = false;
        }
        return response()->json([
            'status' => $status
        ]);
    }

    public function hide(Request $request)
    {

        try {
            $youtube = Youtube::findOrFail($request->id);

            $youtube->update([
                'ishide' => $youtube->ishide == 1 ? 0 : 1
            ]);

            if ($youtube) {
                $status = true;
            } else {
                $status = false;
            }
        } catch (\Exception $e) {
            $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }
}
