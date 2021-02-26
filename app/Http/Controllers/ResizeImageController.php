<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ImageLib;

class ResizeImageController extends Controller
{
    public function createThumbnail($path, $width, $height)
    {
        $img = ImageLib::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }
}
