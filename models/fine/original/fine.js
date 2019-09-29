var data = [[0, '01.01.2999'],
[7.75, '17.12.2018'],
[7.50, '17.09.2018'],
[7.25, '26.03.2018'],
[7.50, '12.02.2018'],
[7.75, '18.12.2017'],
[8.25, '30.10.2017'],
[8.5, '18.09.2017'],
[9.0, '19.06.2017'],
[9.25, '02.05.2017'],
[9.75, '27.03.2017'],
[10.00, '19.09.2016'],
[10.50, '14.06.2016'],
[11.00, '01.01.2016'],
[8.25, '14.09.2012'],
[8, '26.12.2011'],
[8.25, '03.05.2011'],
[8, '28.02.2011'],
[7.75, '01.06.2010'],
[8, '30.04.2010'],
[8.25, '29.03.2010'],
[8.5, '24.02.2010'],
[8.75, '28.12.2009'],
[9, '25.11.2009'],
[9.5, '30.10.2009'],
[10, '30.09.2009'],
[10.5, '15.09.2009'],
[10.75, '10.08.2009'],
[11, '13.07.2009'],
[11.5, '05.06.2009'],
[12, '14.05.2009'],
[12.5, '24.04.2009'],
[13, '01.12.2008'],
[12, '12.11.2008'],
[11, '14.07.2008'],
[10.75, '10.06.2008'],
[10.5, '29.04.2008'],
[10.25, '04.02.2008'],
[10, '19.06.2007'],
[10.5, '29.01.2007'],
[11, '23.10.2006'],
[11.5, '26.06.2006'],
[12, '26.12.2005'],
[13, '15.06.2004'],
[14, '15.01.2004'],
[16, '21.06.2003'],
[18, '17.02.2003'],
[21, '07.08.2002'],
[23, '09.04.2002'],
[25, '04.11.2000'],
[28, '10.07.2000'],
[33, '21.03.2000'],
[38, '07.03.2000'],
[45, '24.01.2000'],
[55, '10.06.1999'],
[60, '24.07.1998'],
[80, '29.06.1998'],
[60, '05.06.1998'],
[150, '27.05.1998'],
[50, '19.05.1998'],
[30, '16.03.1998'],
[36, '02.03.1998'],
[39, '17.02.1998'],
[42, '02.02.1998'],
[28, '11.11.1997'],
[21, '06.10.1997'],
[24, '16.06.1997'],
[36, '28.04.1997'],
[42, '10.02.1997'],
[48, '02.12.1996'],
[60, '21.10.1996'],
[80, '19.08.1996'],
[110, '24.07.1996'],
[120, '10.02.1996'],
[160, '01.12.1995'],
[170, '24.10.1995'],
[180, '19.06.1995'],
[195, '16.05.1995'],
[200, '06.01.1995'],
[180, '17.11.1994'],
[170, '12.10.1994'],
[130, '23.08.1994'],
[150, '01.08.1994'],
[155, '30.06.1994'],
[170, '22.06.1994'],
[185, '02.06.1994'],
[200, '17.05.1994'],
[205, '29.04.1994'],
[210, '15.10.1993'],
[180, '23.09.1993'],
[170, '15.07.1993'],
[140, '29.06.1993'],
[120, '22.06.1993'],
[110, '02.06.1993'],
[100, '30.03.1993'],
[80, '23.05.1992'],
[50, '10.04.1992'],
[20, '01.01.1992']];
var datesBase = [];
var percents = [];

for (var i = data.length - 1; i >= 0; i--) {
	datesBase.push(dateParse(data[i][1]));
	percents.push(data[i][0]);
}

var DATA_TYPE_INFO = 1;
var DATA_TYPE_PAYED = 2;

var RATE_TYPE_SINGLE = 1;
var RATE_TYPE_PERIOD = 2;
var RATE_TYPE_PAY = 3;
var RATE_TYPE_TODAY = 4;
var RATE_TYPE_DATE = 5;

var RESULT_VIEW_SIMPLE = 0;
var RESULT_VIEW_BUH = 1;

var METHOD_300_ALL_TIME = 1;
var METHOD_SPLIT = 2;
var METHOD_NEW_FOR_ALL_TIME = 3;

var NEW_LAW = new Date(2016, 0, 1);

var VACATION_DAYS = [];
var WORK_DAYS = [];
var year;
// 2019
year = 2019;
for (var i = 1; i <= 8; i++)
	VACATION_DAYS.push(new Date(year, 0, i));
VACATION_DAYS.push(new Date(year, 2, 8));
VACATION_DAYS.push(new Date(year, 4, 1));
VACATION_DAYS.push(new Date(year, 4, 2));
VACATION_DAYS.push(new Date(year, 4, 9));
VACATION_DAYS.push(new Date(year, 5, 12));
VACATION_DAYS.push(new Date(year, 10, 4));

// 2018
year = 2018;
for (var i = 1; i <= 8; i++)
	VACATION_DAYS.push(new Date(year, 0, i));
VACATION_DAYS.push(new Date(year, 1, 23));
VACATION_DAYS.push(new Date(year, 2, 8));
VACATION_DAYS.push(new Date(year, 2, 9));
WORK_DAYS.push(new Date(year, 3, 28));
VACATION_DAYS.push(new Date(year, 3, 30));
VACATION_DAYS.push(new Date(year, 4, 1));
VACATION_DAYS.push(new Date(year, 4, 2));
VACATION_DAYS.push(new Date(year, 4, 9));
WORK_DAYS.push(new Date(year, 5, 9));
VACATION_DAYS.push(new Date(year, 5, 11));
VACATION_DAYS.push(new Date(year, 5, 12));
VACATION_DAYS.push(new Date(year, 10, 5));
WORK_DAYS.push(new Date(year, 11, 29));
VACATION_DAYS.push(new Date(year, 11, 31));


// 2017
year = 2017;
for (var i = 1; i <= 8; i++)
	VACATION_DAYS.push(new Date(year, 0, i));
VACATION_DAYS.push(new Date(year, 1, 23));
VACATION_DAYS.push(new Date(year, 1, 24));
VACATION_DAYS.push(new Date(year, 2, 8));
VACATION_DAYS.push(new Date(year, 4, 1));
VACATION_DAYS.push(new Date(year, 4, 8));
VACATION_DAYS.push(new Date(year, 4, 9));
VACATION_DAYS.push(new Date(year, 5, 12));
VACATION_DAYS.push(new Date(year, 10, 6));

// 2016
year = 2016;
for (var i = 1; i <= 8; i++)
	VACATION_DAYS.push(new Date(year, 0, i));
VACATION_DAYS.push(new Date(year, 1, 22));
VACATION_DAYS.push(new Date(year, 1, 23));
WORK_DAYS.push(new Date(year, 1, 20));
VACATION_DAYS.push(new Date(year, 2, 7));
VACATION_DAYS.push(new Date(year, 2, 8));
VACATION_DAYS.push(new Date(year, 4, 2));
VACATION_DAYS.push(new Date(year, 4, 3));
VACATION_DAYS.push(new Date(year, 4, 9));
VACATION_DAYS.push(new Date(year, 5, 13));
VACATION_DAYS.push(new Date(year, 10, 4));

