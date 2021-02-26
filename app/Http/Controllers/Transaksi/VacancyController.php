<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\StoreVacancy;
use App\Model\Transaksi\Image;
use App\Model\Transaksi\Vacancy;
use App\Notifications\Telegram;
use App\Notifications\Twitter;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Twitter\TwitterChannel;

class VacancyController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-vacancy', ['only' => ['index']]);
        // $this->middleware('permission:create-vacancy|update-vacancy', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-vacancy', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => true];
        $fields = ['id', 'tgl_publikasi', 'image_id', 'judul', 'sinopsis', 'isi_berita', 'judul_en', 'sinopsis_en', 'isi_berita_en', 'komentar', 'komentar_auto', 'rated', 'meta_tag', 'total_view', 'last_view', 'reorder', 'slug'];
        $data = Vacancy::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('kategoriSubVacancy', 'brand', 'admin')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,
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
            'data' => $request->socmed['facebook'],
        ]);
    }

    public function updateOrCreate(StoreVacancy $request)
    {
        $image_id = Image::where('image', $request->cover_image)->pluck('id')->first();
        $brand_id = $request->duplicate && gettype($request->brand) == 'array' ? $request->brand['_id'] : $request->brand_id;
        
        $data = array_merge($request->all(), ['tgl_publikasi' => Carbon::now(), 'brand_id' =>  $brand_id, 'posted_by' => Auth::user('admin')->id, 'slug' => Str::slug($request->judul, '-'), 'image_id' => $image_id]);

        $berita = Vacancy::updateOrCreate($request->only('id'), $data);

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
            'slug' => $slug,
        ]);
    }

    public function destroy(Request $request)
    {
        $destroy = Vacancy::destroy($request->id);
        if ($destroy) {
            $status = true;
        } else {
            $status = false;
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
            $data = Vacancy::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $Vacancy = Vacancy::destroy($value['id']);
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
        $hide = Vacancy::where('id', $request->id)->pluck('ishide')->first();
        $bank = Vacancy::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Vacancy::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Vacancy::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data,
        ]);
    }
    public function ajaxId(Request $request)
    {
        $data = Vacancy::where('id', $request->id)->with('kategoriSubVacancy')->first();

        return response()->json([
            'data' => $data,
        ]);
    }
}
