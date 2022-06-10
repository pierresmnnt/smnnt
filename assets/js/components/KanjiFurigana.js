class KanjiFurigana extends HTMLElement {
  constructor() {
    super();
  }

  connectedCallback() {
    this.reading = this.getAttribute("reading");
    this.innerHTML = `<ruby>${this.innerText}<rt>${this.reading}</rt></ruby>`;
  }
}

customElements.define("kanji-furigana", KanjiFurigana);
