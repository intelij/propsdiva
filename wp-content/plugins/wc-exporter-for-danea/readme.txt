=== Woocommerce Exporter for Danea ===
Contributors: ghera74
Tags: Woocommerce, Danea, Easyfatt, ecommerce, exporter, csv, shop, orders, products
Version: 0.9.2
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 0.9.2


Export suppliers, products, customers and orders from your Woocommerce store to Danea.

== Description ==
If you've built your online store with Woocommerce and you're using Danea as management software, you definitely need Woocommerce Exporter for Danea!
With this Free version you can export the suppliers and the products from your store.
With Premium version, you'll be able also to export clients and orders.

**What's new in this release**

* If the Company field is presents, the name will be moved to referent.
* Added the Shipping address.
* Products export improved with all products variations.
* If present, the SKU will be used as product id, instead of the Wordpress post id.
* Fiscal code and P.IVA fields are now recognized by checking the specific plugin installed.

These are the plugins that are supported at the moment:
<ul>
<li>WooCommerce Aggiungere CF e P.IVA</li>
<li>WooCommerce P.IVA e Codice Fiscale per Italia</li>
<li>YITH WooCommerce Checkout Manager</li>
<li>WOO Codice Fiscale</li>
</ul>
Are you using another plugin for these fields? Please, let me know it writing a thread in the Forum support.

**In the Premium version**

* Added the Shipping costs of the orders.
* Added the customer comments in every single order.
* Now you can chose which kind of orders export, based on the statuses like completed, on hold, refunded and so on.

----

Se hai realizzato il tuo negozio online con Woocommerce ed utilizzi Danea come gestionale, Woocommerce Exporter per Danea è quello che ti serve!
Nella versione Free, permette di esportare un elenco di utenti Wordpress come fornitori, e l'elenco dei tuoi prodotti.
Nella versione Premium, potrai esportare anche clienti e ordini.
Ecco il dettaglio dei contenuti che è possibile esportare:

* L'elenco dei fornitori, sotto forma di utenti Wordpress a cui si è assegnato un ruolo specifico (CSV).
* L'elenco dei prodotti Woocommerce (CSV).
* Premium - L'elenco dei clienti Woocommerce (CSV).
* Premium - L'elenco degli ordini Woocommerce, attraverso un feed (xml) che potrà essere usato in ogni momento in Danea per scaricare gli ordini effettuati sul tuo sito.

**Novità in questa release**

* Nell'esportazione dei fornitori, se presente il campo azienda, nome e cognome vengono spostati in referente.
* Aggiunti i dati di spedizione del singolo utente, in questo caso dei fornitori.
* Migliorata l'esportazione dei prodotti, comprensiva ora di tutte le variazioni prodotto di Woocommerce.
* Ora lo SKU ha la priorità e, se presente, viene sempre utilizzato come id prodotto. In caso contrario, viene utilizzato il post id.
* Il campo Codice Fiscale e Partita IVA vengono ora riconosciuti dinamicamente, verificando quale plugin specifico è installato sulla piattaforma. 

Questo l'elenco di quelli attualmente supportati:
<ul>
<li>WooCommerce Aggiungere CF e P.IVA</li>
<li>WooCommerce P.IVA e Codice Fiscale per Italia</li>
<li>YITH WooCommerce Checkout Manager</li>
<li>WOO Codice Fiscale</li>
</ul>
Usi un'altro plugin per questi campi? Fammelo sapere scrivendomi nel Forum di supporto.

**Nella versione Premium**

* Aggiunti i costi di spedizione per il singolo ordine
* Aggiunti i commenti lasciati dall'utente in fase di acquisto.
* Ora è possibile scegliere quali ordini scaricare, basandosi sul loro stato come completati, in attesa, rimborsati, e così via.
		


== Installation ==
From your WordPress dashboard
<ul>
<li>Visit 'Plugins > Add New'</li>
<li>Search for 'Woocommerce Exporter for Danea' and download it.</li>
<li>Activate Woocommerce Exporter for Danea from your Plugins page.</li>
<li>Once Activated, go to Woocommerce/ Woocommerce Exporter for Danea.</li>
</ul>

From WordPress.org
<ul>
<li>Download Woocommerce Exporter for Danea</li>
<li>Upload the 'woocommerce-exporter-for-danea' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)</li>
<li>Activate Woocommerce Exporter for Danea from your Plugins page.</li>
<li>Once Activated, go to Woocommerce/ Woocommerce Exporter for Danea.</li>
</ul>

== Screenshots ==
1. Choose the user role and download your updated suppliers list
2. Download your updated products list


== Changelog ==


= 0.9.2 =
Release Date: 31 October, 2016

* Bug Fix: YITH WooCommerce Checkout Manager fields not recognised.


= 0.9.1 =
Release Date: 27 October, 2016
		
* Enhancement: If the Company field is presents, the name will be moved to referent.
* Enhancement: Added the Shipping address.
* Enhancement: Products export improved with all them variations.
* Enhancement: If present, the SKU will be used as product id, instead of the Wordpress post id.
* Enhancement: Fiscal code and P.IVA fields are now recognized by checking the specific plugin installed.
* Bug Fix: The db query didn't work in case the tables prefix was different than wp_

= 0.9.0 =
Release Date: 03 June, 2016

* First release
