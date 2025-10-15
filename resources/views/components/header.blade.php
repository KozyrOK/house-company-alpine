<header class="header-wrapper">

    <div x-data="headerBackground" class="header-background"
         :style="{ backgroundImage: `url(${image})` }">
    </div>

    <div class="header-topbar">
        <x-login/>
        <x-locale-switch/>
        <x-theme-toggle/>
    </div>

</header>
