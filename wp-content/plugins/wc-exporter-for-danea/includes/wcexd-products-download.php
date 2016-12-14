<?php
/*
WOOCOMMERCE EXPORTER FOR DANEA | TEMPLATE CSV PRODOTTI
*/


add_action('admin_init', 'wcexd_products_download');

function wcexd_products_download() {

	if($_POST['wcexd-products-hidden'] && wp_verify_nonce( $_POST['wcexd-products-nonce'], 'wcexd-products-submit' )) {

		//INIZIO DOCUMENTO CSV
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=wcexd-products-list.csv');
		header("Content-Transfer-Encoding: binary");

		$args = array('post_type' => array('product', 'product_variation'), 'post_status'=>'publish', 'posts_per_page' => -1);

		$products = new WP_Query($args);
		if($products->have_posts()) :

			$fp = fopen('php://output', 'w');
			
			$list = array('Cod.', 'Descrizione',	'Tipologia', 'Categoria', 'Sottocategoria', 'Cod. Udm',	 
					'Cod. Iva', 'Listino 1',	'Listino 2', 'Listino 3',	 'Formula listino 1',	
					'Formula listino 2',	'Formula listino 3',	'Note', 'Cod. a barre',	'Internet',	
					'Produttore',	'Descriz. web (Sorgente HTML)',	'Pubblicaz. su web',	'Extra 1',	'Extra 2',	
					'Extra 3',	'Extra 4',	'Cod. fornitore',	'Fornitore',	'Cod. prod. forn.', 'Prezzo forn.', 
					'Note fornitura', 'Ord. a multipli di', 'Gg. ordine', 'Scorta min.', 'Ubicazione', 'Tot. q.tà caricata', 
					'Tot. q.tà scaricata', 'Q.tà giacenza', 'Q.tà impegnata', 'Q.tà disponibile', 'Q.tà in arrivo', 'Vendita media mensile	', 
					'Stima data fine magazz.', 'Stima data prossimo ordine', 'Data primo carico', 'Data ultimo carico', 'Data ultimo scarico	', 
					'Costo medio d\'acq.',	 'Ultimo costo d\'acq.',	'Prezzo medio vend.',	'Stato magazzino', 'Immagine'	);
					
			fputcsv($fp, $list);
			
			  while($products->have_posts()) : $products->the_post();
			  
				//RICHIAMO IL SINGOLO "DOCUMENT"
				$product = new WC_Product( get_the_ID() );

				//SE PRESENTE LO SKU, HA LA PRECEDENZA
				if(get_post_meta(get_the_ID(), '_sku', true)) {
					$product_id = get_post_meta(get_the_ID(), '_sku', true);			
				} else {
					$product_id = $product->id;
				}

				//RECUPERO LA CATEGORIA DEL PRODOTTO
				if($product->post->post_parent) {
					$product_category = WCtoDanea::get_product_category_name($product->post->post_parent);
				} else {
					$product_category = WCtoDanea::get_product_category_name(get_the_ID());
				}
				
				//CONTROLLO LA PRESENZA DI SENSEI
				if($_POST['sensei'] && ( WCtoDanea::get_sensei_author($product->id) != null) ) {
				  $id_fornitore = WCtoDanea::get_sensei_author($product->id);
				  //Salvo il dato nel database
				  update_option( 'wcexd-sensei-option', 1 ); 
				} else {
				  $id_fornitore = $product->post->post_author; 
				  update_option( 'wcexd-sensei-option', 0 );
				}

				//OTTENGO IL NOME DEL FORNITORE (POST AUTHOR)
				$supplier_name = get_user_meta( $id_fornitore, 'billing_first_name', true ) . ' ' . get_user_meta( $id_fornitore, 'billing_last_name', true );
				//Se presente il nome dell'azienda, modifico la denominazione per Danea
				if(get_user_meta($id_fornitore, 'billing_company', true)) {
					$denominazione = (get_user_meta($id_fornitore, 'billing_company', true));
				} else {
					$denominazione = $supplier_name;
				}
				
				//SCORPORO IVA
				$free_tax_price = $product->price/ ( 1 + ( WCtoDanea::get_tax_rate()/ 100 ) );
				
				//TRASFORMO IL FORMATO DEL PREZZO
				$price = round($free_tax_price, 2);
				$prezzo = str_replace('.', ',', $price);
				
				$data = array($product->id, $product->post->post_title,'Articolo', $product_category,'','', WCtoDanea::get_tax_rate(), 
				$prezzo, '','','','','','','','', '', $product->post->post_content,'','','','','', $id_fornitore, $denominazione,'','','','','','','','','', 
				$product->get_total_stock(),'', $product->get_stock_quantity(),'','','','','','','','','','','','');	
				fputcsv($fp, $data);
			  endwhile;

			fclose($fp);
		endif;

		//FINE DOCUMENTO CSV

		exit;

	}

}