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
 * %GROUP_NAME% Group's name
 * Additional variables are available for some strings.
 *
 * Most messages (except photo captions, for instance) may
 * use Markdown encoding for formatting.
 * You may also use most Unicode emojis in the text.
 */

const WEBSITE_START = "https://www.uniurb.it/it/portale/index.php?mist_id=23000&lang=IT&tipo=S1&page=3752";

const TEXT_UNNAMED_GROUP = "Senza nome";
const TEXT_FAILURE_GENERAL = "Oh! Questo è imbarazzante… Qualcosa è andato storto!\nChi di dovere è stato avvertito e si sta occupando dell’errore.";
const TEXT_FAILURE_NOT_REGISTERED = "Non mi sembra tu sia registrato. 🤔\nSegui le <a href=\"" . WEBSITE_START . "\">istruzioni sul sito ufficiale</a> per iniziare.";
const TEXT_FAILURE_GROUP_ALREADY_ACTIVE = "Sei già pronto per giocare.";
const TEXT_FAILURE_GROUP_INVALID_STATE = "Sembra che il tuo gruppo non sia pronto per giocare. 🙁 Segui le istruzioni che ti sono state date.";
const TEXT_FAILURE_SCHOOL_INVALID = "Il codice non sembra essere valido. 🙁";

// Response to "/help"
const TEXT_CMD_HELP = "Trovi le informazioni per usare il bot sul <a href=\"" . WEBSITE_START . "\">sito ufficiale dell’Open Week</a>.";

// Response to "/reset"
const TEXT_CMD_RESET = "Reset effettuato.";

// Responses to "/start"
const TEXT_CMD_START_NEW = "Ciao, %FULL_NAME%! Benvenuto al bot dell’<b>Open Week</b> presso l’Università di Urbino. Segui le <a href=\"" . WEBSITE_START . "\">istruzioni sul sito ufficiale</a> per iniziare.";
const TEXT_CMD_START_REGISTERED = "Bentornato, %FULL_NAME%! Questo è il bot dedicato all’<b>Open Week</b> presso l’Università di Urbino.";

const TEXT_CMD_START_UNKNOWN_PAYLOAD = "Non ho capito… Forse hai scritto a mano un link ma sarebbe bene che usassi i link contenuti nei <i>QR Code</i> così come sono.";

const TEXT_CMD_START_TARGET_1 = "Benvenuto ad Urbino, io sono Zelda, il BOT dell’Università degli Studi di Urbino ‘Carlo Bo’ e ti guiderò alla scoperta dell’Università durante questa giornata di orientamento. Sono stato progettato e sviluppato da docenti e da ex-studenti del Corso di Laurea di Informatica Applicata di questa Università.";
const TEXT_CMD_START_TARGET_1_QUESTION = "Sai in che anno è stata fondata L’Università degli Studi di Urbino ‘Carlo Bo’?";
const TEXT_CMD_START_TARGET_1_KEYBOARD = [ [ 'Nel 1506' ], [ 'Nel 1706' ], [ 'Nel 1906' ] ];
const TEXT_CMD_START_TARGET_1_RESPONSE = 1506;
const TEXT_CMD_START_TARGET_1_CORRECT = "Complimenti hai indovinato! L’Università di Urbino, fondata nel 1506, con la sua storia  ultra-cinquecentenaria è una delle università più antiche d’Europa. Nel 2003 l'università è stata intitolata al senatore a vita Carlo Bo che ne è stato il magnifico rettore per cinquantaquattro anni, dal 1947 al 2001.";
const TEXT_CMD_START_TARGET_1_WRONG = "Peccato non hai indovinato! Pensa, le origini dell’Università di Urbino risalgono al 1506. L’Università di Urbino con la sua storia ultra-cinquecentenaria è una delle università più antiche d’Europa. Nel 2003 l'università è stata intitolata al senatore a vita Carlo Bo che ne è stato il magnifico rettore per cinquantaquattro anni, dal 1947 al 2001.";

