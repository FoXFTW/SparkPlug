'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    cssmin = require('gulp-cssmin'),
    rename = require('gulp-rename'),
    prefix = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    imagemin = require('gulp-imagemin');

// Configure CSS tasks.
gulp.task('sass', function () {
    return gulp.src('resources/assets/scss/app.scss')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(prefix('last 2 versions'))
        .pipe(cssmin())
        .pipe(concat('app.css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/css'));
});

// Configure JS.
gulp.task('js', function () {
    return gulp.src('resources/assets/js/**/*.js')
        .pipe(uglify())
        .pipe(concat('app.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/js'));
});

// Configure image stuff.
gulp.task('images', function () {
    return gulp.src('resources/assets/img/**/*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest('public/img'));
});

gulp.task('copy', function () {
    return gulp.src('node_modules/font-awesome/fonts/*')
        .pipe(gulp.dest('public/fonts'));
});

gulp.task('watch', function () {
    gulp.watch('resources/assets/scss/**/*.scss', ['sass']);
    gulp.watch('resources/assets/js/**/*.js', ['js']);
});

gulp.task('default', ['sass', 'js', 'images', 'copy']);
