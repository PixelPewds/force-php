@if (session('success'))
    <div class="alert alert-success alert-dismissible text-white mt-2" role="alert">
        <span class="text-sm">{{ session('success') }}</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif


@if (session('danger'))
    <div class="alert alert-danger alert-dismissible text-white mt-2" role="alert">
        <span class="text-sm">{{ session('danger') }}</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif


@if (session('warning'))
    <div class="alert alert-warning alert-dismissible text-white mt-2" role="alert">
        <span class="text-sm">{{ session('warning') }}</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if (session('errors'))
    <div class="alert alert-warning alert-dismissible text-white mt-2" role="alert">
        <span class="text-sm">{{ session('warning') }}</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
<br>