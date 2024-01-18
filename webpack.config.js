const Encore = require('@symfony/webpack-encore');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    // .addEntry('bs-bundle', './assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js')
    // // .addEntry('bs-bundle-front', './assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js')
    // .addEntry('sb-admin', './assets/admin/js/sb-admin-2.min.js')
    // .addEntry('easy-jquery', './assets/admin/vendor/jquery-easing/jquery.easing.min.js')
    //front-js
    // .addEntry('swiper-bundle', './assets/front/vendor/swiper/swiper-bundle.min.js')
    // .addEntry('glightbox', './assets/front/vendor/glightbox/js/glightbox.min.js')
    // // .addEntry('glightbox-js', './node_modules/glightbox/dist/js/glightbox.js')
    // .addEntry('aos', './assets/front/vendor/aos/aos.js')
    // .addEntry('validate', './assets/front/vendor/php-email-form/validate.js')
    // .addEntry('main', './assets/front/js/main.js')

    .addEntry('front-js', [
        './assets/front/vendor/swiper/swiper-bundle.min.js',
        './assets/front/vendor/glightbox/js/glightbox.min.js',
        './assets/front/vendor/aos/aos.js',
        // './assets/front/vendor/php-email-form/validate.js',
        './assets/front/js/main.js',
        // Add more scripts as needed
    ])
    

    .addEntry('front-css', [
        './assets/front/vendor/swiper/swiper-bundle.min.css',
        './assets/front/vendor/glightbox/css/glightbox.min.css',
        './assets/front/vendor/aos/aos.css',
        './assets/front/css/main.css',
     
        // Add more scripts as needed
    ])
    .addEntry('admin-css', [
        './assets/admin/css/sb-admin-2.min.css',
        // Add more scripts as needed
    ])
    .addEntry('admin-js', [
        './assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js',
        // './assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js',
        './assets/admin/vendor/jquery-easing/jquery.easing.min.js',
        './assets/admin/js/sb-admin-2.min.js',
     
        // Add more scripts as needed
    ])

    // .addAliases({
    //     'glightbox': path.resolve(__dirname, 'assets/front/vendor/glightbox/js/glightbox.min.js')
    // })

   

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    // .enableSassLoader()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .autoProvidejQuery()

    .copyFiles({
                from: './assets/images/photos/',
        
                // optional target path, relative to the output dir
                to: 'images/photos/[path][name].[ext]',
        
                 // if versioning is enabled, add the file hash too
                //to: 'images/[path][name].[hash:8].[ext]',
        
                 // only copy files matching this pattern
             //pattern: /\.(png|jpg|jpeg)$/
             })
    .copyFiles({
                from: './assets/admin/img/',
        
                // optional target path, relative to the output dir
                to: '/img/[path][name].[ext]',
        
                 // if versioning is enabled, add the file hash too
                //to: 'images/[path][name].[hash:8].[ext]',
        
                 // only copy files matching this pattern
             //pattern: /\.(png|jpg|jpeg)$/
             })
    
    .addEntry('select2', './node_modules/select2/dist/js/select2.js') // Adjust the path as necessary
    .addStyleEntry('select2-css', './node_modules/select2/dist/css/select2.css') // Adjust the path as necessary
  


         
    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
