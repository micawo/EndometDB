module.exports = ({ file, options, env }) =>  {

    return {

        plugins: {
            'postcss-import': {},
            'postcss-preset-env': {
                browsers: 'ie >= 11, last 2 versions'

            },
            'cssnano': (options.mode === 'production') ? {
                    discardUnused: {
                        keyframes: false
                    },
                    reduceIdents: {
                        keyframes: false
                    },
                    autoprefixer: false
            } : false
        }
    }
}
