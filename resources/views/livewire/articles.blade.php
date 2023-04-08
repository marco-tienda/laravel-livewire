<div>
    <h1 style="font-size: 3rem">Listado de artículos</h1>
    <ul>
        @foreach ($articles as $article)
            <li>{{ $article->title }}</li>
        @endforeach
    </ul>
</div>
