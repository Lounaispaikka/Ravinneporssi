-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Palvelin: localhost
-- Luontiaika: 16.12.2013 klo 08:21
-- Palvelimen versio: 5.1.69
-- PHP:n versio: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- RAVINNEPÖRSSI V1.0
--

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_clients`
--

CREATE TABLE IF NOT EXISTS `rp_clients` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL,
  `language` text NOT NULL,
  `types` text NOT NULL,
  `types2` text NOT NULL,
  `current_latitude` decimal(15,12) NOT NULL,
  `current_longitude` decimal(15,12) NOT NULL,
  `current_zoom` int(11) NOT NULL,
  `current_layers` text NOT NULL,
  `current_annotations` text NOT NULL,
  `base_latitude` decimal(15,12) NOT NULL,
  `base_longitude` decimal(15,12) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `company` text NOT NULL,
  `bic` text NOT NULL,
  `name` text NOT NULL,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `postalcode` text NOT NULL,
  `city` text NOT NULL,
  `municipality` text NOT NULL,
  `state` text NOT NULL,
  `country` text NOT NULL,
  `phonenumber` text NOT NULL,
  `fax` text NOT NULL,
  `gsm` text NOT NULL,
  `description` text NOT NULL,
  `trades` text NOT NULL,
  `arsenal` text NOT NULL,
  `favourites` text NOT NULL,
  `contact_via` text NOT NULL,
  `images` text NOT NULL,
  `notifier` tinyint(4) NOT NULL,
  `notifier_contact` text NOT NULL,
  `notifier_threshold` int(11) NOT NULL,
  `notifier_types` text NOT NULL,
  `notifier_products` text NOT NULL,
  `visibility` text NOT NULL,
  `blacklist` text NOT NULL,
  `first_login` tinyint(4) NOT NULL,
  `startup_location` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_userid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `logged_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logged_ip` text NOT NULL,
  `attempt_datetime` datetime NOT NULL,
  `attempt_ip` text NOT NULL,
  `authenticated_datetime` datetime NOT NULL,
  `authenticated_type` text NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `deactivated` tinyint(4) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_content`
--

