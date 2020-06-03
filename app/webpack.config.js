const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = (env, args) => {

    const mode = args.mode;

    return {
        stats: {
            children: false
        },
        node: {
            fs: 'empty'
        },
        entry: './index.js',
        module: {
            rules: [
                {
                    test: /\.js?/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                                presets: ['@babel/preset-env']
                        }
                    }
                },
                {
                    test: /\.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: { importLoaders: 1 }
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                config: {
                                    path: './postcss.config.js',
                                    ctx: {
                                        mode: mode
                                    }
                                }
                            }
                        }
                    ]
                },
                {
                    test: /\.(jpe?g|png|gif|svg|ico)$/i,
                    loader: 'file-loader?name=../images/[name].[ext]'
                }, {
                    test: /\.(ttf|woff|otf|woff2|eot)$/i,
                    loader: 'file-loader?name=../fonts/[name].[ext]'
                }
            ]
        },
        resolve: {
            extensions: ['*', '.js', '.jsx']
        },
        output: {
            path: __dirname,
            publicPath: './',
            filename: '../js/app.min.js',
            chunkFilename: './js/[name].component.js'
        },
        plugins: [
            new MiniCssExtractPlugin({
              filename: '../css/app.min.css',
              chunkFilename: '../css/[id].css'
            })
        ],
        optimization: {
            splitChunks: {
                chunks: 'async',
                minSize: 30000,
                maxSize: 0,
                minChunks: 1,
                maxAsyncRequests: 5,
                maxInitialRequests: 3,
                automaticNameDelimiter: '~',
                name: true,
                cacheGroups: {
                    default: {
                        minChunks: 2,
                        priority: -20,
                        reuseExistingChunk: true,
                        enforce: true
                    }
                }
            }
        }
    }
};
