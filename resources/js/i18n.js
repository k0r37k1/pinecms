import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import de from './locales/de.json';

const messages = {
    en,
    de,
};

const i18n = createI18n({
    legacy: false, // Use Composition API
    locale: import.meta.env.VITE_APP_LOCALE || 'en',
    fallbackLocale: import.meta.env.VITE_APP_FALLBACK_LOCALE || 'en',
    messages,
    globalInjection: true,
    silentTranslationWarn: true,
    silentFallbackWarn: true,
});

export default i18n;
