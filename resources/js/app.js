import './bootstrap';
import Alpine from 'alpinejs';

import headerPattern from '@/images/header-pattern.png';
import headerPatternDark from '@/images/header-pattern-dark.png';
import mainBackground from '@/images/main-background.jpg';
import companyImage from '@/images/default-image-company.jpg';

import themeToggle from './components/themeToggle.js';
import localeSwitch from './components/localeSwitch';
import mobileMenu from './components/mobileMenu';
import confirmModal from './components/confirmModal';
import loadingButton from './components/loadingButton';
import headerBackground from './components/headerBackground';
import companyImageComponent from './components/companyImage.js';

Alpine.store('assets', {
    headerPattern,
    headerPatternDark,
    mainBackground,
    companyImage,
});

window.Alpine = Alpine;
Alpine.data('themeToggle', themeToggle);
Alpine.data('localeSwitch', localeSwitch);
Alpine.data('mobileMenu', mobileMenu);
Alpine.data('confirmModal', confirmModal);
Alpine.data('loadingButton', loadingButton);
Alpine.data('headerBackground', headerBackground);
Alpine.data('companyImage', companyImageComponent);

Alpine.start();
