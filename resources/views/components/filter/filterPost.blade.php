@php
    $actor = auth()->user();
    $companyOptions = collect();

    if ($actor?->isSuperAdmin()) {
        $companyOptions = \App\Models\Company::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
@endphp

<form method="GET" action="{{ url()->current() }}" class="content-item-wrapper">

    <div class="grid grid-cols-1 {{ $actor?->isSuperAdmin() ? 'md:grid-cols-3' : 'md:grid-cols-2' }} gap-4 items-end">

        <label class="value-content-item">
            <span class="block font-semibold">{{__('app.filters.status')}}</span>
            <select name="status" class="input-field">
                <option value="">{{__('app.filters.all_except_trash')}}</option>
                @foreach(['draft', 'future', 'pending', 'publish', 'trash'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>

        @if($actor?->isSuperAdmin())
            <label class="value-content-item">
                <span class="block font-semibold">{{__('app.filters.company')}}</span>
                <select name="company_id" class="input-field">
                    <option value="">{{__('app.filters.all_companies')}}</option>
                    @foreach($companyOptions as $company)
                        <option value="{{ $company->id }}" @selected((string) request('company_id') === (string) $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        <div class="flex flex-wrap gap-2 justify-center md:justify-end">
            <x-button text="app.buttons.apply" type="submit" class="button-list"/>
            <x-link text="app.buttons.reset" href="{{ url()->current() }}" class="button-delete"/>
        </div>

    </div>

</form>
