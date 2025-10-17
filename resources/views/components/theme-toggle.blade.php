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
        <div class="relative w-28 h-5 flex items-center justify-start overflow-hidden">
            <div
                x-show="!darkMode"
                x-transition.opacity
                class="absolute inset-0 flex items-center space-x-1">
                <span>☀️</span><span>Light Mode</span>
            </div>
            <div
                x-show="darkMode"
                x-transition.opacity
                class="absolute inset-0 flex items-center space-x-1">
                <span>🌙</span><span>Dark Mode</span>
            </div>
        </div>
    </div>
</div>

