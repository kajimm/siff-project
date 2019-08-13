const VueLoaderPlugin = require('vue-loader/lib/plugin');
const apiUrl = process.env.NODE_ENV === 'development' ? 'absoluta' : 'relativa';
module.exports = {
    entry: './resources/src/index.js',
    output: {
        path: __dirname + '/public/assets/js',
        filename: 'bundle.js'
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        }
    },
    watch: true,
    watchOptions: {
        ignored: ['public/assets/*.js', 'node_modules'],
        poll: 1000
    },
    module: {
        rules: [{
            test: /\.js$/,
            exclude: /node_modules/,
            use: {
                loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env']
                }
            }
        }, {
            test: /\.vue$/,
            loader: 'vue-loader',
            options: {
                hotReload: true
            }
        }]
    },
    plugins: [
        new VueLoaderPlugin()
    ]
};