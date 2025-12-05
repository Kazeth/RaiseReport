@extends('app')

@section('title', 'RaiseReport - Manage Threads')

@section('content')

    <body>
        <div class="col d-flex justify-content-center">
            <div class="row container-sm justify-content-center" style="width: 50vw;">
                <h1 class="d-flex justify-content-center">Manage Threads</h1>

                <div class="d-flex mt-5 p-0" id="statusTabs">

                    <div class="col m-0 text-center py-2 approved-tab active"
                        style="background-color: transparent; color: #6c757d; cursor: pointer;">
                        Approved
                    </div>
                    <div class="col text-center py-2 rejected-tab"
                        style="background-color: transparent; color: #6c757d; cursor: pointer;">
                        Rejected
                    </div>
                </div>

                <hr class="" style="border-top:1px solid rgb(0, 0, 0);">

                <div id="threadContainer">
                    @foreach ($threads as $thread)
                        @php
                            $cardBg = match ($thread->threadStatus) {
                                'Pending' => '#fff7d1', // kuning muda
                                'Rejected' => '#ffe1e1', // merah muda
                                default => '#00FF0062', // approved hijau muda
                            };

                        @endphp

                        <a href="{{ route('adminDetail', $thread->id) }}?from={{ url()->current() }}" style="text-decoration:none; color:inherit;">
                            <div class="card row thread-card" data-status="{{ strtolower($thread->threadStatus) }}"
                                style="padding: 1vw; margin: 2vw; background-color: {{ $cardBg }};">

                                <div class="fw-bold mb-2 d-flex align-items-center" style="font-size: 1.2rem; gap: 10px;">
                                    {{ $thread->threadName }}
                                </div>

                                <div class="mb-3" style="text-align: justify;">
                                    {{ $thread->threadContent }}
                                </div>


                                <div class="d-flex justify-content-between mb-2">
                                    @if ($thread->threadStatus === 'Approved')
                                        <div>Upvote : {{ $thread->upvotes_count }}

                                        </div>
                                    @endif
                                    <div>Posted on {{ $thread->created_at->translatedFormat('d F Y - H.i') }}</div>

                                </div>
                                @if ($thread->threadStatus === 'Approved')
                                    <div>
                                        <form action="{{ route('revert', $thread->id) }}" method="POST" class="m-2"
                                            style="">
                                            @csrf
                                            <button class="btn btn-warning"
                                                style="border:2px solid rgb(255, 255, 255);">Revert to Pending</button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </a>
                    @endforeach



                </div>

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {

                function filterThreads(status) {
                    $(".thread-card").each(function() {
                        const threadStatus = $(this).data("status");

                        if (status === "approved" || status === "pending" || status === "rejected") {
                            $(this).toggle(threadStatus === status);
                        } else {
                            $(this).show();
                        }
                    });
                }

                $('#statusTabs').on('click', '.col', function() {
                    const status = $(this).text().trim().toLowerCase();

                    $('#statusTabs .col').removeClass('active');
                    $(this).addClass('active');

                    filterThreads(status);
                });

                filterThreads("approved");
            });
        </script>
        <script>
            $(document).ready(function() {
                const $statusTabs = $('#statusTabs');


                $statusTabs.on('click', '.col', function() {
                    const $clickedTab = $(this);

                    $statusTabs.find('.col').removeClass('active');

                    $clickedTab.addClass('active');

                    const status = $clickedTab.text().trim().toLowerCase();
                    console.log('Tab aktif saat ini:', status);
                });
            });
        </script>
        <style>
            #statusTabs>div:hover {
                transition: background-color 1s, color 1s, border-color 1s;
            }

            .approved-tab.active {
                color: #187a00 !important;
                font-weight: 600;
                box-shadow: 0 -30px 15px -10px rgba(0, 255, 0, 0.384) inset;
                border-bottom: 2px solid #009113 !important;
            }

            .approved-tab:hover {
                font-weight: 600;
                box-shadow:
                    0 -30px 15px -10px rgba(0, 255, 0, 0.384) inset;

                border-bottom: 2px solid #009113 !important;
            }

            .rejected-tab.active {
                color: #ff0000 !important;
                font-weight: 600;
                box-shadow: 0 -30px 15px -10px rgba(255, 105, 105, 0.589) inset;
                border-bottom: 2px solid #ff0000 !important;
            }

            .rejected-tab:hover {
                font-weight: 600;
                box-shadow: 0 -30px 15px -10px rgba(255, 105, 105, 0.589) inset;
                border-bottom: 2px solid #ff0000 !important;
            }
        </style>
    </body>
@endsection
