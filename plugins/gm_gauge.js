//объект круговая шкала
//при создании собираем всю информацию с нашего списка значений
function Gauge(id, p) {
	var self=this,
		el=document.getElementById(id),
		styles=window.getComputedStyle(el, null);		//считываем стили
		minSide=null,
		insAngle=null,
		diffAngle=null,
		p=p || {};

	//для удобства сущности разносим в разные объекты
	//стили для рисования которые задаются в css
	this.style={
		'width': ~~styles.width.substr(0, styles.width.length-2),
		'height': ~~styles.height.substr(0, styles.height.length-2),
		'font': styles.fontFamily.substring(1, styles.fontFamily.length - 1),
		'fontSize': styles.fontSize,
		'textColor': styles.color
	};

	el.className='gauge';

	minSide=Math.min(this.style.width, this.style.height);		//вычисляем минимальную сторону для дефолтного радиуса
	//необязательные параметры, которые имеют значения по умолчанию
	p={
		'radius': p.radius || minSide/2*0.7,			//70% от 1/2 минимальной стороны
		'apert': p.apert || 225,						//апертура в градусах
		'insideText': p.insideText || false,			//текст внутри окружности (внутри / снаружи)
		'lineWidth': p.lineWidth || 2,				//ширина линии
		'color': p.color || '#51545B'					//цвет линии
	}

	insAngle=360-p.apert;					//исходя из заданной апертуры вычисляем обратный угол 
	diffAngle=(180-insAngle)/2;			//обратный угол делим попалам что бы уравнять углы относительно центра

	//объект тех.данных о нашей шкале 
	this.dataGauge={
		'el': el,
		'centerX': this.style.width/2,
		'centerY': this.style.height/2,
		'startAngle': (insAngle+diffAngle)*(Math.PI/180),		//стартовый угол в радианах
		'endAngle': diffAngle*(Math.PI/180),						//конечный угол
		'deg': 270
	};

	for (var key in p) this.dataGauge[key]=p[key];		//дополняем наш объект "необязательными" значениями

	this.data=[];		//объект с данными о значениях

	//анонимная функция, созданная для чистоты кода
	!function() {
		var	child=null,
			index=0,
			color=null;

		//обходим наш список значений, создавая из него массив данных (текст, цвет и текущий активный элемент)
		for (var i=0; i<el.childNodes.length; i++) {
			child=el.childNodes[i];
			if (child.nodeType==1) {
				color=child.getAttribute('data-color') || false;
				if (child.getAttribute('selected')!==null) self.dataGauge.selected=index;
				self.data.push({'text': child.innerHTML, 'color': color});
				index++;
			}
		}
	}();
};

//создаем тумблер (дополнительная плюшка для переключения значений)
//состоиt из заголовка, списка значений и кнопки действия
Gauge.prototype.createTumbler=function() {
	var el=this.dataGauge.el,
		tmb=document.createElement('div'),
		title=document.createElement('h3'),
		select=document.createElement('select'),
		btn=document.createElement('button');

	tmb.className="gaugeTumbler";
	title.appendChild(document.createTextNode('Kuda'));
	btn.appendChild(document.createTextNode('GO!'));

	for (var i=0; i<this.data.length; i++) {
		var child=this.data[i];
		var option=document.createElement('option');
		option.appendChild(document.createTextNode(child.text));
		option.value=child.angle;
		if (i==this.dataGauge.selected) option.selected=true;
		select.appendChild(option);
	}
	tmb.appendChild(title);
	tmb.appendChild(select);
	tmb.appendChild(btn);
	el.appendChild(tmb);

	this.tmb={
		'select': select,
		'button': btn,
		'tmb': tmb
	};
};

//создаем основной канвас для шкалы со значениями
Gauge.prototype.createCanvas=function() {
	var canvas=document.createElement('canvas');
	canvas.setAttribute('width', this.style.width);
	canvas.setAttribute('height', this.style.height);
	this.dataGauge.el.classList.add("gaugeCanvas");
	this.dataGauge.el.innerHTML='';
	this.dataGauge.el.appendChild(canvas);
	this.dataGauge.canvas=canvas;
};

//создаем канвас для стрелки
Gauge.prototype.createCanvasArrow=function() {
	var canvasArrow=document.createElement('canvas');
	canvasArrow.setAttribute('width', this.style.width);
	canvasArrow.setAttribute('height', this.style.height);
	canvasArrow.className="gaugeArrow";
	this.dataGauge.el.appendChild(canvasArrow);
	this.dataGauge.arrow=canvasArrow;
};

//рисуем недоокружность
//цвет и ширину линии берем из необязательных параметров
Gauge.prototype.drawArc=function() {
	var	d=this.dataGauge,
		ctx=d.canvas.getContext("2d");

	ctx.clearRect(0,0, this.style.width, this.style.height);
	ctx.beginPath();
	ctx.lineWidth=d.lineWidth;
	ctx.strokeStyle=d.color;
	ctx.arc(d.centerX, d.centerY, d.radius, d.startAngle, d.endAngle, false);
	ctx.stroke();
	ctx.closePath();
};

