/**
 * Vitest Setup File
 *
 * This file runs before all tests and sets up the test environment.
 */

import { afterEach } from 'vitest';
import { cleanup } from '@testing-library/vue';

// Extend Vitest's expect with custom matchers if needed
// import { expect } from 'vitest';
// import { matchers } from '@testing-library/jest-dom';
// expect.extend(matchers);

// Cleanup after each test case (important for Vue Testing Library)
afterEach(() => {
    cleanup();
});

// Mock window.matchMedia for tests that use responsive features
Object.defineProperty(window, 'matchMedia', {
    writable: true,
    value: (query) => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: () => {}, // deprecated
        removeListener: () => {}, // deprecated
        addEventListener: () => {},
        removeEventListener: () => {},
        dispatchEvent: () => {},
    }),
});

// Mock IntersectionObserver for Alpine.js intersect plugin
global.IntersectionObserver = class IntersectionObserver {
    constructor() {}
    disconnect() {}
    observe() {}
    takeRecords() {
        return [];
    }
    unobserve() {}
};

// Mock ResizeObserver for responsive components
global.ResizeObserver = class ResizeObserver {
    constructor() {}
    disconnect() {}
    observe() {}
    unobserve() {}
};
