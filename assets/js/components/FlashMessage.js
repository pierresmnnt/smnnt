class FlashMessage extends HTMLElement {
  constructor() {
    super();
  }

  connectedCallback() {
    this.type = this.getAttribute("type");
    this.message = this.getAttribute("message");
    this.innerHTML = `<div class="alert alert-${this.type}">${this.message}</div>`;
    this.flash = this.querySelector(".alert");

    this.removeFlash();
  }

  removeFlash() {
    setTimeout(() => {
      this.flash.style.transition = ".8s";
      this.flash.style.opacity = 0;
      this.flash.ontransitionend = () => this.remove();
    }, 4000);
  }

  disconnectedCallback() {
    clearTimeout(this.removeFlash);
  }
}

customElements.define("flash-message", FlashMessage);
