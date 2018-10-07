 /* Gulpfile.js
 /
 /  Last modification 07/10/2018
*/

"use strict";

// Packages variables
var gulp = require( "gulp" ),
    gHTMLMin = require( "gulp-htmlmin" ),
    gImageMin = require( "gulp-imagemin" ),
    gSass = require( "gulp-sass" ),
    gAutoPrefixer = require( "gulp-autoprefixer" ),
    gCSSComb = require( "gulp-csscomb" ),
    gCleanCSS = require( "gulp-clean-css" ),
    gESLint = require( "gulp-eslint" ),
    gBabel = require( "gulp-babel" ),
    jQuery = require( "jquery" ),
    jsSha1 = require( "js-sha1" ),
    gUglify = require( "gulp-uglify" ),
    gRename = require( "gulp-rename" ),
    gNotify = require( "gulp-notify" ),
    gPlumber = require( "gulp-plumber" ),
    browserSync = require( "browser-sync" ).create();

// Utilities variables
var sSrc = "src/",
    sDest = "build/",
    sProjectFolder = "myQG/",
    sTaskError = "",
    fPlumberError = function( sTaskError ) {
        return {
            title: "An error occured on " + sTaskError,
            message: "<%= error.message %>"
        }
    },
    oDependencies = {
        jQuery: {
            in: "node_modules/jquery/dist/jquery.min.js",
            out: sSrc + "vendors/scripts/"
        },
        jsSha1: {
            in: "node_modules/js-sha1/build/sha1.min.js",
            out: sSrc + "vendors/scripts/"
        }
    },
    oAssets = {
        in: sSrc + "assets/**/*",
        out: sDest + "assets/"
    },
    oVendors = {
        scripts: {
            in: sSrc + "vendors/scripts/**/*",
            out: sDest + "scripts/vendors/"
        }
    },
    oImg = {
        in: sSrc + "img_to_optim/**/*",
        out: sDest + "assets/img/"
    },
    oPHP = {
        in: sSrc + "php/**/*",  // all files to copy db.ini with it
        out: sDest,
        plumberOpts: {
            errorHandler: gNotify.onError( fPlumberError( sTaskError = "PHP" ) )
        }
    },
    oHTML = {
        in: sSrc + "**/*.html",
        out: sDest,
        minOpts: {
            collapseWhitespace: true,
            removeComments: true,
            minifyCSS: true,
            minifyJS: true
        },
        plumberOpts: {
            errorHandler: gNotify.onError( fPlumberError( sTaskError = "HTML" ) )
        }
    },
    oStyles = {
        in: sSrc + "sass/**/*.scss",
        out: sDest + "css/",
        sassOpts: {
            // outputStyle: "compressed", // Minify but overwritted by using csscomb, use clean-css to minify after csscomb pipe
            outputStyle: "expanded",
            precision: 3
        },
        autoPrefixOpts: {
            browsers: [ "last 2 versions" ]
        },
        plumberOpts: {
            errorHandler: gNotify.onError( fPlumberError( sTaskError = "Styles" ) )
        }
    },
    oScripts = {
        in: sSrc + "scripts/**/*.js",
        out: sDest + "scripts/",
        uglifyOpts: {
            mangle: {
                toplevel: true // Minify & obfuscate
            }
        },
        plumberOpts: {
            errorHandler: gNotify.onError( fPlumberError( sTaskError = "Scripts" ) )
        }
    },
    oRename = {
        minOpts: {
            suffix: ".min"
        }
    },
    oBrowserSync = {
        initOpts: {
            proxy: "http://localhost/" + sProjectFolder + sDest
        }
    };

// Copy jQuery script from node module directory
gulp.task( "jquery", function() {
    return gulp
        .src( oDependencies.jQuery.in )
        .pipe( gulp.dest( oDependencies.jQuery.out ) );
} );

// Copy js-sha1 script from node module directory
gulp.task( "js-sha1", function() {
    return gulp
        .src( oDependencies.jsSha1.in )
        .pipe( gulp.dest( oDependencies.jsSha1.out ) );
} );

