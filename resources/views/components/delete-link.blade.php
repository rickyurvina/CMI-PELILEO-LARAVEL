@props(['action', 'id', 'text' => trans('messages.warning.delete')])

<form action="{{ $action }}" method="post">
    @csrf
    @method('delete')
    <button class="dropdown-item" id="btn-{{ $id }}">
        <i class="fas fa-trash mr-1 text-danger"></i> <span>{{ trans('general.delete') }}</span>
    </button>
</form>
@push('page_script')
    <script>
        $('#btn-{{ $id }}').on('click', (e) => {
            e.preventDefault();
            let $form = e.currentTarget.form;
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ $text }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                if (result.value) {
                    $form.submit();
                }
            });
        })
    </script>
@endpush