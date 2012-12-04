/* MySQL */

DROP TABLE IF EXISTS admin;

DROP TABLE IF EXISTS borrow;
DROP TABLE IF EXISTS reservation;
DROP TABLE IF EXISTS copy;
DROP TABLE IF EXISTS reader;

DROP TABLE IF EXISTS is_keyword;  # vazebni tabulka
DROP TABLE IF EXISTS keyword;
DROP TABLE IF EXISTS is_author;  # vazebni tabulka
DROP TABLE IF EXISTS author;

DROP TABLE IF EXISTS title;
DROP TABLE IF EXISTS titletype;
DROP TABLE IF EXISTS publisher;

DROP TABLE IF EXISTS is_manager;  # vazebni tabulka
DROP TABLE IF EXISTS section;
DROP TABLE IF EXISTS librarian;

CREATE TABLE admin (
  admin_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  admin_login VARCHAR(30) NOT NULL,
  admin_pass CHAR(41) NOT NULL,
  UNIQUE (admin_login));

create table keyword (
  keyword_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  keyword_word VARCHAR(64) NOT NULL);  # keyword should be short

Create table author (
  author_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  author_name VARCHAR(20) NOT NULL,
  author_surname VARCHAR(60) NOT NULL,
  author_birthdate DATE,
  author_desc TEXT);

Create table titletype (
  titletype_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  titletype_type VARCHAR(64) NOT NULL,
  titletype_desc TEXT);

create table publisher (
  publisher_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  publisher_name VARCHAR(128) NOT NULL,
  publisher_addr VARCHAR(256),
  publisher_phone VARCHAR(20),  -- predstava VoIP klienta se silenym identifikatorem
  publisher_fax VARCHAR(17),
  publisher_www VARCHAR(256),
  publisher_email VARCHAR(256),
  publisher_desc TEXT);

create table title (
  title_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title_title VARCHAR(256) NOT NULL,
  title_subtitle VARCHAR(256),
  title_series VARCHAR(256),
  title_edition TINYINT UNSIGNED,
  title_year YEAR,  # nepredpokladame LiPouv papyrus, Sumerske desticky, atd.
  title_volume TINYINT UNSIGNED,  # rocnik
  title_num SMALLINT UNSIGNED,  # FIXME kontrola v PHP <= 366
  title_pages SMALLINT UNSIGNED,
  title_isbn varchar(13),  # podporujeme pouze ISBN 13 (muze obsahovat krome cisel znak x)
  title_issn varchar(8),  # krome cisel muze obsahovat znak x
  title_price SMALLINT UNSIGNED,
  title_lang ENUM('cz', 'en', 'de', 'sk', 'pl', 'es', 'fr') DEFAULT 'cz',
  title_annotation TEXT,
  title_desc TEXT,
  title_copycount SMALLINT UNSIGNED NOT NULL,
  title_copycountavail SMALLINT UNSIGNED NOT NULL,  # FIXME kontrola v PHP <= copycount

  titletype_id INT UNSIGNED NOT NULL,
  publisher_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (titletype_id) REFERENCES titletype (titletype_id),
  FOREIGN KEY (publisher_id) REFERENCES publisher (publisher_id));

/* vazebni tabulka */
create table is_keyword (
  keyword_id INT UNSIGNED NOT NULL,
  title_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (keyword_id, title_id),

  FOREIGN KEY (title_id) REFERENCES title (title_id),
  FOREIGN KEY (keyword_id) REFERENCES keyword (keyword_id));

/* vazebni tabulka */
create table is_author (
  author_id INT UNSIGNED NOT NULL,
  title_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (author_id, title_id),

  FOREIGN KEY (title_id) REFERENCES title (title_id),
  FOREIGN KEY (author_id) REFERENCES author (author_id));

