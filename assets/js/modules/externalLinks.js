const anchors = document.querySelector("article").getElementsByTagName("a");
for (let i = 0; i < anchors.length; i++) {
  if (anchors[i].hostname != window.location.hostname) {
    anchors[i].setAttribute("rel", "nofollow noreferrer noopener external");
    anchors[i].setAttribute("target", "_blank");
  }
}
