import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { router, usePage } from '@inertiajs/vue3';

export function useLocale() {
    const { locale, availableLocales } = useI18n();
    const page = usePage();

    // Get available locales from Laravel config (passed via Inertia)
    const supportedLocales = computed(() => {
        return page.props.supportedLocales || availableLocales;
    });

    // Current locale
    const currentLocale = computed(() => locale.value);

    // Switch locale (updates both frontend and backend)
    const switchLocale = async (newLocale) => {
        if (!supportedLocales.value.includes(newLocale)) {
            console.warn(`Locale "${newLocale}" is not supported`);
            return;
        }

        // Update frontend (vue-i18n)
        locale.value = newLocale;

        // Update backend (Laravel session)
        router.post(
            '/locale',
            { locale: newLocale },
            {
                preserveState: true,
                preserveScroll: true,
                only: ['locale'],
            },
        );
    };

    return {
        currentLocale,
        supportedLocales,
        switchLocale,
    };
}