create table librarian (
  librarian_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  librarian_birthnumber CHAR(11) NOT NULL,
  librarian_birthday DATE NOT NULL,
  librarian_name VARCHAR(20) NOT NULL,
  librarian_surname VARCHAR(60) NOT NULL,
  librarian_addr VARCHAR(256) NOT NULL,
  librarian_contactaddr VARCHAR(256),  # dorucovaci adresa
  librarian_phone VARCHAR(20),  # predstava VoIP klienta se silenym identifikatorem
  librarian_email VARCHAR(256),
  librarian_entrydate DATE NOT NULL,  # datum nastupu
  librarian_login VARCHAR(30) NOT NULL,
  librarian_pass CHAR(41) NOT NULL,
  UNIQUE (librarian_login));

create table section (
  section_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  section_name VARCHAR(128) NOT NULL,
  section_location VARCHAR(256) NOT NULL);

/* vazebni tabulka */
create table is_manager (
  section_id INT UNSIGNED NOT NULL,
  librarian_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (section_id, librarian_id),

  FOREIGN KEY (section_id) REFERENCES section (section_id),
  FOREIGN KEY (librarian_id) REFERENCES librarian (librarian_id));

create table reader (
  reader_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  reader_birthnumber CHAR(11) NOT NULL,
  reader_birthday DATE NOT NULL,
  reader_name VARCHAR(20) NOT NULL,
  reader_surname VARCHAR(60) NOT NULL,
  reader_addr VARCHAR(256) NOT NULL,
  reader_contactaddr VARCHAR(256),  # dorucovaci adresa
  reader_phone VARCHAR(20),  # predstava VoIP klienta se silenym identifikatorem
  reader_email VARCHAR(256),
  reader_regdate DATE NOT NULL,  # datum registrace
  reader_ticket INT UNSIGNED NOT NULL,  # library ticket
  reader_validity DATE NOT NULL,
  reader_login VARCHAR(30) NOT NULL,
  reader_pass CHAR(41) NOT NULL,
  UNIQUE (reader_login));

create table reservation (
  reservation_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  reservation_date DATE NOT NULL,
  reservation_from DATE NOT NULL,
  reservation_to DATE NOT NULL,

  title_id INT UNSIGNED NOT NULL,
  reader_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (title_id) REFERENCES title (title_id),
  FOREIGN KEY (reader_id) REFERENCES reader (reader_id));

create table copy (
  copy_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  /* y k_dispozici; n neni_k_dispozici*/
  copy_state ENUM('y', 'n') DEFAULT 'y',
  /* n nove; o bezne opotrebeni; p poskozena; v na vyrazeni */
  copy_condition ENUM('n', 'o', 'p', 'v') DEFAULT 'n',
  copy_loanperiod SMALLINT UNSIGNED,  # jednotkou jsou dny

  title_id INT UNSIGNED NOT NULL,
  section_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (title_id) REFERENCES title (title_id),
  FOREIGN KEY (section_id) REFERENCES section (section_id));

create table borrow (
  borrow_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  borrow_from DATE NOT NULL,
  borrow_to DATE NOT NULL,

  copy_id INT UNSIGNED NOT NULL,
  reader_id INT UNSIGNED NOT NULL,
  librarian_id INT UNSIGNED NOT NULL,

  FOREIGN KEY (copy_id) REFERENCES copy (copy_id),
  FOREIGN KEY (reader_id) REFERENCES reader (reader_id),
  FOREIGN KEY (librarian_id) REFERENCES librarian (librarian_id));


###############################
##########  CONTENT  ##########
###############################

INSERT INTO admin VALUES
  (NULL, 'verotor', PASSWORD('admin')),
  (NULL, 'dumblob', PASSWORD('admin'));

/* naplnime tabulky */
insert into keyword values (NULL, 'Hry');
insert into keyword values (NULL, 'PC');
insert into keyword values (NULL, 'Navody');
insert into keyword values (NULL, 'Web');
insert into keyword values (NULL, 'Programovani');
insert into keyword values (NULL, 'Duchovno');
insert into keyword values (NULL, 'Statistika');
insert into keyword values (NULL, 'Román');
insert into keyword values (NULL, 'Drama');
insert into keyword values (NULL, 'Poezie');

