@props([
    'id',
    'title',
    'actionUrl',
    'headers',
    'submitText' => 'Import',
    'entityType' => 'default'
])

<x-my-modal id="{{ $id }}" title="{{ $title }}" iconType="info">
    <div class="mt-2">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4 text-sm text-blue-800">
            <p class="font-semibold mb-1">CSV Format Requirements:</p>
            <p>The file must contain the following exact headers:</p>
            <code class="text-xs bg-white px-2 py-1 rounded mt-2 block border border-blue-100 font-mono">{{ $headers }}</code>
        </div>
        <form id="{{ $id }}-form" method="POST" action="{{ $actionUrl }}" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-center w-full mb-2">
                <label for="dropzone-file-{{ $entityType }}" class="flex flex-col items-center justify-center w-full h-36 border-2 border-zinc-300 border-dashed rounded-xl cursor-pointer bg-zinc-50 hover:bg-zinc-100 hover:border-zinc-400 transition-all duration-200">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <div class="p-3 bg-white shadow-sm border border-zinc-200 rounded-full mb-3">
                            <svg class="w-6 h-6 text-zinc-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                        </div>
                        <p class="mb-1 text-sm text-zinc-600"><span class="font-semibold text-zinc-900">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-zinc-500">Standard .CSV files only</p>
                    </div>
                    <input id="dropzone-file-{{ $entityType }}" type="file" name="csv_file" accept=".csv" required class="hidden" onchange="document.getElementById('file-name-{{ $entityType }}').textContent = 'Selected: ' + this.files[0].name; document.getElementById('file-name-{{ $entityType }}').classList.remove('hidden');" />
                </label>
            </div>
            <p id="file-name-{{ $entityType }}" class="hidden text-sm text-green-600 font-medium text-center mb-4 bg-green-50 py-2 rounded-lg border border-green-100"></p>
            
            <div class="flex justify-end gap-3 mt-6">
                <button data-modal-close type="button"
                    class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 transition-colors sm:mt-0 sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex w-full justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 transition-colors sm:w-auto">
                    {{ $submitText }}
                </button>
            </div>
        </form>
    </div>
</x-my-modal>