// 2015
for (var i = 1; i <= 9; i++)
	VACATION_DAYS.push(new Date(2015, 0, i));
VACATION_DAYS.push(new Date(2015, 1, 23));
VACATION_DAYS.push(new Date(2015, 2, 9));
VACATION_DAYS.push(new Date(2015, 4, 1));
VACATION_DAYS.push(new Date(2015, 4, 4));
VACATION_DAYS.push(new Date(2015, 4, 11));
VACATION_DAYS.push(new Date(2015, 5, 12));
VACATION_DAYS.push(new Date(2015, 10, 4));

// 2014
for (var i = 1; i <= 8; i++)
	VACATION_DAYS.push(new Date(2014, 0, i));
VACATION_DAYS.push(new Date(2014, 2, 10));
VACATION_DAYS.push(new Date(2014, 4, 1));
VACATION_DAYS.push(new Date(2014, 4, 2));
VACATION_DAYS.push(new Date(2014, 4, 9));
VACATION_DAYS.push(new Date(2014, 5, 12));
VACATION_DAYS.push(new Date(2014, 5, 13));
VACATION_DAYS.push(new Date(2014, 10, 3));
VACATION_DAYS.push(new Date(2014, 10, 4));

// 2013
for (var i = 1; i <= 8; i++)
	VACATION_DAYS.push(new Date(2013, 0, i));
VACATION_DAYS.push(new Date(2013, 2, 8));
VACATION_DAYS.push(new Date(2013, 4, 1));
VACATION_DAYS.push(new Date(2013, 4, 2));
VACATION_DAYS.push(new Date(2013, 4, 9));
VACATION_DAYS.push(new Date(2013, 5, 12));
VACATION_DAYS.push(new Date(2013, 10, 4));

// 2012
year = 2012;
for (var i = 1; i <= 9; i++)
	VACATION_DAYS.push(new Date(year, 0, i));
VACATION_DAYS.push(new Date(2015, 1, 23));
VACATION_DAYS.push(new Date(year, 2, 8));
VACATION_DAYS.push(new Date(year, 2, 9));
WORK_DAYS.push(new Date(year, 2, 11));
WORK_DAYS.push(new Date(year, 3, 28));
VACATION_DAYS.push(new Date(year, 3, 30));
VACATION_DAYS.push(new Date(year, 4, 1));
VACATION_DAYS.push(new Date(year, 4, 8));
VACATION_DAYS.push(new Date(year, 4, 9));
WORK_DAYS.push(new Date(year, 4, 12));
WORK_DAYS.push(new Date(year, 5, 9));
VACATION_DAYS.push(new Date(year, 5, 11));
VACATION_DAYS.push(new Date(year, 5, 12));
VACATION_DAYS.push(new Date(year, 10, 5));
WORK_DAYS.push(new Date(year, 11, 29));
VACATION_DAYS.push(new Date(year, 11, 31));

// 2012
year = 2011;
for (var i = 1; i <= 10; i++)
	VACATION_DAYS.push(new Date(year, 0, i));
VACATION_DAYS.push(new Date(2015, 1, 23));
WORK_DAYS.push(new Date(year, 2, 5));
VACATION_DAYS.push(new Date(year, 2, 7));
VACATION_DAYS.push(new Date(year, 2, 8));
VACATION_DAYS.push(new Date(year, 4, 2));
VACATION_DAYS.push(new Date(year, 4, 9));
VACATION_DAYS.push(new Date(year, 5, 13));
VACATION_DAYS.push(new Date(year, 10, 4));

function checkVacation(date) {
	var dow = date.getDay();
	var time = date.getTime();
	if (dow == 0 || dow == 6) {
		for (var i = 0; i < WORK_DAYS.length; i++)
			if (WORK_DAYS[i].getTime() == time)
				return false;
		return true;
	}
	for (var i = 0; i < VACATION_DAYS.length; i++)
		if (VACATION_DAYS[i].getTime() == time)
			return true;
	return false;
}

function checkVacationInput(errorId, inputId, isExpire) {
	var input = document.getElementById(inputId);
	var d = dateParse(input.value);
	if (isExpire)
		d.setDate(d.getDate() - 1);

	var el = errorId? $('#' + errorId) : null;
	if (!d || !checkVacation(d)) {
		$(input).removeClass('warning-field');
		if (el) el.hide();
		return;
	}
	// это выходной!
	do {
		d = new Date(d.getTime() + ONE_DAY);
	} while (checkVacation(d));
	$(input).addClass('warning-field');
	var newDate = fd(isExpire? new Date(d.getTime() + ONE_DAY) : d);
	if (el) {
		if (isExpire)
			el.html('Согласно <a style="color:#990000" target="_blank" href="https://dogovor-urist.ru/кодексы/гк_рф_1/ст_193/">ст. 193 ГК РФ</a> первый день просрочки должен быть следующим после первого рабочего дня. <a href="javascript:" onclick="var el = document.getElementById(\'' + inputId + '\'); el.value=\'' + newDate + '\'; el.onchange()">Изменить на '  + newDate + '</a>');
		else
			el.html('Дата установлена на выходной день. Согласно <a style="color:#990000" target="_blank" href="https://dogovor-urist.ru/кодексы/гк_рф_1/ст_193/">ст. 193 ГК РФ</a> необходимо изменить дату на ближайший рабочий день. <a href="javascript:" onclick="var el = document.getElementById(\'' + inputId + '\'); el.value=\'' + newDate + '\'; el.onchange()">Изменить на '  + newDate + '</a>');
		el.show();
	}
}
function updateData(showErrors) {
	var dates = datesBase;
	$('.calc .error-field').removeClass('error-field');
	var clips = $('.resultAppearing'); clips.hide();
	var hash = [];
	var errors = [];

	var loanAmount = hash['loanAmount'] = ggg('loanAmount');
	if (!loanAmount) {
		wrongData('loanAmount');
		errors.push('Введите сумму задолженности');
	} else {
		loanAmount = normalizeLoan(loanAmount);
		if (!loanAmount) {
			wrongData('loanAmount');
			errors.push('Вы ввели неправильную сумму задолженности');
		}
	}

	var dateStart = dateParse(hash['dateStart'] = ggg('dateStart'));
	if (!dateStart) {
		wrongData('dateStart');
		errors.push('Дата начала периода не введена');
	} else if (dateStart.getTime() > dates[dates.length - 1].getTime()) {
		wrongData('dateStart');
		errors.push('Дата начала периода слишком большая');
	}

	var dateFinish = dateParse(hash['dateFinish'] = ggg('dateFinish'));
	if (!dateFinish) {
		wrongData('dateFinish');
		errors.push('Дата конца периода не введена');
	} else if (dateFinish.getTime() > dates[dates.length - 1].getTime()) {
		wrongData('dateFinish');
		errors.push('Дата конца периода слишком большая');
	}

	var totalDays = 0;
	if (dateFinish && dateStart) {
		totalDays = dateDiff(dateFinish, dateStart);
		if (totalDays <= 0) {
			wrongData('dateStart');
			errors.push('Дата начала периода оказалась больше даты окончания. Обратите внимание');
		}
	}

	var rateType = hash['rateType'] = document.getElementById('rateType').options[document.getElementById('rateType').selectedIndex].value;
	if (!rateType) {
		wrongData('rateType');
		errors.push('Тип расчёта процентных ставок не выбран');
	}

	var exactDate = null;
	if (rateType == RATE_TYPE_DATE) {
		$('#rateDate').show();
		exactDate = dateParse(hash['rateDate'] = ggg('rateDate'));
		if (!exactDate) {
			wrongData('rateDate');
			errors.push('Дата ставки не указана');
		}
	} else {
		$('#rateDate').hide();
	}

	var method = hash['back'] = document.getElementById('method').options[document.getElementById('method').selectedIndex].value;
	var resultView = hash['resultView'] = parseInt($('form[name=calcTable] input[name=resultView]:checked').val());

	var payments = collectPayments();
	if (payments === null) {
		errors.push('Ошибка заполнения полей погашения задолженности');
	}
	var loans = collectLoans();
	if (loans === null) {
		errors.push('Ошибка заполнения полей новых задолженностей');
	}



	var el = $('#error-pane');
	if (errors.length) {
		if (showErrors) {
			var html = '<ul>';
			for (var i = 0; i < errors.length; i++)
				html += '<li>' + errors[i] + '</li>';
			html += '</ul>';
			el.html(html);
			el.show();
			document.location.hash = '';
			document.location.hash = 'calc-error';
		}
		return;
	}
	el.hide();

	hash['payments'] = preparePayments(payments);
	hash['loans'] = prepareLoans(loans);
	updateHash(hash);
	checkVacationInput('lfWarn', 'dateStart', true);

	loans.unshift({sum: loanAmount, date: dateStart, order: ''});

	var toPayments = clearLoans(loans);
	loans = sortLoans(loans);
	payments = sortPayments(payments.concat(toPayments));

	payments = splitPayments(payments, loans);

	var periods = [];
	for (var i = 0; i < loans.length; i++) {
		var loan = loans[i];
		periods.push(countForPeriod(loan.sum, loan.date, dateFinish, payments[i], rateType, exactDate, method));
	}


	document.getElementById('resultPane').innerHTML =
			resultView == RESULT_VIEW_BUH? getBuhHtml(periods) : getClassicHtml(periods);

	document.getElementById('dateStartRes').innerHTML = fd(dateStart);
	document.getElementById('dateFinishRes').innerHTML = fd(dateFinish);
	document.getElementById('rateTypeRes').innerHTML = document.getElementById('rateType').options[document.getElementById('rateType').selectedIndex].innerHTML;

	var href = document.location.href;
	$('#djuLink').html(href);
	var aHref = $('#djuHref');
	aHref.attr('href', href);
	aHref.html(cutLink(href));

	clips.show();
}

