<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonTestController extends Controller
{
    public function testPythonScript()
    {
        $process = new Process(["python3", "../python_scripts/test.py"]);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo "<pre>";
        echo $data = $process->getOutput();
        echo "</pre>";
    }
}
