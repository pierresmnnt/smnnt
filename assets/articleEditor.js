import "./js/modules/editor";
import inputImgPreview from "./js/modules/inputImgPreview";

const imageInput = document.querySelector("input#article_heroImageUrl");
const imagePreview = document.querySelector("img#image_preview");

if (imageInput && imagePreview) {
  inputImgPreview(imageInput, imagePreview);
}
