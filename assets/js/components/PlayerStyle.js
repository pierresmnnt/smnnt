export const playerStyle = `
    .article__video-container {
        position: relative;
        background-color: #2a303b;
        z-index: 4;
        clear: both;
        height: 360px;
        width: 100%;
        overflow: hidden;
    }

    .article__video-deny {
        position: absolute;
        inset: 0;
    }

    .article__video-deny-bg {
        background-color: #2a303b;
        background-size: cover;
        -moz-background-size: cover;
        -webkit-background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        filter: blur(2px);
        -webkit-filter: blur(2px);
    }

    .article__video-deny-msg {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-align: center;
        padding: 0 1.2rem;
    }

    .article__video-deny-msg span{
        padding: .6rem 1rem;
        border-radius: .3rem;
        background-color: rgba(0, 91, 133, .8);
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
        font-size: 1rem;
    }

    iframe,
    video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
`;
