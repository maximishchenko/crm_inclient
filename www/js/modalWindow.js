class ModalWindow {
    modalOverlayNode = null;
    modalBlockHeaderNode = null;
    modalBlockContentNode = null;
    modalContentHtmlNode = null;
    modalBlockFooterNode = null;
    modalContentMessageNode = null;
    htmlNode = null;

    //options
    title = null;
    handleCancel = null;
    handleConfirm = null;
    htmlContent = null;
    isValid = false;
    errorMessage = "";

    constructor(options = {}) {
        this.title = options.title ? options.title : '';
        this.handleCancel = options.handleCancel ? options.handleCancel : null;
        this.handleConfirm = options.handleConfirm ? options.handleConfirm : null;
        this.htmlContent = options.htmlContent ? options.htmlContent : '';
        this.isValid = options.isValid ? options.isValid : false;
        this.errorMessage = options.errorMessage ? options.errorMessage : "";

        this.htmlNode = document.documentElement;
        this.modalOverlayNode = document.getElementById('my-modal');
        this.modalBlockHeaderNode = document.getElementById('modal-block-header');
        this.modalBlockContentNode = document.getElementById('modal-block-content');
        this.modalContentHtmlNode = document.getElementById('modal-content-html');
        this.modalBlockFooterNode = document.getElementById('modal-block-footer');
        this.modalContentMessageNode = document.getElementById('modal-content-message');
    };

    init = () => {
        this.modalOverlayNode.addEventListener('click', this.prevCancel);
        // header
        let titleNode = this.modalBlockHeaderNode.querySelector('span');
        titleNode.textContent = this.title;

        let aNode = this.modalBlockHeaderNode.querySelector('a');
        aNode.onclick = this.cancel;

        //footer
        let btnOkNode = this.modalBlockFooterNode.querySelector('#btnOk');
        btnOkNode.onclick = this.confirm;
        btnOkNode.disabled = !this.isValid;

        let btnCancelNode = this.modalBlockFooterNode.querySelector('#btnCancel');
        btnCancelNode.onclick = this.cancel;

        //content
        this.modalContentMessageNode.innerHTML = this.errorMessage;
        this.modalContentHtmlNode.innerHTML = this.htmlContent;
    };

    prevCancel = (e) => {
        if (e.target.id === 'my-modal') {
            this.cancel();
        }
    };

    confirm = () => {
        if (this.handleConfirm) {
            this.handleConfirm();
        }

        this.hide();
    };

    cancel = () => {
        if (this.handleCancel) {
            this.handleCancel();
        }

        this.hide();
    };

    updateHtmlContent = () => {
        this.init();
    };

    show = () => {
        this.modalOverlayNode.classList.add('active');
        this.htmlNode.classList.add('html-custom');

        this.init();
    };

    hide = () => {
        this.modalOverlayNode.classList.remove('active');
        this.htmlNode.classList.remove('html-custom');
    };
}

export {ModalWindow};