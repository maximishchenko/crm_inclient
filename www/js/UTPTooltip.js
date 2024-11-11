/**
 * Created by Saida on 24.03.14.
 */
UTP = {};
UTP._htmlCache = {};
UTP.htmlToDOM = function (html)
{
    // Метод преобразования html в DOM-вершину
    var el = UTP._htmlCache[html];
    if (!el)
    {
        el = document.createElement('span');
        el._tmpSpan = true;
        el.innerHTML = html;
        UTP._htmlCache[html] = el.cloneNode(true);
    }
    else
        el = el.cloneNode(true);
    return el.firstChild;
};

UTP.addCSSClass = function(node, className)
{
    if(className)
    {
        var cl = node.className;
        if(!cl)
            node.className = className.trim();
        else if((cl.indexOf(className) == -1))
            node.className = (cl + ' ' + className).trim();
    }
};

UTP.removeEvent = function(node, type, handler)
{
    if (node.removeEventListener)
    {
        if (handler.length)
            node.removeEventListener(type, handler);
    }
    else if (node.detachEvent)
        node.detachEvent("on" + type, handler);
    else
        node["on" + type] = null;
};

UTP.addEvent = function(node, type, handler)
{
    if (node.addEventListener)
    {
        node.addEventListener(type, handler);
    }
    else if (node.attachEvent)
    {
        domNode.attachEvent("on" + type, handler);
    }
    else
        domNode["on" + type] = handler;
};

UTP.Tooltip = function(settings)
{
    if(!(this instanceof UTP.Tooltip))
        return new UTP.Tooltip(settings);
    this._Node = settings.Node || null;
    this._Content = settings.Content || null;
    this._Class = settings.Class ||'';
    this._Position = settings.Position ||'TopRight';
    this._Offset = settings.Offset || {X: 0, Y: 0};
    this._Tail = settings.Tail || true;
    this._render();
};

var ttProto = UTP.Tooltip.prototype;

ttProto._render = function()
{
    if(!this._Node)
        return;
    this._DomNode = UTP.htmlToDOM(
        '<div class="UTPTooltip' + (this._Class ? ' ' + this._Class : '') + '" >' +
            '<div class="TooltipContent"></div>' +
            '<div class="TailBorder"></div>' +
            '<div class="Tail"></div>' +
        '</div>');
    this._contentNode = this._DomNode.querySelector('.TooltipContent');
    this._tailBorderNode = this._DomNode.querySelector('.TailBorder');
    this._tailNode = this._DomNode.querySelector('.Tail');

    this._contentNode.innerHTML = this._Content;
    this._bind = {
        'mouseenter': this.show.bind(this),
        'mouseleave': this.hide.bind(this)
    };
    if(!this._Content && this._Node && this._Node.title)
        this._Content = this._Node.title;

    if(this._Content)
    {
        UTP.addEvent(this._Node, 'mouseenter', this._bind.mouseenter);
        UTP.addEvent(this._Node, 'mouseleave', this._bind.mouseleave);
    }
    this.show();
    this.hide();
};

ttProto.setContent = function(val)
{
    this._Content = val;
    this._contentNode.innerHTML = this._Content;
};

