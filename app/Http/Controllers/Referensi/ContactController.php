<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreContact;
use App\Model\Referensi\Contact;


class ContactController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-kontak', ['only' => ['index']]);
        $this->middleware('permission:create-kontak|update-kontak', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        $this->middleware('permission:delete-kontak', ['only' => ['destroy',  'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['isi', 'jenis', 'nama', 'nama_jenis'];
        $data = Contact::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreContact $request)
    {

        $request->validated();
        $v = $request->jenis;

        switch ($v) {
            case 1:
                $icon = 'fa fa-twitter';
                $nama_jenis = 'Twitter';
                break;
            case 2:
                $icon = 'fab fa-instagram';
                $nama_jenis = 'Instagram';
                break;
            case 3:
                $icon = 'fab fa-youtube';
                $nama_jenis = 'Youtube';
                break;
            case 4:
                $icon = 'fa fa-phone';
                $nama_jenis = 'Telephone';
                break;
            case 5:
                $icon = 'fa fa-mobile';
                $nama_jenis = 'Handphone';
                break;
            case 6:
                $icon = 'fa fa-map-marker';
                $nama_jenis = 'Location';
                break;
            case 7:
                $icon = 'fa fa-envelope';
                $nama_jenis = 'Email';
                break;
            case 8:
                $icon = 'fab fa-whatsapp';
                $nama_jenis = 'WhatsApp';
                break;
            case 9:
                $icon = 'fa fa-question';
                $nama_jenis = 'Others';
                break;
            case 0:
                $icon = 'fab fa-facebook-f';
                $nama_jenis = 'Facebook';
                break;
            case 10:
                $icon = 'fa fa-linkedin';
                $nama_jenis = 'Linkedin';
                break;
            default:
                $icon = '';
                $nama_jenis = '';
                break;
        }
        $merge = array_merge($request->all(), ['icon' => $icon, 'nama_jenis' => $nama_jenis]);
        $Contact = Contact::updateOrCreate($request->only('id'), $merge);

        if ($Contact) {
            $status = true;
            $response = $Contact->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'status' => $icon
        ]);
    }
    public function destroy(Request $request)
    {
        $data = Contact::destroy($request->id);

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
            $data = Contact::destroy($value['id']);

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
        $hide = Contact::where('id', $request->id)->pluck('ishide')->first();
        $bank = Contact::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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

    // public function destroys(Request $request)
    // {
    //     $success = 0;
    //     $fail = 0;

    //     foreach ($request->item as $key => $value) {
    //         $Contact = Contact::destroy($request['id']);

    //         if ($Contact) {
    //             $success++;
    //             $status = true;
    //         } else {

    //     $hide = Contact::where('id', $request->id)->pluck('ishide')->first();
    //     $folder = Contact::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

    //     if ($folder) {
    //         $status = true;
    //     } else {
    //         $status = false;
    //     }

    //     return response()->json([
    //         'hide' => $hide,
    //         'status' => $status,
    //     ]);
    //         }
    public function reorder(Request $request)
    {
        $folder = Contact::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Contact::select('id as value', 'nama_folder as text')->where('isedit', 0)->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
