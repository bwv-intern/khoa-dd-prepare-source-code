@props(['breadcrumbs'])
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    @foreach ($breadcrumbs as $breadcrumbTitle => $link)
                        @if ($loop->last)
                            <li class="breadcrumb-item active">
                                {{ $breadcrumbTitle }}</li>
                        @else
                            <li class="breadcrumb-item"><a
                                    href="{{ $link }}">{{ $breadcrumbTitle }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div>
            <div class="col-sm-6"></div>
        </div>
    </div>
</div>
