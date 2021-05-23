<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Auth;

class UserController extends Controller
{
    public function register()
    {
        return view("pages.register",[
            "title" => "Register",
        ]);
    }

    public function registerSubmit(Request $request)
    {
        do{
            $i = 0;
            $id = uniqid();
            $rows = \App\Models\User::where("userId", $id)->get();
            foreach($rows as $row){
                $i++;
            }    
        }while($i != 0);

        $user = new \App\Models\User;
        $user->name = $request->name;
        $user->email = strtolower($request->email);
        $user->password = Hash::make($request->password);
        $user->userId = $id;
        $user->save();

        return redirect("/login?success");
    }

    public function loginView()
    {
        return view("pages.login",[
            "title" => "Login",
        ]);
    }

    public function loginSubmit(Request $request)
    {
        return view("pages.loginOptions",[
            "title" => "Login",
            "email" => $request->email
        ]);
    }

    public function loginPasswordSubmit(Request $request)
    {
        $user = \App\Models\User::where("email", $request->email)->first();

        //dd($user);

        if (Hash::check($request->password, $user->password)) {
            Auth::login($user, true);
            return redirect("dashboard");
        } else {
            return redirect("login?error=Wrong credentials");
        }
    }

    public function loginVoice(Request $request)
    {
        $email = $request->email;

        return view("pages.voiceLogin",[
            "title" => "Login",
            "email" => $email
        ]);
    }

    public function loginVoiceSubmit(Request $request)
    {
        $filename = uniqid() . "_"  . time();
        $request->audio_data->move(('../audio_files/new' . $filename), $filename . '.wav');

        $process = new Process(["py", "../python_scripts/generate_embd.py", "-val_wav", '"../audio_files/'. $filename . "/" . '"']);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $f = "../embeddings/new/" . $filename . "/" . $filename .".txt";
				
        $myfile = fopen($f, "r") or die("Unable to open file!");
        $content = fread($myfile,filesize($f));
        fclose($myfile);

        $user = \App\Models\User::where("email", $_GET["email"])->first();
        $embeddings = \App\Models\Embedding::where("userId", $user->userId)->get();

        $total = 0;
        $count = 0;

        foreach($embeddings as $embedding){
            $total += stringToEmbedding($embedding->content, $content);
            $count++;
        }

        if($count != 0){
            $average = $total/$count;
        } else {
            $average = 0;
        }

        

        if($average >= 0.9){
            Auth::login($user, true);
            echo '
            <button class="btn btn-light btn-lg" style="width:100%;" id="complete">
                <i class="fas fa-check-circle"></i> Success
            </button>
            <a href="' . url('dashboard') . '" class="btn btn-link btn-lg" style="width:100%;" id="go back">
                <i class="fas fa-long-arrow-left"></i> Go to Dashboard
            </a>
            ';
        } else {
            echo '
            <button class="btn btn-light btn-lg" style="width:100%;" id="complete">
                <i class="fas fa-check-cross"></i> Failed
            </button>
            <a href="' . url('/') . '" class="btn btn-link btn-lg" style="width:100%;" id="go back">
                <i class="fas fa-long-arrow-left"></i> Go to Login Page
            </a>
            ';
        }

    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');   
    }
}
