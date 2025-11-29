@extends('app')

@section('title', 'RaiseReport')

@section('content')

    <body>
        <div class="col d-flex justify-content-center">
            <div class="row container-sm justify-content-center" style="width: 50vw;">
                <h1 class="d-flex justify-content-center">Trendings</h1>

                <input type="text" id="searchBox" class="form-control" placeholder="Search threads...">
                <div id="threadContainer">
                    @foreach ($threads as $thread)
                        <div class="card row" style="padding: 1vw; margin: 2vw;">
                            <div class="d-flex justify-content-between mb-3 mt-2">
                                <div class="col-4 fw-bold">{{ $thread->threadName }}</div>
                                <div class="col-4">{{ $thread->threadContent }}</div>
                            </div>
                            <div class="justify-content-between ">
                                <div class="col">Upvote : {{ $thread->threadUpvote }}</div>
                                <div class="col">Posted on {{ $thread->created_at->translatedFormat('d F Y - H.i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $('#searchBox').on('keyup', function() {
                let search = $(this).val();

                $.ajax({
                    url: "{{ route('search') }}",
                    type: "GET",
                    data: {
                        search: search
                    },
                    success: function(data) {

                        $('#threadContainer').html('');

                        data.forEach(thread => {
                            $('#threadContainer').append(`
                        <div class="card row" style="padding: 1vw; margin: 2vw;">
                            <div class="d-flex justify-content-between mb-3 mt-2">
                                <div class="col-4 fw-bold">${thread.threadName}</div>
                                <div class="col-4">${thread.threadContent}</div>
                            </div>
                            <div class="col">Upvote : ${thread.threadUpvote}</div>
                        </div>
                    `);
                        });
                    }
                });
            });
        </script>
    </body>
@endsection
