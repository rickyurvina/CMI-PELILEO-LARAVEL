<div class="card-footer text-muted py-2">
    @if ($items->firstItem())

        {!! $items->withPath(request()->url())->appends(request()->except('page'))->links() !!}

    @else
        <div class="col-xs-12 col-sm-12 text-center">
            <small>{{ trans('general.no_records') }}</small>
        </div>
    @endif
</div>
