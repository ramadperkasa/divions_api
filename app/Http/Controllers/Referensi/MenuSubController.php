<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreMenuSub;
use App\Model\Referensi\MenuSub;

class MenuSubController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-menu-sub', ['only' => ['index']]);
        $this->middleware('permission:create-menu-sub|update-menu-sub', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        $this->middleware('permission:delete-menu-sub', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['title', 'url'];
        $data = MenuSub::where(function ($query) use ($request, $fields) {
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
        $data = $data->where('parent_id', 'LIKE', "%" . $request->parent_id . "%")->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreMenuSub $request)
    {
        $request->validated();
        // $merge = array_merge(['tipe_link', $request->tipe_link == 1 ? '2' : $request->tipe_link == 2 ? '1' : $request->tipe_link], $request->all());
        MenuSub::updateOrCreate($request->only('id'), $request->all());

        return response()->json([
            'status' => true
        ]);
    }

    public function destroy(Request $request)
    {
        $status = false;
        $menuSub = MenuSub::where($request->only('id'))->first();

        if ($menuSub) {
            $status = true;
            $menuSub->delete();
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
            $menuSub = MenuSub::where($request->only('id'))->first();

            if ($menuSub) {
                $menuSub = $menuSub->delete();
                $status = true;
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
        $hide = MenuSub::where('id', $request->id)->pluck('ishide')->first();
        $bank = MenuSub::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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

    public function hideFooter(Request $request)
    {
        $hide = MenuSub::where('id', $request->id)->pluck('ishide_footer')->first();
        $bank = MenuSub::where('id', $request->id)->update(['ishide_footer' => $hide == 1 ? 0 : 1]);

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
        $bank = MenuSub::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = MenuSub::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
