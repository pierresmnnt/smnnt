import "./js/modules/editor";
import inputImgPreview from "./js/modules/inputImgPreview";
import toggleCheckbox from "./js/modules/toggleCheckbox";

const publishedInput = document.getElementById("article_published");
const privateInput = document.getElementById("article_privateAccess");
const imageInput = document.querySelector("input#article_heroImageUrl");
const imagePreview = document.querySelector("img#image_preview");

if (publishedInput && privateInput) {
  toggleCheckbox(publishedInput, privateInput);
}

if (imageInput && imagePreview) {
  inputImgPreview(imageInput, imagePreview);
}
