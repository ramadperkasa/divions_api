<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Referensi\StoreSubscribe;
use App\Model\Referensi\Subscribe;
use App\Mail\Subscribe as MailSubscribe;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendEmail;
use View;

class SubscribeController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:read-subscribe', ['only' => ['index']]);
        // $this->middleware('permission:create-subscribe|update-subscribe', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-subscribe', ['only' => ['destroy', 'destroys']]);
    }
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['email'];
        $data = Subscribe::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreSubscribe $request)
    {
        $request->validated();

        $Subscribe = Subscribe::updateOrCreate($request->only('id'), $request->all());

        if ($Subscribe) {
            $status = true;
            $response = $Subscribe->wasRecentlyCreated;
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
        $data = Subscribe::destroy($request->id);

        if (!$data) {
            $status = false;
        } else {
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
            $data = Subscribe::destroy($value['id']);

            if (!$data) {
                $status = false;
                $fail++;
            } else {
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
        $hide = Subscribe::where('id', $request->id)->pluck('ishide')->first();
        $bank = Subscribe::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Subscribe::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Subscribe::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }



    public function testEmail()
    {
        $data = array(
            array('nama' => 'kevin', 'email' => 'kevin@gmail.com', 'username' => 'user', 'password' => 'password'),
            array('nama' => 'asep', 'email' => 'asep@gmail.com', 'username' => 'user', 'password' => 'password'),            
        );
        $zz = array(0 => 
            array('judul' => 'kisah classic','kategori' => 'romance'),
            1 => array('judul' => 'Bedebag', 'kategori' => 'politic'),
        );
// dd($data);
        foreach ($data as $item) {
            Mail::to('prabujanwar32@gmail.com')->queue(new MailSubscribe($zz));
        }

        // Mail::to('prabujanwar32@gmail.com')->send(new MailSubscribe($data));
        // dispatch(new SendEmail($data));

    }
}
