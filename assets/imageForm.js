import cloneValue from "./js/modules/cloneInput";

const imageAltInput = document.querySelector("input#image_alt");
const imageDescInput = document.querySelector("input#image_description");

if (imageAltInput && imageDescInput) {
  cloneValue(imageDescInput, imageAltInput);
}