function clearLoans(arr) {
	var res = [];
	var i = 0;
	while (i < arr.length) {
		var c = arr[i];
		if (c.sum < 0) {
			res[res.length] = {date: c.date, datePlus: c.datePlus, sum: -c.sum, payFor: null};
			arr.splice(i, 1);
		} else
			i++;
	}
	return res;
}

function getBuhHtml(periods) {
	var resultString = '<table contenteditable="true" class="judge-table jt-4">' +
			'<tr class="head">' +
				'<td rowspan="2">Месяц</td>' +
				'<td rowspan="2">Начислено</td>' +
				'<td rowspan="2">Долг</td>' +
				'<td colspan="3">Период просрочки</td>' +
				'<td rowspan="2">Ставка</td>' +
				'<td rowspan="2">Доля ставки</td>' +
				'<td rowspan="2">Формула</td>' +
				'<td rowspan="2">Пени</td>' +
			'</tr>' +
			'<tr class="head"><td>с</td><td>по</td><td>дней</td>' +
			'</tr>';
	var totalPct = 0;
	var endSum = 0;
	for (var i = 0; i < periods.length; i++) {
		var period = periods[i];
		var html = getBuhMonthHtml(period, false);
		resultString += html.html;
		totalPct += html.totalPct;
		endSum += html.endSum;
	}

	resultString += '<tr><td colspan="10" style="font-size:14px; text-align: right">Сумма основного долга: ' + moneyFormat(endSum)  +' руб.</td></tr>';
	resultString += '<tr><td colspan="10" style="font-size:14px; text-align: right">Сумма пеней по всем задолженностям: ' + moneyFormat(totalPct)  +' руб.</td></tr>';
	resultString += '</table>';
	return resultString;
}

function getBuhMonthHtml(data, isFirst) {
	var resData = data.data;
	if (resData.length == 0)
		return {html: '', totalPct: 0, endSum : 0};
	var dateStart = data.dateStart;
	var total = 0;
	var resultString;
	var r;
	for (var i = 0; i < resData.length; i++) {
		r = resData[i];
		if (r.type == DATA_TYPE_INFO)
			break;
	}

	// TODO: нужно нормально подводить сумму начислений, а не костялять
	var sum = i == resData.length? 0 : r.data.sum;
	for (i--; i >= 0; i--) {
		var d = resData[i];
		sum += d.data.sum;
	}


	r = resData[0];
	resultString = '<tr class="jtb-first">' +
		(isFirst? '<td rowspan="' +  resData.length + '">сальдо на<br>' + fd(dateStart) + '</td>'
			: '<td rowspan="' +  resData.length + '">' + buhDate(dateStart) + '</td>'
		)
		+ '<td rowspan="' +  resData.length + '">' + moneyFormat(sum) + '</td>';
	if (r.type == DATA_TYPE_INFO) {
		resultString += '<td>' + moneyFormat(r.data.sum) + '</td>' +
			'<td>' + fd(r.data.dateStart) + '</td>' +
			'<td>' + fd(r.data.dateFinish) + '</td>' +
			'<td>' + r.data.days + '</td>' +
			'<td>' + moneyFormat(r.data.percent) + ' %</td>' +
			'<td>' + r.data.rate + '</td>' +
			'<td>' + moneyFormat(r.data.sum) + ' × ' + r.data.days + ' × ' + r.data.rate + ' × ' + r.data.percent + '% </td>' +
			'<td>' + moneyFormat(r.data.cost)  + ' р.</td>' +
		'</tr>';
		total += r.data.cost;
	} else {
		resultString +=
			'<td class="jt-payed">-' + moneyFormat(r.data.sum) + '</td>' +
			'<td class="jt-payed">' + fd(r.data.date) + '</td>' +
			'<td class="jt-payed" colspan="6" style="text-align: left">Погашение части долга' + (r.data.order? ' (' + r.data.order + ')' : '')  + '</td></tr>';
	}

	for (var i = 1; i < resData.length; i++) {
		r = resData[i];
		if (r.type == DATA_TYPE_INFO) {
			resultString += '<tr>' +
					'<td>' + moneyFormat(r.data.sum) + '</td>' +
					'<td>' + fd(r.data.dateStart) + '</td>' +
					'<td>' + fd(r.data.dateFinish) + '</td>' +
					'<td>' + r.data.days + '</td>' +
					'<td>' + moneyFormat(r.data.percent) + ' %</td>' +
					'<td>' + r.data.rate + '</td>' +
					'<td>' + moneyFormat(r.data.sum) + ' × ' + r.data.days + ' × ' + r.data.rate + ' × ' + r.data.percent + '% </td>' +
					'<td>' + moneyFormat(r.data.cost)  + ' р.</td>' +
					'</tr>';
			total += r.data.cost;
		} else if (r.type == DATA_TYPE_PAYED) {
			resultString += '<tr class="jt-payed">' +
					'<td>-' + moneyFormat(r.data.sum) + '</td>' +
					'<td>' + fd(r.data.date) + '</td>' +
					'<td colspan="6" style="text-align: left">Погашение части долга' + (r.data.order? ' (' + r.data.order + ')' : '')  + '</td></tr>'
		}
	}
	return {html: resultString, totalPct: total, endSum : data.endSum};
}

