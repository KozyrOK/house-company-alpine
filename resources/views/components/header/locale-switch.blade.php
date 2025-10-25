<div x-data="localeSwitch" x-init="init()" class="locale-switch">
    <button @click="toggleLocale" class="locale-switch-button">
        <span>🌐</span>
        <span x-text="locale.toUpperCase()"></span>
    </button>
</div>
