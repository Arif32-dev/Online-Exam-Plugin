/**
 * requirements for gulpfile to work
 * @param importer for gulp file
 */
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var rename = require("gulp-rename");
var cleanCSS = require('gulp-clean-css');


/**
 * path variables
 * @var path_includer
 */
var style_scss_files = './src/sass/*.scss';
var scss_compile_dest = './public/assets/css/';


/**
 * @function sass_compiler for scss file
 */
const sass_compiler = async () => {
    gulp.src(style_scss_files)
        .pipe(sourcemaps.init({
            loadMaps: true
        }))
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS({
            compatibility: 'ie8'
        }))
        .pipe(rename({
            suffix: ".min",
        }))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(scss_compile_dest))
}
const watch = () => {
    gulp.watch('./src/sass/**/*.scss', sass_compiler)
}
exports.default = watch;