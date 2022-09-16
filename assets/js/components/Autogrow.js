// Inspired by grafikart.fr

class Autogrow extends HTMLTextAreaElement {
  constructor() {
    super();
    this.onFocus = this.onFocus.bind(this);
    this.autogrow = this.autogrow.bind(this);
  }

  connectedCallback() {
    this.style.overflow = "hidden";
    this.autogrow();
    this.addEventListener("focus", this.onFocus);
  }

  onFocus() {
    this.autogrow();
    this.style.overflow = "hidden";
    this.addEventListener("input", this.autogrow);
    this.removeEventListener("focus", this.onFocus);
  }

  autogrow() {
    const height = this.scrollHeight;
    const scrollTop = window.scrollY;
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
    window.scrollTo(0, scrollTop);
  }
}

customElements.define("textarea-autogrow", Autogrow, { extends: "textarea" });
