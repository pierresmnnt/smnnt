/**
 * @property {HTMLFormElement} form
 * @property {HTMLElement} content
 * @property {HTMLElement} pagination
 */
export default class Filter {
  /**
   * @param {HTMLElement|null} element
   */
  constructor(element) {
    if (element === null) {
      return;
    }
    this.form = document.querySelector(".js-filter-form");
    this.content = document.querySelector(".js-filter-content");
    this.pagination = document.querySelector(".js-filter-pagination");
    this.bindEvents();
    this.hideSubmitButton();
  }

  bindEvents() {
    this.form.querySelectorAll("input").forEach((input) => {
      input.addEventListener("change", this.loadForm.bind(this));
    });
  }

  async loadForm() {
    const data = new FormData(this.form);
    const url = new URL(
      this.form.getAttribute("action") || window.location.href
    );
    const params = new URLSearchParams();
    data.forEach((value, key) => {
      params.append(key, value);
    });
    return this.loadUrl(url.pathname + "?" + params.toString());
  }

  async loadUrl(url) {
    this.showLoader();
    const params = new URLSearchParams(url.split("?")[1] || "");
    params.set("ajax", 1);
    const response = await fetch(url.split("?")[0] + "?" + params.toString(), {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });
    if (response.status >= 200 && response.status < 300) {
      const data = await response.json();
      this.content.innerHTML = data.content;
      params.delete("ajax");
      history.replaceState({}, "", url.split("?")[0] + "?" + params.toString());
    } else {
      console.error(response);
    }
    this.hideLoader();
  }

  showLoader() {
    this.form.classList.add("is-loading");
    const loader = this.form.querySelector(".js-loader");
    if (loader === null) {
      return;
    }

    loader.setAttribute("aria-hidden", false);
    loader.style.display = null;
  }

  hideLoader() {
    this.form.classList.remove("is-loading");
    const loader = this.form.querySelector(".js-loader");
    if (loader === null) {
      return;
    }

    loader.setAttribute("aria-hidden", true);
    loader.style.display = "none";
  }

  hideSubmitButton() {
    const button = this.form.querySelector(".form-submit");
    button.style.display = "none";
    button.firstElementChild.setAttribute("disabled", "disabled");
  }
}
