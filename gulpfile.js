const { src, dest, watch, parallel, series } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('autoprefixer');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');
const cssnano = require('cssnano');
const concat = require('gulp-concat');
const terser = require('gulp-terser-js');
const rename = require('gulp-rename');
const imagemin = require('gulp-imagemin');
const notify = require('gulp-notify');
const cache = require('gulp-cache');
const webp = require('gulp-webp');
const fs = require('fs');

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    imagenes: 'src/img/**/*'
}

function crearCarpetas(done) {
    const carpetas = [
        'src/scss',
        'src/js',
        'src/img',
        'public/build/css',
        'public/build/js',
        'public/build/img'
    ];

    carpetas.forEach(carpeta => {
        if (!fs.existsSync(carpeta)) {
            fs.mkdirSync(carpeta, { recursive: true });
            console.log(`Carpeta creada: ${carpeta}`);
        }
    });

    done();
}

function css() {
    return src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('./public/build/css'));
}

function javascript() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat('bundle.js'))
        .pipe(terser())
        .on('error', function(err) {
            console.error('Error en JS:', err.toString());
            this.emit('end');
        })
        .pipe(sourcemaps.write('.'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest('./public/build/js'));
}

function imagenes() {
    return src(paths.imagenes)
        .pipe(cache(imagemin({ optimizationLevel: 3 })))
        .pipe(dest('public/build/img'))
        .pipe(notify({ message: 'Imagen Completada' }));
}

function versionWebp() {
    return src(paths.imagenes)
        .pipe(webp())
        .pipe(dest('public/build/img'))
        .pipe(notify({ message: 'Imagen Completada' }));
}

function watchArchivos() {
    watch(paths.scss, css);
    watch(paths.js, javascript);
    watch(paths.imagenes, imagenes);
    watch(paths.imagenes, versionWebp);
}

exports.default = series(
    crearCarpetas,
    parallel(css, javascript, imagenes, versionWebp, watchArchivos)
);