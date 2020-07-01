<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    </head>
    <body>
        @if (session()->has('flash-msg'))
            <div class="{{ session()->get('flash-msg.success') ? 'success' : 'alert' }}">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <b>{{ session()->pull('flash-msg.message') }}</b>
            </div>
        @endif
        <div class="flex-center position-ref full-height">
            <div class="content">
                <form id="process-image" action="/process" method="post">
                    @csrf
                    <input type="hidden" name="image">
                    <input type="file" onchange="previewFile()">
                    <select name="type">
                        <option value="original">Original image</option>
                        <option value="square">Square image</option>
                        <option value="small">Small image</option>
                        <option value="all">All three</option>
                    </select>
                    <input type="submit" value="Process">
                </form>

                @if ($errors->any())
                    <div class="validation-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </body>
    <script type="application/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</html>
