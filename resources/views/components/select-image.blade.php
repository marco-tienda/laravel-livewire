<div class="relative">
    @php($id = $attributes->wire('model')->value)
    @if ($image instanceof Livewire\TemporaryUploadedFile)
        <x-danger-button wire:click="$set('{{ $id }}')" class="absolute bottom-2 right-2">
            {{ __('Change Image') }}
        </x-danger-button>
        <img src="{{ $image->temporaryUrl() }}" alt="" class="border-2 rounded">
    @elseif ($existing)
        <x-label :value="__('Select Image')" :for="$id"
            class="absolute bottom-2 right-2 cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        </x-label>
        <img src="{{ asset('storage/' . $existing) }}">
    @else
        <div class="h-32 bg-gray-50 border-2 border-dashed rounded flex items-center justify-center">
            <x-label :value="__('Select Image')" :for="$id"
                class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            </x-label>
        </div>
    @endif
    <x-input wire:model="{{ $id }}" type="file" :id="$id" class="hidden" />
</div>
