<div x-data="localeSwitch">
    <button @click="toggleLocale" class="text-white hover:text-[var(--color-accent-hover)]">
        🌐 <span x-text="locale.toUpperCase()"></span>
    </button>
</div>
