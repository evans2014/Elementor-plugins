<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}

	if(!isset($options)){
		$options= 'germany';
	}
?>
<select name="nahiro_country_option">

   <option value='afghanistan' <?php selected($options, 'afghanistan'); ?>>Afghanistan</option>  
   <option value='africa' <?php selected($options, 'africa'); ?>>Africa</option>  
   <option value='albania' <?php selected($options, 'albania'); ?>>Albania</option>  
   <option value='algeria' <?php selected($options, 'algeria'); ?>>Algeria</option>  
   <option value='americanSamoa' <?php selected($options, 'americanSamoa'); ?>>American Samoa</option>  
   <option value='andorra' <?php selected($options, 'andorra'); ?>>Andorra</option>  
   <option value='angola' <?php selected($options, 'angola'); ?>>Angola</option>  
   <option value='anguilla' <?php selected($options, 'anguilla'); ?>>Anguilla</option>  
   <option value='antiguaBarbuda' <?php selected($options, 'antiguaBarbuda'); ?>>Antigua Barbuda</option>  
   <option value='argentina' <?php selected($options, 'argentina'); ?>>Argentina</option>  
   <option value='armenia' <?php selected($options, 'armenia'); ?>>Armenia</option>  
   <option value='aruba' <?php selected($options, 'aruba'); ?>>Aruba</option>  
   <option value='asia' <?php selected($options, 'asia'); ?>>Asia</option>  
   <option value='australia' <?php selected($options, 'australia'); ?>>Australia</option>  
   <option value='austria' <?php selected($options, 'austria'); ?>>Austria</option>  
   <option value='azerbaijan' <?php selected($options, 'azerbaijan'); ?>>Azerbaijan</option>  
   <option value='thebahamas' <?php selected($options, 'thebahamas'); ?>>Bahamas</option>  
   <option value='bahrain' <?php selected($options, 'bahrain'); ?>>Bahrain</option>  
   <option value='bangladesh' <?php selected($options, 'bangladesh'); ?>>Bangladesh</option>  
   <option value='barbados' <?php selected($options, 'barbados'); ?>>Barbados</option>  
   <option value='belgium' <?php selected($options, 'belgium'); ?>>Belgium</option>  
   <option value='bolivia' <?php selected($options, 'bolivia'); ?>>Bolivia</option>  
   <option value='brazil' <?php selected($options, 'brazil'); ?>>Brazil</option>  
   <option value='canada' <?php selected($options, 'canada'); ?>>Canada</option>  
   <option value='chile' <?php selected($options, 'chile'); ?>>Chile</option>  
   <option value='china' <?php selected($options, 'china'); ?>>China</option>  
   <option value='colombia' <?php selected($options, 'colombia'); ?>>Colombia</option>  
   <option value='costaRica' <?php selected($options, 'costaRica'); ?>>Costa Rica</option>  
   <option value='ecuador' <?php selected($options, 'ecuador'); ?>>Ecuador</option>  
   <option value='finland' <?php selected($options, 'finland'); ?>>Finland</option>  
   <option value='france' <?php selected($options, 'france'); ?>>France</option>  
   <option value='germany' <?php selected($options, 'germany'); ?>>Germany</option>  
   <option value='hongKong' <?php selected($options, 'hongKong'); ?>>Hong Kong</option>  
   <option value='india' <?php selected($options, 'india'); ?>>India</option>  
   <option value='ireland' <?php selected($options, 'ireland'); ?>>Ireland</option>  
   <option value='israel' <?php selected($options, 'israel'); ?>>Israel</option>  
   <option value='italy' <?php selected($options, 'italy'); ?>>Italy</option>  
   <option value='japan' <?php selected($options, 'japan'); ?>>Japan</option>  
   <option value='luxembourg' <?php selected($options, 'luxembourg'); ?>>Luxembourg</option>  
   <option value='mexico' <?php selected($options, 'mexico'); ?>>Mexico</option>  
   <option value='netherlands' <?php selected($options, 'netherlands'); ?>>Netherlands</option>  
   <option value='norway' <?php selected($options, 'norway'); ?>>Norway</option>  
   <option value='paraguay' <?php selected($options, 'paraguay'); ?>>Paraguay</option>  
   <option value='peru' <?php selected($options, 'peru'); ?>>Peru</option>  
   <option value='poland' <?php selected($options, 'poland'); ?>>poland</option>  
   <option value='portugal' <?php selected($options, 'portugal'); ?>>Portugal</option>  
   <option value='russia' <?php selected($options, 'russia'); ?>>Russia</option>  
   <option value='southKorea' <?php selected($options, 'southKorea'); ?>>South Korea</option>  
   <option value='spain' <?php selected($options, 'spain'); ?>>Spain</option>  
   <option value='sweden' <?php selected($options, 'sweden'); ?>>Sweden</option>  
   <option value='switzerland' <?php selected($options, 'switzerland'); ?>>Switzerland</option>  
   <option value='taiwan' <?php selected($options, 'taiwan'); ?>>Taiwan</option>  
   <option value='unitedKingdom' <?php selected($options, 'unitedKingdom'); ?>>United Kingdom</option>  
   <option value='uruguay' <?php selected($options, 'uruguay'); ?>>Uruguay</option>  
   <option value='usa' <?php selected($options, 'usa'); ?>>United States</option>  
   <option value='venezuela' <?php selected($options, 'venezuela'); ?>>Venezuela</option>  

</select>