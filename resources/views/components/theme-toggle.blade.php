<div x-data="themeToggle">
    <button @click="toggleDark" class="text-white hover:text-[var(--color-accent-hover)]">
        <template x-if="darkMode">🌙</template>
        <template x-if="!darkMode">☀️</template>
    </button>
</div>
