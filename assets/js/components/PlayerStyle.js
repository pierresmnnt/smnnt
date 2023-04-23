export const playerStyle = `
    .article__video-container {
        position: relative;
        background-color: #2a303b;
        z-index: 4;
        clear: both;
        width: 100%;
        overflow: hidden;
    }

    .article__video-container iframe {
        width: 100%;
        display: block;
    }

    .article__video-deny-msg {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-align: center;
        padding: 0 1.2rem;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 360px;
        position: relative
    }

    .article__video-deny-msg:before{
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        backdrop-filter: blur(2px);
    }

    .article__video-deny-msg span{
        padding: .6rem 1rem;
        border-radius: .3rem;
        background-color: #005b85;
        z-index: 4;
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
        z-index: 4;
    }
`;
