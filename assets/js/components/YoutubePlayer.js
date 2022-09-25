import { playerStyle } from "./PlayerStyle.js";
// Inspired by grafikart.fr
/**
 * Instance de l'API youtube iframe
 * @type {null|YT}
 */
let YT = null;

/**
 * Element représentant une video youtube `<youtube-player video="UEINCHBN">`.
 * @property {ShadowRoot} root
 * @property {YT.Player} player
 */
export class YoutubePlayer extends HTMLElement {
  constructor(attributes = {}) {
    super();
    Object.keys(attributes).forEach((k) => this.setAttribute(k, attributes[k]));
    this.root = this.attachShadow({ mode: "open" });
    this.root.innerHTML = `
    <style>${playerStyle}</style>
    <div class="article__video-container">
        <div class="player">
            <div class="article__video-deny-msg">
                Le visionnage de cette vidéo est susceptible d'entraîner un dépôt de cookies de la part de l'opérateur de la plate-forme vidéo vers laquelle vous serez dirigé(e). Cliquez sur le buton ci-dessous si vous souhaitez continuer et lire la vidéo.
                <button class="yt_player_load">Lire la vidéo</button>
            </div>
        </div>
    </div>`;
    const onClick = () => {
      this.startPlay();
      this.removeEventListener("click", onClick);
    };
    this.root
      .querySelector(".yt_player_load")
      .addEventListener("click", onClick);
  }

  startPlay() {
    this.root
      .querySelector(".article__video-deny-msg")
      .setAttribute("aria-hidden", "true");
    this.loadPlayer(this.getAttribute("video"));
  }

  /**
   * @param {string} youtubeID
   * @return {Promise<void>}
   */
  async loadPlayer(youtubeID) {
    await loadYoutubeApi();
    if (this.player) {
      this.player.cueVideoById(youtubeID);
      this.player.playVideo();
      return;
    }
    this.player = new YT.Player(this.root.querySelector(".player"), {
      videoId: youtubeID,
      host: "https://www.youtube-nocookie.com",
      playerVars: {
        loop: 0,
        modestbranding: 1,
        controls: 1,
        showinfo: 0,
        rel: 0,
      },
    });
  }
}

/**
 * Charge l'API Youtube Player
 * @returns {Promise<YT>}
 */
async function loadYoutubeApi() {
  return new Promise((resolve) => {
    if (YT) {
      resolve(YT);
    }
    const tag = document.createElement("script");
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName("script")[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    window.onYouTubeIframeAPIReady = function () {
      YT = window.YT;
      window.onYouTubeIframeAPIReady = undefined;
      resolve(YT);
    };
  });
}

customElements.define("youtube-player", YoutubePlayer);
