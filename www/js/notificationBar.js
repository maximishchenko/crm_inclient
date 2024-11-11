class NotificationBar {
    title = 'Заголовок';
    description = 'Описание';
    type = 'warning';
    _intervalId = null;
    _notificationBarElemDom = null;
    timeLife = 3200;

    constructor(options) {
        if (options) {
            this.title = options.title;
            this.description = options.description;
            this.icon = options.icon;
            this.type = options.type;
            if (options.timeLife && +options.timeLife) {
                this.timeLife = options.timeLife ;
            }
        }
    };

    // интервал жизни уведомления
    setIntervalLife = (time = 0) => {
        this._intervalId = setInterval(() => {
            clearInterval(this._intervalId);
            if (this._notificationBarElemDom) {
                this._notificationBarElemDom.remove();
            }
        }, time);
    };

    close = () => {
        this.setIntervalLife(0)
    };

    show = () => {
        this._notificationBarElemDom = document.createElement('div');
        this._notificationBarElemDom.classList.add('notification-bar', 'notification-' + this.type);

        let iconPath = '';
        switch (this.type) {
            case "success": {
                iconPath = "/img/gud.svg";
                break;
            }
            case "error": {
                iconPath = "/img/error_gud.svg";
                break;
            }
            case "warning": {
                iconPath = "/img/add_gud.svg";
                break;
            }
        }

        this._notificationBarElemDom.innerHTML = '' +
            '<div class="notification-bar-header">' +
                '<button>Закрыть</button>' +
            '</div>' +

            '<div class="notification-bar-message">' +
                '<div class="notification-icon">' +
                    '<img src="' + iconPath + '"/>' +
                '</div>' +

                '<div class="notification-content">' +
                    '<span class="notification-content-title">' + this.title + '</span>' +
                    '<span class="notification-content-description">' + this.description + '</span>' +
                '</div>' +
            '</div>';
        document.body.append(this._notificationBarElemDom);

        this._notificationBarElemDom.addEventListener('mouseover', this.handlerMouseover);
        this._notificationBarElemDom.addEventListener('mouseout', this.handlerMouseout);
        this._notificationBarElemDom.querySelector('button').addEventListener('click', this.setIntervalLife);
        this.setIntervalLife(this.timeLife);
    };

    handlerMouseover = () => {
        clearInterval(this._intervalId);
    };

    handlerMouseout = () => {
        this.setIntervalLife(this.timeLife);
    };
};

export {NotificationBar};