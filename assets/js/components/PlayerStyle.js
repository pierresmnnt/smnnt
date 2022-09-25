export const playerStyle = `
    .article__video-container {
        position: relative;
        background-color: #2a303b;
        z-index: 4;
        clear: both;
        height: 360px;
        width: 100%;
    }

    .article__video-deny-msg {
        position: absolute;
        top: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-align: center;
        padding: 0 1.2rem;
    }

    .yt_player_load {
        display: inline-block;
        background-color: #005b85;
        border: 0;
        border-radius: .3rem;
        color: #fff;
        cursor: pointer;
        padding: .6rem 1rem;
        margin-top: 1.2rem;
    }

    iframe,
    video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
`;
