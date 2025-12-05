import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

import headerPattern from '@/images/header-pattern.png';
import headerPatternDark from '@/images/header-pattern-dark.png';
import mainBackground from '@/images/main-background.jpg';
import companyImage from '@/images/default-image-company.jpg';

import themeToggle from './components/themeToggle.js';
import localeSwitch from './components/localeSwitch.js';
import mobileMenu from './components/mobileMenu.js';
import confirmModal from './components/confirmModal.js';
import loadingButton from './components/loadingButton.js';
import headerBackground from './components/headerBackground.js';
import companyImageComponent from './components/companyImage.js';
import defaultContentBackground from "./components/defaultContentBackground.js";
import adminUsersList from './components/adminUsersList.js';
import adminCompaniesList from './components/adminCompaniesList.js';
import adminPostsList from './components/adminPostsList.js';
import showCompany from './components/showCompany.js';
import companyLogoUploader from './components/companyLogoUploader.js';
import editCompany from "@/js/components/editCompany.js";

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
Alpine.data('defaultContentBackground', defaultContentBackground);
Alpine.data('adminUsersList', adminUsersList);
Alpine.data('adminCompaniesList', adminCompaniesList);
Alpine.data('adminPostsList', adminPostsList);
Alpine.data('showCompany', showCompany);
Alpine.data('companyLogoUploader', companyLogoUploader);
Alpine.data('editCompany', editCompany);

Alpine.start();
