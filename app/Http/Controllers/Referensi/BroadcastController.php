<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\Broadcast;
use App\Model\Transaksi\Berita;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe as MailSubscribe;
use App\Http\Requests\Referensi\StoreBroadcast;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Model\Referensi\Subscribe;

class BroadcastController extends Controller
{
    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['created_at', 'total_news', 'total_subscribe'];
        $data = Broadcast::where(function ($query) use ($request, $fields) {
            foreach ($fields as $item) {
                $query->orWhere($item, 'LIKE', "%" . $request->search . "%");
            }
        })->orderBy('created_at', 'desc');
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

    public function updateOrCreate(Request $request)
    {

        // $request->validated();        
        $berita = Berita::select('judul', 'image_id', 'posted_by', 'total_view', 'tgl_publikasi', 'type_img', 'slug')->with('admin')->whereIn('id', $request->broadcastNews)->get();
        if ($request->pilihSemua == 1) {
            $subscribeSend = Subscribe::select('email')->where('ishide', 0)->get();
            foreach ($subscribeSend as $item) {                
                $hasil =  Mail::to($item->email)->queue(new MailSubscribe($berita->toArray()));
            };            
        } else {
            foreach ($request->subscribe as $item) {
                $hasil =  Mail::to($item['email'])->queue(new MailSubscribe($berita->toArray()));
            };
        }
        $dataBroadcast = [
            'created_at' => Carbon::now(),
            'total_news' => count($request->broadcastNews),
            'total_subscribe' => $request->pilihSemua == 0 ? count($request->subscribe) : count($subscribeSend),
            'updated_at' => Carbon::now(),
        ];
        $Broadcast = Broadcast::create($dataBroadcast);
        if ($Broadcast) {
            $status = true;
            $response = $Broadcast->wasRecentlyCreated;
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
        $data = Broadcast::destroy($request->id);

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
            $data = Broadcast::destroy($value['id']);

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
        $hide = Broadcast::where('id', $request->id)->pluck('ishide')->first();
        $bank = Broadcast::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Broadcast::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Broadcast::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
