<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RecognitionController extends Controller
{
    public function upload()
    {
        return view("demo.upload");
    }

    public function recognize(Request $request)
    {
        $request->file->move(('../audio_files'), 'test.WAV');

        $process = new Process(["python3", "../python_scripts/test.py"]);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $data = $process->getOutput();
        
        return view("demo.result", [
            "data" => $data
        ]);
    }
}
