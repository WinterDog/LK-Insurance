/*
	Здесь находятся расширяющие методы для класса Date, которые необходимы для работы шахматки, а также функции для создания шапки-календаря.
*/

/*
	Разница между датами в месяцах (без округлений и т. п.). За точку отсчёта берётся параметр, т. е. если this-дата позже даты в параметре, то результат будет положительным, в противном случае - отрицательным.
*/
Date.prototype.monthsDiff = function (date)
{
	var months;

	months = (this.getFullYear() - date.getFullYear()) * 12;
	months += this.getMonth();
	months -= date.getMonth();

	return months;
}

/*
	Разница между датами в днях (без округлений и т. п.). За точку отсчёта берётся параметр, т. е. если this-дата позже даты в параметре, то результат будет положительным, в противном случае - отрицательным.
*/
Date.prototype.daysDiff = function (date)
{
	var utc_this = Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()),
		utc_date = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());

	return Math.floor((utc_this - utc_date) / (24 * 60 * 60 * 1000));
}

/*
	Получение количества дней в месяце.
*/
Date.prototype.daysInMonth = function ()
{
	var last_day = new Date(this);

	last_day.setDate(1);
	last_day.setMonth(this.getMonth() + 1);
	last_day.setDate(0);

	return last_day.getDate();
}

/*
	Получение даты (числа месяца) из двух цифр.
*/
Date.prototype.getDateDD = function ()
{
	var day = this.getDate();
	if (day < 10)
		day = '0' + day;
	return day;
}

/*
	Получение номера месяца из двух цифр (от 1 до 12).
*/
Date.prototype.getMonthDD = function ()
{
	var month = this.getMonth() + 1;
	if (month < 10)
		month = '0' + month;
	return month;
}

/*
	Получение часа из двух цифр.
*/
Date.prototype.getHoursDD = function ()
{
	var hours = this.getHours();
	if (hours < 10)
		hours = '0' + hours;
	return hours;
}

/*
	Получение минут из двух цифр.
*/
Date.prototype.getMinutesDD = function ()
{
	var minutes = this.getMinutes();
	if (minutes < 10)
		minutes = '0' + minutes;
	return minutes;
}

/*
	Номер недели от начала года (не доделано, пока не используется).
*/
Date.prototype.getWeekNumber = function ()
{
	var onejan = new Date(this.getFullYear(), 0, 1);

	return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
}

/*
	Округление текущего дня до полуночи.
*/
Date.prototype.round = function ()
{
	this.setHours(0, 0, 0, 0);
	return this;
}

Date.prototype.addYears = function (year)
{
	this.setFullYear(this.getFullYear() + parseInt(year));
	return this;
}

Date.prototype.addMonths = function (months)
{
	this.setMonth(this.getMonth() + parseInt(months));
	return this;
}

Date.prototype.addDays = function (days)
{
	this.setDate(this.getDate() + parseInt(days));
	return this;
}

Date.prototype.addHours = function (hours)
{
	this.setHours(this.getHours() + parseInt(hours));
	return this;
}

/*
	Проверка строки str на соответствие формату даты ДД.ММ.ГГГГ и адекватность этой самой даты. Адекватность значения проверяется поверхностно, день месяца может быть некорректным.
*/
function php_date2js_date(
	str)
{
	var date = new Date(),
		str_array = str.trim().split('.');

	if (str_array.length != 3)
		return false;

	str_array[0] = +str_array[0];
	str_array[1] = +str_array[1];
	str_array[2] = +str_array[2];

	if ((!str_array[0]) || (str_array[0].length < 1) || (str_array[0].length > 2) || (str_array[0] < 1) || (str_array[0] > 31))
		return false;

	if ((!str_array[1]) || (str_array[1].length < 1) || (str_array[1].length > 2) || (str_array[1] < 1) || (str_array[1] > 12))
		return false;

	if ((!str_array[2]) || (str_array[2].length < 2) || (str_array[2].length > 4) || (str_array[2] < 1900) || (str_array[2] > 2200))
		return false;

	date.setFullYear(str_array[2], str_array[1] - 1, 1);
	if (str_array[0] > date.daysInMonth())
		return false;

	date.setDate(str_array[0]);

	return date;
}

function php_date2db_date(
	str)
{
	var str_array = str.trim().split('.'),
		db_array = [];

	db_array[0] = +str_array[2];
	db_array[1] = +str_array[1];
	db_array[2] = +str_array[0];

	return db_array.join('-');
}

function is_cor_time(
	str)
{
	var time_array = str.trim().split(':');

	if (time_array.length != 2)
		return false;

	if ((time_array[0].length < 1) || (time_array[0].length > 2) || (time_array[0] < 0) || (time_array[0] > 23))
		return false;

	if ((time_array[1].length < 1) || (time_array[1].length > 2) || (time_array[1] < 0) || (time_array[1] > 59))
		return false;

	return true;
}

/*
	Проверка строки str на соответствие формату даты-времени ДД.ММ.ГГГГ ЧЧ:ММ.
*/
function is_cor_datetime(
	str)
{
	var date_array = str.trim().split(' ');

	if (!is_cor_date(date_array[0]))
		return false;

	if (!is_cor_time(date_array[1]))
		return false;

	return true;
}

/*
function php_date2js_date(str)
{
	var date_obj = new Date(),
		temp_array;

	temp_array = str.split(' ');

	temp_array[0] = temp_array[0].split('.');
	date_obj.setFullYear(temp_array[0][2], temp_array[0][1] - 1, temp_array[0][0]);

	if (temp_array.length > 1)
	{
		temp_array[1] = temp_array[1].split(':');
		date_obj.setHours(temp_array[1][0], temp_array[1][1], 0, 0);
	}
	else
		date_obj.setHours(0, 0, 0, 0);

	return date_obj;
}
*/

/*
	Преобразование даты корректного формата в метку времени (PHP-типа, т. е. количество секунд).
*/
function php_date2ts(
	str)
{
	var js_date = php_date2js_date(str);

	return date2ts(js_date);
}

/*
	Преобразование объекта-даты JS в строку с датой (без времени).
*/
function js_date2php_date(
	date)
{
	var result_str;

	result_str = date.getDateDD() + '.' + date.getMonthDD() + '.' + date.getFullYear();
	// result_str += ' ' + date.getHoursDD() + ':' + date.getMinutesDD();

	return result_str;
}

/*
	Преобразование объекта-даты JS в строку с датой и временем.
*/
function js_date2php_datetime(
	date)
{
	var result;

	result = js_date2php_date(date) + ' ' + date.getHoursDD() + ':00';// + date.getMinutesDD();

	return result;
}

function ts2php_date(
	ts)
{
	var date = ts2date(ts);

	return js_date2php_date(date);
}

function ts2php_datetime(
	ts)
{
	var date = ts2date(ts);

	return js_date2php_datetime(date);
}

function cor_date(
	date)
{
	var temp, result;
/*
	temp = date.getDate();
	if (temp < 10)
		temp = '0' + temp;
	result = temp + '.';

	temp = date.getMonth() + 1;
	if (temp < 10)
		temp = '0' + temp;
	result += temp + '.' + date.getFullYear() + ' г.';
*/
	result = js_date2php_date(date); // + ' г.';

	return result;
}

function cor_datetime(
	date)
{
	var temp, result;

	result = js_date2php_date(date); // + ' г. ';

	temp = date.getHours();
	if (temp < 10)
		temp = '0' + temp;
	result += temp + ':';

	temp = date.getMinutes();
	if (temp < 10)
		temp = '0' + temp;
	result += temp;

	return result;
}