ttProto.refreshPosition = function()
{
    var tailClass,
        tailPos = { X: 0, Y: 0 },
        tailBorderPos = { X: 0, Y: 0 },
        pos = { X: 0, Y: 0 },
        nodeRect = this._Node.getBoundingClientRect(),
        domNodeRect = this._DomNode.getBoundingClientRect(),
        offset = {
            X: this._Offset.X,
            Y: this._Offset.Y
        };

    switch (this._Position)
    {
        case 'Top':
            pos.Y = nodeRect.top - domNodeRect.height - this._Offset.Y - (this._Tail ? 10 : 0);
            pos.X = nodeRect.left + this._Offset.X - 8;
            tailClass = 'Top';
            tailPos.Y = pos.Y  + domNodeRect.height - 2;
            tailBorderPos.Y = tailPos.Y + 2;
            tailBorderPos.X = tailPos.X = pos.X + 8;
            break;
        case 'TopLeft':
            pos.Y  = nodeRect.top - domNodeRect.height - this._Offset.Y - (this._Tail ? 10 : 0);
            pos.X = nodeRect.left - domNodeRect.height - this._Offset.X + (this._Tail ? 20 : 0);
            tailClass = 'Top';
            tailPos.Y = pos.Y  + domNodeRect.height - 2;
            tailBorderPos.Y = tailPos.Y + 2;
            tailBorderPos.X = tailPos.X = pos.X + domNodeRect.height - 24;
            break;
        case 'TopRight':
            pos.Y  = nodeRect.top - domNodeRect.height - this._Offset.Y - (this._Tail ? 9 : 0);
            pos.X = nodeRect.left + nodeRect.width + this._Offset.X - (this._Tail ? 28 : 0);
            tailClass = 'Top';
            tailPos.Y = pos.Y  + domNodeRect.height - 2;
            tailBorderPos.Y = tailPos.Y + 2;
            tailBorderPos.X = tailPos.X = pos.X + 8;
            break;
        case 'Left':
            pos.Y = nodeRect.top + this._Offset.Y;
            pos.X = nodeRect.left - domNodeRect.width - this._Offset.X - (this._Tail ? 10 : 0);
            tailClass = 'Left';
            tailPos.X = pos.X + domNodeRect.width - 2;
            tailBorderPos.X = tailPos.X + 2;
            tailPos.Y = tailBorderPos.Y = pos.Y + 2;
            break;
        case 'Right':
            pos.Y = nodeRect.top + this._Offset.Y;
            pos.X  = nodeRect.left + nodeRect.width + this._Offset.X + (this._Tail ? 10 : 0);
            tailClass = 'Right';
            tailPos.Y = tailBorderPos.Y = pos.Y + 2;
            tailPos.X = pos.X - 18;
            tailBorderPos.X = tailPos.X - 2;
            break;
        case 'BottomLeft':
            pos.Y = nodeRect.top + nodeRect.height + this._Offset.Y + (this._Tail ? 10 : 0);
            pos.X  = nodeRect.left - domNodeRect.width - this._Offset.X + (this._Tail ? 20 : 0);
            tailClass = 'Bottom';
            tailPos.Y = pos.Y - 19;
            tailBorderPos.Y = tailPos.Y - 2;
            tailBorderPos.X = tailPos.X = pos.X + this._DomNode.offsetWidth - 24;
            break;
        case 'BottomRight':
            pos.Y = nodeRect.top + nodeRect.height + this._Offset.Y + (this._Tail ? 10 : 0);
            pos.X  = nodeRect.left + nodeRect.width + this._Offset.X - (this._Tail ? 20 : 0);
            tailClass = 'Bottom';
            tailPos.Y = pos.Y - 19;
            tailBorderPos.Y = tailPos.Y - 2;
            break;
        default:
            pos.Y = nodeRect.top + nodeRect.height + offset.Y + (this._Tail ? 10 : 0);
            pos.X  = nodeRect.left + this._Offset.X;
            tailClass = 'Bottom';
            tailPos.Y = pos.Y - 19;
            tailBorderPos.Y = tailPos.Y - 2;
            break;
    }
    if(this._Tail)
    {
        UTP.addCSSClass(this._tailBorderNode, tailClass);
        UTP.addCSSClass(this._tailNode, tailClass);
        this._tailBorderNode.style.top =  tailBorderPos.Y + 'px';
        this._tailNode.style.top =  tailPos.Y + 'px';
        this._tailBorderNode.style.left =  tailBorderPos.X + 'px';
        this._tailNode.style.left =  tailPos.X + 'px';
    }
    this._DomNode.style.left = pos.X + 'px';
    this._DomNode.style.top = pos.Y + 'px';
};

ttProto.show = function(event)
{
    var parent = document.getElementsByTagName('body')[0];
    parent.insertBefore(this._DomNode, parent.firstChild);
    this._DomNode.style.display = 'inline';
    this.refreshPosition();
};

ttProto.hide = function()
{
    this._DomNode.style.display = 'none';
};

ttProto.remove = function()
{
    UTP.removeEvent(this._Node, 'mouseenter', this._bind.mouseenter);
    UTP.removeEvent(this._Node, 'mouseleave', this._bind.mouseleave);
};
ttProto = null;