<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $embeddings = \App\Models\Embedding::where("userId", auth()->user()->userId);

        $embeddingsCount = $embeddings->count();

        return view("pages.dashboard",[
            "title" => "Dashboard",
            "embeddingsCount" => $embeddingsCount,
        ]);
    }

    public function voiceNew()
    {
        return view("pages.addVoice",[
            "title" => "Add New Voice Sample",
        ]);
    }

    public function voiceNewSubmit(Request $request)
    {
        $filename = auth()->user()->userId . "_"  . uniqid();
        
        mkdir($filename);
        $request->audio_data->move(('../audio_files/new/' . $filename), $filename . '.wav');
        
        $process = new Process(["py", "../python_scripts/generate_embd.py", "-val_wav", '../audio_files/new/'. $filename . '/']);
        $process->setTimeout(2 * 36000000);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        $embedding = new \App\Models\Embedding;
        $embedding->userId = auth()->user()->userId;
        $embedding->embeddingId = $filename;

        $f = "../embeddings/" . $filename . ".txt";
		
        //echo $f ."\r\n";

        $myfile = fopen($f, "r") or die("Unable to open file!");
        $content = fread($myfile,filesize($f));
        fclose($myfile);
        

        $embedding->content = $content;
        $embedding->save();

        echo '
        <button class="btn btn-light btn-lg" style="width:100%;" id="complete">
            <i class="fas fa-check-circle"></i> Success
        </button>
        <a href="' . url('dashboard') . '" class="btn btn-link btn-lg" style="width:100%;" id="go back">
            <i class="fas fa-long-arrow-left"></i> Go Back
        </a>
        ';

    }


}
