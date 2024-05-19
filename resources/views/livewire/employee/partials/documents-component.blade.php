@section('css')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endsection
<div>
    <h2>Upload Documents</h2>
    <div>
        <input type="file" wire:model="files" multiple>

        <button wire:click="upload">Upload</button>

        @if (session()->has('message'))
            <div>{{ session('message') }}</div>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview
            );

            const inputElement = document.querySelector('input[type="file"]');

            const pond = FilePond.create(inputElement, {
                acceptedFileTypes: ['image/*'],
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('files', file, load, error, progress);
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('files', filename, load);
                    },
                },
            });
        });
    </script>
@endpush
