<div>
    <h1>Crear artículo</h1>

    <form wire:submit.prevent="save">
        <label for="">
            <input wire:model="title" type="text" placeholder="Título">
            @error('title') <div>{{ $message }}</div> @enderror
        </label>

        <label for="">
            <textarea wire:model="content" placeholder="Contenido"></textarea>
            @error('content') <div>{{ $message }}</div> @enderror
        </label>

        <input type="submit" value="Guardar">
    </form>
</div>
