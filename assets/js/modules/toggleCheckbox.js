/**
 * @param {HTMLInputElement} master
 * @param {HTMLInputElement} pupil
 */
export default function toggleCheckbox(master, pupil) {
  pupil.disabled = master.checked;

  master.addEventListener("change", (e) => {
    pupil.disabled = e.target.checked;
    if (e.target.checked) {
      pupil.checked = false;
    }
  });
}
