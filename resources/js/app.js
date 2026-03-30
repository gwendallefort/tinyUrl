import './bootstrap';
import { initTheme } from './theme';

initTheme();

document.querySelectorAll('.site-logo-a').forEach((siteNavBrand) => {
    const siteNavLogoPath = siteNavBrand.querySelector('.site-logo-path');
    if (!siteNavLogoPath) {
        return;
    }
    siteNavBrand.addEventListener('mouseenter', () => {
        siteNavLogoPath.classList.remove('anim-logo', 'anim-logo2');
        siteNavLogoPath.offsetWidth;
        siteNavLogoPath.classList.add(Math.random() < 0.5 ? 'anim-logo' : 'anim-logo2');
    });
    siteNavBrand.addEventListener('mouseleave', () => {
        siteNavLogoPath.classList.remove('anim-logo', 'anim-logo2');
    });
});
