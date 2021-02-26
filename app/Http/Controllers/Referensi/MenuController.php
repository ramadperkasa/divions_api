<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreMenu;
use App\Model\Referensi\Menu;

class MenuController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-menu', ['only' => ['index']]);
        $this->middleware('permission:create-menu|update-menu', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-menu', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => true];
        $fields = ['title', 'url'];
        $data = Menu::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('page')->withCount('menuSub')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreMenu $request)
    {
        $request->validated();
        Menu::updateOrCreate($request->only('id'), $request->all());

        return response()->json([
            'status' => true
        ]);
    }

    public function destroy(Request $request)
    {
        $status = false;
        $menu = Menu::where('id', $request->id)->first();

        if ($menu) {
            $status = true;
            $menu->delete();
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
            $menu = Menu::where('id', $value['id'])->first();

            if ($menu) {
                $status = true;
                $menu->delete();
                $success++;
            } else {
                $status = false;
                $fail++;
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
        $hide = Menu::where('id', $request->id)->pluck('ishide')->first();
        $folder = Menu::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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

    public function hideFooter(Request $request)
    {
        $hide = Menu::where('id', $request->id)->pluck('ishide_footer')->first();
        $folder = Menu::where('id', $request->id)->update(['ishide_footer' => $hide == 1 ? 0 : 1]);

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
        $folder = Menu::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Menu::where('tipe_link', 0)->orWhere('tipe_link', 1)->select('id as value', 'title as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
