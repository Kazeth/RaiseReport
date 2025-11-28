<div class="row align-items-center justify-content-start  shadow p-3 mb-5 bg-body rounded" style="padding: 1vw">
    <div class="row col-8 t" style="margin-left: 1vw">
        <div class="col-2">
            <a href="{{ route('/') }}">RaiseReport</a>
        </div>
        <div class="col-10 d-flex justify-content-evenly">
            <a href="{{ route('/threads') }}">Threads</a>
            <a href='{{ route('/userThreads') }}'>My Threads </a>
            <a href='{{ route('/createThread') }}'>Create Thread</a>
            <a href='{{ route('/editThread') }}'>Edit Thread</a>
            <a href='{{ route('/profile') }}'>Profile</a>
        </div>


    </div>
    <div class="col-4">

    </div>

</div>
<style>
    a {
        text-decoration: none;
    }
</style>
