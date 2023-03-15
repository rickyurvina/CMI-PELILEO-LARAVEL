@foreach (session('flash_notification', collect())->toArray() as $message)
    @push('scripts_start')
        <script>
            Toast.fire({
                icon: "{{ $message['level'] }}",
                title: "{!! $message['message'] !!}"
            })
        </script>
    @endpush
@endforeach

{{ session()->forget('flash_notification') }}
