<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>SpeakerVerification - willCodeForFood</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="{{ url('resources/css/style.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm">
                    <div class="card uploadForm">
                        <div class="card-body text-center">
                            <div class="form-group">
                                <h4>Type your User ID</h4>
                                <br>
                                <form method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input class="form-control" type="input" name="userId" />
                                    <br>
                                    <button class="btn btn-success uploadSubmit" type="submit">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>
    </body>
</html>