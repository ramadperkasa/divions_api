<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StorePage;
use App\Model\Referensi\Page;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PageController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-halaman', ['only' => ['index']]);
        $this->middleware('permission:create-halaman|update-halaman', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        $this->middleware('permission:delete-halaman', ['only' => ['destroy']]);
    }

    public function ajax(Request $request)
    {
        $data = Page::select('id as value', 'judul as text')->orderBy('judul', 'ASC')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['judul', 'konten', 'judul_en', 'konten_en', 'meta_tag', 'kategori_id', 'total_view', 'last_view', 'slug'];
        $data = Page::where(function ($query) use ($request, $fields) {
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
        $data = $data->withCount('menu','menuSub')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StorePage $request)
    {
        $request->validated();
        $merge = array_merge($request->all(), ['slug' => Str::slug($request->judul), 'last_view' => Carbon::now()]);
        $page = Page::updateOrCreate($request->only('id'), $merge);

        if ($page) {
            $status = true;
            $response = $page->wasRecentlyCreated;
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
        $data = Page::where('id', $request->id)->first();

        if (!$data) {
            $status = false;
        } else {
            $page = Page::destroy($request->id);
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
            $data = Page::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $page = Page::destroy($value['id']);
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


    public function custom(Request $request)
    {
        $data = Page::select('slug as value', 'judul as text')->orderBy('judul', 'ASC')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
