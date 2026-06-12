@php
    $actor = auth()->user();
    $cityOptions = \App\Models\Company::query()
        ->when(!$actor?->isSuperAdmin(), fn ($query) => $query->where('id', currentCompany()?->id))
        ->whereNotNull('city')
        ->where('city', '!=', '')
        ->distinct()
        ->orderBy('city')
        ->pluck('city');
@endphp

<form method="GET" action="{{ url()->current() }}" class="content-item-wrapper">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <label class="value-content-item">
            <span class="block font-semibold">{{__('app.filters.city')}}</span>
            <select name="city" class="input-field">
                <option value="">{{__('app.filters.all_cities')}}</option>
                @foreach($cityOptions as $city)
                    <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                @endforeach
            </select>
        </label>

        <label class="value-content-item">
            <span class="block font-semibold">{{__('app.filters.status_company')}}</span>
            <select name="status_company" class="input-field">
                <option value="">{{__('app.filters.all_statuses')}}</option>
                @foreach(['active', 'deleted'] as $status)
                    <option value="{{ $status }}" @selected(request('status_company') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>

        <div class="flex flex-wrap gap-2 justify-center md:justify-end">
            <x-button text="app.buttons.apply" type="submit" class="button-list"/>
            <x-link text="app.buttons.reset" href="{{ url()->current() }}" class="button-delete"/>
        </div>

    </div>

</form>
