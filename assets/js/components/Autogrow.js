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
    this.addEventListener("input", this.autogrow);
    this.addEventListener("focus", this.onFocus);
  }

  onFocus() {
    this.autogrow();
    this.removeEventListener("focus", this.onFocus);
  }

  autogrow() {
    this.style.height = "auto";
    this.style.overflow = "hidden";
    this.style.height = this.scrollHeight + "px";
  }
}

customElements.define("textarea-autogrow", Autogrow, { extends: "textarea" });