CREATE TABLE IF NOT EXISTS `rp_content` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_userid` int(11) NOT NULL DEFAULT '0',
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `removable` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Vedos taulusta `rp_content`
--

INSERT INTO `rp_content` (`id`, `parent`, `type`, `name`, `title`, `content`, `added_datetime`, `added_ip`, `added_userid`, `modified_datetime`, `modified_ip`, `modified_userid`, `priority`, `removable`, `published`) VALUES
(1, 0, 'help', 'Yleistä', 'Yleistä tietoa', 'Ravinnepörssi on verkkopalvelu, jossa kaiken kokoiset orgaanisia lannoitteita tuottavat ja niitä vastaanottavat tahot sekä organisaatiot voivat ilmoittaa ravinteiden luovutus- tai vastaanottohalukkuudestaan, verkostoitua, ja hankkia työlle urakoitsijan tai ravinteille kuljettajan.\r\n<br /><br />\r\nPalvelua voi käyttää rekisteröitymättä, mutta kaikki ilmoitukset ja ilmoittajien tiedot eivät näy. Rekisteröitymällä ilmaiseksi pystyt mm. luomaan omia ilmoituksiasi ja ottamaan yhteyttä muihin palvelun käyttäjiin. Palveluun rekisteröidytään aloitussivulta valitsemalla ensin käyttäjätyyppi. Tämän jälkeen täytetään sähköpostiosoite ja nimi sekä salasana.\r\n<br /><br />\r\nRekisteröitymisen jälkeen määriteltyyn sähköpostiosoitteeseen lähetetään vahvistusviesti, jonka linkkiä klikkaamalla uusi käyttäjätunnus aktivoidaan. Vahvistusviesti voi mahdollisesti mennä automaattisesti roskapostikansioon, joten se tulee tarkistaa jos viesti ei saavu perille tunnin kuluessa.\r\n<br /><br />\r\nKun käyttäjätunnus on aktivoitu, palveluun kirjaudutaan etusivulta kirjoittamalla sähköpostiosoite sekä salasana. Palvelusta kirjaudutaan ulos klikkaamalla karttanäkymän vasemmanpuoleisen valikon "Kirjaudu ulos" -nappia.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 1, 0, 1),
(2, 0, 'help', 'Karttanäkymä', 'Karttanäkymän navigointi', 'Palvelun karttanäkymä esittää ilmoitukset, pellot ja muut kohteet kartalla selkeinä ikoneina. Lannoitteen vastaanottajat, luovuttajat ja mm. urakoitsijat on merkitty kartalle omina ikoneina. Klikkaamalla ikonia karttanäkymän oikeaan alakulmaan ilmestyy tietoa ko. kohteesta sekä oikopolut muihin toimintoihin.\r\n<br /><br />\r\nKarttaa liikutetaan perinteisesti hiirellä raahaamalla. Oikeassa yläkulmassa sijaitsevat liikutuskursorien lisäksi kartan zoomausnapit, joilla karttaa voidaan lähentää ja loitontaa. Liikutuskursorien keskellä olevaa kotinappia painamalla siirrytään määriteltyyn kotipaikkaan.\r\n<br /><br />\r\nVasemmassa yläkulmassa sijaitsee karttanäkymän valittavat karttapohjat. Klikkaamalla karttapohjaa karttanäkymä päivittyy uudestaan. Karttapohjien ohessa on näkymävalinnat, joilla kartalla näkyviä ikoneita voidaan piilottaa näkyvistä.\r\n<br /><br />\r\nOikealla ylhäällä olevasta hakupalkista voidaan siirtyä nopeasti eri kohteisiin kirjoittamalla kohteen osoite.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 2, 0, 1),
(3, 0, 'help', 'Profiili', 'Oman profiilin ja asetusten muokkaaminen', 'Klikkaamalla vasemmassa yläkulmassa olevan valikon "Oma profiili" -ikonia käyttäjä voi muokata omia tietojaan sekä kenelle ne ovat näkyvissä. Samalla käyttäjä voi määritellä käyttäjätyyppinsä ja muokata tuotantosuuntiaan. Puutteellinen profiili vaikeuttaa käyttäjän ilmoitusten näkyvyyttä, joten on suositeltavaa määritellä ainakin maakunta, paikkakunta, tuotantosuunnat ja sallitut yhteydenottotavat.\r\n<br /><br />\r\nJokaisella käyttäjällä on oma kotipaikkansa, joka edustaa käyttäjän fyysistä kotipaikkaa kartalla. Kotipaikka on näkyvissä pelkästään käyttäjälle. Kotipaikkaa käytetään reittien hakemisen aloituspisteenä. Kotipaikka sijoitetaan profiilissa määritellyn osoitteen perusteella. Tarkan kotipaikkasijainnin voi määritellä kaksoisklikkaamalla karttaa ja valitsemalla "Siirrä kotipaikka tähän".\r\n<br /><br />\r\nProfiiliin on mahdollista lisätä myös kalustoa ja kuvia. Aktivoimalla Ravinnereino-ominaisuuden voit vastaanottaa automaattisesti sinua kiinnostavia ilmoituksia sähköpostitse ja karttanäkymässä.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 3, 0, 1),
(4, 0, 'help', 'Ilmoitukset', 'Ilmoitusten hakeminen, lisääminen, muokkaaminen ja poistaminen', 'Karttanäkymän vasemmassa yläkulmassa olevasta "Listanäkymä"-ikonista siirrytään palvelun ilmoitusten hakuun. Ilmoituksia haetaan määrittelemällä ilmoituksen tyyppi, maakunta ja paikkakunta sekä mahdolliset lisäkriteerit. Kun hakukriteerit on määritelty, "Päivitä hakutulokset"-nappia klikkaamalla uudet ilmoitukset haetaan. Ilmoituksia on mahdollista lajitella "Ilmoitusten lajittelu" -kohdasta etäisyyden, iän, otsikon ja lannoitemäärän mukaan.\r\n<br /><br />\r\nListanäkymästä voidaan siirtyä ilmoituksen tarkempiin tietoihin tai ko. ilmoituksen paikkaan karttanäkymässä, josta käyttäjä voi siirtyä ilmoituksen lisätietoihin ja ilmoittajan yhteystietoihin. Karttanäkymässä näytetään lyhyesti ilmoituksen tiedot. Klikkaamalla "Näytä tiedot" nähdään kaikki ilmoitukseen määritellyt tuotteet ja lisätiedot.\r\n<br /><br />\r\nKarttanäkymästä on mahdollista lisätä ilmoitus suosikkeihin. Klikkaamalla "Näytä reittiohjeet" palvelu etsii lyhyimmän reitin sekä etäisyyden kohteeseen kotipaikasta. Reitti-ikkunasta on mahdollisuus tallentaa reitti tai näyttää tarkemmat ajo-ohjeet kohteeseen klikkaamalla "Näytä tarkemmat ohjeet", jolloin siirrytään Google Maps -palveluun.\r\n<br /><br />\r\nOma ilmoitus luodaan vasemmanpuoleisesta valikosta tai kaksoisklikkaamalla karttapohjaa, jonka jälkeen valitaan "Ilmoitukset" ja "Luo uusi ilmoitus tähän". Ilmoitukselle määritellään otsikko ja tiedot sekä näkyvyys. Ilmoitus voidaan myös ajastaa päättyväksi tiettynä ajankohtana. Lannoitemäärän kokonaismäärän määrittely selkeyttää ilmoituksen näkyvyyttä. Ilmoitukseen lisätään sisältöä valitsemalla tyyppi sekä tuote. Kaikille tuotteille on omat lisävalintansa, joiden tarkka määrittely edesauttaa ilmoituksen näkyvyyttä. Mahdolliset liitetiedostot voidaan myös lisätä ilmoitukseen.\r\n<br /><br />\r\n<h2>Omien suosikkien lisääminen</h2>\r\nKarttanäkymän ilmoitustiedoista on mahdollista lisätä ilmoituksia omaan "Suosikit"-osioon. Ilmoitus lisätään suosikkeihin klikkaamalla haluttua ilmoitusta kartalla. Tämän jälkeen klikataan "Lisää suosikkeihin" oikealla alakulmassa olevaa tähti-ikonia.\r\n<br /><br />\r\nOmat suosikit löytyvät Toiminnot-valikon "Suosikit"-kohdasta. Suosikkien ja kotipaikan välille voidaan luoda reittejä.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 4, 0, 1),
(5, 0, 'help', 'Viestit', 'Viestien lähettäminen, vastaanottaminen ja muokkaaminen', 'Palvelussa on mahdollista lähettää yksityisviestejä käyttäjien kesken. Uudet viestit näkyvät karttanäkymän vasemman yläkulman valikossa, "Viestit"-ikonissa. Klikkaamalla ikonia siirrytään tarkastelemaan saapuneita ja lähetettyjä viestejä.\r\n<br /><br />\r\nKlikkaamalla viestin otsikkoa, sen alapuolelle ilmestyy viestin sisältö liitetiedostoineen. Viesti voidaan poistaa tai vastata takaisin oikeassa reunassa olevilla napeilla. Uusia viestejä voi lähettää muille käyttäjille klikkaamalla käyttäjän profiiliin kartta- ja ilmoitusnäkymistä tai viestivalikosta, kohdasta "Uusi viesti".\r\n<br /><br />\r\nUutta viestiä tai vastausta kirjoittaessa määritellään viestin otsikko ja sisältö sekä mahdolliset liitetiedostot. Viestin vastaanottajia voi lisätä "Lisää vastaanottaja" -kohdasta kirjoittamalla ko. henkilön nimen ja klikkaamalla hakutulosta. Tiedosto lisätään viestiin klikkaamalla "Lisää tiedosto" -nappia, jonka jälkeen haluttu tiedosto valitaan tietokoneelta. Kun viesti on valmis, se lähetetään klikkaamalla "Lähetä viesti".\r\n<br /><br />\r\nViestejä voi hakea myös klikkaamalla Viestit-osiosta "Haku". Hakusana kirjoitetaan oikeanpuoleiseen harmaaseen tekstikenttään. Haku etsii vastaavia sanoja viestin otsikosta ja sisällöstä.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 5, 0, 1),
(6, 0, 'help', 'Pellot', 'Peltojen lisääminen, muokkaaminen ja poistaminen', 'Käyttäjät voivat lisätä omia peltojaan palveluun ja määritellä niiden näkyvyyden muille käyttäjille. Uusi pelto luodaan valitsemalla Toiminnot-valikon "Pellot"-kohdasta "Piirrä uusi pelto". Karttanäkymässä klikataan hiirellä pellon ääriviivat kulma kerrallaan. Kun pelto on "piirretty", sille voidaan antaa nimi ja tallentaa klikkaamalla "Tallenna pelto".\r\n<br /><br />\r\nPellon tietoja voidaan muokata Toiminnot-valikosta klikkaamalla "Pellot". Lisätyt omat pellot näkyvät listana, josta niitä voidaan muokata tai poistaa. Muokkaamalla peltoa sille voidaan määritellä tarkemmat tiedot (esim. viljavuus) sekä näkyvyys.\r\n<br /><br />\r\nPeltoja voidaan lisätä myös ilman ääriviivojen määrittelyä. Tällöin kaksoisklikkaamalla karttaa voidaan "Pellot"-kohdasta valita "Luo uusi pelto tähän".', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 6, 0, 1),
(7, 0, 'help', 'Reitit', 'Omat reitit ja reittiohjeiden etsiminen', 'Omia reittejä voi manuaalisesti "piirtää" samalla tavalla kuin pellot määritellään. Uusi piirretty reitti luodaan Toiminnot-valikon "Reitit"-kohdasta klikkaamalla "Piirrä uusi reitti". Valmiille reitille voi määritellä nimen ja tallentaa.\r\n<br /><br />\r\nReittien manuaalisen "piirtämisen" lisäksi reittejä voi luoda määrittelemällä aloitus- ja päätöskohdat Toiminnot-valikon "Reitit"-osiosta. "Mistä"- ja "Mihin"-kohtiin määritellään reitin aloitus- ja päätöskohdat joko osoitteina tai leveys- sekä pituuspiirikoordinaatteina. Löytyneestä reitistä näytetään pituus kilometreinä sekä aika ajoneuvolla. Klikkaamalla "Näytä kartalla" reittiä voi tarkastella tarkemmin sekä hakea tarkemmat ajo-ohjeet Google Maps -palvelusta klikkaamalla oikeassa alakulmassa olevaa "Näytä tarkemmat ohjeet" -nappia.\r\n<br /><br />\r\nKarttanäkymän ilmoituksiin ja peltoihin on mahdollista hakea reittiohjeet klikkaamalla oikeassa alakulmassa olevaa "Näytä reittiohjeet" -nappia.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 7, 0, 1),
(13, 0, 'help', 'Sopimukset', 'Sopimuksien luominen ja muokkaaminen', 'Uuden sopimuksen luominen onnistuu Toiminnot-valikon "Sopimukset"-kohdasta. Uuteen sopimukseen lisätään osapuolet samalla tavalla kuin viesteihin lisätään vastaanottajia; kirjoittamalla henkilön nimen ja klikkaamalla hakutulosta. Osapuolten yhteystietoja voidaan liittää sopimuspohjaan oikeanpuoleisesta alasvetovalikosta. Tekstikenttien lisäksi voidaan lisätä tilan eläinlajit ja luovutettavat lantalajit sekä niiden määrät. Sopimus voidaan jättää myös vapaasti muiden sopimusosapuolten muokattavaksi.\r\n<br /><br />\r\nUudesta sopimuksesta lähetetään automaattinen sähköpostiviesti sopimuksen osapuolille. Osapuolet näkevät sopimuksen ja voivat muokkaamisen lisäksi ladata siitä tulostettavan PDF-tiedoston allekirjoittamista varten.', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 8, 0, 1),
(9, 0, 'page', 'Etusivu', 'Tervetuloa Ravinnepörssiin!', '<p>Suomen ravinteikkain verkkopalvelu yhdist&auml;&auml; lantaa tuottavat ja vastaanottavat viljelij&auml;t,<br />muut orgaanisten lannoitteiden tuottajat ja urakoitsijat. Ravinnep&ouml;rssin avulla saat<br />parhaan hy&ouml;dyn irti jo olemassa olevista ravinteista ja<br />muodostat verkostoja l&auml;hialueesi toimijoiden kanssa.</p>\r\n<p>Klikkaa mukaan kierr&auml;tt&auml;m&auml;&auml;n!</p>', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 9, 0, 1),
(10, 0, 'page', 'Tietopörssi', 'Tietopörssi', '<h2><a href="https://portal.mtt.fi/portal/page/portal/kasper/pelto/peltopalvelut/fosforilaskuri" target="_blank">Fosforilaskuri</a></h2>\r\n\r\nKattaako suuremmalla fosforilannoituksella saavutettu sadonlisä siitä aiheutuvat lannoituskustannukset? Miten peltoni P-luvun ennustetaan kehittyvän nykyisellä fosforilannoituksella? Hyödynnä MTT:n kehittämää fosforilaskuria!<br /><br />\r\n\r\n<h2><a href="http://www.ymparisto.fi/download/noname/{969AB322-757C-44C3-8DA5-BFB83EBB8278}/75840" target="_blank">Lannoite ja lantalaskuri</a></h2>\r\n\r\nLannoituskustannukset ovat usein suurin menoerä viljelijän muuttuvissa kustannuksissa. Laske TEHO Plussan lantalaskurilla kuinka paljon lannoituksesi maksaisi eri tilanteissa. Laskuri arvioi lannoituskustannukset käytettäessä väkilannoitteita tai eri lantalajeja. Laskuri toimii Excel-ohjelmassa ja tarkemmat käyttöohjeet löydät laskurista.<br /><br />\r\n\r\n<h2><a href="http://www.ymparisto.fi/download/noname/{3D9916D8-BD8C-4714-A0C9-21AD8FD71BB4}/75841" target="_blank">Ravinnetaselaskuri</a></h2>\r\n\r\nLaske yksinkertaisen laskurin avulla oman peltolohkosi ravinnetase. Samalla saat selville käyttämäsi lannoituksen hyötysuhteen. Lopuksi laskuri laskee vielä ylijäämäisen ravinnetaseen taloudellisen merkityksen. Laskuri on alunperin kehitetty Tarmokas -hankkeessa ja sitä on kehitetty eteenpäin TEHO Plussassa. ', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 10, 0, 1),
(11, 0, 'page', 'Lähetä palautetta', 'Lähetä palautetta', '<h2>RAVITA-hanke</h2>\r\nSomeron kaupunki<br />\r\nPaimionjoki-yhdistys ry<br />\r\n040-1268461', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 11, 0, 1),
(12, 0, 'page', 'Käyttöehdot', 'Käyttöehdot', '<p>N&auml;iden WWW-sivujen omistusoikeus sek&auml; tekij&auml;noikeus ja kaikki muut immateriaalioikeudet kuuluvat Ravita-hankkeelle. Sivujen k&auml;ytt&auml;j&auml;ll&auml; ei ole, yksityist&auml; k&auml;ytt&ouml;&auml; lukuunottamatta, oikeutta levitt&auml;&auml;, julkaista, kopioida, saattaa yleis&ouml;n saataviin tai muuten hy&ouml;dynt&auml;&auml; sivuilla olevaa suojattua materiaalia ilman Ravita-hankkeen kirjallista lupaa. Mik&auml;li kopiointi, julkaiseminen, levitt&auml;minen tai yleis&ouml;lle saattaminen on lains&auml;&auml;d&auml;nn&ouml;n nojalla erityistapauksissa sallittua, on k&auml;ytt&auml;j&auml;n aina mainittava tekij&auml;n tai muun oikeudenhaltijan nimi.</p>', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 12, 0, 1);

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_contracts`
--

