var gulp = require('gulp'),
    nodemon = require('gulp-nodemon'),
    jshint = require('gulp-jshint');

gulp.task('lint', function () {
  return gulp.src('server/**/*.js')
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('default'));
});

gulp.task('default', function () {
  nodemon({ script: 'server.js', ext: 'html js' })
    .on('change', ['lint'])
    .on('restart', function () {
      console.log('restarted!')
    })
});
