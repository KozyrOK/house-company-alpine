@auth
    @php($companies = auth()->user()->isSuperAdmin() ? collect() : auth()->user()->companies()->where('companies.status_company', 'active')->wherePivot('status_membership', 'active')->orderBy('name')->get())
    @if($companies->count() > 1)
        <details class="company-switcher">
            <summary class="company-switcher-summary">{{__('app.layouts.company_switcher')}} ▼</summary>
            <div class="company-switcher-dropdown">
                @foreach($companies as $company)
                    <form method="POST" action="{{ route('companies.switch', $company) }}">
                        @csrf
                        <button type="submit" class="company-switcher-option">
                            {{ $company->name }}
                        </button>
                    </form>
                @endforeach
            </div>
        </details>
    @endif
@endauth
