<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Text strings.
 *
 * Generally, all strings can make use of the following variables:
 * %FULL_NAME% User's full name
 * %FIRST_NAME% User's first name
 * %WEEKDAY% Week day in letters
 * Additional variables are available for some strings.
 */

const WEBSITE_START = "https://www.uniurb.it/studiaconnoi/futuri-studenti/orientarsi-e-scegliere/caccia-al-tesoro-digitale-scopriuniversita-di-urbino-con-zelda";

const TEXT_UNNAMED_GROUP = "Senza nome";

const TEXT_FAILURE_GENERAL = "Oh! Questo è imbarazzante… Qualcosa è andato storto!\nChi di dovere è stato avvertito e si sta occupando dell’errore.";
const TEXT_FAILURE_NOT_REGISTERED = "Non mi sembra tu sia registrato. 🤔\nSegui le <a href=\"" . WEBSITE_START . "\">istruzioni sul sito ufficiale</a> per iniziare.";
const TEXT_FAILURE_GROUP_ALREADY_ACTIVE = "Sei già pronto per giocare.";
const TEXT_FAILURE_GROUP_INVALID_STATE = "Sembra che tu non sia pronto per giocare. 🙁 Segui le <a href=\"" . WEBSITE_START . "\">istruzioni</a> che ti sono state date.";
const TEXT_FAILURE_SCHOOL_INVALID = "Il codice della scuola non sembra essere valido. 🙁";

// Response to "/help"
const TEXT_CMD_HELP = "Trovi le informazioni per usare il bot sul <a href=\"" . WEBSITE_START . "\">sito ufficiale dell’Open Week</a>. Per giocare, scansiona i <i>QR Code</i> che trovi nei luoghi in cui si svolge l’Open Week: ti porteranno di nuovo da me.";

// Response to "/reset"
const TEXT_CMD_RESET = "Reset effettuato.";

// Responses to "/start"
const TEXT_CMD_START_WELCOME = "Ciao, %FULL_NAME%! Benvenuto al bot dell’<b>Open Week</b> presso l’<b>Università di Urbino “Carlo Bo”</b>.";
const TEXT_CMD_START_NEW = TEXT_CMD_START_WELCOME . " Segui le <a href=\"" . WEBSITE_START . "\">istruzioni sul sito ufficiale</a> per iniziare.";
const TEXT_CMD_START_REGISTERED = "Bentornato, %FULL_NAME%! Questo è il bot dedicato all’<b>Open Week</b> presso l’<b>Università di Urbino “Carlo Bo”</b>.";

const TEXT_CMD_START_UNKNOWN_PAYLOAD = "Non ho capito… Forse hai scritto a mano un link? Ti prego di scansionare i <i>QR Code</i>.";

const TEXT_WELCOME_PREAMBLE = "Benvenuto ad Urbino!\nIo sono Zelda, il bot dell’Università degli Studi di Urbino “Carlo Bo” e ti guiderò alla scoperta dell’Università durante questa <b>giornata di orientamento</b>. Sono stato progettato e sviluppato da docenti e da ex-studenti del <a href=\"http://informatica.uniurb.it\">Corso di Laurea di Informatica Applicata</a> di questa Università. (Ricordati di seguire il canale Telegram @openuniurb per ricevere aggiornamenti. 📢)";

const TEXT_CMD_FLYER = "Ti guiderò alla scoperta dell’Università durante la <b>giornata di orientamento</b>. Sono stato progettato e sviluppato da docenti e da ex-studenti del <a href=\"http://informatica.uniurb.it\">Corso di Laurea di Informatica Applicata</a> di questa Università. (Ricordati di seguire il canale Telegram @openuniurb per ricevere aggiornamenti. 📢)\nCi vediamo <a href=\"https://www.uniurb.it/universitaaperta\">a Urbino</a>!";

