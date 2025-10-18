'use strict';

const browserSync = require('browser-sync').create(),
  reload = browserSync.reload,
  gulp = require('gulp'),
  sass = require('gulp-dart-sass'),
  sourcemaps = require('gulp-sourcemaps'),
  csso = require('gulp-csso'),
  pump = require('pump'),
  uglify = require('gulp-uglify'),
  plumber = require('gulp-plumber'),
  autoprefixer = require('autoprefixer'),
  postcss = require('gulp-postcss'),
  cached = require("gulp-cached"),
  watch = require('gulp-watch');


const changed = require('gulp-changed');

var i;

gulp.task('browserSync-Local', () => {

  browserSync.init({
    logPrefix: "escala",
    open: false,

    notify: true,
    injectChanges: true,
    proxy: "https://luiexplica.dev/",
    files: ['dist/styles/**'],
    port: 3050,
    serveStatic: ["assets/css"],
    files: "assets/css/adswinStyles.css",
    snippetOptions: {
      rule: {
        match: /<\/head>/i,
        fn: function (snippet, match) {
          return '<link rel="stylesheet" type="text/css" href="/adswinStyles.css"/>' + snippet + match;
        }
      }
    }

  });

});

gulp.task('browserSync-Server', () => {

  browserSync.init({
    logPrefix: "escala",
    open: true,
    https: true,
    online: true,
    notify: true,
    port: 3100,
    injectChanges: true,
    proxy: "https://luiexplica.com/",
    files: ['dist/styles/**'],
    serveStatic: ["dist/styles"],
    files: "assets/css/adswinStyles.css",
    snippetOptions: {
      rule: {
        match: /<\/head>/i,
        fn: function (snippet, match) {
          return '<link rel="stylesheet" type="text/css" href="/adswinStyles.css"/>' + snippet + match;
        }
      }
    }

  });
});

gulp.task('sass', () => {
  return gulp.src('./src/styles/**/*.scss')

    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed',
      sourceMap: true,

      // maxConcurrency: 4,
      parallel: true
    }).on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(postcss([autoprefixer()]))
    .pipe(csso())
    .pipe(gulp.dest('./dist/styles'))
    .pipe(browserSync.stream());
});

gulp.task('sassGeneral', () => {
  return gulp.src('./src/styles/**/*.scss')

    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed',
      sourceMap: true,

      // maxConcurrency: 4,
      parallel: true
    }).on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(postcss([autoprefixer()]))
    .pipe(csso())
    .pipe(gulp.dest('./dist/styles'))
    .pipe(browserSync.stream());
});

gulp.task('js', () => {
  return gulp.src('./src/scripts/**/*.js')
    .pipe(watch('./src/scripts/**/*.js'))
    .pipe(plumber(
      // {errorHandler: errorScripts},
      function (error) {
        console.log(error);
        this.emit('end');
      }
    ))
    .pipe(uglify())
    .pipe(gulp.dest('./dist/scripts/'))
    .pipe(browserSync.stream());
});

gulp.task('compile-init', gulp.series(gulp.parallel('sassGeneral', 'js')));
gulp.task('watch', () => {

  gulp.watch("./src/styles/**/*.scss", gulp.series('sass'));

  gulp.watch("./src/scripts/**/*.js", gulp.series('js'));

});

gulp.task('local', gulp.series(gulp.parallel(
  'compile-init',
  'watch',
  'browserSync-Local'
)));

gulp.task('online', gulp.series(gulp.parallel(
  'compile-init',
  'watch',
  'browserSync-Server'
)));


