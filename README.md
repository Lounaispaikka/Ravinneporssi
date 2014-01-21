Ravinneporssi
=============

Ravinnepörssi v1.0
------------------

Ravinnepörssi on verkkopalvelu, jossa kaiken kokoiset orgaanisia lannoitteita tuottavat ja niitä vastaanottavat tahot sekä organisaatiot voivat ilmoittaa ravinteiden luovutus- tai vastaanottohalukkuudestaan, verkostoitua, ja hankkia työlle urakoitsijan tai ravinteille kuljettajan.


Tekniset tiedot
---------------

Ravinnepörssi vaatii toimiakseen vähintään PHP 5.x ja MySQL 5.x. Nykyinen versio hakee karttarasterit kahdesta paikasta; Maanmittauslaitoksen palvelimelta ja omalta Geoserver-palvelimelta (peltolohkorekisteri).


Asennus
-------

- HTML-kansion sisältö siirretään haluttuun kansioon.

- SQL-tiedoston (ravinneporssi_mysql_base_v1.sql) tietokantarakenne tuodaan esimerkiksi phpMyAdminin kautta MySQL-tietokantaan.

- Sovelluksen tietokantatiedot ja asetukset määritellään engine/rp.settings.php -tiedostoon.
        - Pakolliset tiedot: secret, domain, dburl, database, username, password, publicUsername, publicPassword
        - Hyväksytyt tiedostotyypit: allowFileTypes, allowImageTypes
        - Suurin tiedostokoko: maximumFileSize 

- Karttarastereiden pyyntöosoitteet ja lisäparametrit määritellään engine/rp.wms.get.map.php -tiedostoon.
        - Maanmittauslaitoksen username ja password tulee määritellä.

- Karttanäkymän lisäasetukset löytyvät js/rp.setup.js -tiedostosta.
        - Jos selain ei luovuta paikkatietoa, käytetään arvoja: fallBackLatitude, fallBackLongitude, fallBackZooom
        - Reittien ja peltojen linjakoot ja -värit määritellään: routeLineWidth, routeLineColor, fieldLineWidth, fieldLineColor
        - Karttapohjat määritellään: mapLayers. Nimenä käytetään WMS-palvelimelle määriteltyä karttapohjaa
        - Karttapohjien suurin zoomaustaso: mapMaxZoom[]

- Karttapohjien valintamahdollisuudet voidaan määritellä engine/frontend/rp.front.mapview.php -tiedostosta.

- Itsensä poistaneiden käyttäjien tyhjentämisen ajastus luodaan määrittelemällä ajastettu tehtävä (cron). Intervalliksi määritellään joka päivä kello 00.00 ja pyyntö (wget) lähetetään osoitteeseen {domain}/engine/rp.process.removed.php?rpCheck=processremoved


Sisällönhallinta
----------------

Verkkopalvelun tekstisisältöjen muokkaaminen onnistuu kirjautumalla palveluun pääkäyttäjänä. Sisältöjen muokkausvalikko löytyy karttanäkymän vasemmasta palstasta. Myös käyttäjien, ilmoitusten ja arvostelujen näkyvyyttä voidaan muokata pääkäyttäjän ominaisuudessa. Palvelun ensimmäinen pääkäyttäjä määritellään esimerkiksi phpMyAdmin-työkalun avulla rp_clients-taulukosta vaihtamalla ko. käyttäjän admin-rivi 0 => 1.


Versiohistoria
--------------

1.0 (12.12.2013)


Lisätiedot ja tuki
------------------

Matti Tihveräinen
matti@bmm.fi