const TEXT_LOCATION_MERCATALE = "Ora ti trovi a <b>Borgo Mercatale</b>!\nDurante il tuo percorso, ti farò alcune domande riguardo all'Università di Urbino. Rispondi scrivendo oppure utilizzando la tastiera che comparirà.";
const TEXT_LOCATION_MERCATALE_QUESTION = "Sai in che anno è stata fondata L’Università degli Studi di Urbino “Carlo Bo”?";
const TEXT_LOCATION_MERCATALE_KEYBOARD = [ [ 'Nel 1506' ], [ 'Nel 1706' ], [ 'Nel 1906' ] ];
const TEXT_LOCATION_MERCATALE_RESPONSE = 1506;
const TEXT_LOCATION_MERCATALE_CORRECT = "Complimenti hai indovinato! L’Università di Urbino, fondata nel 1506, con la sua storia ultra-cinquecentenaria è una delle università più antiche d’Europa. Nel 2003 l’università è stata intitolata al senatore a vita Carlo Bo che ne è stato il magnifico rettore per cinquantaquattro anni, dal 1947 al 2001.";
const TEXT_LOCATION_MERCATALE_WRONG = "Peccato non hai indovinato! Pensa, le origini dell’Università di Urbino risalgono al 1506. L’Università di Urbino con la sua storia ultra-cinquecentenaria è una delle università più antiche d’Europa. Nel 2003 l’università è stata intitolata al senatore a vita Carlo Bo che ne è stato il magnifico rettore per cinquantaquattro anni, dal 1947 al 2001.";
const TEXT_LOCATION_MERCATALE_OFFYOUGO = "Come primo passo dovrai raggiungere il <b>Polo Didattico Volponi</b> che si trova in via Saffi al numero 15. Utilizza la mappa per raggiungere più facilmente la tua destinazione.";
const TEXT_LOCATION_MERCATALE_OFFYOUGO_POSITION = [ 43.722113, 12.636623 ];

const TEXT_LOCATION_STALUCIA = "Ora ti trovi presso la <b>Porta S. Lucia</b>!\nDurante il tuo percorso, ti farò alcune domande riguardo all'Università di Urbino. Rispondi scrivendo oppure utilizzando la tastiera che comparirà.";
const TEXT_LOCATION_STALUCIA_QUESTION = "Sai in che anno è stata fondata L’Università degli Studi di Urbino “Carlo Bo”?";
const TEXT_LOCATION_STALUCIA_KEYBOARD = [ [ 'Nel 1506' ], [ 'Nel 1706' ], [ 'Nel 1906' ] ];
const TEXT_LOCATION_STALUCIA_RESPONSE = 1506;
const TEXT_LOCATION_STALUCIA_CORRECT = "Complimenti hai indovinato! L’Università di Urbino, fondata nel 1506, con la sua storia ultra-cinquecentenaria è una delle università più antiche d’Europa. Nel 2003 l’università è stata intitolata al senatore a vita Carlo Bo che ne è stato il magnifico rettore per cinquantaquattro anni, dal 1947 al 2001.";
const TEXT_LOCATION_STALUCIA_WRONG = "Peccato non hai indovinato! Pensa, le origini dell’Università di Urbino risalgono al 1506. L’Università di Urbino con la sua storia ultra-cinquecentenaria è una delle università più antiche d’Europa. Nel 2003 l’università è stata intitolata al senatore a vita Carlo Bo che ne è stato il magnifico rettore per cinquantaquattro anni, dal 1947 al 2001.";
const TEXT_LOCATION_STALUCIA_OFFYOUGO = "Come primo passo dovrai raggiungere il <b>Polo Didattico Volponi</b> che si trova in via Saffi al numero 15. Utilizza la mappa per raggiungere più facilmente la tua destinazione.";
const TEXT_LOCATION_STALUCIA_OFFYOUGO_POSITION = [ 43.722113, 12.636623 ];

