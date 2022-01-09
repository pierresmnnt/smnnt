import fetchUrl from "./fetchUrl";

/**
 * @property {HTMLElement} content
 * @property {HTMLElement} pagination
 * @property {number} page
 * @property {boolean} moreNav
 */
export default class More {
  /**
   * @param {HTMLElement|null} element
   */
  constructor(element) {
    if (element === null) {
      return;
    }
    this.content = document.querySelector(".js-filter-content");
    this.pagination = document.querySelector(".js-filter-pagination");
    this.page = parseInt(
      new URLSearchParams(window.location.search).get("page") || 1
    );
    this.moreNav = this.page === 1;
    this.bindEvents();
  }

  bindEvents() {
    const aClickListener = (e) => {
      if (e.target.tagName === "A") {
        e.preventDefault();
        this.loadUrl(e.target.getAttribute("href"));
      }
    };
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

  async loadUrl(url, append = false) {
    const params = new URLSearchParams(url.split("?")[1] || "");
    params.set("ajax", 1);
    const data = await fetchUrl(url.split("?")[0] + "?" + params.toString());
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
  }
}
