class Reservation {

    _adresseDepart;
    _adresseArrivee;
    _date;
    _prix;
    _nom;
    _prenom;
    _numero;
    _mail;
    _distance;

    constructor() {
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
        $('#confirmationDate').html(this._date.format(" D/M à HH:mm"));
        if(this._distance){
            this.setPrix(this._distance,this._date)
        }
    }

    getPrix() {
        return this._prix;
    }

    setPrix(distance,date) {
        this._prix = (Math.round((( distance / 1000) * 1.7 )) + ((date._d.getHours() > 18 || date._d.getHours() < 6) ? 10 : 5 ))
        $('#prix').html(this._prix);
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
            this.setPrix(this._distance,this._date)
        }
    }

    fillConfirmation(){
        this.setNom($('#nom').val())
        this.setPrenom($('#prenom').val())
        this.setMail($('#email').val())
        this.setNumero($('#telephone').val())
    }

}
moment().locale('fr');
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