const TEXT_CMD_START_TARGET_2 = "Benvenuto al Polo Didattico Volponi. Ora avrai l’intera mattinata per poter conoscere l’intera offerta formativa dell’Università degli Studi di Urbino ‘Carlo Bo’. Il primo appuntamento è in aula magna al piano E dove il Rettore dell’Università sarà felice di descriverti i punti di forza della nostra Università. In seguito potrai esplorare, ai piani D, C, B  le sale espositive di tutti i corsi di laurea presenti ad Urbino, e al piano A i servizi di supporto allo studio.";
const TEXT_CMD_START_TARGET_2_QUESTION = "Sai quanti studenti sono attualmente iscritti alla nostra università?";
const TEXT_CMD_START_TARGET_2_KEYBOARD = [ [ 'Circa 8000' ], [ 'Circa 10000' ], [ 'Più di 13000' ] ];
const TEXT_CMD_START_TARGET_2_RESPONSE = 13000;
const TEXT_CMD_START_TARGET_2_CORRECT = "Complimenti, hai indovinato!! Gli iscritti attuali sono più di 13.000. Pensa che il comune di Urbino ha circa 15.000 abitanti e quindi gli studenti dell’Università sono un numero quasi pari a quello degli abitanti. Questo significa che l’intera città è un vero e proprio campus universitario.";
const TEXT_CMD_START_TARGET_2_WRONG = "Peccato non hai indovinato! Gli iscritti attuali sono molti di più. Sono addirittura più di 13.000. Pensa che il comune di Urbino ha circa 15.000 abitanti e quindi gli studenti dell’Università sono un numero quasi pari a quello degli abitanti. Questo significa che l’intera città è un vero e proprio campus universitario.";

const TEXT_CMD_START_TARGET_3 = "Ciao, ti trovi di fronte al Teatro Sanzio e alle tue spalle puoi vedere la facciata del Palazzo Ducale con i suoi famosissimi Torricini. Cosa ne dici di scattare selfie con i tuoi compagni e di inviarmelo? Ci penserò io poi ad inoltrarlo a tutti gli altri partecipanti.";
const TEXT_CMD_START_TARGET_3_NOT_PHOTO = "Inviami un tuo <i>selfie</i>, per favore.";
const TEXT_CMD_START_TARGET_3_CHANNEL_CAPTION = "%FIRST_NAME% saluta dal selfie point!";

const TEXT_CMD_START_TARGET_4 = "Eccoci arrivati al Collegio Tridente. Il prossimo passo sarà trovare la mensa e goderti una meritata pausa pranzo. Scendi al piano sottostante. Lì troverai quello che cerchi. Buon pranzo. Puoi utilizzare la pianta del collegio per orientarti…";
const TEXT_CMD_START_TARGET_4_QUESTION = "Sai quanti sono i posti letto disponibili presso i collegi universitari?";
const TEXT_CMD_START_TARGET_4_KEYBOARD = [ [ 'Circa 800' ], [ 'Circa 1400' ], [ 'Più di 5000' ] ];
const TEXT_CMD_START_TARGET_4_RESPONSE = 1400;
const TEXT_CMD_START_TARGET_4_CORRECT = "Esatto! Avrai a disposizione più di 1.400 posti letto dislocati nelle 7 residenze universitarie. Inoltre sappi che sono a tua disposizione 3 mense con oltre 850 posti totali. Ricorda che lo studente è al centro delle nostre attenzioni.";
const TEXT_CMD_START_TARGET_4_WRONG = "Peccato non hai indovinato! I posti disponibili sono più di 1.400 dislocati nelle 7 residenze universitarie. Inoltre sappi che sono a tua disposizione 3 mense con oltre 850 posti totali. Ricorda che lo studente è al centro delle nostre attenzioni.";

