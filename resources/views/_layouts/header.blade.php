<header class="header-wrapper">

    <div x-data="headerBackground" class="header-background"
         :style="{ backgroundImage: `url(${image})` }">
    </div>

    <div class="header-topbar">
        <x-auth.login-button-topbar/>
        <x-header.locale-switch/>
        <x-header.theme-toggle/>
    </div>

</header>