CREATE TABLE IF NOT EXISTS `rp_contracts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `output_name` text NOT NULL,
  `output_address` text NOT NULL,
  `output_phonenumber` text NOT NULL,
  `output_email` text NOT NULL,
  `output_companycode` text NOT NULL,
  `output_animals` text NOT NULL,
  `output_animal_amount` text NOT NULL,
  `output_field_area` text NOT NULL,
  `output_other_contracts` text NOT NULL,
  `output_total_animal_amount` text NOT NULL,
  `input_name` text NOT NULL,
  `input_address` text NOT NULL,
  `input_bic` text NOT NULL,
  `input_phonenumber` text NOT NULL,
  `input_email` text NOT NULL,
  `input_dung_area` text NOT NULL,
  `input_refinement` tinyint(4) NOT NULL,
  `input_contract_time` text NOT NULL,
  `input_products` text NOT NULL,
  `transporter` text NOT NULL,
  `distributor` text NOT NULL,
  `transportation_payer` text NOT NULL,
  `distribution_payer` text NOT NULL,
  `remarks` text NOT NULL,
  `place_and_time` text NOT NULL,
  `output_signature` text NOT NULL,
  `input_signature` text NOT NULL,
  `extra` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL DEFAULT '0',
  `to_noticeid` text NOT NULL,
  `to_clientid` text NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_clientid` int(11) NOT NULL DEFAULT '0',
  `editable` tinyint(4) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_feedback`
--

CREATE TABLE IF NOT EXISTS `rp_feedback` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_clientid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_fields`
--