var MONTHS = ['янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'];

function buhDate(date) {
	var newMonth = (date.getMonth() + 11) % 12;
	var year = date.getFullYear() - ((newMonth == 11)? 1 : 0);
	return MONTHS[newMonth] + '.' + year;
}

/**********************************************************/
function getClassicHtml(periods) {
	var resultString = '<table contenteditable="true" class="judge-table jt-2">';
	var totalPct = 0;
	var endSum = 0;
	for (var i = 0; i < periods.length; i++) {
		var period = periods[i];
		var html = getHtml(period);
		resultString += html.html;
		totalPct += html.totalPct;
		endSum += html.endSum;
	}

	resultString += '<tr><td colspan="8" style="font-size:14px; text-align: right">Сумма основного долга: ' + moneyFormat(endSum)  +' руб.</td></tr>';
	resultString += '<tr><td colspan="8" style="font-size:14px; text-align: right">Сумма пеней по всем задолженностям: ' + moneyFormat(totalPct)  +' руб.</td></tr>';
	resultString += '</table>';
	return resultString;
}

function splitPayments(payments, loans) {
	var res = [];
	var i;
	loans = loans.slice(0);

	for (i = 0; i < loans.length; i++) {
		res[i] = [];
		var c = loans[i];
		loans[i] = {sum: c.sum, date: c.date, month: c.date.getFullYear()*12 + c.date.getMonth(), order: c.order};
	}

	for (i = 0; i < payments.length; i++) {
		var payment = payments[i];
		if (payment.payFor) {
			var curMonth = payment.payFor.getFullYear()*12 + payment.payFor.getMonth() + 1;
			var j;
			// ищем текущий месяц
			for (j = 0; j < loans.length; j++) {
				if (loans[j].month == curMonth)
					break;
			}

			if (j < loans.length) { // нашли
				var loan = loans[j];
				var toCut = Math.min(payment.sum, loan.sum);
				if (toCut >= 0.01) {
					loan.sum -= toCut;
					payment.sum -= toCut;
					res[j].push({date: payment.date, datePlus: payment.datePlus, sum: toCut, payFor: payment.payFor});
				}
			}
		}

		for (j = 0; j < loans.length && payment.sum > 0; j++) {
			var loan = loans[j];
			var toCut = Math.min(payment.sum, loan.sum);

			if (toCut >= 0.01) {
				loan.sum -= toCut;
				payment.sum -= toCut;
				res[j].push({date: payment.date, datePlus: payment.datePlus, sum: toCut, payFor: payment.payFor});
			}
		}
	}
	return res;
}

function getHtml(data) {
	var dateStart = data.dateStart;
	var resData = data.data;
	var total = 0;
	var resultString =
			'<tr><td colspan="8"><h4 style="text-align: left">Расчёт пеней по задолженности, возникшей ' + fd(dateStart) + '</h4></td></tr>' +
			'<tr class="head">' +
			'<td rowspan="2">Задолженность</td>' +
			'<td colspan="3">Период просрочки</td>' +
			'<td rowspan="2">Ставка</td>' +
			'<td rowspan="2">Доля ставки</td>' +
			'<td rowspan="2">Формула</td>' +
			'<td rowspan="2">Пени</td>' +
			'</tr>' +
			'<tr class="head"><td>с</td><td>по</td><td>дней</td></tr>';
	for (var i = 0; i < resData.length; i++) {
		var r = resData[i];
		if (r.type == DATA_TYPE_INFO) {
			resultString += '<tr>' +
					'<td>' + moneyFormat(r.data.sum) + '</td>' +
					'<td>' + fd(r.data.dateStart) + '</td>' +
					'<td>' + fd(r.data.dateFinish) + '</td>' +
					'<td>' + r.data.days + '</td>' +
					'<td>' + moneyFormat(r.data.percent) + ' %</td>' +
					'<td>' + r.data.rate + '</td>' +
					'<td>' + moneyFormat(r.data.sum) + ' × ' + r.data.days + ' × ' + r.data.rate + ' × ' + r.data.percent + '% </td>' +
					'<td>' + moneyFormat(r.data.cost)  + ' р.</td>' +
					'</tr>';
			total += r.data.cost;
		} else if (r.type == DATA_TYPE_PAYED) {
			resultString += '<tr class="jt-payed">'
					+ '<td>-' + moneyFormat(r.data.sum) + '</td>'
					+ '<td>' + fd(r.data.date) + '</td>'
					+ '<td colspan="6" style="text-align: left">Погашение части долга' + (r.data.order? ' (' + r.data.order + ')' : '')  + '</td></tr>'
		}
	}
	resultString += '<tr class="calc-footer"><td></td><td></td><td></td><td></td><td></td><td></td><td style="text-align: right"><b>Итого:</b></td><td><b>' + moneyFormat(total) + '</b> р.</td></tr>'
					;
	return {html: resultString, totalPct: total, endSum : data.endSum};
}

