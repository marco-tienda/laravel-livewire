<div>
    <x-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">Are you sure?</x-slot>
        <x-slot name="content">Do you want to delete the article: {{ $article->title }}?</x-slot>
        <x-slot name="footer">
            <x-button class="mr-auto" wire:click.prevent="$set('showDeleteModal', false)">{{ __('Cancel') }}</x-button>
            <x-danger-button wire:click.prevent="delete">{{ __('Confirm') }}</x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
