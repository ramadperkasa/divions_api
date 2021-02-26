<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaksi\Berita;
use App\Http\Requests\Transaksi\StoreBerita;
use Carbon\Carbon;
use Auth;
use Notification;
use Illuminate\Support\Str;
use App\Notifications\Telegram;
use App\Notifications\Twitter;
use App\Notifications\FacebookPoster;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\Facebook;
use NotificationChannels\FacebookPoster\FacebookPosterChannel;
use App\Model\Transaksi\Image;

class BeritaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-berita', ['only' => ['index']]);
        $this->middleware('permission:create-berita|update-berita', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-berita', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => true];
        $fields = ['id', 'id_kategori', 'tgl_publikasi', 'image_id', 'judul', 'sinopsis', 'isi_berita', 'judul_en', 'sinopsis_en', 'isi_berita_en', 'komentar', 'komentar_auto', 'rated', 'meta_tag', 'total_view', 'last_view', 'reorder', 'slug'];
        $data = Berita::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('Kategori','image', 'admin')->withCount('slider','komentar','rated')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function socmed(Request $request)
    {
        // if ($request->socmed['facebook']) {
        // Notification::route(FacebookPosterChannel::class, '')->notify(new FacebookPoster);
        // }
        if ($request->socmed['telegram']) {
            Notification::route(TelegramChannel::class, $request->form)->notify(new Telegram($request->form));
        }
        if ($request->socmed['twitter']) {
            Notification::route(TwitterChannel::class, $request->form)->notify(new Twitter($request->form));
        }

        return response()->json([
            'data' => $request->socmed['facebook']
        ]);
    }

    public function updateOrCreate(StoreBerita $request)
    {
        $ox = array(
            "webm",
            "mpg",
            "mp2",
            "mpeg",
            "mpe",
            "mpv",
            "mp4",
            "m4p",
            "m4v",
            "avi",
            "wmv",
            "mov",
            "qt",
            "flv",
            "swf",
            "avchd",
        );

        // if (!is_file($request->image_id)) {
        //     $type = 2;
        // } else {
        //     if (in_array(strtolower(pathinfo($request->image_id, PATHINFO_EXTENSION)), $ox)) {
        //         $type = 1;
        //     } else {
        //         $type = 0;
        //     }
        // }
        
        $image_id = Image::where('image', $request->cover_image )->pluck('id')->first();
        $publish = $request->status == 1 ? Carbon::now() : null;
        $data = array_merge($request->all(), ['tgl_publikasi' => Carbon::now(), 'posted_by' => Auth::user('admin')->id, 'slug' => Str::slug($request->judul, '-'), 'image_id' => $image_id, 'published_at' => $publish]);
            
        $berita = Berita::updateOrCreate($request->only('id'), $data);

        if ($berita) {
            $status = true;
            $response = $berita->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }
        $slug = Str::slug($request->judul);

        return response()->json([
            'status' => $status,
            'response' => $response,
            'slug' => $slug
        ]);
    }

    public function destroy(Request $request)
    {
        $destroy =  Berita::destroy($request->id);
        if ($destroy) {
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
            $data = Berita::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $Berita = Berita::destroy($value['id']);
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
        $hide = Berita::where('id', $request->id)->pluck('ishide')->first();
        $berita = Berita::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($berita) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'hide' => $hide,
            'status' => $status,
        ]);
    }

    public function publish(Request $request)
    {
        $publish = Berita::where('id', $request->id)->pluck('status')->first();
        
        if($publish == 0){
            $berita = Berita::where('id', $request->id)->update(['published_at' => Carbon::now()]);
        }

        $berita = Berita::where('id', $request->id)->update(['status' => $publish == 1 ? 0 : 1]);
        

        if ($berita) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'statusPublish' => $publish,
            'status' => $status,
        ]);
    }

    public function reorder(Request $request)
    {
        $berita = Berita::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($berita) {
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
        $data = Berita::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function ajaxId(Request $request)
    {
        $data = Berita::with('Kategori')->where('id', $request->id)->first();

        return response()->json([
            'data' => $data
        ]);
    }
}
