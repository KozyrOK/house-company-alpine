@auth
    @php($companies = auth()->user()->isSuperAdmin() ? collect() : auth()->user()->companies()->orderBy('name')->get())
    @if($companies->count() > 1)
        <details class="company-switcher">
            <summary class="company-switcher-summary">Company Switcher ▼</summary>
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
