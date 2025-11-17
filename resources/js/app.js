import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

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
import allCompaniesList from './components/allCompaniesList.js';
import defaultContentBackground from "./components/defaultContentBackground.js";

Alpine.store('assets', {
    headerPattern,
    headerPatternDark,
    mainBackground,
    companyImage,
});

Alpine.data('themeToggle', themeToggle);
Alpine.data('localeSwitch', localeSwitch);
Alpine.data('mobileMenu', mobileMenu);
Alpine.data('confirmModal', confirmModal);
Alpine.data('loadingButton', loadingButton);
Alpine.data('headerBackground', headerBackground);
Alpine.data('companyImage', companyImageComponent);
Alpine.data('allCompaniesList', allCompaniesList);
Alpine.data('defaultContentBackground', defaultContentBackground);

Alpine.start();
