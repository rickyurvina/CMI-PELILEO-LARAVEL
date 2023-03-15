@props(['method' => 'POST', 'action', 'hasFiles', 'card', 'urlBack' => url()->previous()])

<form action="{{ $action }}" method="{{ $method == 'get' ? 'get' : 'post' }}" {{ $attributes }} {!! isset($hasFiles) ? 'enctype="multipart/form-data"' : '' !!}>
    @if ($method != 'get')
        @csrf
    @endif

    @if (in_array(strtolower($method), ['put', 'patch', 'delete']))
        @method($method)
    @endif

    @if(isset($card))
        <div class="card shadow-5">
            <div class="card-body">
                {{ $slot }}
            </div>
            <x-form.footer urlBack="{{ $urlBack }}"/>
        </div>
    @else
        {{ $slot }}
    @endif
</form>