/**
 *
 * @param {HTMLInputElement} from
 * @param {HTMLInputElement} to
 */
export default function cloneValue(from, to) {
  console.log("input clone ready");
  from.addEventListener("input", (e) => (to.value = e.target.value));
}