CREATE TABLE IF NOT EXISTS `rp_fields` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `latitude` decimal(15,12) NOT NULL DEFAULT '0.000000000000',
  `longitude` decimal(15,12) NOT NULL DEFAULT '0.000000000000',
  `pos_x` decimal(17,9) NOT NULL,
  `pos_y` decimal(17,9) NOT NULL,
  `size` decimal(20,2) NOT NULL DEFAULT '0.00',
  `polygon` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `visibility` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_messages`
--

CREATE TABLE IF NOT EXISTS `rp_messages` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `files` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL,
  `to_clientid` text NOT NULL,
  `seen_clientid` text NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_clientid` int(11) NOT NULL DEFAULT '0',
  `hide_clientid` text NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_notices`
--

CREATE TABLE IF NOT EXISTS `rp_notices` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `types` text NOT NULL,
  `types2` text NOT NULL,
  `latitude` decimal(15,12) NOT NULL DEFAULT '0.000000000000',
  `longitude` decimal(15,12) NOT NULL DEFAULT '0.000000000000',
  `pos_x` decimal(17,9) NOT NULL,
  `pos_y` decimal(17,9) NOT NULL,
  `value` decimal(9,2) NOT NULL DEFAULT '0.00',
  `title` text NOT NULL,
  `description` text NOT NULL,
  `trades` text NOT NULL,
  `state` text NOT NULL,
  `city` text NOT NULL,
  `address` text NOT NULL,
  `products` text NOT NULL,
  `files` text NOT NULL,
  `contact_via` text NOT NULL,
  `visibility` text NOT NULL,
  `publish_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_products`
--

CREATE TABLE IF NOT EXISTS `rp_products` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `types` text NOT NULL,
  `prefix` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `options` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_userid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Vedos taulusta `rp_products`
--

INSERT INTO `rp_products` (`id`, `parent`, `types`, `prefix`, `title`, `description`, `options`, `added_datetime`, `added_ip`, `added_userid`, `modified_datetime`, `modified_ip`, `modified_userid`, `priority`, `published`) VALUES
(1, 0, '[input][output]', 'product_dry_dung', 'Kuivalanta', '', 'product_dry_dung_cow|Naudan|0|end|product_dry_dung_pig|Sian|0|end|product_dry_dung_broiler|Broilerin|0|end|product_dry_dung_chicken|Kanan|0|end|product_dry_dung_turkey|Kalkkunan|0|end|product_dry_dung_horse|Hevosen|0|end|product_dry_dung_sheep|Lampaan|0|end|product_dry_dung_other|Muun, minkä?|1|end|product_dry_dung_composted|Kompostoitu|0|end|product_dry_dung_peatbedding|Turvekuivike|0|end|product_dry_dung_strawbedding|Olkikuivike|0|end|product_dry_dung_strawpelletbedding|Olkipellettikuivike|0|end|product_dry_dung_woodpelletbedding|Puupellettikuivike|0|end|product_dry_dung_powderbedding|Purukuivike|0|end|product_dry_dung_organic|Lanta sopii luomuviljelyyn|0|end|product_dry_dung_amount|Lannan määrä|2|end|product_dry_dung_analysis|Lanta-analyysin pvm ja tulos|2|end|product_dry_dung_description|Lisätiedot|2|end|product_dry_dung_no_hukkakaura|Lannoite ei sisällä hukkakauraa|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 1, 1),
(2, 0, '[input][output]', 'product_sludge_dung', 'Lietelanta', '', 'product_sludge_dung_pig|Sian liete|0|end|product_sludge_dung_cow|Naudan liete|0|end|product_sludge_dung_decked|Katettu lantala|0|end|product_sludge_dung_organic|Liete sopii luomuviljelyyn|0|end|product_sludge_dung_amount|Lietteen määrä|2|end|product_sludge_dung_analysis|Lanta-analyysin pvm ja tulos|2|end|product_sludge_dung_description|Lisätiedot|2|end|product_sludge_dung_no_hukkakaura|Lannoite ei sisällä hukkakauraa|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 2, 1),
(3, 0, '[input][output]', 'product_preparation', 'Lannoitevalmiste', '', 'product_preparation_separated_sludge_dung_amount|Separoidun nesteosan määrä|2|end|product_preparation_separated_sludge_dung_analysis|Nesteosan analyysin pvm ja tulos|2|end|product_preparation_separated_dry_dung_amount|Separoidun kuivaosan määrä|2|end|product_preparation_separated_dry_dung_analysis|Kuivaosan analyysin pvm ja tulos|2|end|product_preparation_separated_other_organic|Muuta orgaanista lannoitetta|2|end|product_preparation_no_hukkakaura|Lannoite ei sisällä hukkakauraa|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 3, 1),
(4, 0, '[contractor]', 'product_contractor', 'Urakoitsijan palvelut', '', 'product_contractor_transport_sludge_dung|Kuljetusliike, lietemäiset ja nestemäiset|0|end|product_contractor_transport_dry_dung|Kuljetusliike, kuivalanta, komposti ja maanparannus|0|end|product_contractor_distribution_sludge_dung|Levitysurakointi, liete|0|end|product_contractor_distribution_dry_dung|Levitysurakointi, kuivalanta|0|end|product_contractor_separation_contractor|Separointiurakointi|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 4, 1);

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_ratings`
--

