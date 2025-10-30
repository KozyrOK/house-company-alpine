<div x-data="themeToggle" class="theme-toggle-button-group">
    <button
        @click="toggleDark"
        type="button"
        class="theme-toggle-button"
        :class="darkMode ? 'theme-toggle-button-dark' : 'theme-toggle-button-light'"
    >
        <span
            class="theme-toggle-button-circle"
            :class="darkMode ? 'translate-x-6' : 'translate-x-1'"
        ></span>
    </button>

    <div class="theme-toggle-button-text"
         :class="darkMode ? 'theme-toggle-button-text-dark' : 'theme-toggle-button-text-light'">
        <div class="theme-toggle-group-wrapper">
            <div
                x-show="!darkMode"
                x-transition.opacity
                class="theme-toggle-item">
                <span>☀️</span><span>{{ __('app.components.light_mode') }}</span>
            </div>
            <div
                x-show="darkMode"
                x-transition.opacity
                class="theme-toggle-item">
                <span>🌙</span><span>{{ __('app.components.dark_mode') }}</span>
            </div>
        </div>
    </div>
</div>

