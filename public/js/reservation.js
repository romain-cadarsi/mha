class Reservation {


    constructor() {
        this._adresseDepart = null;
        this._adresseArrivee  = null;
        this._date  = null;
        this._prix  = null;
        this._nom = null ;
        this._prenom = null ;
        this._numero = null ;
        this._mail = null ;
        this._distance = null ;
        this._paiementMethod = null ;

    }

    getAddresseDepart() {
        return this._adresseDepart;
    }

    setAddresseDepart(value) {
        this._adresseDepart = value;
        $('#confirmationAller').html(this._adresseDepart);
    }

    getAdresseArrivee() {
        return this._adresseArrivee;
    }

    setAdresseArrivee(value) {
        this._adresseArrivee = value;
        $('#confirmationDepot').html(this._adresseArrivee);
    }

    getDate() {
        return this._date;
    }

    setDate(value) {
        this._date = value;
        $('#confirmationDate').html(this._date.format("D/M à HH:mm"));
        if(this._distance){
            this.setPrix(this._distance,value)
        }
    }

    getPrix() {
        return this._prix;
    }

    setPrix(distance,date) {
            $('#prix').html('...')
            $.ajax({
                url : 'index.php/xhr/getPrix?infos='+ JSON.stringify({
                    'from' :    this.getAddresseDepart(),
                    'to' : this.getAdresseArrivee(),
                    'h' :  date._d.getHours()
                }),
            }).done(function (data){
                this._prix = JSON.parse(data)['prix']
                $('#prix').html(this._prix);
            })
        }

    getNom() {
        return this._nom;

    }

    setNom(value) {
        this._nom = value;
        $('#confirmationNom').html(this._nom);
    }

    getPrenom() {
        return this._prenom;
    }

    setPrenom(value) {
        this._prenom = value;
        $('#confirmationPrenom').html(this._prenom);
    }

    getNumero() {
        return this._numero;
    }

    setNumero(value) {
        this._numero = value;
        $('#confirmationTelephone').html(this._numero);
    }

    getMail() {
        return this._mail;
    }

    setMail(value) {
        this._mail = value;
        $('#confirmationMail').html(this._mail);
    }

    getDistance() {
        return this._distance;
    }

    setDistance(value) {
        this._distance = value;
        if (this._date){
            this.setPrix(value,this._date)
        }
    }

    getPaiementMethod() {
        return this._paiementMethod;
    }

    setPaiementMethod(value) {
        this._paiementMethod = value;
    }

    fillConfirmation(){
        this.setNom($('#nom').val())
        this.setPrenom($('#prenom').val())
        this.setMail($('#email').val())
        this.setNumero($('#telephone').val())
    }

}
moment().locale('fr');
moment().tz("Europe/Paris").format();
window['moment-range'].extendMoment(moment);
let stepsVars = [
    [
        '#origin-input',
        '#destination-input'
    ],
    [],
    [
        '#nom',
        '#prenom',
        '#email',
        '#telephone'
    ]
]

