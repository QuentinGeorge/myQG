 /* Gulpfile.js
 /
 /  Last modification 03/08/2017
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
    oCopy = {
        in: sSrc + "only_copy_to_dest/**/*",
        out: sDest
    },
    oImg = {
        in: sSrc + "img_to_optim/**/*",
        out: sDest + "assets/img/"
    },
    oPHP = {
        in: sSrc + "**/*.php",
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
            proxy: "http://localhost/" + sProjectFolder
        }
    };

// Copy tasks
gulp.task( "copy", function() {
    return gulp
        .src( oCopy.in )
        // Copy files wich doesn't need any modifications into destination directory
        .pipe( gulp.dest( oCopy.out ) );
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
gulp.task( "lint", function() {
    return gulp
        .src( oScripts.in )
        .pipe( gESLint() )
        .pipe( gESLint.format() );
} );

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
    gulp.watch( oCopy.in, [ "copy" ] ).on( "change", browserSync.reload );
    gulp.watch( oImg.in, [ "img" ] ).on( "change", browserSync.reload );
    gulp.watch( oPHP.in, [ "php" ] ).on( "change", browserSync.reload );
    // gulp.watch( oHTML.in, [ "html" ] ).on( "change", browserSync.reload );
    gulp.watch( oStyles.in, [ "styles" ] ).on( "change", browserSync.reload );
    gulp.watch( oScripts.in, [ "lint", "scripts" ] ).on( "change", browserSync.reload );
} );

// Create command-line tasks
gulp.task( "default", [ "copy", "img", "php", "styles", "lint", "scripts" ] );

gulp.task( "work", [ "default", "watch", "browser-sync" ] );