function countForPeriod(sum, dateStart, dateFinish, payments, rateType, exactDate, method) {

	var rulesData = [];
	if (method == METHOD_300_ALL_TIME && dateStart.getTime() < NEW_LAW.getTime()) {
		rulesData.push({rate: '1/300', dateStart:dateStart, dateFinish:dateFinish});
	} else if (method == METHOD_NEW_FOR_ALL_TIME  && dateStart.getTime() < NEW_LAW.getTime()) {
		var newDate = dateStart;
		var days30 = new Date(dateStart.getTime() + (30 - 1)*ONE_DAY);
		var days90 = new Date(dateStart.getTime() + (90 - 1)*ONE_DAY);

		if (newDate.getTime() <= days30.getTime()) {
			var till = (dateFinish.getTime() > days30.getTime())? days30 : dateFinish;
			rulesData.push({rate: '0', dateStart:newDate, dateFinish:till});

		}
		if (newDate.getTime() <= days90.getTime() && dateFinish.getTime() > days30.getTime()) {
			var from = (newDate.getTime() > days30.getTime())? newDate : new Date(days30.getTime() + ONE_DAY);
			var till = (dateFinish.getTime() > days90.getTime())? days90 : dateFinish;
			rulesData.push({rate: '1/300', dateStart:from, dateFinish:till});
		}

		if (dateFinish.getTime() > days90.getTime()) {
			var from = (newDate.getTime() >= days90.getTime())? newDate : new Date(days90.getTime() + ONE_DAY);
			rulesData.push({rate: '1/130', dateStart:from, dateFinish:dateFinish});
		}
	} else {
		if (dateStart.getTime() < NEW_LAW.getTime()) {
			var newDate = (dateFinish.getTime() >= NEW_LAW.getTime())? new Date(NEW_LAW.getTime() - ONE_DAY) : dateFinish;
			rulesData.push({rate: '1/300', dateStart:dateStart, dateFinish:newDate});
		}

		if (dateFinish.getTime() >= NEW_LAW.getTime()) {
			var newDate = (dateStart.getTime() < NEW_LAW.getTime())? NEW_LAW : dateStart;
			var days30 = new Date(dateStart.getTime() + (30 - 1)*ONE_DAY);
			var days90 = new Date(dateStart.getTime() + (90 - 1)*ONE_DAY);

			if (newDate.getTime() <= days30.getTime()) {
				var till = (dateFinish.getTime() > days30.getTime())? days30 : dateFinish;
				rulesData.push({rate: '0', dateStart:newDate, dateFinish:till});

			}
			if (newDate.getTime() <= days90.getTime() && dateFinish.getTime() > days30.getTime()) {
				var from = (newDate.getTime() > days30.getTime())? newDate : new Date(days30.getTime() + ONE_DAY);
				var till = (dateFinish.getTime() > days90.getTime())? days90 : dateFinish;
				rulesData.push({rate: '1/300', dateStart:from, dateFinish:till});
			}

			if (dateFinish.getTime() > days90.getTime()) {
				var from = (newDate.getTime() >= days90.getTime())? newDate : new Date(days90.getTime() + ONE_DAY);
				rulesData.push({rate: '1/130', dateStart:from, dateFinish:dateFinish});
			}
		}
	}

	var preData = [];

	if (rateType == RATE_TYPE_SINGLE) {
		var dateFinishInd = 0;
		for (var i = datesBase.length - 1; i >= 0; i--)
			if (dateFinish.getTime() >= datesBase[i].getTime()) {
				dateFinishInd = i;
				break;
			}
		preData = pushRules([dateStart, new Date(3000, 0, 1)], [percents[dateFinishInd], 0], rulesData, dateStart, dateFinish);
	} else if (rateType == RATE_TYPE_PAY) {
		var payDates = [dateStart], payPercents = [];
		var curPercents = 0;
		for (var i = 0; i < payments.length && curPercents < percents.length; i++) {
			for (; curPercents < percents.length; curPercents++) {
				if (payments[i].date.getTime() < datesBase[curPercents].getTime()) {
					payDates.push(payments[i].datePlus);
					payPercents.push(curPercents >= 1? percents[curPercents - 1] : 0);
					break;
				}
			}
		}
		var dateFinishInd = 0;
		for (var i = datesBase.length - 1; i >= 0; i--)
			if (dateFinish.getTime() >= datesBase[i].getTime()) {
				payPercents.push(percents[i]);
				break;
			}
		payDates.push(new Date(3000, 0, 1));
		payPercents.push(0);

		preData = pushRules(payDates, payPercents, rulesData, dateStart, dateFinish);

	} else if (rateType == RATE_TYPE_TODAY) {
		var today = new Date();
		today.setHours(0, 0, 0, 0);
		var dateFinishInd = 0;
		for (var i = datesBase.length - 1; i >= 0; i--)
			if (today.getTime() >= datesBase[i].getTime()) {
				dateFinishInd = i;
				break;
			}
		preData = pushRules([dateStart, new Date(3000, 0, 1)], [percents[dateFinishInd], 0], rulesData, dateStart, dateFinish);
	} else if (rateType == RATE_TYPE_DATE) {
		var date = exactDate;
		var dateFinishInd = 0;
		for (var i = datesBase.length - 1; i >= 0; i--)
			if (date.getTime() >= datesBase[i].getTime()) {
				dateFinishInd = i;
				break;
			}
		preData = pushRules([dateStart, new Date(3000, 0, 1)], [percents[dateFinishInd], 0], rulesData, dateStart, dateFinish);
	} 
	else {
		preData = pushRules(datesBase, percents, rulesData, dateStart, dateFinish);
	}

	var resData = [];
	var startJ = 0;

	var data;

	for (var j = startJ; j < payments.length; j++) {
		var payment = payments[j];
		if (dateStart.getTime() <= payment.datePlus.getTime()) // убрал, потому что если платёж 12.02.2015, а просрочка с 16.02.2015, то расчёт ведётся только с 01.01.2016
			break;

		var toCut;
		if (payment.sum <= sum) {
			toCut = payment.sum;
			sum -= payment.sum;
			payment.sum = 0;
		} else {
			toCut = sum;
			payment.sum -= sum;
			sum = 0;
		}

		resData.push({type: DATA_TYPE_PAYED, data: {sum: toCut, date: payment.date, order: payment.order}});
	}
	startJ = j;


	for (var i = 0; i < preData.length; i++) {
		data = preData[i];
		var lastStartJ = startJ;
		for (var j = startJ; j < payments.length && sum > 0; j++) {
			var payment = payments[j];
			if (payment.sum >= 0.01 && payment.datePlus <= data.dateFinish) {
				startJ = j + 1;
				var dateStartInPeriod;
				if (payment.datePlus.getTime() > data.dateStart.getTime()) {
					if ( j == 0 || j >= 1 && payments[j - 1].datePlus < data.dateStart) {
						resData.push({type: DATA_TYPE_INFO, data: processData(sum, data, data.dateStart, payment.date)});
					}
					dateStartInPeriod = payment.datePlus;
				} else
					dateStartInPeriod = data.dateStart;
				var toCut;
				if (payment.sum <= sum) {
					toCut = payment.sum;
					sum -= payment.sum;
					payment.sum = 0;
				} else {
					toCut = sum;
					payment.sum -= sum;
					sum = 0;
				}

				resData.push({type: DATA_TYPE_PAYED, data: {sum: toCut, date: payment.date, order: payment.order}});

				if (sum < 0.01) {
					sum = 0;
					continue;
				}

				if (j + 1 >= payments.length || j + 1 < payments.length && payments[j + 1].datePlus > data.dateFinish && payment.date.getTime() != payments[j + 1].date.getTime()) {
					resData.push({type: DATA_TYPE_INFO, data: processData(sum, data, dateStartInPeriod, data.dateFinish)});
				} else if (j + 1 < payments.length && payments[j + 1].datePlus <= data.dateFinish && payment.date.getTime() != payments[j + 1].date.getTime()) {
					resData.push({type: DATA_TYPE_INFO, data: processData(sum, data, dateStartInPeriod, payments[j + 1].date)});
				}

			} else {//if (data.dateFinish <= payment.date) { // все остальные платежи уже из будущих
				break;
			}
		}
		if (sum < 0.01) {
			sum = 0;
			break;
		}
		if (lastStartJ == startJ) { // не было периода в диапазоне ставки
			resData.push({type: DATA_TYPE_INFO, data: processData(sum, data, data.dateStart, data.dateFinish)});
		}
	}

	for (var j = startJ; j < payments.length; j++) {
		var payment = payments[j];
		var toCut;
		if (payment.sum <= sum) {
			toCut = payment.sum;
			sum -= payment.sum;
			payment.sum = 0;
		} else {
			toCut = sum;
			payment.sum -= sum;
			sum = 0;
		}

		resData.push({type: DATA_TYPE_PAYED, data: {sum: toCut, date: payment.date, order: payment.order}});
	}
	return {dateStart: dateStart, dateFinish: dateFinish, data: resData, endSum: parseFloat(sum)};
}

