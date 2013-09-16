<?php error_reporting(0); ?><?php
ob_start();
/* 
Plugin Name: XML FEED Hledejceny (PODUJATIE.EU)
Plugin URI: http://www.podujatie.eu/
Version: 2.00
Author: Podujatie.eu, Ing. Igor Kona
License: SW sa nesmie ďalej predávať, ani akokoľvek šíriť bez vedomia a dohody s autorom. SW licencia je platná na všetky weby v rámci jednej domény jedného kupujúceho. Autor nenesie zodpovednosť pokiaľ SW akokoľvek poškodí dáta, alebo spôsobí škodu. Kúpou a platbou súhlasíte s týmto licenčným dojednaním. Autor si ďalej vyhradzuje právo úpravy tohto licenčného dojednania. Platba je jednorazová a sú v nej zahrnuté prípadné aktualizácie a pomoc pri štandardnej inštalácii. 
Description: Generuje xml feed pre Hledejceny od woocommerce produktov. Vytvoril Podujatie.eu. !! Nastavenia -> XML Feed hledejceny !!
*/
/*  Copyright 2013  Podujatie.eu  (email : office@podujatie.eu)

SW sa nesmie ďalej predávať, ani akokoľvek šíriť bez vedomia a dohody s autorom. SW licencia je platná na všetky weby v rámci jednej domény jedného kupujúceho. Autor nenesie zodpovednosť pokiaľ SW akokoľvek poškodí dáta, alebo spôsobí škodu. Kúpou a platbou súhlasíte s týmto licenčným dojednaním. Autor si ďalej vyhradzuje právo úpravy tohto licenčného dojednania. Platba je jednorazová a sú v nej zahrnuté prípadné aktualizácie a pomoc pri štandardnej inštalácii. Zasahovanie do kódu pluginu, jeho časti či akákoľvek úprava je zakázaná. V opačnom prípade autor nenesia žiadnu zodpovednosť a taktiež povinnosť na akejkoľvek náprave.

Ďalšie šírenie tohto pluginu je ZAKÁZANÉ! Zákon č. 618/2003 Z.z. o autorskom práve a právach súvisiacich s autorským právom (autorský zákon) a Zákon č. 300/2005 Z.z. Trestný zákon, §283 Porušovanie autorského práva.

Ing. Igor Kona; IČO: 43729444; DIČ: 1078646503IČ; DPH: SK1078646503; platobné údaje na stránke Podujatie.eu.
*/

if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];

//Načítanie update
require plugin_dir_path( __FILE__ ) . 'update-notifier-hledejceny-feed-free.php';

//Define the product feed php pages
function hledejceny_feed_rss() {
 $rss_template = dirname(__FILE__) . "/".hledejceny.'/product-feed-hledejceny.php';
 load_template ( $rss_template );
}

//Add the product feed RSS
add_action('do_feed_hledejceny', 'hledejceny_feed_rss', 10, 1);

//Update the Rerewrite rules
add_action('init', 'moje_pridanie_produkt_hledejceny');

