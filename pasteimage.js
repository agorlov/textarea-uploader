
/**
 * Paste image to textarea and send it to server
 *
 * Like in github comments.
 *
 * @author Artem Trushnskiy
 * @author Alexandr Gorlov
 */
class PasteImage {
    /**
     * Constructor.
     *
     * @param canvas element, where to show pasted image
     * @param textarea dom-element, where to listen event
     */
    constructor(canvas, textarea) {
        this.canvas = canvas; //document.getElementById("canvas_id");
        this.textarea = textarea; //document.getElementById("canvas_id");
        this.ctx = this.canvas.getContext("2d");
    }

    init(element) {
        var textarea = this.textarea;
        this.textarea.addEventListener('paste', event => {
            console.log(event.target);
            console.log(event);
            if (event.target == this.textarea) {
                this.pasteExtractData(event);
            }
        }, false);
    }

    pasteExtractData(event) {
        if (! event.clipboardData) {
            return;
        }

        let items = event.clipboardData.items;
        if (items[0].type.indexOf("image") !== -1) {
            let blob = items[0].getAsFile();
            let URLObj = window.URL || window.webkitURL;
            let source = URLObj.createObjectURL(blob);
            this.postPrintScreen(blob);
            this.pasteCreateImage(source);
            event.preventDefault();
        } else {
            console.log("clipboard doesn't contain image");
        }
    }

    pasteCreateImage(source) {
        let pastedImage = new Image();
        pastedImage.onload = () => {
            this.canvas.height = pastedImage.height / 2;
            this.canvas.width = pastedImage.width / 2;
            this.ctx.drawImage(pastedImage, 0, 0);
        }
        pastedImage.src = source;
    }

    postPrintScreen(blob) {
        let httpRequest = new XMLHttpRequest();
        httpRequest.open("POST", "/paste", true);
        httpRequest.setRequestHeader("X-PASTEIMAGE", "1");
        // httpRequest.setRequestHeader("Content-Type", "image/png");
        httpRequest.onload = (event) => {
            // console.log("upload: onload event", httpRequest.responseText);
            // console.log("textarea", this.textarea);

            // cursor position
            //if (this.textarea.selectionStart)

            var myLink = "![image]" + httpRequest.responseText;

            var cursorPos = this.textarea.selectionStart;

            // insert image link
            this.textarea.value =
                this.textarea.value.substr(0, this.textarea.selectionStart) +
                "\n" + myLink + "\n" +
                this.textarea.value.substr(this.textarea.selectionStart);

            // move cursor right after image link
            this.textarea.selectionEnd = cursorPos + myLink.length + 1;
        };
        httpRequest.send(blob);
    }
}