insert into author values (
  NULL, 'Neznámý', 'Neznámý', NULL, 'Neznamy autor nebo skupina vice autoru.');
insert into author values (
  NULL, 'Jindrich', 'Cigánek', NULL, 'Stavar.');
insert into author values (
  NULL, 'Jaroslav', 'Zendulka', NULL, 'Dekan FIT VUTBR.');
insert into author values (
  NULL, 'Lucie', 'Sedláčková', '1982-10-25', 'Lucie Sedláčková vystudovala obor Český jazyk a literatura na Filozofické fakultě Univerzity Karlovy v Praze. Pracuje jako redaktorka v nakladatelství.');
insert into author values (
  NULL, 'Emily', 'Brontëová', NULL, '');
insert into author values (
  NULL, 'Jan Amos', 'Komenský', NULL, '');
insert into author values (
  NULL, 'Karel', 'Čapek', NULL, '');
insert into author values (
  NULL, 'Karel Jaromír', 'Erben', NULL, '');
insert into author values (
  NULL, 'Karel Hynek', 'Mácha', NULL, '');

insert into titletype values (
  NULL, 'Kniha', 'Svetova, domaci, technicka i netechnicka (beletrie atd.) literatura.');
insert into titletype values (
  NULL, 'Casopis', 'Odborne ci neodborne periodikum.');
insert into titletype values (
  NULL, 'Odborna prace', 'Bakalarska ci diplomova prace.');
insert into titletype values (
  NULL, 'Clanek', 'Kratsi odborne pojednani na zadane tema.');

insert into publisher values (
  NULL, 'Lingea s.r.o.', 'Vackova 757/9, 612 00  Brno-Královo Pole', '+420 541 233 160', NULL, 'http://www.lingea.cz', 'info@lingea.cz', 'Vývoj a prodej elektronických a knižních slovníků i dalších jazykových titulů (nejprodávanější slovníky v ČR, dodávky předním světovým vydavatelům). Tvorba jazykových nástrojů a aplikací. Překladatelské služby, korektury, lokalizace, DTP, tlumočení.');
insert into publisher values (
  NULL, 'MONTANEX, a.s.', 'Kutuzovova 25, Ostrava - Vítkovice 703 00', '+420 596 621 161', '+420 596 627 848', 'http://montanex.cz/', 'as@montanex.cz', 'Za více než patnáct let naší existence můžeme říci, že se na českém knižnímu trhu dokážeme velmi dobře orientovat. Máme za sebou nespočetné množství edičních počinů, ve kterých se zúročují mnohaleté zkušenosti s touto činností.');
insert into publisher values (
  NULL, 'Albatros Media a.s.', 'Na Pankráci 1618/30, 140 00  Praha-Nusle', '+420 234 633 260', NULL, 'http://www.albatrosmedia.cz', 'albatros@albatrosmedia.cz', 'Mediální společnost nabízející pod značkami Albatros, BizBooks, CooBoo, CPress, Computer Press, Edika, Motto, Plus, XYZ a v projektu Edice České televize široké spektrum produktů - knih, interaktivních encyklopedií na CD-ROM, audionahrávek a DVD.');
insert into publisher values (
  NULL, 'Český statistický úřad - Redakce Demografie', 'Na padesátém 81, 100 82 Praha 10', '274054065', NULL, 'http://www.czso.cz/csu/redakce.nsf/i/kontakt_demografie', 'pavel.ctrnact@czso.cz', 'Komunikace se zástupci médií, organizace tiskových konferencí, vydávání tiskových zpráv, článků a podkladů pro novináře. Vydávání časopisů ČSÚ (Demografie, Statistika a Statistika and My) a dalších publikací');
