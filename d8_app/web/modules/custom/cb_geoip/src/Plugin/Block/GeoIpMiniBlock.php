<?php
namespace Drupal\cb_geoip\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides 'geoip' block.
 *
 * @Block(
 *   id = "geoip_mini_block",
 *   admin_label = @Translation("geoip")
 * )
 */
class GeoIpMiniBlock extends BlockBase {

	public static $icons = [

'US'=>'🇺🇸',
'CA'=>'🇨🇦',
'MX'=>'🇲🇽',
'PH'=>'🇵🇭',
'UK'=>'🇬🇧',
'CN'=>'🇨🇳'

	];

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'geoip' => $this->t('geoip'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {


  	$geo_vars = [
		'country_name'=>$_SERVER['GEOIP_COUNTRY_NAME'],
		'country_code'=>$_SERVER['GEOIP_COUNTRY_CODE']	,
		'cont_code'=>$_SERVER['GEOIP_CONTINENT_CODE'],
		'ip'=>$_SERVER['GEOIP_ADDR'],
		'icon'=>self::$icons[$_SERVER['GEOIP_COUNTRY_CODE']]
  	];

    if(is_object($this->configuration['geoip']) && $this->configuration['geoip']->render()){
      $geoip_content  = str_replace('{{ icon }}',$geo_vars['icon'], $this->configuration['geoip']->render());
      $geoip_content  = str_replace('{{ country_name }}',$geo_vars['country_name'], $geoip_content);
    }
    else{
      $geoip_content  = str_replace('{{ icon }}',$geo_vars['icon'], $this->configuration['geoip']['value']);
        $geoip_content  = str_replace('{{ country_name }}',$geo_vars['country_name'], $geoip_content);

    }

    $form['geoip'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Block contents'),
      '#format' => 'full_html',
      '#description' => $this->t('This text will appear in the block.'),
      '#default_value' => $geoip_content,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This method processes the blockForm() form fields when the block
   * configuration form is submitted.
   *
   * The blockValidate() method can be used to validate the form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['geoip']
      = $form_state->getValue('geoip');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
$country = 	  $_SERVER['GEOIP_COUNTRY_NAME'];
if($country==='United States' || $country==='Philippines') {
$country = ' the ' . $country;
}
 $geo_vars = [
                'country_name'=>$country,
                'country_code'=>$_SERVER['GEOIP_COUNTRY_CODE']  ,
                'cont_code'=>$_SERVER['GEOIP_CONTINENT_CODE'],
                'ip'=>$_SERVER['GEOIP_ADDR'],
                'icon'=>self::$icons[$_SERVER['GEOIP_COUNTRY_CODE']]
        ];

    if(is_object($this->configuration['geoip']) && $this->configuration['geoip']->render()){
      $geoip_content  = str_replace('{{ icon }}',$geo_vars['icon'], $this->configuration['geoip']->render());
      $geoip_content  = str_replace('{{ country_name }}',$geo_vars['country_name'], $geoip_content);
    }
    else{
      $geoip_content  = str_replace('{{ icon }}',$geo_vars['icon'], $this->configuration['geoip']['value']);
        $geoip_content  = str_replace('{{ country_name }}',$geo_vars['country_name'], $geoip_content);

    }



    return [
      '#block_content' => $geoip_content,
      '#theme' => 'geoip_mini_block',
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}


