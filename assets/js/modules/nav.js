const showNav = (toggleId, navId) => {
  const toggle = document.getElementById(toggleId);
  const nav = document.getElementById(navId);

  if (toggle && nav) {
    toggle.addEventListener("click", () => {
      document.body.classList.toggle(`${navId}-is-open`);
      nav.classList.toggle(`${navId}-visible`);
      toggle.classList.toggle(`${navId}-closed`);
    });
  }
};

showNav("js-toggleBtn", "js-nav");