insert into publisher values (
  NULL, 'Nakladatelství Bor', 'Údolní 541/17, 460 01 Liberec 14', '485 104 374', NULL, 'http://www.naklbor.cz/', 'info@naklbor.cz', 'Nakladatelství Bor bylo založeno na jaře 2000 se základním záměrem vydávat knihy tematicky spjaté s Náchodskem, odkud pochází zakladatelka a majitelka Eva Koudelková. V této oblasti je to dosud jediné takto profilované nakladatelství.');
insert into publisher values (
  NULL, 'LEDA', 'Plzeňská 3217/16, 150 00 Praha 5', '296 336 980', NULL, 'http://www.leda.cz/', 'leda@leda.cz', '');
insert into publisher values (
  NULL, 'WaldPress', '', '', NULL, '', '', '');
insert into publisher values (
  NULL, 'ARTUR', '', '', NULL, '', '', '');
insert into publisher values (
  NULL, 'FRAGMENT', '', '', NULL, '', '', '');
insert into publisher values (
  NULL, 'Nakladatelství Franze Kafky', '', '', NULL, '', '', '');
insert into publisher values (
  NULL, 'ACADEMIA', '', '', NULL, '', '', '');

insert into title values (
  NULL, 'VELKÉ PŘÍBĚHY BIBLE', NULL, NULL,
  1, 2011, NULL, NULL, NULL, '9788072253500', NULL, 734, 'cz',
  'Tato kniha je určena všem věřícím, kteří se chtějí seznámit s kořeny své víry, i nevěřícím, kteří se chtějí seznámit s kořeny euro-americké civilizace. Publikace pojednává o knihách Starého i Nového zákona, které jsou pevně zasazeny do příslušného historického prostředí, takže uvádí také řadu mimobiblickýchh sdělení z historie Izraele a okolních národů, které souvisí s popisovanými ději a osobnostmi.',
  'Kniha je systematicky řazena v časové posloupnosti popisovaných dějů a obsahuje řadu přehledových tabulek a mapových příloh, usnadňujících orientaci čtenářů. Na rozdíl od Bible obsahuje také mnohé další informace, po staletí přenášené od úst k ústům tradicí, které dokreslují kolorit popisovaných událostí.',
  10, 10, 1, 2);
insert into title values (
  NULL, 'Obchodní němčina', 'Vše, co potřebujete pro rozvoj písemného i ústního projevu', NULL,
  3, 2012, NULL, NULL, NULL, '9788026600398', NULL, 365, 'de',
  'Učebnice Obchodní němčina je určena všem zájemcům o získání znalosti odborného jazyka používaného ve světě obchodu.',
  'S její pomocí lze rozvinout kvalitu písemného projevu a rovněž schopnost vést obchodní jednání v daném jazyce. Každá kapitola obsahuje: výklad problematiky; frazeologii; poznámky ke gramatice a stylu jazyka používaného ve světě obchodu; cvičení; texty k poslechovým cvičením; dialogy. Nahrávka napomůže utvrzení slovní zásoby a procvičení výslovnosti, najdete zde dialogy, fráze a další poslechová cvičení. Na konci učebnice je zařazen přehled nepravidelných sloves, německo-český a česko-německý odborný slovník a klíč ke cvičením. Témata jsou seřazena podle logického průběhu projednání obchodního případu – seznámíte se s tím, jak mají být strukturovány žádosti, objednávky, kupní smlouvy, projdete problematiku dodacích podmínek, způsob platby, reklamace, pojištění, pocvičíte si zákonitosti obchodního jednání.',
  5, 3, 1, 3);
