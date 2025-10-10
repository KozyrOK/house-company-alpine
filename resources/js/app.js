import './bootstrap';
import Alpine from 'alpinejs';

import headerPattern from '@/images/header-pattern.png';
import headerPatternDark from '@/images/header-pattern-dark.png';

import themeToggle from './components/themeToggle';
import localeSwitch from './components/localeSwitch';
import mobileMenu from './components/mobileMenu';
import confirmModal from './components/confirmModal';
import loadingButton from './components/loadingButton';

Alpine.store('assets', {
    headerPattern,
    headerPatternDark,
});

window.Alpine = Alpine;
Alpine.data('themeToggle', themeToggle);
Alpine.data('localeSwitch', localeSwitch);
Alpine.data('mobileMenu', mobileMenu);
Alpine.data('confirmModal', confirmModal);
Alpine.data('loadingButton', loadingButton);

Alpine.start();
