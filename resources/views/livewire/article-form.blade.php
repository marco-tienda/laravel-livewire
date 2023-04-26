<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New article') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-form-section submit="save">
                <x-slot name="title">{{ __('New article') }}</x-slot>
                <x-slot name="description">{{ __('Some description') }}</x-slot>

                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-4">
                        <x-select-image wire:model="image" :image="$image" :existing="$article->image" />
                        <x-input-error for="image" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-label :value="__('Title')" for="title"></x-label>
                        <x-input wire:model="article.title" type="text" id="title" class="mt-1 block w-full" />
                        <x-input-error for="article.title" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-label :value="__('Slug')" for="slug"></x-label>
                        <x-input wire:model="article.slug" type="text" id="slug" class="mt-1 block w-full" />
                        <x-input-error for="article.slug" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-label :value="__('Content')" for="content"></x-label>
                        <x-html-editor wire:model="article.content" id="content" class="mt-1 block w-full" />
                        <x-input-error for="article.content" class="mt-2" />
                    </div>

                    <x-slot name="actions">
                        <x-button>{{ __('Save') }}</x-button>
                    </x-slot>

                </x-slot>
            </x-form-section>
        </div>
    </div>
</div>
