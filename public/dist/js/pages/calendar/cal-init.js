!(function ($) {
    "use strict";

    var CalendarApp = function () {
        this.$body = $("body");
        (this.$calendar = $("#calendar")),
            (this.$event = "#calendar-events div.calendar-events"),
            (this.$categoryForm = $("#add-new-event form")),
            (this.$extEvents = $("#calendar-events")),
            (this.$modal = $("#my-event")),
            (this.$saveCategoryBtn = $(".save-category")),
            (this.$calendarObj = null);
    };

    CalendarApp.prototype.onEventClick = function (calEvent, jsEvent, view) {
        var $this = this;

        // Tampilkan waktu mulai dan waktu selesai di modal atau di elemen HTML yang sesuai
        var startTime = calEvent.start.format("YYYY-MM-DD HH:mm:ss");
        var endTime = calEvent.end
            ? calEvent.end.format("YYYY-MM-DD HH:mm:ss")
            : "";
        var ruangan = calEvent.ruangan;

        // Contoh: Tampilkan di modal
        $this.$modal.find(".event-title").text(calEvent.title);
        $this.$modal.find(".event-start").text(startTime);
        $this.$modal.find(".event-end").text(endTime);
        $this.$modal.find(".event-ruangan").text(ruangan);
        $this.$modal.modal();
    };
    CalendarApp.prototype.enableDrag = function () {
        //init events
        $(this.$event).each(function () {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()), // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data("eventObject", eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0, //  original position after the drag
            });
        });
    };

    /* Initializing */
    CalendarApp.prototype.init = function () {
        this.enableDrag();

        var $this = this;
        $this.$calendarObj = $this.$calendar.fullCalendar({
            // ... konfigurasi head saat hosting
            events: `/api/kalender`,
            height: 500, // Atur tinggi kalender
            width: 600,
            header: {
                left: 'prev,next today',
                center: 'title',
            },
            // ... konfigurasi lainnya ...
            eventRender: function (event, element) {
                // Add Bootstrap tooltip to each event element
                var titleText =
                    "Kegiatan: " +
                    event.title +
                    "<br>Tempat: " +
                    event.ruangan +
                    "<br>Kegiatan Mulai: " +
                    event.start.format("HH:mm") +
                    (event.end
                        ? "<br>Kegiatan Selesai: " + event.end.format("HH:mm")
                        : "");

                element.attr("title", titleText);

                // Add additional data attributes if needed
                element.attr("data-location", event.location);
                element.attr("data-description", event.description);
                element.tooltip({
                    title: event.title, // You can customize this based on your event data
                    placement: "top",
                    trigger: "hover",
                    container: "body",
                    html: true,
                });
            },
        });
    };

    //init CalendarApp
    ($.CalendarApp = new CalendarApp()),
        ($.CalendarApp.Constructor = CalendarApp);
})(window.jQuery),
    //initializing CalendarApp
    $(window).on("load", function () {
        $.CalendarApp.init();
    });
