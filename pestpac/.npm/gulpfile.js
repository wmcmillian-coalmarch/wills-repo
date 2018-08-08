'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sassGlob = require('gulp-sass-glob'),
    sassLint = require('gulp-sass-lint'),
    livereload = require('gulp-livereload'),
    del = require('del');
var notify = require("gulp-notify");
var sourcemaps = require('gulp-sourcemaps');

gulp.task('clean', function() {
    return del([
        '../public/css'
    ], {
        force: true
    });
});

gulp.task('sass', function() {
    gulp.src('../assets/sass/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sassGlob())
        .pipe(sass().on('error', notify.onError('<%= error.message %>')))
        .pipe(autoprefixer())
        .pipe(gulp.dest('../public/css'))
});

gulp.task('watch', function() {
    gulp.watch('../assets/sass/**/*.scss', ['sass']);
});

gulp.task('default', ['clean', 'sass', 'watch']);