const TEXT_LOCATION_VOLPONI = "Eccoci al <b>Polo Didattico Volponi</b>!\nIl primo appuntamento è in aula magna al <b>piano E</b> dove il Rettore dell’Università sarà felice di descriverti i punti di forza della nostra Università. In seguito potrai esplorare, ai piani B, C e D, le sale espositive di tutti i corsi di laurea presenti ad Urbino, e al piano A i servizi di supporto allo studio.";
const TEXT_LOCATION_VOLPONI_QUESTION = "Sai quanti studenti sono attualmente iscritti alla nostra università?";
const TEXT_LOCATION_VOLPONI_KEYBOARD = [ [ 'Circa 8000' ], [ 'Circa 10000' ], [ 'Più di 13000' ] ];
const TEXT_LOCATION_VOLPONI_RESPONSE = 13000;
const TEXT_LOCATION_VOLPONI_CORRECT = "Complimenti, hai indovinato! Gli iscritti attuali sono più di 13.000. Pensa che il comune di Urbino ha circa 15.000 abitanti e quindi gli studenti dell’Università sono un numero quasi pari a quello degli abitanti. Questo significa che l’intera città è un vero e proprio campus universitario.";
const TEXT_LOCATION_VOLPONI_WRONG = "Peccato non hai indovinato! Gli iscritti attuali sono molti di più: sono addirittura più di 13.000. Pensa che il comune di Urbino ha circa 15.000 abitanti e quindi gli studenti dell’Università sono un numero quasi pari a quello degli abitanti. Questo significa che l’intera città è un vero e proprio campus universitario.";
const TEXT_LOCATION_VOLPONI_OFFYOUGO = "Ora avrai l’intera mattinata per poter conoscere l’intera offerta formativa dell’Università degli Studi di Urbino “Carlo Bo”.";

const TEXT_LOCATION_VOLPONI_EXIT = "Dopo questa mattinata intensa che ne dici di fare una pausa? Ti guiderò fino alla mensa dei collegi universitari dove potrai usufruire dei servizi che normalmente sono riservati agli studenti dell’Università.\nUtilizza la mappa per raggiungere più facilmente la tua destinazione.";
const TEXT_LOCATION_VOLPONI_EXIT_POSITION = [ 43.720846, 12.624561 ];

const TEXT_LOCATION_INFORMATICA = "Hai raggiunto lo stand del Corso di Laurea in <b>Informatica Applicata</b>. Docenti ed ex-studenti di questo corso mi hanno sviluppato. 🤖\nMandami un tuo <i>selfie</i> e ti preparerò un regalo speciale per essere passato…";
const TEXT_LOCATION_INFORMATICA_CAPTION = "Ecco qua. Condividi questa immagine con l’hashtag #infoappl! Non dimenticarti anche di prendere un omaggio al nostro stand.";

const TEXT_LOCATION_TRIDENTE = "Eccoci arrivati al <b>Collegio Tridente</b>. Il prossimo passo sarà trovare la mensa e goderti una meritata pausa pranzo. Scendi al piano sottostante. Lì troverai quello che cerchi. Buon pranzo!";
// Puoi utilizzare la pianta del collegio per orientarti…";
const TEXT_LOCATION_TRIDENTE_QUESTION = "Sai quanti sono i posti letto disponibili presso i collegi universitari?";
const TEXT_LOCATION_TRIDENTE_KEYBOARD = [ [ 'Circa 800' ], [ 'Circa 1400' ], [ 'Più di 5000' ] ];
const TEXT_LOCATION_TRIDENTE_RESPONSE = 1400;
const TEXT_LOCATION_TRIDENTE_CORRECT = "Esatto! Avrai a disposizione più di 1.400 posti letto dislocati nelle 7 residenze universitarie. Inoltre sappi che sono a tua disposizione 3 mense con oltre 850 posti totali. Ricorda che lo studente è al centro delle nostre attenzioni.";
const TEXT_LOCATION_TRIDENTE_WRONG = "Peccato, non hai indovinato! I posti disponibili sono più di 1.400 dislocati nelle 7 residenze universitarie. Inoltre sappi che sono a tua disposizione 3 mense con oltre 850 posti totali. Ricorda che lo studente è al centro delle nostre attenzioni.";
const TEXT_LOCATION_TRIDENTE_OFFYOUGO = "Dopo pranzo ci sposteremo nella parte più alta dei collegi universitari. Dobbiamo raggiungere il <b>teatro del Collegio La Vela</b>. Lì ti aspetteranno delle performance offerte dalle associazioni studentesche per mostrarti uno spaccato di quelle che potrebbero essere le attività da svolgere ad Urbino nel tempo libero. Buon divertimento!\nSe vuoi puoi anche passare per il <i>selfie-point</i> dal quale potrai scattare un selfie proprio qui al Collegio Tridente ed inviarmelo… ne sarei felice.";

