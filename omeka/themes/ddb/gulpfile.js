var gulp = require('gulp');
var minify = require('gulp-minify');
let cleanCSS = require('gulp-clean-css');

gulp.task('compress', function() {
    gulp.src('javascripts/*.js')
        .pipe(minify({
            ext:{
                src:'.js',
                min:'.min.js'
            },
            exclude: ['vendor'],
            noSource: true,
            ignoreFiles: ['*.min.js', 'ddb.js', 'messages.js']
        }))
        .pipe(gulp.dest('javascripts'))
});

gulp.task('minify-css', () => {
    return gulp.src('css/*.css')
        .pipe(cleanCSS({
            debug: false
        }
    ))
    .pipe(gulp.dest('css/min'));
});