//function to add the rewrite rules
function moje_prepisanie_produktov_hledejceny( $wp_rewrite ) {
 $new_rules = array(
 'feed/(.+)' => 'index.php?feed='.$wp_rewrite->preg_index(1)
 );
 $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

//add the rewrite rule
function moje_pridanie_produkt_hledejceny( ) {
 global $wp_rewrite;
 add_action('generate_rewrite_rules', 'moje_prepisanie_produktov_hledejceny');
 $wp_rewrite->flush_rules();
}

$podujatie3_ver = '2.00';

/* 
 * NEMENIT - inak moze nastat poskodenie systemu
 */
add_option('products_in_feed', 50);
add_option('feed_title', '');
add_option('product_condition', 'nové');
add_option('product_brand', 'Nejaký výrobca');
add_option('product_doprava_hledejceny', 'Doprava_availability');
add_option('product_na_sklade_hledejceny', 'na sklade');
add_option('product_out_stock_hledejceny', 'mimo skladu');

add_option('hledejceny_what_to_show', 'both');
add_option('hledejceny_which_first', 'posts');
add_option('hledejceny_post_sort_order', 'title');
add_option('hledejceny_page_sort_order', 'title');
add_option('hledejceny_comments_on_posts', FALSE);
add_option('hledejceny_comments_on_pages', FALSE);
add_option('hledejceny_show_zero_comments', FALSE);
add_option('hledejceny_hide_future', FALSE);
add_option('hledejceny_new_window', FALSE);
add_option('hledejceny_show_post_date', FALSE);
add_option('hledejceny_show_page_date', FALSE);
add_option('hledejceny_date_format', 'F jS, Y');
add_option('hledejceny_hide_protected', TRUE);

add_option('hledejceny_excluded_pages', '');
add_option('hledejceny_page_nav', '1');
add_option('hledejceny_page_nav_where', 'top');
add_option('hledejceny_xml_path', '');

add_option('hledejceny_xml_where', 'last');

/*
 * Nastavenie na stránke pluginu v nastaveniach systemu
 */
function hledejceny_xml_feed2() {
	if (function_exists('add_options_page')) {
		add_options_page('XML Feed hledejceny', 'XML Feed Hledejceny', 'manage_options', __FILE__, 'hledejceny_options_page');
	}
}

/* 
 * Vygenerovanie nastaveni
 */
function hledejceny_options_page() {

	global $hledejceny_ver;

	if (isset($_POST['set_defaults'])) {
		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('products_in_feed', 50);
		update_option('feed_title', '');
		update_option('product_condition_hledejceny', 'new');
		update_option('hledejceny_what_to_show', 'both');
		update_option('hledejceny_which_first', 'posts');	
		update_option('hledejceny_post_sort_order', 'title');
		update_option('hledejceny_page_sort_order', 'title');
		update_option('hledejceny_comments_on_posts', FALSE);
		update_option('hledejceny_comments_on_pages', FALSE);
		update_option('hledejceny_show_zero_comments', FALSE);
		update_option('hledejceny_hide_future', FALSE);
		update_option('hledejceny_new_window', FALSE);
		update_option('hledejceny_show_post_date', FALSE);
		update_option('hledejceny_show_page_date', FALSE);
		update_option('hledejceny_date_format', 'F jS, Y');
		update_option('hledejceny_hide_protected', TRUE);
		update_option('hledejceny_excluded_pages', '');
		update_option('hledejceny_page_nav', '1');
		update_option('hledejceny_page_nav_where', 'top');
		update_option('hledejceny_xml_path', '');
		update_option('product_brand', 'Nejaký výrobca');
		update_option('product_doprava_hledejceny', 'Doprava');
		update_option('product_na_sklade_hledejceny', 'na sklade');
		update_option('product_out_stock_hledejceny', 'mimo skladu');
		update_option('hledejceny_xml_where', 'last');

		echo Nastavenia_upravene;
		echo '</strong></p></div>';	

	} else if (isset($_POST['info_update'])) {

		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('products_in_feed', (int) $_POST["products_in_feed"]);
		update_option('feed_title', (string) $_POST["feed_title"]);
		update_option('hledejceny_what_to_show', (string) $_POST["hledejceny_what_to_show"]);
		update_option('hledejceny_which_first', (string) $_POST["hledejceny_which_first"]);
		update_option('hledejceny_post_sort_order', (string) $_POST["hledejceny_post_sort_order"]);	
		update_option('hledejceny_page_sort_order', (string) $_POST["hledejceny_page_sort_order"]);	
		update_option('hledejceny_comments_on_posts', (bool) $_POST["hledejceny_comments_on_posts"]);
		update_option('hledejceny_comments_on_pages', (bool) $_POST["hledejceny_comments_on_pages"]);
		update_option('hledejceny_show_zero_comments', (bool) $_POST["hledejceny_show_zero_comments"]);	
		update_option('hledejceny_hide_future', (bool) $_POST["hledejceny_hide_future"]);
		update_option('hledejceny_new_window', (bool) $_POST["hledejceny_new_window"]);	
		update_option('hledejceny_show_post_date', (bool) $_POST["hledejceny_show_post_date"]);
		update_option('hledejceny_show_page_date', (bool) $_POST["hledejceny_show_page_date"]);
		update_option('hledejceny_date_format', (string) $_POST["hledejceny_date_format"]);
		update_option('hledejceny_hide_protected', (bool) $_POST["hledejceny_hide_protected"]);
		update_option('product_condition_hledejceny', (string) $_POST["product_condition_hledejceny"]);
		update_option('hledejceny_excluded_pages', (string) $_POST["hledejceny_excluded_pages"]);
		update_option('hledejceny_page_nav', (string) $_POST["hledejceny_page_nav"]);
		update_option('hledejceny_page_nav_where', (string) $_POST["hledejceny_page_nav_where"]);
		update_option('hledejceny_xml_path', (string) $_POST["hledejceny_xml_path"]);
		update_option('product_brand', (string) $_POST["product_brand"]);
		update_option('product_doprava_hledejceny', (string) $_POST["product_doprava_hledejceny"]);
		update_option('product_na_sklade_hledejceny', (string) $_POST["product_na_sklade_hledejceny"]);
		update_option('product_out_stock_hledejceny', (string) $_POST["product_out_stock_hledejceny"]);
		update_option('hledejceny_xml_where', (string) $_POST["hledejceny_xml_where"]);	

		echo Nastavenia_upravene;
	    echo '</strong></p></div>';
ob_flush();
} ?>
<div class="wrap">

	<h2>Woocommerce xml feed pre web Hledejceny od Podujatie.eu - v.<?php echo $podujatie3_ver; ?>- Free verzia</h2>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true" />
<table width=100% border=0>
<tr><td width=50%  valign="top">
<center><a href="http://www.podujatie.eu/"><img src="<?php bloginfo_rss('wpurl') ?>/wp-content/plugins/hledejceny-feed/pic/web-logo3.jpg" width="300" height="100" border="0"></a></center>
<p><font face="Georgia, Arial, Garamond" size="3">Získajte najnovšiu verziu tohto produktu na tejto stránke 
<a target="_blank" href="http://www.podujatie.eu">Podujatie.eu</a></font></p>

<font face="Georgia, Arial, Garamond">Potrebujete pomoc s týmto pluginom? Potrebujete jeho úpravu? Prosím kontaktujte nás na <a href=mailto:office@podujatie.eu>office@podujatie.eu</a>.

	<legend>Ahoj, Podujatie.eu sa snaží upravovať pluginy pre použitie na slovenskom webe.<br />
	Tento plugin je Free verzia - zadarmo s obmädzenými možnosťami. Odporúčame ti však objednanie si PRO verzie...<br />
	Plugin je vyvíjaný v spolupráci so spoločnosťou Ing. Igor Kóňa, kde prebehne aj tvoja platba, ak sa rozhodneš aktivovať si Premium verziu. Ďakujeme</legend>

<br />
<p> Ak chceš pomoc, vrámci Free verzie tohto programu, ti pomôžeme prostredníctvom dotazníka pomoci alebo na fóre. </font><font face="Times New Roman, Arial, Garamond" color="darkred"><b>
Ďalšie šírenie tohto pluginu je ZAKÁZANÉ! Zákon č. 618/2003 Z.z. o autorskom práve a právach súvisiacich s autorským právom (autorský zákon) a Zákon č. 300/2005 Z.z. Trestný zákon, §283 Porušovanie autorského práva.
</b></p></font>
</td><td widht=50%  valign="top">
<img src="<?php bloginfo_rss('wpurl') ?>/wp-content/plugins/hledejceny-feed/pic/hledejceny-free-xml.jpg" align="right" width="159" height="159">
<p><font face="Georgia, Arial, Garamond" size="3">Ďakujeme, že využívaš verziu FREE.<br>
<img src="<?php bloginfo_rss('wpurl') ?>/wp-content/plugins/hledejceny-feed/pic/hledejceny-pro-xml.jpg" align="left" width="159" height="159"><b>Výhody, ktoré získaš, ak si aktivuješ premium verziu:</b><br>
<ol>
<li>vo feede sa ti zobrazí až 99999 produktov
<li>vo feede sa zobrazuje link na obrázok produktu
<li>nastavenie si kategóriu, kde sa majú produkty zaradiť
<li>nastavenie si cenu dopravy
<li>môžeš si určiť dodatočné náklady
<li>zobrazenie počtu mesiacov záruky
<li>a mnohé ďalšie možnosti ...
</ol>
</font>
<br><br>
Nezabudni sa stať naším fanúšikom na </font><font face="Georgia, Arial, Garamond" size="3" color="3B5998"><b>
<a href="http://www.facebook.com/PodujatieEu">Facebook-u Podujatie.eu</a></font></b></p>
</td></tr></table>
<hr>
	<fieldset class="options">

	<table width="100%" border="0" cellspacing="0" cellpadding="6">

   	<tr valign="top">

	<th width="45%" valign="top" align="right" scope="row">Titulok xml feed-u</th><td valign="top">
	<input name="feed_title" type="text" size="35" value="<?php echo bloginfo_rss('name'); wp_title_rss(); ?>"/><br />
	Prednastavené je <strong><?php echo bloginfo_rss('name'); wp_title_rss(); ?></strong>
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row">Popis feed-u</th><td valign="top">
	<input name="feed_description" type="text" size="45" value="<?php bloginfo_rss('description') ?>"/><br />
	Prednastavené je <strong><?php bloginfo_rss('description') ?></strong>
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row">Stav produktu</th><td valign="top">
	<input name="product_condition_hledejceny" type="radio" value="new" <?php if (get_option('product_condition_hledejceny') == "new") echo "checked='checked'"; ?> />&nbsp;&nbsp; nové
	<input name="product_condition_hledejceny" type="radio" value="used" <?php if (get_option('product_condition_hledejceny') == "used") echo "checked='checked'"; ?> />&nbsp;&nbsp; použité
	<input name="product_condition_hledejceny" type="radio" value="refurbished" <?php if (get_option('product_condition_hledejceny') == "refurbished") echo "checked='checked'"; ?>/>&nbsp;&nbsp;	opravené/zrepasované<br />
	Prednastavené je <strong>nové</strong>. Môžeš ale nastaviť na <strong>používané</strong> alebo <strong>repasované</strong>.<br /><br />
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row">Na sklade - možnosti</th><td valign="top">
	<input name="product_na_sklade_hledejceny" type="radio" value="na sklade" <?php if (get_option('product_na_sklade_hledejceny') == "na sklade") echo "checked='checked'"; ?> />&nbsp;&nbsp; na sklade
	<input name="product_na_sklade_hledejceny" type="radio" value="available for order" <?php if (get_option('product_na_sklade_hledejceny') == "available for order") echo "checked='checked'"; ?> />&nbsp;&nbsp; možné na objednávku<br />
	Prednastavené je <strong>na sklade</strong>. Môžeš tiež nastaviť na <strong>možné na objednávku</strong>.<br /><br />
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row">Mimo skladu - možnosti</th><td valign="top">
	<input name="product_out_stock_hledejceny" type="radio" value="mimo skladu" <?php if (get_option('product_out_stock_hledejceny') == "mimo skladu") echo "checked='checked'"; ?> />&nbsp;&nbsp; mimo skladu
	<input name="product_out_stock_hledejceny" type="radio" value="preorder" <?php if (get_option('product_out_stock_hledejceny') == "preorder") echo "checked='checked'"; ?> />&nbsp;&nbsp; predobjednávka<br />
	Prednastavené je <strong>mimo skladu</strong>. Môžeš tiež nastaviť na <strong>predobjednávka</strong>.<br /><br />
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row">Značka produktov</th><td valign="top">
	<input name="product_brand" type="text" size="25" value="<?php echo get_option('product_brand') ?>"/><br />
	Prednastavené je <strong>Nejaký výrobca</strong><br />
	Výrobce, autor, či poskytovatel službyPožadované je to pre všetky výrobky smerované na trh USA, EU a Japonska. Netreba zadávať pre knihy a tovary ručnej výroby.
	</td></tr>

	<tr><td width="45%" valign="top" align="right" scope="row">Doprava</th><td valign="top">
	<input name="product_doprava_hledejceny" type="text" size="25" value="<?php echo get_option('product_doprava_hledejceny') ?>"/><br />
	Zadať <strong>počet dní</strong><br />
	Doba doručení produktu ve dnech. Zadáva sa pre všetky produkty naraz!
	</td></tr>

	<br /><br /><br />

	</table>
	</fieldset>

	<div class="submit">
		<input type="submit" name="set_defaults" value="Nastaviť na prednastavené &raquo;" />
		<input type="submit" name="info_update" value="Uložiť nastavenia &raquo;" />
	</div>

	</form>
<hr>
	<h2> Tvoj celkový xml feed môžeš vzhliadnuť tu <a target="_blank" href="<?php bloginfo_rss('wpurl') ?>/feed/hledejceny/"><?php bloginfo_rss('wpurl') ?>/feed/hledejceny/</a></h2>
	<strong>Nevidíš svoj xml feed?</strong>

<br />
	<li>Nemáš povolené permant links - prosím povoliť</li>
	<li>Nemáš nahodené žiadne produkty</li> <br />

	<h2>Náhľad (tu je náhľad posledných 3 produktov, je to len orientačné, takto to bude vyzerať)</h2><br />

<table width="100%" border="0" cellspacing="0" cellpadding="6">
<tr>
<th>Názov</th>
<th>URL link</th>
<th>Cena s DPH</th>
<th>Popis</th>
<th>Obrázok</th>
<th>ID produktu</th>
<th>Výrobca</th>
<th>Kategória</th>
<th>EAN</th>
<th>Doručenie</th>
<th>Cena doručenia</th>
<th>Záruka</th>
<th>Dodatočné náklady</th>
</tr>
<?php
    $args = array( 'post_type' => 'product', 'posts_per_page' => 1 );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post(); global $product;
    ?><product>
	<tr>
        <td><PRODUCT><?php the_title_rss() ?></PRODUCT></td>
		<td><URL><?php the_permalink_rss() ?></URL></td>
        <td><PRICE_VAT><?php echo $product->price ?></PRICE_VAT></td>
<td><?php if (get_option('rss_use_excerpt')) : ?>
        <description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
<?php else : ?>
        <description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
<?php endif; ?></td>
		<td><IMGURL><?php echo wp_get_attachment_url( get_post_thumbnail_id() ) ?></IMGURL></td>
		<td><MANUFACTURER><?php echo get_option('product_brand') ?></MANUFACTURER></td>
		<td><DELIVERY_DATE><?php echo get_option('product_doprava_hledejceny') ?></DELIVERY_DATE></td>

<?php rss_enclosure(); ?><?php do_action('rss2_item'); ?></tr></product><?php endwhile; ?></table>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39354652-1']);
  _gaq.push(['_setDomainName', 'podujatie.eu']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

	</div><?php
}
add_action('admin_menu', 'hledejceny_xml_feed2');