function pushRules(dates, percents, rules, dateStartUser, dateFinishUser) {
	var res = [];
	var len = dates.length;
	var ds = dateStartUser.getTime();
	var df = dateFinishUser.getTime();
	var rulePos = 0;
	for (var i = 0; i + 1 < len; i++) {
		var dateStart = dates[i].getTime();
		var dateFinish = dates[i + 1].getTime() - ONE_DAY;
		if (dateFinish < ds || dateStart > df) continue;
		if (dateStart < ds) dateStart = ds;
		if (dateFinish > df) dateFinish = df;

		for (var j = rulePos; j < rules.length; j++) {
			var rule = rules[j];
			var ruleStart = rule.dateStart.getTime();
			var ruleEnd = rule.dateFinish.getTime();

			if (ruleStart < dateStart && ruleEnd >= dateStart && ruleEnd <= dateFinish) { // [ dS]dF
				res.push({rate: rule.rate, dateStart: dateStart, dateFinish : rule.dateFinish, percent : percents[i]});
			} else if (ruleStart < dateStart && ruleEnd > dateFinish) { // [ dS dF ]
				res.push({rate: rule.rate, dateStart: dateStart, dateFinish : dateFinish, percent : percents[i]});
			} else if (ruleStart >= dateStart && ruleEnd <= dateFinish) // dS[ ]dF
				res.push({rate: rule.rate, dateStart: ruleStart, dateFinish : ruleEnd, percent : percents[i]});
			else if (ruleStart >= dateStart && ruleStart <= dateFinish && ruleEnd > dateFinish) // dS[dF ]
				res.push({rate: rule.rate, dateStart: ruleStart, dateFinish : dateFinish, percent : percents[i]});
			else { // не было периода в диапазоне ставки
				res.push({rate: rule.rate, dateStart: dateStart, dateFinish : dateFinish, percent : percents[i]});
			}
			if (ruleEnd <= dateFinish) {
				rulePos++;
				if (ruleEnd == dateFinish)
					break;
			} else
				break;
		}
	}


	for (var i = 0; i < res.length; i++) {
		var d = res[i];
		d.dateStart = new Date(d.dateStart);
		d.dateFinish = new Date(d.dateFinish);
	}


	return res;
}

function cutLink(link) {
	var len = link.length;
	if (len < 50)
		return link;
	return link.substring(0, 40) + ' ... ' + link.substring(len - 10, len);
}

function preparePayments(payments) {
	if (!payments.length)
		return '';
	var res = '';
	for (var i = 0; i < payments.length; i++) {
		var p = payments[i];
		res += ';' + fd(p.date) + '_' + p.sum + '_' + (p.payFor? ((p.payFor.getMonth() < (10 - 1)? '0' : '') + (p.payFor.getMonth() + 1)) + '.' + p.payFor.getFullYear(): '');
	}
	return res.substring(1);
}

function prepareLoans(payments) {
	if (!payments.length)
		return '';
	var res = '';
	for (var i = 0; i < payments.length; i++) {
		var p = payments[i];
		res += ';' + fd(p.date) + '_' + p.sum;
	}
	return res.substring(1);
}

function processData(sum, data, dateStart, dateFinish) {
	var ratePart = data.rate;
	var days = dateDiff(dateFinish, dateStart);
	return {rate : data.rate, percent : data.percent, cost: countCost(sum, days, data.percent, getRate(ratePart)), days:days, dateStart:dateStart, dateFinish:dateFinish, sum:sum};
}

function getRate(part) {
	if (part == '1/300')
		return 1./300;
	if (part == '1/130')
		return 1./130;
	return 0;
}

function collectPayments() {
	var form = document.forms['calcTable'];
	var res = [];
	var payDates = form.elements['payDates[]'];
	if (!payDates)
		return res;
	var val;
	if (payDates.length) {// больше, чем 2 оплаты
		var paySums = form.elements['paySums[]'];
		var payOrders = form.elements['payFor[]'];
		for (var i = 0; i < payDates.length; i++) {
			val = testPaymentLine(payDates[i], paySums[i], payOrders[i]);
			if (!val)
				return null;
			if (val.date != null)
				res.push(val);
		}
	} else {
		val = testPaymentLine(form.elements['payDates[]'], form.elements['paySums[]'], form.elements['payFor[]']);
		if (!val)
			return null;
		if (val.date != null)
			res.push(val);
	}

	return res;
}

function sortPayments(arr) {
	for (var i = 0; i + 1 < arr.length; i++) {
		for (var j = i + 1; j < arr.length; j++) {
			if (arr[i].date > arr[j].date) {
				var tmp = arr[i];
				arr[i] = arr[j];
				arr[j] = tmp;
			}
		}
	}
	return arr;
}

function testPaymentLine(payDate, paySum, payFor) {
	if (!(payDate.value && paySum.value))
		return {date: null, sum: null, isCurrent: false};
	var resDate = null;
	if (payDate.value) {
		resDate = dateParse(payDate.value);
		if (resDate.getFullYear() < 1990)
			resDate = null;
	}

	if (!resDate) {
		wrongData($(payDate).attr('id'));
		return null;
	}

	var resSum = null;
	if (paySum.value) {
		resSum = normalizeLoan(paySum.value);
	}

	if (!resSum) {
		wrongData($(paySum).attr('id'));
		return null;
	}

	var resFor = null;
	if (payFor.value) {
		resFor = dateParse('01.' + payFor.value);
		if (!resFor) {
			wrongData($(payFor).attr('id'));
			return null;
		}
	}

	return {date: resDate, datePlus: new Date(resDate.getTime() + ONE_DAY), sum: resSum, payFor: resFor};
}

function collectLoans() {
	var form = document.forms['calcTable'];
	var res = [];
	var payDates = form.elements['loanDates[]'];
	if (!payDates)
		return res;
	var val;
	if (payDates.length) {// больше, чем 2 оплаты
		var paySums = form.elements['loanSums[]'];
		for (var i = 0; i < payDates.length; i++) {
			val = testLoanLine(payDates[i], paySums[i]);
			if (!val)
				return null;
			if (val.date != null)
				res.push(val);
		}
	} else {
		val = testLoanLine(form.elements['loanDates[]'], form.elements['loanSums[]']);
		if (!val)
			return null;
		if (val.date != null)
			res.push(val);
	}

	return res;
}

function sortLoans(arr) {
	for (var i = 0; i + 1 < arr.length; i++)
		for (var j = i + 1; j < arr.length; j++)
			if (arr[i].date > arr[j].date) {
				var tmp = arr[i];
				arr[i] = arr[j];
				arr[j] = tmp;
			}
	return arr;
}

function testLoanLine(payDate, paySum) {
	if (!(payDate.value && paySum.value))
		return {date: null, sum: null, order: null};
	var resDate = null;
	if (payDate.value) {
		resDate = dateParse(payDate.value);
		if (resDate.getFullYear() < 1990)
			resDate = null;
	}

	if (!resDate) {
		wrongData($(payDate).attr('id'));
		return null;
	}

	checkVacationInput(null, $(payDate).attr('id'), true);

	var resSum = null;
	if (paySum.value) {
		resSum = normalizeLoan(paySum.value);
	}

	if (!resSum) {
		wrongData($(paySum).attr('id'));
		return null;
	}

	return {date: resDate, datePlus: new Date(resDate.getTime() + ONE_DAY), sum: resSum};
}

