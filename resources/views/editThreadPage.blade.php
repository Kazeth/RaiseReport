@extends('app')

@section('title', 'RaiseReport - Edit Thread')

@section('content')

    <body>
        <div class="container mt-4" style="max-width: 700px;">
            <h2>@lang('messages.edit_thread')</h2>

            <form id="editThreadForm" action="{{ route('thread.update', $thread->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <label class="mt-3">@lang('messages.thread_name')</label>
                <input type="text" name="threadName" class="form-control"
                    value="{{ old('threadName', $thread->threadName) }}" required>

                <label class="mt-3">@lang('messages.thread_content')</label>
                <textarea name="threadContent" class="form-control" rows="5" required>{{ old('threadContent', $thread->threadContent) }}</textarea>

                <label class="mt-3">@lang('messages.add_attachments')</label>
                <input type="file" name="files[]" class="form-control" multiple>

                {{-- Existing attachments --}}
                @if ($thread->files->count() > 0)
                    <h5 class="mt-4">@lang('messages.current_attachments')</h5>
                    <ul class="list-group">
                        @foreach ($thread->files as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $file->fileName }}</span>

                                <div class="form-check">
                                    <input type="checkbox" name="remove_files[]" class="form-check-input"
                                        value="{{ $file->id }}" id="removeFile{{ $file->id }}">

                                    <label for="removeFile{{ $file->id }}" class="form-check-label text-danger">
                                        @lang('messages.remove')
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif


                <button type="button" id="saveBtn" class="btn btn-primary mt-3 w-100">@lang('messages.save_changes')</button>
            </form>
        </div>

        <!-- Warning Modal -->
        <div class="modal fade" id="confirmEditModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">⚠ @lang('messages.warning')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            @lang('messages.edit_warning1') <strong>@lang('messages.pending')</strong>@lang('messages.edit_warning2')
                        </p>

                        <p class="mb-0 fw-bold">@lang('messages.edit_warning3')</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                        <button id="confirmEditBtn" class="btn btn-danger">@lang('messages.edit_confirm1')</button>
                    </div>

                </div>
            </div>
        </div>

        <script>
            const saveBtn = document.getElementById("saveBtn");
            const confirmBtn = document.getElementById("confirmEditBtn");
            const form = document.getElementById("editThreadForm");

            saveBtn.addEventListener("click", function() {
                const modal = new bootstrap.Modal(document.getElementById('confirmEditModal'));
                modal.show();
            });

            confirmBtn.addEventListener("click", function() {
                form.submit();
            });
        </script>

    </body>
@endsection
