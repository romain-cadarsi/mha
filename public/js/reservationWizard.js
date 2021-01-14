
$('#wizard3').steps({
    headerTag: 'h2',
    bodyTag: '.wizard-content',
    autoFocus: true,
    enableAllSteps: true,
    labels: {
        cancel: "Annuler",
        current: "Etape actuelle:",
        finish: "Terminer",
        loading: "Chargement ...",
        next: "Suivant",
        pagination: "Pagination",
        previous: "Précedent",
    },
    titleTemplate: '<span class="number">#index#</span><span class="title">#title#</span>',
    onStepChanging: function (event, currentIndex, priorIndex) {
        return verify(currentIndex)
    },
    onFinished: function (event, currentIndex) {
        if(!$('#terms').prop('checked')){
            INSPIRO.elements.notification("Veuillez cocher la case",
                "Vous n'avez pas accepté les conditions générales de ventes", "warning");

        }
        else{
            storeCommande();
        }

    }
});


function verify(step) {
    switch (step){
        case 1 :
            return (reservation._date !== undefined)
        case 2 :
            if(verifyFieldsStep(step)){
                reservation.fillConfirmation()
                return true;
            }
            else {
                return false;
            }
            break
        default:
            return verifyFieldsStep(step)
    }


}

function verifyFieldsStep(step) {
    let valid = true;
    let i = 0;
    $(stepsVars[step]).each(function () {
        if ($(stepsVars[step][i]).val() === "") {
            valid = false;
        }
        i++;
    })
    if (step === 0) {
        if(!reservation._adresseDepart || !reservation._adresseArrivee || !reservation._distance){
            valid = false;
        }
    }
    return valid;
}

function storeCommande() {
    $.ajax({
        url : '/index.php/xhr/storeCommande?reservation=' + JSON.stringify(reservation)
    }).done(function (response){
        if (response){
            INSPIRO.elements.notification("Réservation envoyée",
                "Merci, votre réservation est prise en compte, vous recevrez une confirmation de la part du chauffeur", "success");
        }
        else{
            INSPIRO.elements.notification("Tentative échouée",
                "Oups, votre réservation à connu un problème, veuillez essayer de nouveau ", "warning");

        }
    })

}

$('#email').on("focusout",function (){
    $.ajax({
        url: "/index.php/xhr/doesThisClientStored?mail=" + $('#email').val(),
    }).done(function (data){
        $('#clientRecognized').html(data);
    })
})
