<div>
    <h1 style="font-size: 3rem">Listado de artículos</h1>

    <input
        wire:model="search"
        type="search"
        placeholder="Buscar..."
    >

    <ul>
        @foreach ($articles as $article)
            <li>{{ $article->title }}</li>
        @endforeach
    </ul>
</div>
