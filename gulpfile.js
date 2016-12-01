var gulp = require('gulp');
var stylus = require('gulp-stylus');
var concat = require('gulp-concat');

const BOWER_DIR = './bower_components';
const NPM_DIR = './node_modules';

gulp.task('default', ['build']);
gulp.task('build', [
    'styles',
    'styles_vendor',
    'js_custom',
    'js_vendor',
    'bootstrap_fonts'
]);

//************************[FONTS]*********************************************
gulp.task('bootstrap_fonts', function () {
    return gulp.src(BOWER_DIR + '/bootstrap/fonts/**.*')
        .pipe(gulp.dest('web/fonts'));
});

//**********************[CSS]*****************************
gulp.task('styles', function () {
    return gulp.src([
        'assets/styles/**.*'
    ])
        .pipe(stylus())
        .on('error', error)
        .pipe(concat('styles.css'))
        .on('error', error)
        .pipe(gulp.dest('web/css'));
});

gulp.task('styles_vendor', function () {
    return gulp.src([
        BOWER_DIR + '/bootstrap/dist/css/bootstrap.min.css',
        BOWER_DIR + '/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        BOWER_DIR + '/datatables/media/css/dataTables.bootstrap.min.css'
    ])
        .pipe(concat('vendor.css', {
            rebaseUrls: false
        }))
        .on('error', error)
        .pipe(gulp.dest('web/css'));
});

//***********************[JAVASCRIPT]******************************************
gulp.task('js_custom', function () {
    return gulp.src([
        'assets/js/*.js'
    ])
        .pipe(concat('custom.js'))
        .pipe(gulp.dest('web/js'))
});

gulp.task('js_vendor', function () {
    return gulp.src([
        BOWER_DIR + '/jquery/dist/jquery.min.js',
        BOWER_DIR + '/bootstrap/dist/js/bootstrap.min.js',
        BOWER_DIR + '/datatables/media/js/jquery.dataTables.min.js',
        BOWER_DIR + '/datatables/media/js/dataTables.bootstrap.min.js',
        BOWER_DIR + '/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        NPM_DIR + '/moment/moment.js'
    ])
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('web/js'));
});


//***********************[CUSTOM FUNCTIONS]***************************************
function error(error) {
    console.log(error.toString());
    done();
}
