<div
    x-data="{ value: @entangle($attributes->wire('model')).defer }"
    x-on:trix-change="value = $event.target.value"
>
    <div wire:ignore>
        <div {!! $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
            <input type="hidden" id="trix-content">
            <trix-editor :value="value" input="trix-content"></trix-editor>
        </div>
    </div>
</div>
