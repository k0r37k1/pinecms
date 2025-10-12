<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $t('app.name') }}</h1>
                <p class="text-lg text-gray-600 mb-6">{{ $t('app.tagline') }}</p>

                <!-- Locale Switcher Demo -->
                <div class="flex justify-center gap-2">
                    <button
                        v-for="locale in supportedLocales"
                        :key="locale"
                        :class="[
                            'px-4 py-2 rounded-lg transition-colors',
                            currentLocale === locale
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50',
                        ]"
                        @click="switchLocale(locale)"
                    >
                        {{ locale.toUpperCase() }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useLocale } from '@/Composables/useLocale';

const { currentLocale, supportedLocales, switchLocale } = useLocale();
</script>
