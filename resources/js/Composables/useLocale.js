import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';

export function useLocale() {
    const { locale, availableLocales, t } = useI18n();

    const currentLocale = computed({
        get: () => locale.value,
        set: (newLocale) => {
            if (availableLocales.includes(newLocale)) {
                locale.value = newLocale;
                localStorage.setItem('locale', newLocale);
                document.documentElement.setAttribute('lang', newLocale);
            }
        },
    });

    const supportedLocales = [
        { code: 'en', name: 'English', flag: '🇬🇧' },
        { code: 'de', name: 'Deutsch', flag: '🇩🇪' },
    ];

    const switchLocale = (newLocale) => {
        if (availableLocales.includes(newLocale)) {
            currentLocale.value = newLocale;

            // Update URL with new locale
            const currentPath = window.location.pathname;
            const pathSegments = currentPath.split('/').filter(Boolean);

            // Remove old locale from path if exists
            if (availableLocales.includes(pathSegments[0])) {
                pathSegments.shift();
            }

            // Add new locale
            const newPath = `/${newLocale}/${pathSegments.join('/')}`;

            // Navigate with Inertia
            router.visit(newPath, {
                preserveState: true,
                preserveScroll: true,
            });
        }
    };

    const getLocalizedPath = (path, localeCode = null) => {
        const targetLocale = localeCode || currentLocale.value;
        const cleanPath = path.replace(/^\/[a-z]{2}\//, '/');
        return `/${targetLocale}${cleanPath}`;
    };

    return {
        currentLocale,
        supportedLocales,
        switchLocale,
        getLocalizedPath,
        t,
    };
}
