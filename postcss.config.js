/** @type {import('postcss-load-config').Config} */
export default {
    plugins: {
        // MUST be first - resolves @import statements
        'postcss-import': {},
        // CSS nesting support (before autoprefixer)
        'postcss-nesting': {},
        // Add vendor prefixes (for custom CSS outside Tailwind)
        autoprefixer: {},
        // Production only: minify CSS
        ...(process.env.NODE_ENV === 'production'
            ? {
                  cssnano: {
                      preset: [
                          'default',
                          {
                              discardComments: {
                                  removeAll: true,
                              },
                          },
                      ],
                  },
              }
            : {}),
    },
};