// Assets tasks
gulp.task( "assets", function() {
    return gulp
        .src( oAssets.in )
        // Copy assets files into destination directory
        .pipe( gulp.dest( oAssets.out ) );
} );

// Vendors tasks
gulp.task( "vendors", function() {
    return gulp
        .src( oVendors.scripts.in )
        // Copy vendors scripts files into destination directory
        .pipe( gulp.dest( oVendors.scripts.out ) );
} );

// Images tasks
gulp.task( "img", function() {
    return gulp
        .src( oImg.in )
        // Optimize images
        .pipe( gImageMin() )
        .pipe( gulp.dest( oImg.out ) );
} );

// PHP tasks
gulp.task( "php", function() {
    return gulp
        .src( oPHP.in )
        .pipe( gPlumber( oPHP.plumberOpts ) ) // Don't stop watch task if an error occured
        .pipe( gulp.dest( oPHP.out ) );
} );

// HTML tasks
// gulp.task( "html", function() {
//     return gulp
//         .src( oHTML.in )
//         .pipe( gPlumber( oHTML.plumberOpts ) ) // Don't stop watch task if an error occured
//         // Minify HTML
//         .pipe( gHTMLMin( oHTML.minOpts ) )
//         .pipe( gulp.dest( oHTML.out ) );
// } );

// Styles tasks
gulp.task( "styles", function() {
    return gulp
        .src( oStyles.in )
        .pipe( gPlumber( oStyles.plumberOpts ) ) // Don't stop watch task if an error occured
        // Compile sass files
        .pipe( gSass( oStyles.sassOpts ) )
        // .pipe( gSass( oStyles.sassOpts ).on( "error", gSass.logError ) )
        // Add css prefixes
        .pipe( gAutoPrefixer( oStyles.autoPrefixOpts ) )
        // Format CSS coding style
        .pipe( gCSSComb() )
        // Minify
        .pipe( gCleanCSS() )
        // Add suffix .min before writting file
        .pipe( gRename( oRename.minOpts ) )
        .pipe( gulp.dest( oStyles.out ) );
} );

// Check es-lint
// gulp.task( "lint", function() {
//     return gulp
//         .src( oScripts.in )
//         .pipe( gESLint() )
//         .pipe( gESLint.format() );
// } );

// Scripts tasks
gulp.task( "scripts", function() {
    return gulp
        .src( oScripts.in )
        .pipe( gPlumber( oScripts.plumberOpts ) ) // Don't stop watch task if an error occured
        // Compile es2016-js files
        .pipe( gBabel() )
        // Minify & obfuscate JS
        .pipe( gUglify( oScripts.uglifyOpts ) )
        // Add suffix .min before writting file
        .pipe( gRename( oRename.minOpts ) )
        .pipe( gulp.dest( oScripts.out ) );
} );

// Browser-sync initialisation
gulp.task( "browser-sync", function() {
    browserSync.init( oBrowserSync.initOpts );
} );

// Watching files modifications & reload browser
gulp.task( "watch", function() {
    gulp.watch( oAssets.in, [ "assets" ] ).on( "change", browserSync.reload );
    gulp.watch( oImg.in, [ "img" ] ).on( "change", browserSync.reload );
    gulp.watch( oPHP.in, [ "php" ] ).on( "change", browserSync.reload );
    // gulp.watch( oHTML.in, [ "html" ] ).on( "change", browserSync.reload );
    gulp.watch( oStyles.in, [ "styles" ] ).on( "change", browserSync.reload );
    gulp.watch( oScripts.in, [ "scripts" ] ).on( "change", browserSync.reload );
} );

// Create command-line tasks
gulp.task( "get-dependencies", [ "jquery", "js-sha1" ] );

gulp.task( "default", [ "get-dependencies", "assets", "vendors", "img", "php", "styles", "scripts" ] );

gulp.task( "work", [ "default", "watch", "browser-sync" ] );
