var gulp = require( 'gulp' );
var sass = require( 'gulp-sass' );
var rename = require( 'gulp-rename' );
var cssmin = require( 'gulp-cssmin' );
var uglify = require( 'gulp-uglify' );
var livereload = require( 'gulp-livereload' );

gulp.task( 'sass', function() {
	return gulp.src( 'assets/scss/**/*.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( gulp.dest( 'assets/css' ) )
		.pipe( livereload() );
} );

gulp.task( 'css', [ 'sass' ], function() {
	return gulp.src( [ 'assets/css/**/*.css', '!assets/css/**/*.min.css' ] )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( cssmin() )
		.pipe( gulp.dest( 'assets/css' ) )
} );

gulp.task( 'js', function() {
	return gulp.src( [ 'assets/js/**/*.js', '!assets/js/**/*.min.js' ] )
		.pipe( uglify( {
			mangle: false
		} ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( 'assets/js' ) )
		.pipe( livereload() );
} );

gulp.task( 'default', [ 'css', 'js' ] );

gulp.task( 'watch', [ 'css', 'js' ], function() {
	livereload.listen();
	gulp.watch( [ 'assets/scss/**/*.scss' ], [ 'css' ] );
	gulp.watch( [ 'assets/js/**/*.js' ], [ 'js' ] );
} );