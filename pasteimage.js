/**
 * Paste image and send it to server
 *
 * @author Artem Trushnskiy
 */
class PasteImage {
    constructor(canvas, textarea) {
        this.canvas = canvas; //document.getElementById("canvas_id");
        this.textarea = textarea; //document.getElementById("canvas_id");
        this.ctx = this.canvas.getContext("2d");
    }
    init(element) {
        this.textarea.addEventListener('paste', event => {
            console.log(event);
            this.pasteExtractData(event);
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
            console.error("clipboard doesn't contain not image");
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
            console.log("upload: onload event")
        };
        httpRequest.send(blob);
    }
}
