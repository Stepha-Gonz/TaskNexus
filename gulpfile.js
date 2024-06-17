import path from "path";
import fs from "fs";
import { glob } from "glob";
import { src, dest, watch, series } from "gulp";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import concat from "gulp-concat";
import terser from "gulp-terser";
import sharp from "sharp";
import rename from "gulp-rename";

const sass = gulpSass(dartSass);

const paths = {
  scss: "src/scss/**/*.scss",
  js: "src/js/**/*.js",
  img: "src/img/**/*",
};

function handleError(err) {
  console.error(err.toString());
  this.emit("end");
}

export function css() {
  return src(paths.scss, { sourcemaps: true })
    .pipe(sass({ outputStyle: "compressed" }).on("error", handleError))
    .pipe(dest("./public/build/css", { sourcemaps: "." }))
    .on("error", handleError);
}

export function js() {
  return src(paths.js)
    .pipe(concat("bundle.js").on("error", handleError))
    .pipe(terser().on("error", handleError))
    .pipe(rename({ suffix: ".min" }).on("error", handleError))
    .pipe(dest("./public/build/js"))
    .on("error", handleError);
}

export async function imagenes() {
  try {
    const srcDir = "./src/img";
    const buildDir = "./public/build/img";
    const images = await glob(paths.img);

    for (const file of images) {
      const relativePath = path.relative(srcDir, path.dirname(file));
      const outputSubDir = path.join(buildDir, relativePath);
      await procesarImagenes(file, outputSubDir);
    }
  } catch (err) {
    handleError.call(this, err);
  }
}

async function procesarImagenes(file, outputSubDir) {
  try {
    if (!fs.existsSync(outputSubDir)) {
      fs.mkdirSync(outputSubDir, { recursive: true });
    }
    const baseName = path.basename(file, path.extname(file));
    const extName = path.extname(file);

    if (extName.toLowerCase() === ".svg") {
      const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
      fs.copyFileSync(file, outputFile);
    } else {
      const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
      const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
      const outputFileAvif = path.join(outputSubDir, `${baseName}.avif`);
      const options = { quality: 80 };

      await sharp(file).jpeg(options).toFile(outputFile).catch(handleError);
      await sharp(file).webp(options).toFile(outputFileWebp).catch(handleError);
      await sharp(file).avif().toFile(outputFileAvif).catch(handleError);
    }
  } catch (err) {
    handleError.call(this, err);
  }
}

export function dev() {
  watch(paths.scss, css).on("error", handleError);
  watch(paths.js, js).on("error", handleError);
  watch(paths.img, imagenes).on("error", handleError);
}

export const build = series(js, css, imagenes);
export default dev;
