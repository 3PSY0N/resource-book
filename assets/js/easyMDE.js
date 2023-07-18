import '../css/easymde.css';
import EasyMDE from "easymde";

const marker = {
    name: 'marker',
    title: "Marker text",
    action: (editor) => {
        const cm = editor.codemirror;
        const selectedText = cm.getSelection();
        cm.replaceSelection('==' + selectedText + '==');
    },
    className: "fa fa-window-minimize"
};

const toolbar = [
    'bold', 'italic', 'strikethrough', '|',
    'heading', 'heading-smaller', 'heading-bigger', 'heading-1', 'heading-2', 'heading-3', 'code', '|',
    marker ,'quote', 'unordered-list', 'ordered-list', '|',
    'clean-block', 'link', 'image', 'table', 'horizontal-rule', '|',
    'undo', 'redo', '|',
    'side-by-side', 'fullscreen', 'preview'
]

const easyMDE = new EasyMDE({
    element: document.getElementById("article_content"),
    maxHeight: "550px",
    autosave: {
        enabled: false,
    },
    autoDownloadFontAwesome: false,
    spellChecker: false,
    lineWrapping: true,
    lineNumbers: true,
    placeholder: "Type here ..",
    toolbar: toolbar,
});
