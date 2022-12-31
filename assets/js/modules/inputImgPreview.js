/**
 *
 * @param {HTMLInputElement} input
 * @param {HTMLElement} img
 */
export default function inputImgPreview(input, img) {
  console.log("input image preview ready");
  if (input.value !== "") {
    img.src = input.value;
  }

  input.addEventListener("change", (event) => {
    img.src = event.target.value;
  });
}