CREATE TABLE IF NOT EXISTS `rp_ratings` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `rating` tinyint(4) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL DEFAULT '0',
  `to_clientid` int(11) NOT NULL DEFAULT '0',
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_routes`
--

CREATE TABLE IF NOT EXISTS `rp_routes` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `title` text NOT NULL,
  `route` text NOT NULL,
  `distance` decimal(8,3) NOT NULL DEFAULT '0.000',
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_clientid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_clientid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Rakenne taululle `rp_trades`
--

CREATE TABLE IF NOT EXISTS `rp_trades` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `prefix` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `options` text NOT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_ip` text NOT NULL,
  `added_userid` int(11) NOT NULL,
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_ip` text NOT NULL,
  `modified_userid` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Vedos taulusta `rp_trades`
--

INSERT INTO `rp_trades` (`id`, `parent`, `type`, `prefix`, `title`, `description`, `options`, `added_datetime`, `added_ip`, `added_userid`, `modified_datetime`, `modified_ip`, `modified_userid`, `priority`, `published`) VALUES
(1, 0, '', 'trade_cow', 'Nautakarjaa', '', 'trade_cow_dairy|Lypsykarjaa|0|end|trade_cow_calver|Emolehmiä|0|end|trade_cow_beef|Lihakarjaa|0|end|trade_cow_other_cattle|Muuta nautakarjaa|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 1, 1),
(3, 0, '', 'trade_poultry', 'Siipikarjaa', '', 'trade_poultry_eggs|Munantuotantoa|0|end|trade_poultry_broilers|Broilereita|0|end|trade_poultry_turkeys|Kalkkunoita|0|end|trade_poultry_other_poultry|Muu siipikarja, mikä?|1|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 3, 1),
(7, 0, '', 'trade_crop', 'Kasvinviljely', '', 'trade_crop_grain|Viljatila|0|end|trade_crop_garden|Puutarhatila|0|end|trade_crop_other_crop|Viljeltävät kasvit:|2|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 7, 1),
(8, 0, '', 'trade_other_farm', 'Sekamuotoinen tila', '', 'trade_other_farm_regular|Tavanomainen|0|end|trade_other_farm_organic|Luomu|0|end|trade_other_farm_other|Muuta:|2|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 8, 1),
(4, 0, '', 'trade_horse', 'Hevosia', '', 'trade_horse_horses|Hevosia|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 4, 1),
(2, 0, '', 'trade_pig', 'Sikatila', '', 'trade_pig_pig_farm|Sikatila|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 2, 1),
(5, 0, '', 'trade_sheep', 'Lampaita', '', 'trade_sheep_sheeps|Lampaita|0|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 5, 1),
(6, 0, '', 'trade_other_animal', 'Muita koti/tuotantoeläimiä', '', 'trade_other_animal_other|Muita koti-/tuotantoeläimiä, mitä?|1|end|', '0000-00-00 00:00:00', '', 0, '0000-00-00 00:00:00', '', 0, 6, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