const TEXT_LOCATION_SELFIE = "Cosa ne dici di scattare un selfie con i tuoi compagni e di inviarmelo? Ci penserò io poi a ricordarmi di te durane la premiazione finale.";
const TEXT_LOCATION_SELFIE_CAPTION = "Il tuo distintivo per la partecipazione all’Open Week! 🏅";

const TEXT_LOCATION_END_VELA = "Complimenti sei arrivato alla tappa finale del percorso di orientamento. Qua potrai goderti gli spettacoli offerti dai centri servizi culturali e ricreativi dell'Università presso il teatro del collegio.";
const TEXT_LOCATION_END_VELA_CAPTION = TEXT_LOCATION_SELFIE_CAPTION;

const TEXT_END_NO_BADGE = "Siccome non hai raggiunto la tappa del <i>selfie point</i>, inviami ora una tua foto in modo da ricevere un distintivo per la tua partecipazione. 🏅\n(Accertati che il soggetto sia ben centrato nell’immagine.)";
const TEXT_END_P1 = "Hai concluso la caccia al tesoro! 🏁\n";
const TEXT_END_P2 = "Durante il tragitto hai raggiunto ";
const TEXT_END_P3_SING = "solo una tappa";
const TEXT_END_P3_PLUR = "<b>%REACHED_LOCATIONS%</b> tappe";
const TEXT_END_P4 = ", rispondendo correttamente a";
const TEXT_END_P5_NONE = " 0 domande (peccato).";
const TEXT_END_P5_SING = "d <b>una</b> domanda su 3.";
const TEXT_END_P5_PLUR = " <b>%CORRECT_ANSWERS%</b> domande su 3!";
const TEXT_END_P5_ALL  = " <b>tutte</b> le domande! 🏆";
const TEXT_END_P5_CLOSE = "\nÈ stato un piacere accompagnarti durante questa giornata. Spero di trovarti di nuovo a Urbino, magari iscritto ad uno dei nostri corsi! 👋";

/*
const TEXT_CMD_START_TARGET_5 = "Complimenti, sei arrivato alla tappa finale del percorso di orientamento! Qua potrai goderti gli spettacoli offerti dalle associazioni studentesche presso il <b>teatro del collegio</b>.";
const TEXT_CMD_START_TARGET_5_QUESTION = "Sai chi è stato il famoso architetto che ha progettato i collegi universitari di Urbino?";
const TEXT_CMD_START_TARGET_5_KEYBOARD = [ [ 'Giancarlo De Carlo' ], [ 'Massimiliano Fuksas' ], [ 'Renzo Piano' ] ];
const TEXT_CMD_START_TARGET_5_RESPONSE = [ "giancarlo de carlo", "de carlo", "carlo", "giancarlo", "decarlo" ];
const TEXT_CMD_START_TARGET_5_CORRECT = "Esattamente! I collegi universitari ed alcuni interventi nel centro storico sono stati progettati dall’architetto <a href=\"https://it.wikipedia.org/wiki/Giancarlo_De_Carlo\">Giancarlo De Carlo</a>. È stato tra i primi a sperimentare ed applicare in architettura la partecipazione da parte degli utenti nelle fasi di progettazione.";
const TEXT_CMD_START_TARGET_5_WRONG = "No. I collegi universitari ed alcuni interventi nel centro storico sono stati progettati dall’architetto <a href=\"https://it.wikipedia.org/wiki/Giancarlo_De_Carlo\">Giancarlo De Carlo</a>. È stato tra i primi a sperimentare ed applicare in architettura la partecipazione da parte degli utenti nelle fasi di progettazione.";
*/