insert into title values (
  NULL, 'Počítačový démon', NULL, 'Čtyři kamarádi v akci - Brezina T.',
  2, 2012, NULL, NULL, 136, '9788000026947', NULL, 161, 'cz',
  NULL,
  'Pozor, počítačový démon útočí! Erikův monitor se v noci sám od sebe rozsvítí a na něm se objeví rozšklebená tvář příšery z počítačových her, která mu začne diktovat příkazy. Erik je zděšený. Jak je možné, že se počítač sám zapojil do sítě a odesílá data? S kamarády se pustí do pátrání po zákeřném programátorovi. Najdou ho dřív, než spustí nebezpečný projekt ohrožující celosvětovou počítačovou síť?',
  2, 1, 1, 3);
insert into title values (
  NULL, 'Demografie, revue pro výzkum populačního vývoje', NULL, NULL,
  1, 2011, 53, 4, 108, NULL, '18027881', 58, 'en',
  'Časopis je jediným odborným demografickým časopisem v České republice. Vychází od roku 1959 a vydává jej Český statistický úřad. Zveřejňuje aktuální články, analýzy a přehledy o populačním vývoji v ČR i v zahraničí, poskytuje data o sňatečnosti, rozvodovosti, porodnosti, úmrtnosti, potratovosti, o migraci a analýzy populačních cenzů. Ve zpravodajské části přináší informace o české a zahraniční literatuře v podobě recenzí a anotací a o hlavních demografických akcích.',
  NULL,
  1, 0, 2, 4);
insert into title values (
  NULL, 'Islám v médiích', 'Mediální reprezentace sporu o karikatury islámského proroka Mohameda v Mladé frontě DNES', 'Jazyky a texty',
  2, 2010, NULL, NULL, 124, '9788086807652', NULL, 189, 'cz',
  NULL,
  'Pro mnoho lidí může být spojení masových médií a rasismu nečekané. Tento vztah je však těsnější, než se na první pohled zdá. Dokazuje to i tato kritická analýza mediální reprezentace sporu o karikatury proroka Mohameda v deníku Mladá fronta Dnes v roce 2006. authorka analyzuje reprezentaci sociálních činitelů a intertextualitu ve vztahu k sociálnímu kontextu a mediálnímu diskurzu. Islám je spojován s celou řadou mýtů, stereotypů a předsudků, ke kterým současná média vydatně přispívají. Islám představují většinou jako nebezpečí. Cílem této knihy je přispět k pochopení vzniku islamofobních postojů v českých médiích, a poukázat tak na užitečnost kritického přístupu k médiím.',
  1, 1, 1, 5);
insert into title values (
  NULL, 'Na větrné hůrce', NULL, NULL,
  13, 2009, NULL, NULL, 374, '9788073351939', NULL, 219, 'cz',
  NULL,
  'Slavný romantický a dramatický román Emily Brontëové se odehrává v tajuplném prostředí samoty uprostřed mokřin, kam přichází nový nájemce, aby nečekaně prožil strašidelné noční dobrodružství a začal pátrat po osudech nevlídných a drsných obyvatel usedlosti. Objevuje příběh, na jehož počátku stojí nenaplněná láska, cit, jenž jako zhoubný oheň spálil duši a zanechal v ní jenom pustošivou nenávist a celoživotní touhu po pomstě. Heathcliffova neukojitelná krutost po léta ničí životy lidí z usedlosti, pak ale láska, kterou nelze řídit rozumem, znovu zasáhne do osudů hlavních hrdinů.',
  3, 3, 1, 6);
insert into title values (
  NULL, 'Labyrint světa a ráj srdce', NULL, NULL,
  1, 2005, NULL, NULL, 183, '8090323235', NULL, 450, 'cz',
  NULL,
  'Významné dílo Jana Amose Komenského v nádherné grafické úpravě a s originálními ilustracemi Rut Kohnové.',
  2, 2, 1, 7);
insert into title values (
  NULL, 'Bílá nemoc', NULL, NULL,
  2, 2005, NULL, NULL, 96, '9788087128527', NULL, 169, 'cz',
  NULL,
  'Drama o třech aktech ve 14 obrazech.',
  1, 1, 1, 8);
