// const Encore = require("@symfony/webpack-encore");
// const WebPack = require('webpack');

// // Manually configure the runtime environment if not already configured yet by the "encore" command.
// // It's useful when you use tools that rely on webpack.config.js file.
// if (!Encore.isRuntimeEnvironmentConfigured()) {
//   Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
// }

// class WatchRunPlugin {
//   apply(compiler) {
//     compiler.hooks.watchRun.tap("WatchRun", (comp) => {
//       return;
//       console.log(comp.watchFileSystem.watcher);
//       const changedFiles = Object.keys(comp.watchFileSystem.watcher.mtimes)
//         .map((file) => `\n  ${file}`)
//         .join("");
//       if (changedFiles.length) {
//         console.log("====================================");
//         console.log("NEW BUILD FILES CHANGED:", changedFiles);
//         console.log("====================================");
//       }
//     });
//   }
// }

// Encore
//   // directory where compiled assets will be stored
//   .setOutputPath("public/build/")
//   // public path used by the web server to access the output path
//   .setPublicPath("/build")
//   // only needed for CDN's or sub-directory deploy
//   //.setManifestKeyPrefix('build/')

//   /*
//    * ENTRY CONFIG
//    *
//    * Each entry will result in one JavaScript file (e.g. app.js)
//    * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
//    */
//   .addEntry("main", "./frontend/main.ts")
//   .enableVueLoader()
//   // .enableBabelTypeScriptPreset({
//   //   onlyRemoveTypeImports: true
//   // })
//   .enableTypeScriptLoader(function (tsConfig) {
//     tsConfig.appendTsSuffixTo = [/\.vue$/];
//     tsConfig.appendTsxSuffixTo = [/\.vue$/];
//   })
//   .enableSassLoader()
//   .enablePostCssLoader()

//   // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
//   .enableStimulusBridge("./assets/controllers.json")

//   // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
//   .splitEntryChunks()

//   // will require an extra script tag for runtime.js
//   // but, you probably want this, unless you're building a single-page app
//   .enableSingleRuntimeChunk()

//   // .addPlugin(new WebPack.ProvidePlugin({
//   //   process: 'process/browser',
//   // }))

//   /*
//    * FEATURE CONFIG
//    *
//    * Enable & configure other features below. For a full
//    * list of features, see:
//    * https://symfony.com/doc/current/frontend.html#adding-more-features
//    */
//   .cleanupOutputBeforeBuild()
//   .enableBuildNotifications()
//   .enableSourceMaps(!Encore.isProduction())
//   // enables hashed filenames (e.g. app.abc123.css)
//   .enableVersioning(Encore.isProduction())

// // .configureBabel((config) => {
// //   config.plugins.push("@babel/plugin-proposal-class-properties");

// // }, {
// //   // includeNodeModules: ['@kabaliserv/wavify'],
// //   // exclude: /(node_modules|bower_components)/
// // });

// // enables @babel/preset-env polyfills
// // .configureBabelPresetEnv((config) => {
// //   config.useBuiltIns = "usage";
// //   config.debug = true;
// //   config.corejs = 3;
// // });
// // .configureWatchOptions((watchOptions) => {
// //         watchOptions.poll = 250;
// //         watchOptions.ignored = [
// //             "/../.git",
// //             "**.php",
// //             "tsconfig.json",
// //             "webpack.config.js",
// //             "public/plugins.json",
// //             "node_modules/**",
// //             "var/**",
// //             "public/",
// //             "*.log"
// //         ];
// //     });
// // enables Sass/SCSS support
// //.enableSassLoader()

// // uncomment if you use TypeScript
// //.enableTypeScriptLoader()

// // uncomment if you use React
// //.enableReactPreset()

// // uncomment to get integrity="..." attributes on your script & link tags
// // requires WebpackEncoreBundle 1.4 or higher
// //.enableIntegrityHashes(Encore.isProduction())

// // uncomment if you're having problems with a jQuery plugin
// //.autoProvidejQuery()
// let config = Encore.getWebpackConfig();

// config.plugins = [...config.plugins, new WatchRunPlugin()];
// config.watchOptions = {
//   ignored: [
//     "/../.git",
//     "**.php",
//     "tsconfig.json",
//     "webpack.config.js",
//     "public/plugins.json",
//     "node_modules/**",
//     "var/**",
//     "public/",
//     "*.log",
//   ],
// };
// config.watchOptions = {
//   poll: 300,
// };

// config.resolve.modules = ['node_modules'];
// // config.path = path.resolve(__dirname, 'frontend')
// // config.resolve.alias["@"] = path.resolve(__dirname, 'frontend');

// module.exports = config;
















const Encore = require('@symfony/webpack-encore');
const path = require('path');
const webpack = require('webpack');

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
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')
  .copyFiles({
    from: './frontend/assets/media',
    to: 'media/[path][name].[ext]',
    pattern: /.(png|jpg|jpeg|svg)$/
  })
  .copyFiles({
    from: './frontend/assets/fonts',
    to: 'fonts/[path][name].[ext]',
    pattern: /.(ttf)$/
  })
  /*
   * ENTRY CONFIG
   *
   * Add 1 entry for each "page" of your app
   * (including one that's included on every page - e.g. "app")
   *
   * Each entry will result in one JavaScript file (e.g. main.js)
   * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
   */
  .addEntry('main', './frontend/main.ts')

  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()

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

  // enables @babel/preset-env polyfills
  .configureBabel(() => {
  }, {
    useBuiltIns: 'usage',
    corejs: 3
  })

  // enables Sass/SCSS support
  .enableSassLoader()
  .enablePostCssLoader()
  // enables Vue support
  .enableVueLoader(() => {
  }, {
    version: 3,
    runtimeCompilerBuild: true //if using only single file components, this is not needed (https://symfony.com/doc/current/frontend/encore/vuejs.html#runtime-compiler-build)

  })
  // uncomment if you use TypeScript
  .enableTypeScriptLoader(function (tsConfig) {
    tsConfig.appendTsSuffixTo = [/\.vue$/];
    tsConfig.appendTsxSuffixTo = [/\.vue$/];
  })

  // uncomment if you're having problems with a jQuery plugin
  // .autoProvidejQuery()
  .addAliases({
    'frontend': path.resolve('./frontend')
  })
  ;
const config = Encore.getWebpackConfig()

config.module.rules = [
  ...config.module.rules,
  {
    test: /\.html$/i,
    loader: "html-loader",
  },
  // {
  //   test: /\.css$/i,
  //   use: ["style-loader", "css-loader"],
  // },
]

module.exports = config;