import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import de from './locales/de.json';

// Get locale from Laravel (passed via Inertia or default to 'en')
const defaultLocale = document.documentElement.lang || 'en';

const i18n = createI18n({
    legacy: false, // Use Composition API mode
    locale: defaultLocale,
    fallbackLocale: 'en',
    messages: {
        en,
        de,
    },
    globalInjection: true, // Enable $t() in templates
});

export default i18n;
