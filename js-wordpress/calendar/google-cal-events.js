/**
 * Get a list of google calendar events.
 *
 * This is a modified version of the one done by Milan Kacurak.
 *
 * You can find his version here: https://github.com/MilanKacurak/FormatGoogleCalendar
 * And maybe here               : https://www.kacurak.com/blog/turn-google-calendar-into-events-list-on-website
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Milan Kačurák
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */
var googleCalEvents = (function() {

    'use strict';
    var globalSettings;

    //Gets JSON from Google Calendar and transfroms it into html list items and appends it to past or upcoming events list
    var init = function(settings) {
        var result;
        globalSettings = settings;

        //Get JSON, parse it, transform into list items and append it to past or upcoming events list
        var calendarUrl = 'https://www.googleapis.com/calendar/v3/calendars/' + settings.calendarAddress + '/events?key=' + settings.calendarAPI;
        jQuery.getJSON(calendarUrl, function(data) {
            result = data.items;
            result.sort(comp).reverse();

            var pastCounter = 0,
                upcomingCounter = 0,
                pastResult = [],
                upcomingResult = [],
                $upcomingElem = jQuery(settings.upcomingSelector),
                $pastElem = jQuery(settings.pastSelector),
                i;

            // Change max number of events based on settings.
            if      (settings.past        === false) { settings.maxPast = 0; }
            else if (settings.maxPast     === -1)    { settings.maxPast = result.length; }
            if      (settings.upcoming    === false) { settings.maxUpcoming = 0; }
            else if (settings.maxUpcoming === -1)    { settings.maxUpcoming = result.length; }

            for (i in result) {
                if (isPast(result[i].end.dateTime || result[i].end.date)) {
                    if (pastCounter < settings.maxPast) {
                       pastResult.push(result[i]);
                       pastCounter++;
                    }
                } else {
                    upcomingResult.push(result[i]);
                }
            }

            upcomingResult.reverse(); // chronological order, from past to present

            pastCounter = 1;
            for (i in pastResult) {
                $pastElem.append(transformationList(pastResult[i], settings.format, settings.times, pastCounter === 1, pastCounter === pastResult.length));
                pastCounter++;
            }

            upcomingCounter = 1;
            for (i in upcomingResult) {
                if (upcomingCounter < settings.maxUpcoming) {
                    $upcomingElem.append(transformationList(upcomingResult[i], settings.format, settings.times, upcomingCounter === 1, upcomingCounter === upcomingResult.length || upcomingCounter === settings.maxUpcoming));
                    upcomingCounter++;
                } else {
                    break;
                }
            }


            if ($upcomingElem.children().length !== 0) { // upcoming events
                $("div").addClass(function(index, currentClass) {
                    var newClass;
                    if (currentClass === "cal") { newClass="cal-calendar"; }
                    return newClass;
                });
                jQuery(settings.upcomingHeading).insertBefore($upcomingElem);
            } else if (settings.maxUpcoming !== 0) {     // no upcoming events
                jQuery(settings.noUpcomingHeading).insertBefore($upcomingElem);
            }

            if ($pastElem.children().length !== 0) {
                $("div").addClass(function(index, currentClass) {
                    var newClass;
                    if (currentClass === "cal") { newClass="cal-calendar"; }
                    return newClass;
                });
                jQuery(settings.pastHeading).insertBefore($pastElem);
            } else if (settings.maxUpcoming !== 0) {
                jQuery(settings.noPastHeading).insertBefore($pastElem);
            }
        });
    };
    // Comparison function for sorting events based on Date.getTime().
    var comp = function(a, b) {
        return new Date(a.start.dateTime || a.start.date).getTime() - new Date(b.start.dateTime || b.start.date).getTime();
    };
    // Check if an event is in the past.
    var isPast = function(date) {
        var compareDate = new Date(date),
            now = new Date();
        if (now.getTime() > compareDate.getTime()) { return true; }
        return false;
    };
    // Transforms the result list into human readable results.
    var transformationList = function(result, format, times, first, last) {
        // Date indicates ALL DAY EVENT
        // Date time indicates it is NOT an all day event.
        var isAllDayEvent = result.start.date !== undefined,
            dateStart = getDateInfo(result.start.dateTime||result.start.date, isAllDayEvent),
            dateEnd   = getDateInfo(result.end.dateTime||result.end.date, isAllDayEvent),
            monthStart    = dateStart[1] + 1,
            linkDateVal   = dateStart[2].toString() + (monthStart < 10 ? '0' + monthStart : monthStart) + (dateStart[0] < 10 ? '0' + dateStart[0] : dateStart[0]),
            eventClassOut = first ? 'cal-event cal-event-first' : last ? 'cal-event cal-event-last' : 'cal-event',
            output        = '<div class="'+eventClassOut+'">',
            summary       = result.summary || '',
            description   = result.description || '',
            location      = result.location || '',
            i;
        if (isAllDayEvent) { dateEnd = subtractOneMinute(dateEnd); }

        for (i = 0; i < format.length; i++) {

            format[i] = format[i].toString();

            if (format[i] === '*summary*') {
                if (globalSettings.links === true) {
                    output = output.concat('<div class="cal-summary"><a class="cal-link" href="' + result.htmlLink +'">' + summary + '</a></div>');
                } else {
                    output = output.concat('<div class="cal-summary">'+summary+'</div>');
                }
            } else if (format[i] === '*date*') {
                output = output.concat(getFormattedDate(dateStart, dateEnd, isAllDayEvent, times));
            } else if (format[i] === '*description*') {
                output = output.concat('<div class="cal-description">' + description + '</div>');
            } else if (format[i] === '*location*') {
                output = output.concat('<div class="cal-location">' + location + '</div>');
            } else if (format[i] === '*starttime*' && times && !isAllDayEvent) {
                output = output.concat(getFormattedTime(dateStart));
            } else if (format[i] === '*endtime*' && times && !isAllDayEvent) {
                output = output.concat(getFormattedTime(dateEnd));
            } else {
                output = output.concat(format[i]);
            }
        }

        return output + '</div>';
    };
    // Returns array with [day #, month #, year, hours, minutes, weekday #]
    var getDateInfo = function(date, isAllDayEvent) {
        var info = date.toString().split('-');
        var ndate = isAllDayEvent ? new Date(info[0],parseInt(info[1])-1,info[2],0,0,0) : new Date(date);
        return [ndate.getDate(), ndate.getMonth(), ndate.getFullYear(), ndate.getHours(), ndate.getMinutes(), ndate.getDay()];
    };
    // Check differences between dates and output appropriate string representation.
    var getFormattedDate = function(dateStart, dateEnd, isAllDayEvent) {
        var formattedDate = '';
        if (dateStart[2] !== dateEnd[2] || dateStart[1] !== dateEnd[1] || dateStart[0] !== dateEnd[0]) { // Different years, or different months, or different days.
            //month day, year - month day, year
            formattedDate = formatDateDifferentDay(dateStart, dateEnd);
        } else {
            //month day, year
            formattedDate = formatDateSameDay(dateStart, dateEnd, isAllDayEvent);
        }
        return formattedDate;
    };
    // Gets the formatted date for an event starting one day and ending another
    var formatDateDifferentDay = function(dateStart, dateEnd) {
        return '<div class="cal-date"><div class="cal-date-day">'+dateStart[0]+'</div><div class="cal-date-sub"><div class="cal-date-month">'+getMonthName(dateStart[1])+'</div><div class="cal-date-year">'+dateStart[2]+'</div></div></div><div class="cal-date-to">-</div><div class="cal-date cal-date-end"><div class="cal-date-day">'+dateEnd[0]+'</div><div class="cal-date-sub"><div class="cal-date-month">'+getMonthName(dateEnd[1])+'</div><div class="cal-date-year">'+dateEnd[2]+'</div></div></div>';
    };
    // Gets the formatted date for an event that starts and ends on the same day.
    var formatDateSameDay = function(dateStart, dateEnd, isAllDayEvent) {
        return '<div class="cal-date"><div class="cal-date-day">'+dateStart[0]+'</div><div class="cal-date-sub"><div class="cal-date-month">'+getMonthName(dateStart[1])+'</div><div class="cal-date-year">'+dateStart[2]+'</div></div></div>';
    };
    // Gets the time in a formatted string.
    var getFormattedTime = function(date) {
        var period = 'AM',
            hour   = date[3],
            minute = date[4];
        // Handle afternoon.
        if (hour >=12) {
            period = 'PM';
            hour = hour > 12 ? hour-12 : hour;
        }
        // Handle midnight.
        if (hour === 0) { hour = 12; }
        // Ensure 2 digit minute value.
        minute = (minute < 10 ? '0' : '') + minute;
        // Format time. XX:XXAM
        return (hour + ':' + minute + period);
    };
    // Gets a strng representing the name of a month.
    var getMonthName = function(month) {
        var monthNames = [
            'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'
        ];

        return monthNames[month];
    };
    // Take a minute off the date.  Use a date object so we don't have to mess withe the
    // fiddly bits.
    var subtractOneMinute = function(dateInfo) {
        var date = getDateFromInfo(dateInfo);
        date.setTime(date.getTime() - 60000);
        return getDateInfo(date);
    };
    // Function to get a Date object from the information we have about the day.
    var getDateFromInfo = function(dateInfo) {
      return new Date(dateInfo[2], dateInfo[1], dateInfo[0], dateInfo[3], dateInfo[4], 0);
    };
    // Overwrites defaultSettings values with overrideSettings and adds overrideSettings if non existent in defaultSettings
    var mergeOptions = function(defaultSettings, overrideSettings){
        var newObject = {},
            i;
        for (i in defaultSettings) {
            newObject[i] = defaultSettings[i];
        }
        for (i in overrideSettings) {
            newObject[i] = overrideSettings[i];
        }
        return newObject;
    };
    return {
        init: function (settingsOverride) {
            var settings = {
              calendarAddress: 'cwhunterjumper@gmail.com',
              calendarAPI: 'AIzaSyAPQVAhGRpl8rDGLFMdYeBAvp1mYzyLr4g',
              past: false,
              upcoming: true,
              dayNames: true,
              times: true,
              maxPast: -1,
              maxUpcoming: 5,
              upcomingSelector: '#cal-upcoming',
              upcomingHeading: '<div class="cal-header">Upcoming events</div>',
              noUpcomingHeading: '<div class="cal-header">There are no events coming up.</div>',
              format: ['*date*', '<div class="cal-event-info">', '*summary*', '*location*', '*description*', '</div>']
            };

            settings = mergeOptions(settings, settingsOverride);

            init(settings);
        }
    };
})();