insert into title values (
  NULL, 'Válka s mloky', NULL, NULL,
  1, 2010, NULL, NULL, 237, '9788025310786', NULL, 199, 'cz',
  NULL,
  'Světově proslulý utopistický sci-fi román, kterým Karel Čapek poukázal na nebezpečí rozmachu fašismu a reálnou hrozbu druhé světové války. V oceánu lidé objevují zvláštní tvory - mloky, podivuhodně učenlivé a inteligentní živočichy, velmi podobné člověku. Začnou je cvičit jako levnou pracovní sílu pro podmořské stavby. Mloci však záhy potřebují stále větší prostor, vzbouří se a nakonec vyhlašují lidstvu válku. Hrozí zbraněmi, které jim lidé na počátku sami dali. Záhubu lidské civilizace odvrátí až šovinismus panující jak mezi lidmi, tak mloky, který jejich útočnou jednotu nakonec rozleptá.',
  1, 1, 1, 9);
insert into title values (
  NULL, 'Kytice', NULL, NULL,
  1, 2010, NULL, NULL, 149, '9788025310779', NULL, 199, 'cz',
  NULL,
  'Klasické dílo české literatury, nejznámější a nejoblíbenější sbírka baladických básní 19. století, kterou Karel Jaromír Erben začal psát již jako student. Balady inspirované slovanskými i jinými pověstmi se vyznačují dějově dramatickým spádem a stručně vykresleným prostředím.',
  1, 1, 1, 9);
insert into title values (
  NULL, 'Máj', NULL, NULL,
  1, 2004, NULL, NULL, 61, '9788025310656', NULL, 129, 'cz',
  NULL,
  'author své kultovní dílo Máj vydal jako šestadvacetiletý v roce 1836 vlastním nákladem v šesti stech výtiscích. Porozumění od svých obrozeneckých souputníků se však nedočkal. Máchovu velikost rozpoznali až následující generace. Poznejte a připomeňte si ji i vy.',
  1, 1, 1, 9);

insert into is_keyword values (6, 1);
insert into is_keyword values (2, 2);
insert into is_keyword values (3, 2);
insert into is_keyword values (2, 3);
insert into is_keyword values (4, 3);
insert into is_keyword values (5, 3);
insert into is_keyword values (7, 4);
insert into is_keyword values (3, 5);
insert into is_keyword values (6, 5);
insert into is_keyword values (7, 5);
insert into is_keyword values (8, 6);
insert into is_keyword values (6, 7);
insert into is_keyword values (9, 8);
insert into is_keyword values (8, 9);
insert into is_keyword values (10, 10);
insert into is_keyword values (10, 11);

insert into is_author values (1, 2);
insert into is_author values (1, 3);
insert into is_author values (1, 4);
insert into is_author values (2, 1);
insert into is_author values (4, 5);
insert into is_author values (5, 6);
insert into is_author values (6, 7);
insert into is_author values (7, 8);
insert into is_author values (7, 9);
insert into is_author values (8, 10);
insert into is_author values (9, 11);

insert into librarian values (
  NULL, '900227/3478', '1990-02-27', 'Jan', 'Pacner', 'Poricska 58, 54932 Velke Porici', NULL, '731251966', 'xpacne00@stud.fit.vutbr.cz', '2000-12-24', 'xpacne00', PASSWORD('xpacne00pass'));
insert into librarian values (
  NULL, '860118/1642', '1986-01-18', 'Frantisek', 'Maly', 'Ulice0, 12345 Zname mesto', NULL, '956346274', NULL, '2010-10-05', 'framal02', PASSWORD('framal02pass'));

insert into section values (
  NULL, 'Beletrie', '1. patro - na chodbe vzadu, dvere 256');
insert into section values (
  NULL, 'Odborna literatura', 'prizemi - u hlavniho vchodu, dvere 3');
insert into section values (
  NULL, 'Dobrodruzna literatura', '3. patro - vedle vytahu, vchod 302');
