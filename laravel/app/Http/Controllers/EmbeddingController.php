<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmbeddingController extends Controller
{
    public function index()
    {
        return view("demo.embeddingForm");
    }

    public function redirect(Request $request)
    {
        return redirect("embedding/" . $request->userId);
    }

    public function upload($userId)
    {   
        $user = \App\Models\User::where("userId", $userId)->first();

        if(!$user){
            echo "User not found";
            exit;
        }

        return view("demo.embeddingUpload", ["userId" => $userId]);
    }

    public function result($userId, Request $request)
    {
        $user = \App\Models\User::where("userId", $userId)->first();

        $request->file->move(('../uploads/embeddings'), 'embedding.txt');

        $filename = "../uploads/embeddings/embedding.txt";
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $content = fread($myfile,filesize($filename));
        fclose($myfile);

        $total = 0;
        $count = 0;

        foreach($user->embeddings as $embedding){
            $total += stringToEmbedding($embedding->content, $content);
            $count++;
        }

        $average = $total/$count;

        return view("demo.embeddingResult", ["average" => $average]);
    }
}
