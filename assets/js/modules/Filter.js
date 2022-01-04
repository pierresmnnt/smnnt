/**
 * @property {HTMLFormElement} form
 * @property {HTMLElement} content
 * @property {HTMLElement} pagination
 * @property {number} page
 * @property {boolean} moreNav
 */
export default class Filter {
  /**
   * @param {HTMLElement|null} element
   * @param {boolean} hideButton
   */
  constructor(element, hideButton = true) {
    if (element === null) {
      return;
    }
    this.form = document.querySelector(".js-filter-form");
    this.content = document.querySelector(".js-filter-content");
    this.pagination = document.querySelector(".js-filter-pagination");
    this.page = parseInt(
      new URLSearchParams(window.location.search).get("page") || 1
    );
    this.moreNav = this.page === 1;
    this.bindEvents();
    if (hideButton) {
      this.hideSubmitButton();
    }
  }

  bindEvents() {
    const aClickListener = (e) => {
      if (e.target.tagName === "A") {
        e.preventDefault();
        this.loadUrl(e.target.getAttribute("href"));
      }
    };
    this.form.querySelectorAll("input").forEach((input) => {
      input.addEventListener("change", this.loadForm.bind(this));
    });
    if (this.moreNav) {
      this.pagination.innerHTML =
        "<button class='button-primary'>More</button>";
      this.pagination
        .querySelector("button")
        .addEventListener("click", this.loadmore.bind(this));
    } else {
      this.pagination.addEventListener("click", aClickListener);
    }
  }

  async loadmore() {
    const button = this.pagination.querySelector("button");
    button.setAttribute("disabled", "disabled");
    button.innerHTML = "Loading...";
    this.page++;
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    params.set("page", this.page);
    await this.loadUrl(url.pathname + "?" + params.toString(), true);
    button.removeAttribute("disabled");
    button.innerHTML = "More";
  }

  async loadForm() {
    this.page = 1;
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

  async loadUrl(url, append = false) {
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
      if (append) {
        this.content.innerHTML += data.content;
      } else {
        this.content.innerHTML = data.content;
      }
      if (!this.moreNav) {
        this.pagination.innerHTML = data.pagination;
      } else if (this.page === data.pageCount) {
        this.pagination.style.display = "none";
      } else {
        this.pagination.style.display = null;
      }
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