insert into section values (
  NULL, 'Cizi jazyky', '1. podzemni podlazi');
insert into section values (
  NULL, 'Periodika', '1. patro - pred sekci Beletrie, dvere 207');

insert into is_manager values (1, 1);
insert into is_manager values (1, 2);
insert into is_manager values (2, 1);
insert into is_manager values (3, 2);
insert into is_manager values (4, 2);
insert into is_manager values (5, 1);

insert into reader values (
  NULL, '501218/4355', '1954-11-20', 'Jana', 'Bohdanova', 'K Rybniku 2, 09874 Vesnice00', 'Poricska 9, 10003 Praha 14', 246246246, 'janbog@email.com', '2000-02-20', 345, '2014-12-12', 'jbogda00', PASSWORD('jbogda00pass'));
insert into reader values (
  NULL, '481914/4557', '1980-09-04', 'Petr', 'Marsik', 'Masarykova 34, 63473 Mesto00', NULL, 234254564, 'petr@marsik.cz', '2007-10-11', 354, '2015-01-01', 'pmarsi00', PASSWORD('pmarsi00pass'));
insert into reader values (
  NULL, '000101/1234', '2000-01-01', 'Josef', 'Velky', 'Pricna 458, 98743 Neznamy zapadakov', NULL, NULL, NULL, '2011-07-03', 45, '2013-07-03', 'josvel20', PASSWORD('josvel20pass'));

insert into copy values (
  NULL, 'y', 'n', '40 0:0:0', 1, 3);
insert into copy values (
  NULL, 'n', 'o', '65 0:0:0', 2, 4);
insert into copy values (
  NULL, 'y', 'p', '190 0:0:0', 3, 3);
insert into copy values (
  NULL, 'n', 'v', '0 23:59:59', 4, 5);
insert into copy values (
  NULL, 'y', 'n', '32 0:0:0', 5, 3);
insert into copy values (
  NULL, 'y', 'o', '40 0:0:0', 6, 1);
insert into copy values (
  NULL, 'y', 'p', '40 0:0:0', 6, 1);
insert into copy values (
  NULL, 'y', 'v', '40 0:0:0', 6, 1);
insert into copy values (
  NULL, 'y', 'n', '40 0:0:0', 7, 1);
insert into copy values (
  NULL, 'y', 'o', '40 0:0:0', 7, 1);
insert into copy values (
  NULL, 'y', 'p', '40 0:0:0', 8, 1);
insert into copy values (
  NULL, 'y', 'o', '40 0:0:0', 9, 1);
insert into copy values (
  NULL, 'y', 'n', '40 0:0:0', 10, 1);
insert into copy values (
  NULL, 'y', 'n', '40 0:0:0', 11, 1);

/* pujcuje se i o vikendech */
insert into borrow values (
  NULL, '2004-09-09', '2004-10-15', 1, 1, 1);
insert into borrow values (
  NULL, '2005-01-31', '2005-02-28', 5, 1, 2);
insert into borrow values (
  NULL, '2007-01-16', '2007-01-17', 4, 1, 1);
insert into borrow values (
  NULL, '2008-06-16', '2008-12-13', 3, 2, 2);
insert into borrow values (
  NULL, '2009-01-16', '2009-02-01', 1, 1, 2);
insert into borrow values (
  NULL, '2011-11-01', '2012-01-01', 2, 3, 1);

insert into reservation values (
  NULL, '2008-10-20', '2008-10-20', '2008-12-21', 4, 1);
insert into reservation values (
  NULL, '2009-03-27', '2010-03-27', '2010-04-01', 3, 2);
insert into reservation values (
  NULL, '2010-12-01', '2010-12-15', '2011-12-16', 2, 1);
insert into reservation values (
  NULL, '2011-09-04', '2011-09-04', '2011-10-04', 1, 3);

# vim: set ft=mysql:
