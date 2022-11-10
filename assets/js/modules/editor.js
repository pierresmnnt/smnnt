const renderBtn = (a) => {
  const btn = document.createElement("button");
  btn.innerText = a;
  btn.dataset.action = a;
  btn.setAttribute("id", `control-${a}`);

  return btn;
};

const renderControls = () => {
  const div = document.createElement("div");
  div.classList.add("editor-controls");
  div.classList.add("card");

  const actionsDiv = document.createElement("div");
  mkdActions.forEach((element) => {
    actionsDiv.appendChild(renderBtn(element));
  });

  div.appendChild(actionsDiv);

  return div;
};

const action = (key, value) => {
  let v = value;
  switch (key) {
    case "h1":
      v = `# ${value}`;
      break;
    case "h2":
      v = `## ${value}`;
      break;
    case "bold":
      v = `**${value}**`;
      break;
    case "italic":
      v = `*${value}*`;
      break;
    case "link":
      v = `[${value}](path_URL)`;
      break;
    case "img":
      v = `![alt text](${value})`;
      break;
    case "video":
      v = `<youtube-player video="${value}"></youtube-player>`;
      break;
    case "furigana":
      v = `<kanji-furigana reading="〇〇">${value}</kanji-furigana>`;
      break;
    default:
      break;
  }
  return v;
};

const replaceWord = (string, start, end, newValue) => {
  return string.substr(0, start) + newValue + string.substr(end);
};

const onClick = (e) => {
  e.preventDefault();

  const a = e.target.dataset.action;
  const value = textarea.value;
  const selection = value.substring(
    textarea.selectionStart,
    textarea.selectionEnd
  );

  if (value === "" || selection === "") {
    return;
  }

  textarea.value = replaceWord(
    value,
    textarea.selectionStart,
    textarea.selectionEnd,
    action(a, selection)
  );
};

const textarea = document.getElementById("article_content");
const mkdActions = ["h2", "bold", "italic", "link", "img", "video", "furigana"];
const controls = renderControls();
const buttons = controls.querySelectorAll("button");
if (textarea) {
  textarea.before(controls);
}

buttons.forEach((element) => {
  element.addEventListener("click", onClick);
});