function fd(date) {
	var day = date.getDate();
	if (day < 10)
		day = '0' + day;
	var monthIndex = date.getMonth() + 1;
	if (monthIndex < 10)
		monthIndex = '0' + monthIndex;
	var year = date.getFullYear();
	return day + '.' + monthIndex + '.' + year;
}

function countCost(money, days, percent, ratePart) {
	var res = money*days*percent*ratePart/100.;
	res = Math.round(res*100)/100;
	return parseFloat(toDigitsAfter(res, 2));
}

function toDigitsAfter(amount, digitsAfter) {
	return (+(Math.round(+(amount + 'e' + digitsAfter)) + 'e' + -digitsAfter)).toFixed(digitsAfter);
}

function wrongData(id) {
	var d = $('#' + id);
	d.addClass('error-field');
}

function normalizeLoan(money) {
	money = (money).replace(',', '.').replace(/ /g,"").replace(/ /g,"");
	return parseFloat(money);
}

function ggg(id) {
	return document.getElementById(id).value;
}

var ONE_DAY = 1000*60*60*24;

function dateDiff(date1, date2) {

	var date1_ms = date1.getTime();
	var date2_ms = date2.getTime();
	var difference_ms = date1_ms - date2_ms;
	var absDiff = Math.round(difference_ms/ONE_DAY);
	return absDiff + 1;

}



function dateParse(dateStr) {
	if (dateStr == null) return null;
	var dateParts = dateStr.split(".");
	if (dateParts.length != 3)
		return null;
	var d = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0]);
	if (d && !isNaN(d.getTime())) {
		var fy = parseInt(dateParts[2]);
		if (fy < 50)
			d.setFullYear(2000 + fy);
		else if (fy < 100)
			d.setFullYear(1900 + fy);
		return d;
	}
	return null;
}

function moneyFormat(money) {
	return addCommas(parseFloat(money).toFixed(2)).replace(/\./g, ',');
}

function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ' ' + '$2');
	}
	return x1 + x2;
}

function getTemplate(inner) {
	return '<!doctype html><html>'
		+ '<head>'
		+ '<meta http-equiv=Content-Type content="text/html; charset=utf-8">'
		+ '<title>Расчёт пени по оплате коммунальных услуг (155 ЖК РФ)</title>'
		+ '<link href="https://dogovor-urist.ru/images/calc.css" rel="stylesheet">'
		+ '<link href="https://dogovor-urist.ru/images/calc_print.css" rel="stylesheet">'
		+ '<link rel="stylesheet" href="https://dogovor-urist.ru/images/calc_buh.css">'
		+ '</head>'
		+ '<body><div id="wrapper">'
		+ inner
		+ '<br><br><div>____________________________/ _______________________________/</div>'
		+ '</div></body>'
		+ '</html>';
}

function waitPrint(el) {
	var ss = el.document.styleSheets;
	for (var i = 0, max = ss.length; i < max; i++) {
		if (ss[i].href == "https://dogovor-urist.ru/images/calc_print.css") {
			el.print();
			return;
		}
	}
	setTimeout(function() {waitPrint(el)}, 100);
}

function printingPage() {
	showTnx();
	var el = window.open("about:blank");
	if(el){
		el.document.open();
		var res = document.getElementById('resultParams').innerHTML
			+	document.getElementById('resultPane').innerHTML;
		if ($('#sign').prop('checked')) {
			var path = document.location.href;
			var ind = path.indexOf('#');
			if (ind >= 0)
				path = path.substring(0, ind);
			res += '<div>Калькулятор расчёта: ' + path + '</div>';
		}
		el.document.write(getTemplate(res));
		waitPrint(el);
	}
}

/******************************/

function daysInMonth(year, month) {
	return new Date(year, month, 0).getDate();
}

function setupData() {
	var data = ph();
	clearCalc();
	if (data['dateFinish'])
		document.getElementById('dateFinish').value = data['dateFinish'];
	else
		document.getElementById('dateFinish').value = fd(new Date());
	if (data['dateStart'])
		document.getElementById('dateStart').value = data['dateStart'];
	if (data['loanAmount'])
		document.getElementById('loanAmount').value = data['loanAmount'];

	var resultView = $('input:radio[name=resultView]');
	resultView.filter('[value=' + data['resultView'] + ']').prop('checked', true);

	if (data['rateType']) {
		var options = document.getElementById('rateType').options;
		for (var i = 0; i < options.length; i++)
			if (data['rateType'] == options[i].value) {
				document.getElementById('rateType').selectedIndex = i;
				break;
			}
	}

	if (data['method']) {
		var options = document.getElementById('method').options;
		for (var i = 0; i < options.length; i++)
			if (data['method'] == options[i].value) {
				document.getElementById('method').selectedIndex = i;
				break;
			}
	}

	if (data['payments'])
		setPayments(data['payments']);
	if (data['loans'])
		setLoans(data['loans']);
	if (data['rateDate'])
		document.getElementById('rateDate').value = data['rateDate'];
	ud();
}

var requestData = [];

function ph() {
	var hash = document.location.hash;
	if (hash.charAt(0) == '#')
		hash = hash.substring(1);
	if (!hash)
		return requestData;
	var items = hash.split("&");
	for (var i = 0; i < items.length; i++) {
		var data = items[i].split("=");
		if (data.length == 2) {
			requestData[data[0]] = data[1];
		}
	}

	return requestData;
}

function uh(requestData) {

	var res = '';
	for (var word in requestData) {
		if (requestData[word])
   			res += "&" + word + "=" + requestData[word];
	}
	if (document.location.hash != res)
		history.pushState(res, document.title, (res? '#' + res.substring(1) : ''));
}

function clearCalc() {
	$('#loanAmount').val('');
	$('#dateStart').val('');
	$('#dateFinish').val('');
	document.getElementById('resultPane').innerHTML = '';
	$('.resultAppearing').hide();
	$('#pays').html('');
	$('#loans').html('');
	payId = -1;
	addPay();
	loanId = -1;
	uh([]);
}

function copyToClipboard(elementId) {
	fnSelect(elementId);
	document.execCommand("copy");
	fnDeSelect();
}

function copyTableToClipboard(elementId) {
	showTnx();
	if (copyToClipboard(elementId))
		alert('Таблица скопирована в буфер обмена\nЧтобы вставить, нажмите Ctrl+V');
	else {
		fnSelect(elementId);
		alert('К сожалению, Ваш браузер не позволяет автоматически копировать текст.\nВам нужно самостоятельно нажать Ctrl+C и таблица скопируется');
	}
}

function copyHrefToClipboard(elementId) {
	document.getElementById(elementId).style.display = 'block';
	var res = copyToClipboard(elementId);
	document.getElementById(elementId).style.display = 'none';
	if (res)
		alert('Ссылка на результаты скопирована в буфер обмена');
	else
		alert('К сожалению, Ваш браузер не позволяет автоматически копировать текст.\nВы можете самостоятельно скопировать ссылку из адресной строки браузера.');
	showTnx();
}

