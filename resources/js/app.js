import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

import headerPattern from '@/images/header-pattern.webp';
import headerPatternDark from '@/images/header-pattern-dark.webp';
import mainBackground from '@/images/main-background.webp';
import defaultCompanyLogo from '@/images/default-image-company.webp';

import themeToggle from './components/themeToggle.js';
import localeSwitch from './components/localeSwitch.js';
import mobileMenu from './components/mobileMenu.js';
import confirmModal from './components/confirmModal.js';
import loadingButton from './components/loadingButton.js';
import headerBackground from './components/headerBackground.js';
import companyImageComponent from './components/companyImageComponent.js';
import defaultContentBackground from './components/defaultContentBackground.js';
import showCompany from './components/showCompany.js';
import companyLogoUploader from './components/companyLogoUploader.js';

Alpine.store('assets', {
    headerPattern,
    headerPatternDark,
    mainBackground,
    defaultCompanyLogo,
});

Alpine.store('current', {
    companyId: null
});

Alpine.data('themeToggle', themeToggle);
Alpine.data('localeSwitch', localeSwitch);
Alpine.data('mobileMenu', mobileMenu);
Alpine.data('confirmModal', confirmModal);
Alpine.data('loadingButton', loadingButton);
Alpine.data('headerBackground', headerBackground);
Alpine.data('companyImageComponent', companyImageComponent);
Alpine.data('defaultContentBackground', defaultContentBackground);
Alpine.data('showCompany', showCompany);
Alpine.data('companyLogoUploader', companyLogoUploader);

Alpine.start();
