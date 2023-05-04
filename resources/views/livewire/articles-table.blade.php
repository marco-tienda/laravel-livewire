<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between">
            <x-input wire:model="search" type="search" placeholder="Buscar..." />
            <a class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                href="{{ route('articles.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ __('New article') }}
            </a>
        </div>

        <section class="antialiased bg-gray-100 text-gray-600">
            <div class="flex flex-col justify-center mt-5">
                <!-- Table -->
                <div class="w-full mx-auto bg-white shadow-lg rounded-md border border-gray-200">
                    <div class="p-3">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full">
                                <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                    <tr>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">
                                                <button wire:click="sortBy('title')" class="flex items-center uppercase hover:underline">
                                                    Title
                                                    @if ($sortField === 'title')
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="w-5 h-5 ml-1 duration-200 @if($sortDirection === 'desc') rotate-180 @endif"
                                                        >
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">
                                                <button wire:click="sortBy('created_at')" class="flex items-center uppercase hover:underline">
                                                    Created at
                                                    @if ($sortField === 'created_at')
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="w-5 h-5"
                                                            class="w-5 h-5 ml-1 duration-200 @if($sortDirection === 'desc') rotate-180 @endif"
                                                        >
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-100">
                                    @foreach ($articles as $article)
                                        <tr>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3">
                                                        <img
                                                            class="rounded-full"
                                                            src="{{ $article->imageUrl() }}"
                                                            alt="{{ $article->title }}"
                                                        >
                                                    </div>
                                                    <div class="font-medium text-gray-600">
                                                        <a href="{{ route('articles.show', $article) }}">
                                                            {{ $article->title }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="text-left">{{ $article->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="text-left text-base font-medium text-indigo-500 flex space-x-2">
                                                    <a href="{{ route('articles.edit', $article) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                    </a>
                                                    <livewire:article-delete-modal wire:key="{{ 'article-delete-button'.$article->id }}" :article="$article">
                                                        <button wire:click="$emit('confirmArticleDeletion', {{ $article }})" class="mr-auto text-red-500 hover:text-red-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </livewire:article-delete-modal>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="px-4 py-3 bg-gray-50 border-t">
                                {{ $articles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
