<div x-data="themeToggle" x-init="init()" class="theme-toggle-button-group">
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

    <div class="theme-toggle-button-text" :class="darkMode ? 'theme-toggle-button-text-dark' : 'theme-toggle-button-text-light'">
        <template x-if="darkMode">
            <div><span>🌙</span><span> Dark Mode</span></div>
        </template>
        <template x-if="!darkMode">
            <div><span>☀️</span><span> Light Mode</span></div>
        </template>
    </div>
</div>


