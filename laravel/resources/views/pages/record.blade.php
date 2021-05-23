@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm">
            <div class="card uploadForm">
                <div class="card-body text-center">
                    <h4>Result</h4>
                    <br>
                    <div id="controls">
                        <button id="recordButton">Record</button>
                        <button id="pauseButton" disabled="">Pause</button>
                        <button id="stopButton" disabled="">Stop</button> 
                    </div>
                     <div class="row">
                     <ol id="recordingsList"></ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm"></div>
    </div>
</div>
@endsection