function fnSelect(objId) {
	fnDeSelect();
	if (document.selection) {
		var range = document.body.createTextRange();
		range.moveToElementText(document.getElementById(objId));
		range.select();
	}
	else if (window.getSelection) {
		var range = document.createRange();
		range.selectNode(document.getElementById(objId));
		window.getSelection().addRange(range);
	}
}
function fnDeSelect() {
	if (document.selection)
		document.selection.empty();
	else if (window.getSelection)
		window.getSelection().removeAllRanges();
}

/************************************/

function setPayments(params) {
	var elements = params.split(';');
	if (elements.length == 0)
		return;
	var d = elements[0].split('_');
	$('#payDate0').val(d[0]);
	$('#paySum0').val(d[1]);
	$('#payFor0').val(d[2]);

	for (var i = 1; i < elements.length; i++) {
		d = elements[i].split('_');
		addPaymentInsert(d[0], d[1], d[2]);
	}
}

function addPaymentInsert(date, sum, payFor) {
	var d = ggg('payDate' + payId);
	var s = ggg('paySum' + payId);
	var o = ggg('payFor' + payId);
	if (d || s || o)
		addPay();
	var id = payId;
	$('#payDate' + id).val(date);
	$('#paySum' + id).val(sum);
	$('#payFor' + id).val(payFor);
}

function pastePayments() {
	var el = $('#payImportText');
	var text = el.val();
	el.val('');
	var data = parseTableText(text);
	for (var i = 0; i < data.length; i++) {
		var d = data[i];
		addPaymentInsert(fd(d.date), d.sum, d.order);
	}
	closePaymentImport();
}

function openPaymentImport() {
	$('#payImport').show();
	$('#payImportText').focus();
}

function closePaymentImport() {
	$('#payImport').hide();
}

var payId = 0;

function addPay() {
	payId++;
	var str = '<div id="pay' + payId + '">'
			+ '<input name="payDates[]" class="payDate" id="payDate' + payId + '" placeholder="дд.мм.гггг" type="text" value="">'
			+ ' <input name="paySums[]" class="paySum" id="paySum' + payId + '" type="text" placeholder="сумма оплаты">'
			+ ' <input name="payFor[]" class="payDate" id="payFor' + payId + '" placeholder="мм.гггг" type="text" value="">'
			+ ' <label style="display:none" title="Оплата за текущий месяц"><input name="payOrders[]" class="payOrder" id="payOrder' + payId + '" type="checkbox"> за тек. мес. </label>'
			+ ' <input  class="payAdd" type="button" value="+" title="Добавить строчку" onclick="addPay()">';
	if (payId)
			str += ' <input class="payRemove" type="button" value="-" title="Удалить строчку" onclick="removePay(' + payId + ')">';
	str += ' </div>';
	$('#pays').append(str);
	$("#payDate" + payId).datepicker(dpOptions);
	$("#payFor" + payId).datepicker(dpOptions2);

}

function removePay(id) {
	$('#pay' + id).remove();
}

/********************************/


var loanId = -1;

function addLoan(afterId) {
	loanId++;
	var str = '<div id="loan' + loanId + '">'
		+ '<input name="loanDates[]" class="payDate" id="loanDate' + loanId + '" placeholder="дд.мм.гггг" type="text" value="" onchange="checkVacationInput(\'lfWarn' + loanId + '\', \'loanDate' + loanId + '\', true)">'
		+ ' <input name="loanSums[]" class="paySum" id="loanSum' + loanId + '" type="text" placeholder="сумма долга">'
		+ ' <input  class="payAdd" type="button" value="+" title="Добавить строчку" onclick="addLoan(' + loanId + ')">'
		+ ' <input class="payRemove" type="button" value="-" title="Удалить строчку" onclick="removeLoan(' + loanId + ')">'
		+ '<div id="lfWarn' + loanId + '" class="notice" style="display: none;  color:#000"></div>'
		+ ' </div>';
	var el;
	if (afterId !== undefined) {
		el = $('#loan' + afterId);
	} else
		el = null;
	if (el && el.length) {
		el.after(str);
	} else {
		$('#loans').append(str);
	}
	$("#loanDate" + loanId).datepicker(dpOptions);

}

function addLoanMonth() {
	var date, sum;
	var addLoans = $('#loans').find('input.payDate');
	var i;
	for (i = addLoans.length - 1; i >= 0; i--)
		if (addLoans[i].value)
			break;
	if (i < 0) {
		date = ggg('dateStart');
		sum = ggg('loanAmount');
	} else {
		var id = addLoans[i].getAttribute('id').substring('loanDate'.length);
		date = ggg('loanDate' + id);
		sum = ggg('loanSum' + id);
	}
	if (!date)
		return;
	date = dateParse(date);
	if (!date)
		return;
	date = new Date(date.getFullYear(), date.getMonth() + 1, date.getDate());
	addLoanInsert(fd(date), sum);
}

function setLoans(params) {
	var elements = params.split(';');
	if (elements.length == 0)
		return;
	for (var i = 0; i < elements.length; i++) {
		var d = elements[i].split('_');
		addLoanInsert(d[0], d[1]);
	}
}

function addLoanInsert(date, sum) {
	addLoan();
	var id = loanId;
	$('#loanDate' + id).val(date);
	$('#loanDate' + id).change();
	$('#loanSum' + id).val(sum);
}

function pasteLoans() {
	var el = $('#loanImportText');
	var text = el.val();
	el.val('');
	var data = parseTableText(text);
	for (var i = 0; i < data.length; i++) {
		var d = data[i];
		if (!ggg('dateStart') && !ggg('loanAmount')) {
			document.getElementById('dateStart').value = fd(d.date);
			document.getElementById('loanAmount').value = d.sum;
		} else
			addLoanInsert(fd(d.date), d.sum, d.order);
	}
	closeLoanImport();
}

function parseTableText(text) {
	var lines = text.split('\n');
	var res = [];
	for (var i = 0; i < lines.length; i++) {
		var data = lines[i].split('\t');
		if (data.length < 2) continue;
		var sum = normalizeLoan(data[1]);
		var date = dateParse(data[0]);
		if (!(date && sum))
			continue;

		res.push({date: date, sum: sum});
	}
	return res;
}

function removeLoan(id) {
	$('#loan' + id).remove();
}

function openLoanImport() {
	$('#loanImport').show();
	$('#loanImportText').focus();
}

function closeLoanImport() {
	$('#loanImport').hide();
}

function formEnterToTab(e) {
	var self = $(this)
	  , form = self.parents('form:eq(0)')
	  , focusable
	  , next
	  ;
	if (e.keyCode == 13) {
		focusable = form.find('input[type=text],a,select').filter(':visible:not([readonly]):enabled');
		next = focusable.eq(focusable.index(this)+1);
		if (next.length) {
			next.focus();
		} else if (form.submit) {
			form.submit();
		}
		return false;
	}
}
/************************************/
function showTnx() {
	if (!$.cookie || $.cookie('calc-tnx')) return;
	$.cookie('calc-tnx', '1', {expires: 30, path: '/'});
	$('#duh-wrap').show();
}

function parseHash() {
	ph();
}

function updateHash(requestData) {
	uh(requestData);
}

function ud(d) {
	updateData(d);
}