const TEXT_CMD_START_ALREADY_REACHED = "Sei già stato in questo luogo.";

// Commands
const TEXT_CMD_REGISTER_CONFIRM = "Ciao, %FULL_NAME%! Benvenuto al bot dell’<b>Open Week</b> presso l’<b>Università di Urbino “Carlo Bo”</b>. 🎉";
const TEXT_CMD_REGISTER_REGISTERED = "Risulti già registrato. 👍";
const TEXT_CMD_REGISTER_SCHOOL_OK = "Ok, quindi studi presso la scuola %SCHOOL_NAME% di %SCHOOL_PLACE%.";

const TEXT_STATE_NEW = "Per completare la registrazione, scrivi qui il <i>codice meccanografico</i> della tua scuola di provenienza. (Si tratta di un codice alfanumerico a 10 caratteri.)";
const TEXT_STATE_REG_OK = "Quando raggiungi Urbino, scansiona il <i>QR Code</i> che troverai a <b>Borgo Mercatale</b> o a <b>Porta Santa Lucia</b>. Ci vediamo lì!\nNel frattempo puoi iscriverti al canale Telegram @openuniurb per ricevere aggiornamenti. 📢";

const TEXT_STATE_ARCHIVED = "La tua caccia al tesoro è conclusa. Spero di rivederti presto a Urbino, magari in uno dei nostri corsi! 👋";

// Channel updates
const TEXT_CHANNEL_ARRIVALS_START = "I primi partecipanti alla caccia del tesoro della giornata di %WEEKDAY% stanno arrivando! 🎉";
const TEXT_CHANNEL_ARRIVALS_UPDATE = "👥 Finora ho registrato <b>%COUNT%</b> arrivi a Borgo Mercatale e a Porta Santa Lucia.";
const TEXT_CHANNEL_SELFIE_START = "Qualcuno sembra aver scoperto il <i>selfie point</i> in prossimità del Palazzo Ducale!";
const TEXT_CHANNEL_SELFIE_UPDATE = "🖼️ Ho ricevuto <b>%COUNT%</b> vostre foto. Grazie!";
const TEXT_CHANNEL_COMPLETE_START = "La nostra giornata insieme si sta concludendo: i primi partecipanti hanno concluso la caccia al tesoro di %WEEKDAY%.";
const TEXT_CHANNEL_COMPLETE_UPDATE = "🏁 <b>%COUNT%</b> partecipanti hanno completato la caccia al tesoro!";

// Stat messages
const TEXT_STATS_ARRIVALS_FIRST = "Sei il primo partecipante alla caccia al tesoro questo %WEEKDAY%!";
const TEXT_STATS_ARRIVALS_OTHER = "Sei il %COUNT%° partecipante alla caccia al tesoro di %WEEKDAY%!";
const TEXT_STATS_SELFIE_FIRST = "Questo è il primo selfie che ricevo oggi, fantastico!";
const TEXT_STATS_SELFIE_OTHER = "Questo è il %COUNT%° selfie che ricevo oggi!";
const TEXT_STATS_COMPLETE_FIRST = " (Tra l'altro, sei il primo partecipante a concludere la caccia al tesoro oggi!)";
const TEXT_STATS_COMPLETE_OTHER = " (Tra l'altro, sei il %COUNT%° partecipante a concludere la caccia al tesoro oggi.)";

// Default response for anything else
const TEXT_FALLBACK_RESPONSE = "Scusami, non ho capito cosa intendi. Usa i comandi /start o /help.";
const TEXT_UNREQUESTED_PHOTO = "Grazie per la foto!";
const TEXT_UNSUPPORTED_OTHER = "Piano, piano!\n\nPurtroppo non gestisco questo tipo di messaggi: inviami solo messaggi testuali per piacere.";
const TEXT_EXPECTING_PHOTO = "Inviami un tuo <i>selfie</i>, per favore.";
const TEXT_PLACEHOLDER = "???";

const TEXT_WEEKDAYS = [
    'domenica',
    'lunedì',
    'martedì',
    'mercoledì',
    'giovedì',
    'venerdì',
    'sabato'
];
