<?php

declare(strict_types=1);
namespace Drupal\bt_enlarger\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bootstrap_toolbox\UtilityServiceInterface;



/**
 * Plugin implementation of the 'image_enlarger' formatter.
 *
 * @FieldFormatter(
 *   id = "bt_image_enlarger",
 *   label = @Translation("BT Image Enlarger"),
 *   field_types = {
 *     "entity_reference",
 *     "image"
 *   }
 * )
 */
class ImageEnlargerFormatter extends FormatterBase {


  /**
   * The entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $pluginManager;

  /**
   * The utility service.
   *
   * @var \Drupal\bootstrap_toolbox\UtilityServiceInterface
   */
  protected $utilityService;


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    $instance = parent::create($container, $configuration, $pluginId, $pluginDefinition);
    $instance->utilityService = $container->get('bootstrap_toolbox.utility_service');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'normal_image_style' => 'thumbnail',
      'enlarged_image_style' => 'default',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $settings = $this->getSettings();
    
    $imageStyles = $this->utilityService->getImageStyles();

    $element['normal_image_style'] = [
      '#type' => 'select',
      '#title' => t('Image style'),
      '#default_value' => $this->getSetting('normal_image_style'),
      '#options' => $imageStyles,
    ];

    $element['enlarged_image_style'] = [
      '#type' => 'select',
      '#title' => t('Enlarged image style'),
      '#default_value' => $this->getSetting('enlarged_image_style'),
      '#options' => $imageStyles,
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $normalImageStyle = $this->getSetting('normal_image_style');
    $enlargedImageStyle = $this->getSetting('enlarged_image_style');
    $summary[] = t('Normal image style: @style', ['@style' => $normalImageStyle]);
    $summary[] = t('Enlarged image style: @style', ['@style' => $enlargedImageStyle]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {

      $normalImageStyle = $this->getSetting('normal_image_style');
      $enlargedImageStyle = $this->getSetting('enlarged_image_style');
      
      $enlargedImageId = 'enlarged-image-id-' . uniqid();
      $normalImageUrl = NULL;
      $enlargedImageUrl = NULL;

      if($item->getPluginId() == 'field_item:entity_reference'){
        $mediaId = $item->target_id;
        $normalImageUrl = $this->utilityService->getMediaUrlByMediaIdAndImageStyle($mediaId,$normalImageStyle);
        $enlargedImageUrl =  $this->utilityService->getMediaUrlByMediaIdAndImageStyle($mediaId,$enlargedImageStyle);
      } elseif($item->getPluginId() == 'field_item:image'){
        $mediaId = $item->getParent()->getEntity()->id();
        $normalImageUrl = $this->utilityService->getMediaUrlByMediaIdAndImageStyle($mediaId,$normalImageStyle);
        $enlargedImageUrl = $this->utilityService->getMediaUrlByMediaIdAndImageStyle($mediaId,$enlargedImageStyle);
      }

      $settings = $this->getThirdPartySetting('bootstrap_toolbox','settings',[]);
      $settings['wrapper'] = $this->utilityService->getWrapperById($settings['wrapper']);
      $settings['wrapper_style'] = $this->utilityService->getStyleById($settings['wrapper_style']);
      $settings['style'] = $this->utilityService->getStyleById($settings['style']);
      
      $elements[$delta] = [
        '#theme' => 'bt_enlarger_image',
        '#normal_image_url' => $normalImageUrl,
        '#enlarged_image_url' => $enlargedImageUrl,
        '#enlarged_image_id' => $enlargedImageId,
        '#settings' => $settings,
        '#attached' => [
          'library' => [
            'bt_enlarger/bt_enlarger_image',
          ],
        ],
      ];
    }
    return $elements;
  }
}
