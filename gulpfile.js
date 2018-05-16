var gulp = require('gulp'),
	sass = require('gulp-sass'),
	concat = require('gulp-concat'),
	plumber = require('gulp-plumber'),
	notify = require('gulp-notify'),
	cssnano = require('gulp-cssnano'),
	flatten = require('gulp-flatten'),
	sourcemaps = require('gulp-sourcemaps'),
	uglify = require('gulp-uglify'),
	cssreplace = require('gulp-replace'),
	imagemin = require('gulp-imagemin');
	cache = require('gulp-cache');

var theme_location = './wp-content/themes/fxprotools-theme',
	config = {
		theme_sass: theme_location + '/assets/sass/**/*.scss',
		theme_js: theme_location + '/assets/js/theme/custom/**/*.js',
		theme_img: theme_location + '/assets/img/**/*',
		wp_uploads: `./wp-content/uploads/${new Date().getFullYear()}/**/*`,
		output: theme_location + '/assets'
	};

var imagemin_settings = [
		imagemin.gifsicle({interlaced: true}),
		imagemin.jpegtran({progressive: true}),
		imagemin.optipng({optimizationLevel: 5}),
		imagemin.svgo({
			plugins: [
				{removeViewBox: true},
				{cleanupIDs: false}
			]
		})
	];

// ------------
// THEME - SASS
// ------------
gulp.task('sass', function(){
	gulp.src(config.theme_sass)
		.pipe(plumber())
		.pipe(sass({
			outputStyle: 'compressed'
		}))
		.pipe(cssreplace('/*!', '/*'))
		.pipe(concat('theme.css'))
		.pipe(cssnano())
		.pipe(gulp.dest(config.output + '/css/theme'))
		.pipe(notify('SASS processed'));
});

// ----------
// THEME - JS
// ----------
gulp.task('js', function(){
	gulp.src(config.theme_js)
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(uglify({ mangle: true }))
		.pipe(concat('theme.js'))
		.pipe(sourcemaps.write(''))
		.pipe(gulp.dest(config.output + '/js/theme'))
		.pipe(notify('JS processed'));
});

// Default Task for watching sass
gulp.task('watch-sass', ['sass'], function(){
	gulp.watch(config.theme_sass, ['sass']);
});

// Default Task for watching js
gulp.task('watch-js', ['js'], function(){
	gulp.watch(config.theme_js, ['js']);
});

// Optimize theme images
gulp.task('opt-img', function(){
	gulp.src(config.theme_img)
		.pipe(imagemin(imagemin_settings, { verbose: true }))
		.pipe(gulp.dest(config.output + '/img'));
});

// Optimize wp-uploads
gulp.task('opt-wpimg', function(){
	gulp.src(config.wp_uploads)
		.pipe(imagemin(imagemin_settings, { verbose: true }))
		.pipe(gulp.dest(`./wp-content/uploads/${new Date().getFullYear()}`));
});

// Default Task for watching both sass/js
gulp.task('default', ['sass', 'js'], function(){
	gulp.watch(config.theme_sass, ['sass']);
	gulp.watch(config.theme_js, ['js']);
});