//рисуем саму шкалу засечек и заодно повторяем/учим школьный курс геометрии
Gauge.prototype.drawScale=function() {
	var d=this.dataGauge,
		count=this.data.length,
		ctx=d.canvas.getContext("2d"),
		sectorRad=(d.apert/(count-1))*(Math.PI/180),		//вычисляем сколько радиан в одном секторе
		i=null,
		x0=null,
		y0=null,
		x1=null,
		y1=null,
		sin=null,
		cos=null,
		xText=null,
		yText=null,
		dir=d.insideText ? -1 : 1,						//немного хитрая штука для определения расположения текста относительно окружности
		fPoint=20*dir,									//длина засечек и отступ текста
		lPoint=10*dir,
		tPoint=27*dir,
		firstAngle=d.startAngle,						//нужные углы на случай цветных отрезков
		nextAngle=firstAngle+sectorRad/2,
		middle=Math.floor(count/2),
		angle=null;

	ctx.beginPath();
	ctx.textBaseline="middle";
	ctx.fillStyle=this.style.textColor;
	ctx.font=this.style.fontSize+' '+this.style.font;
	ctx.textAlign="right";

	//обходим массив с данными, чтоб сразу можно было вытащить нужную информацию
	for (var i in this.data) {
		//рисуем засечки, вычисляя их координаты
		ctx.strokeStyle=d.color;
		ctx.lineWidth=d.lineWidth;
		angle=d.startAngle+sectorRad*i;
		cos=Math.cos(angle);
		sin=Math.sin(angle);
		x0=(cos*(d.radius+fPoint))+d.centerX;
		y0=(sin*(d.radius+fPoint))+d.centerY;
		x1=(cos*(d.radius+lPoint))+d.centerX;
		y1=(sin*(d.radius+lPoint))+d.centerY;

		this.data[i].angle=angle*(180/Math.PI);		//добовляем значение углов в наш массив данных

		//координаты для вывода значений
		xText=(cos*(d.radius+tPoint))+d.centerX;
		yText=(sin*(d.radius+tPoint))+d.centerY;

		ctx.moveTo(x0,y0);
		ctx.lineTo(x1,y1);

		//вычисляем значения радианов на случай если сектор цветной
		if (i==0) {
			firstAngle=d.startAngle;
			nextAngle=firstAngle+sectorRad/2;
		} else if (i==count-1) {
			firstAngle=nextAngle;
			nextAngle+=sectorRad/2;
		} else {
			firstAngle=nextAngle;
			nextAngle+=sectorRad;
		}

		//если у значения есть цвет, то рисуем (ра)дугу:
		if (this.data[i].color) {
			ctx.stroke();
			ctx.closePath();
			ctx.beginPath();
			ctx.lineWidth=4;
			ctx.strokeStyle=this.data[i].color;
			ctx.arc(d.centerX, d.centerY, d.radius, firstAngle, nextAngle, false);
			ctx.stroke();
			ctx.closePath();
			ctx.beginPath();
		}

		//меняем расположение текста в зависимости от текущего положения на шкале окружности (^^,)
		if (i<middle) ctx.textAlign=d.insideText ? "left" : "right";
		if (i==middle) ctx.textAlign="center";
		if (i>middle) ctx.textAlign=d.insideText ? "right" : "left";		

		ctx.fillText(this.data[i].text, xText, yText);
	};	//end for

	ctx.stroke();
	ctx.closePath();
};

//рисуем стрелку
Gauge.prototype.drawArrow=function() {
	var d=this.dataGauge,
		ctx=d.arrow.getContext("2d"),
		x=d.centerX,
		y=d.centerY;

	ctx.beginPath();
	ctx.lineWidth=1;
	ctx.fillStyle='#00C6FF';
	ctx.strokeStyle='#00C6FF';
	ctx.moveTo(x, y+3);
	ctx.lineTo(x+(d.radius/1.2), y);
	ctx.lineTo(x, y-3);
	ctx.lineTo(x, y+3);
	ctx.arc(x,y, 5, 0, 180, false);
	ctx.stroke();
	ctx.fill();
	ctx.closePath();
};

//поворачиваем стрелку на заданный угол в градусах (с анимацией css3)
Gauge.prototype.rotateArrow=function(deg) {
	var d=this.dataGauge,
		deg=deg || this.data[d.selected].angle;

	//хитрая штука для определения направления движения стрелки
	//но недостаточно хитрая, чтоб всегда работать правильно
	if (d.deg<deg) {
		deg=(360-deg)*(-1);
		d.deg=deg*(-1);
	} else d.deg=deg;

	d.arrow.style.transform='rotate('+deg+'deg)';
};

//прикручиваем обработчик для кнопки тумблера
Gauge.prototype.bindEvent=function() {
	var self=this;
	this.tmb.button.onclick=function() {
		var deg=self.tmb.select.options[self.tmb.select.selectedIndex].value;
		self.rotateArrow(deg);
	}
};

//автоматическая сборка в одной функции
Gauge.prototype.runDrawGauge=function() {
	this.createCanvas();
	this.createCanvasArrow();
	this.drawArc();
	this.drawScale();
	this.drawArrow();
	this.rotateArrow();
	this.createTumbler();
	this.bindEvent();
};
