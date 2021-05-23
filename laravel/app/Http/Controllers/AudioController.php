<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function record()
    {
        return view("pages.record", [
            "title" => "Record"
        ]);
    }

    public function upload(Request $request)
    {
        $id = uniqid();
        $request->audio_data->move(('../audio_files'), $id . '.wav');

        dd($request->audio_data);
    }
}
