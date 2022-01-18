<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=d  evice-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ asset('/js/script.js') }}"></script>
    </head>
    <body>
        <div class="col-xl-10 offset-xl-1">
            <h1>METAR CHECKER</h1>
            <div class="text-center">
                <img src="{{ asset('img/japan_moji.png') }}" class="img-fluid" alt="">
                <p></p>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-5 col-md-4">
                            <p class="lh-1 fw-bolder">UTC</p>
                            <p id="showDateUTC"></p>
                        </div>
                        <div class="col-5 col-md-4">
                            <p class="lh-1 fw-bolder">LOCAL</p>
                            <p id="showDateLOCAL"></p>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NAME</th>
                            <th scope="col">ALTIMETAR</th>
                            <th scope="col">CATEGORY</th>
                            <th scope="col">RAW TEXT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lists as $list)
                        <tr>
                            <th scope="row">{{$list->icao}}</th>
                            <td>{{$list->name}}</td>
                            <td>{{number_format(round($list->altim, 2), 2)}}</td>
                            <td>{{$list->category}}</td>
                            <td>{{$list->raw_text}}</td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
            </div>
        </div>
    </body>
</html>