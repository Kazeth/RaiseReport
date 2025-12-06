@extends('app')

@section('title', 'RaiseReport - Edit Thread')

@section('content')

    <body>
        <div class="container mt-4" style="max-width: 700px;">
            <h2>Edit Thread</h2>

            <form id="editThreadForm" action="{{ route('thread.update', $thread->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <label class="mt-3">Thread Name</label>
                <input type="text" name="threadName" class="form-control"
                    value="{{ old('threadName', $thread->threadName) }}" required>

                <label class="mt-3">Thread Content</label>
                <textarea name="threadContent" class="form-control" rows="5" required>{{ old('threadContent', $thread->threadContent) }}</textarea>

                <label class="mt-3">Add More Attachments</label>
                <input type="file" name="files[]" class="form-control" multiple>

                {{-- Existing attachments --}}
                @if ($thread->files->count() > 0)
                    <h5 class="mt-4">Current Attachments</h5>
                    <ul class="list-group">
                        @foreach ($thread->files as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $file->fileName }}</span>

                                <div class="form-check">
                                    <input type="checkbox" name="remove_files[]" class="form-check-input"
                                        value="{{ $file->id }}" id="removeFile{{ $file->id }}">

                                    <label for="removeFile{{ $file->id }}" class="form-check-label text-danger">
                                        Remove
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif


                <button type="button" id="saveBtn" class="btn btn-primary mt-3 w-100">Save Changes</button>
            </form>
        </div>

        <!-- Warning Modal -->
        <div class="modal fade" id="confirmEditModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">⚠ Warning</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Once you update this thread, it must be reviewed again by an admin.
                            Its status will change back to <strong>Pending</strong> until approval.
                        </p>

                        <p class="mb-0 fw-bold">Are you sure you want to proceed?</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button id="confirmEditBtn" class="btn btn-danger">Yes, Continue</button>
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
