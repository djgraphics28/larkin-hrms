<div>
    <div class="card">
        <div class="card-body">
            <div wire:ignore>
                <form wire:submit.prevent="save">
                    <input type="file" wire:model="files">

                    @error('files.*')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <button class="btn btn-primary" type="submit">Upload</button>
                </form>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h4>Employee's Documents</h4>
        </div>
        <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $document)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $document->file_name }}</td>
                            <td>{{ number_format($document->size / 1024, 2) }} KB</td>
                            <td>{{ $document->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ $document->getUrl() }}" class="btn btn-primary" target="_blank">View</a>
                                <a href="{{ route('employee.downloadDocument', $document->id) }}" class="btn btn-success">Download</a>
                                <form action="{{ route('employee.deleteDocument', $document->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                           <tr>
                                <td class="text-center" colspan="5"> no data</td>
                           </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', () => {
            const inputElement = document.querySelector('input[type="file"]');
            const pond = FilePond.create(inputElement);

            pond.setOptions({
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer,
                    options) => {
                        @this.upload('files', file, load, error, progress);
                    }
                }
            });
        });
    </script>
@endpush
