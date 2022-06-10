/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// start the Stimulus application
import "./bootstrap";

import "./js/modules/nav";
import "./js/components/Autogrow";
import "./js/components/FlashMessage";
import "./js/components/KanjiFurigana";
import cloneValue from "./js/modules/cloneInput";

const imageAltInput = document.querySelector("input#image_alt");
const imageDescInput = document.querySelector("input#image_description");

if (imageAltInput && imageDescInput) {
  cloneValue(imageDescInput, imageAltInput);
}
