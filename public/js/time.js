$(function () {
    $('#datetimepicker1').datetimepicker({
        inline: true,
        sideBySide: true,
        format: 'LT',
        stepping: 20,
        minDate: new Date(),
        maxDate: moment({h: 24})
    });
});
$(function () {
    $('#datetimepicker5').datetimepicker({
        stepping: 20,
        disabledDates: [
            new Date(),
        ],
        minDate: new Date()
    });
});

$('#btnAujourdhui').click(function () {
    $('#aujourdhui').removeClass('hidden');
    $('#plusTard').addClass('hidden');
    $('#btnAujourdhui').addClass('selected');
    $('#btnPlusTard').removeClass('selected');
    $('#when').addClass('toHideOnMobile');
    $('#retour').addClass('visiblePhone');
    $('#prixPrevu').removeClass('hidden');
})

$('#btnPlusTard').click(function () {
    $('#plusTard').removeClass('hidden');
    $('#aujourdhui').addClass('hidden');
    $('#btnPlusTard').addClass('selected');
    $('#btnAujourdhui').removeClass('selected');
    $('#when').addClass('toHideOnMobile');
    $('#retour').addClass('visiblePhone');
    $('#prixPrevu').removeClass('hidden');
})


$('#retour').click(function () {
    $('#plusTard').addClass('hidden');
    $('#aujourdhui').addClass('hidden');
    $('#when').removeClass('toHideOnMobile');
    $('#retour').removeClass('visiblePhone');
    $('#prixPrevu').addClass('hidden');
})

let disabledDates = [new Date(2020, 7, 28)
    , new Date(2020, 7, 29)
    , new Date(2020, 7, 30)
    , new Date(2020, 7, 31)
    , new Date(2020, 8, 1)
    , new Date(2020, 8, 2)
    , new Date(2020, 8, 3)
    , new Date(2020, 8, 4)
    , new Date(2020, 8, 5)
    , new Date(2020, 8, 6),
    new Date(2020, 8, 7)

];

let reservation = new Reservation();
$('body').trigger('reservationCreated')
$('#datetimepicker1').on('change.datetimepicker', function(e){
    console.log(e.date)
    reservation.setDate(e.date)
})

$('#datetimepicker5').on('change.datetimepicker', function(e){
    reservation.setDate(e.date)
})