const TEXT_CMD_START_TARGET_5 = "Complimenti sei arrivato alla tappa finale del percorso di orientamento. Qua potrai goderti gli spettacoli offerti dalle associazioni studentesche presso il teatro del collegio.";
const TEXT_CMD_START_TARGET_5_QUESTION = "Sai chi è stato il famoso architetto che ha progettato i collegi universitari di Urbino?";
const TEXT_CMD_START_TARGET_5_KEYBOARD = [ [ 'Giancarlo De Carlo' ], [ 'Massimiliano Fuksas' ], [ 'Renzo Piano' ] ];
const TEXT_CMD_START_TARGET_5_RESPONSE = [ "giancarlo de carlo", "de carlo", "carlo", "giancarlo", "decarlo" ];
const TEXT_CMD_START_TARGET_5_CORRECT = "Esattamente! I collegi universitari ed alcuni interventi nel centro storico sono stati progettati dall'architetto Giancarlo De Carlo. È stato tra i primi a sperimentare ed applicare in architettura la partecipazione da parte degli utenti nelle fasi di progettazione.";
const TEXT_CMD_START_TARGET_5_WRONG = "No. I collegi universitari ed alcuni interventi nel centro storico sono stati progettati dall'architetto Giancarlo De Carlo. È stato tra i primi a sperimentare ed applicare in architettura la partecipazione da parte degli utenti nelle fasi di progettazione.";

const TEXT_CMD_START_ALREADY_REACHED = "Sei già stato in questo luogo.";

// Commands
const TEXT_CMD_REGISTER_CONFIRM = "Benvenuto al bot dell’<b>Open Week</b> presso l’Università di Urbino! 🎉";
const TEXT_CMD_REGISTER_REGISTERED = "Risulti già registrato. 👍";
const TEXT_CMD_REGISTER_SCHOOL_OK = "Ok, quindi studi presso la scuola %SCHOOL_NAME% di %SCHOOL_PLACE%.";

// States
const TEXT_STATE_NEW = "Per completare la registrazione, fornisci qui il <i>codice meccanografico</i> della tua scuola di provenienza.";
const TEXT_STATE_REG_OK = "Quando raggiungi Urbino, scannerizza il <i>QR Code</i> che troverai a Borgo Mercatale o a Porta Santa Lucia. Ci vediamo lì!";
const TEXT_STATE_1 = "Come primo passo dovrai raggiungere il <b>Polo Didattico Volponi</b> che si trova in via Saffi n. 15. Utilizza la mappa per raggiungere più facilmente la tua destinazione.";
const TEXT_STATE_1_LOCATION = [ 43.722022, 12.636611 ];
const TEXT_STATE_2 = "Sulla via della mensa, puoi passare al <i>selfie point</i> di fronte ai famosi Torricini del Palazzo Ducale di Urbino. Ti invio la posizione del punto da raggiungere.";
const TEXT_STATE_2_LOCATION = [ 43.724226, 12.635721 ];
const TEXT_STATE_3 = "Bella foto! Ora dirigiti ai collegi universitari per raggiungere la mensa.";
const TEXT_STATE_3_LOCATION = [ 43.720846, 12.624561 ];
const TEXT_STATE_4 = "Ora ci sposteremo nella parte più alta dei collegi universitari, il teatro del <b>Collegio La Vela</b>. Lì ti aspettano delle esibizioni delle associazioni studentesche per mostrarti uno spaccato di quelle che potrebbero essere le attività da svolgere ad Urbino nel tempo libero. Buon divertimento!";
const TEXT_STATE_4_LOCATION = [ 43.717976, 12.626624 ];
const TEXT_STATE_5 = "Hai concluso la caccia al tesoro! 🏁";
const TEXT_STATE_ARCHIVED = "La caccia al tesoro è conclusa. Spero di rivederti presto a Urbino, magari in uno dei nostri corsi! 👋";

// Default response for anything else
const TEXT_FALLBACK_RESPONSE = "Scusami, non ho capito cosa intendi. Usa i comandi /start o /help.";
const TEXT_UNREQUESTED_PHOTO = "Grazie per la foto!";
const TEXT_UNSUPPORTED_OTHER = "Piano, piano!\n\nPurtroppo non gestisco questo tipo di messaggi: inviami solo messaggi testuali per piacere.";
const TEXT_PLACEHOLDER = "???";
