var gulp = require('gulp');
var minify = require('gulp-minify');

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
