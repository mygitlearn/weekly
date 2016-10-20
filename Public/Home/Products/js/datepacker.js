//--Jquery Select2--
$("#e1").select2();
$("#e2").select2({
    placeholder: "Select a State",
    allowClear: true
});

//--Bootstrap Date Picker--
$('.date-picker').datepicker();

//--Bootstrap Time Picker--
$('#timepicker1').timepicker();

//--Bootstrap Date Range Picker--
$('#reservation').daterangepicker();

//--JQuery Autosize--
$('#textareaanimated').autosize({ append: "\n" });

//--Fuelux Spinner--
$('.spinner').spinner();


//--jQuery MiniColors--
$('.colorpicker').each(function () {
    $(this).minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom left',
        change: function (hex, opacity) {
            if (!hex) return;
            if (opacity) hex += ', ' + opacity;
            try {
                console.log(hex);
            } catch (e) { }
        },
        theme: 'bootstrap'
    });

});


//---Jquery Knob--
$(".knob").knob();


//---noUiSlider--
$("#sample-minimal").noUiSlider({
    range: [0, 100],
    start: [20, 80],
    connect: true,
    serialization: {
        mark: ',',
        resolution: 0.1,
        to: [[$("#minimal-label1"), 'html'],
            [$('#minimal-label2'), 'html']]
    }
});

$("#sample-onehandle").noUiSlider({
    range: [20, 100],
    start: 40,
    step: 20,
    handles: 1,
    connect: "lower",
    serialization: {
        to: [$("#low"), 'html']
    }
});
$("#sample-onehandle-upper").noUiSlider({
    range: [20, 100],
    start: 70,
    step: 20,
    handles: 1,
    connect: "upper",
    serialization: {
        to: [$("#low"), 'html']
    }
});
$('.slider').noUiSlider({
    range: [0, 255],
    start: 127,
    handles: 1,
    connect: "lower",
    orientation: "vertical",
    serialization: {
        resolution: 1
    }
    , slide: function () {

        var color = 'rgb(' + $("#red").val()
            + ',' + $("#green").val()
            + ',' + $("#blue").val()
            + ')';

        $(".result").css({
            background: color
            , color: color
        });
    }
});

$(".sized-slider").noUiSlider({
    range: [0, 100],
    start: 50,
    handles: 1,
    connect: "lower",
    serialization: {
        to: [$("#low"), 'html']
    }
});

$(".colored-slider").noUiSlider({
    range: [0, 100],
    start: 30,
    handles: 1,
    connect: "lower",
    serialization: {
        to: [$("#low"), 'html']
    }
});

//--jQRangeSlider--
$(".sized-rangeslider").rangeSlider();
$(".colored-rangeslider").rangeSlider();
$("#rangeslider").rangeSlider();
$("#editrangeslider").editRangeSlider();
$("#daterangeslider").dateRangeSlider();
$("#noarrowsrangeslider").rangeSlider({ arrows: false });
$("#boundsrangeslider").rangeSlider({ bounds: { min: 10, max: 90 } });
$("#dvrangeslider").rangeSlider({ defaultValues: { min: 13, max: 66 } });
$("#delayrangeslider").rangeSlider({ valueLabels: "change", delayOut: 4000 });
$("#durationrangeslider").rangeSlider({ valueLabels: "change", durationIn: 1000, durationOut: 1000 });
$("#disabledrangeslider").rangeSlider({ enabled: false });
$("#steprangeslider").rangeSlider({ step: 10 });
$("#labelsrangeslider").rangeSlider({ valueLabels: "hide" });
$("#simlescalesrangeslider").rangeSlider({
    scales: [
        // Primary scale
        {
            first: function (val) { return val; },
            next: function (val) { return val + 10; },
            stop: function (val) { return false; },
            label: function (val) { return val; },
            format: function (tickContainer, tickStart, tickEnd) {
                tickContainer.addClass("myCustomClass");
            }
        },
        // Secondary scale
        {
            first: function (val) { return val; },
            next: function (val) {
                if (val % 10 === 9) {
                    return val + 2;
                }
                return val + 1;
            },
            stop: function (val) { return false; },
            label: function () { return null; }
        }]
});
var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
$("#dateRulersExample").dateRangeSlider({
    bounds: { min: new Date(2012, 0, 1), max: new Date(2012, 11, 31, 12, 59, 59) },
    defaultValues: { min: new Date(2012, 1, 10), max: new Date(2012, 4, 22) },
    scales: [{
        first: function (value) { return value; },
        end: function (value) { return value; },
        next: function (value) {
            var next = new Date(value);
            return new Date(next.setMonth(value.getMonth() + 1));
        },
        label: function (value) {
            return months[value.getMonth()];
        },
        format: function (tickContainer, tickStart, tickEnd) {
            tickContainer.addClass("myCustomClass");
        }
    }]
});