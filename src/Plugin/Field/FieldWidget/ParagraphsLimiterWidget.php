<?php

namespace Drupal\paragraph_limiter_widget\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\narrow_widgets\MultipleNarrowWidgetTrait;
use Drupal\paragraphs\Plugin\Field\FieldWidget\InlineParagraphsWidget;

/**
 * Plugin implementation of the 'paragraph_limiter_widget' widget.
 *
 * Allows for the form widget display mode to set minimum and maximum number
 * of paragraph references.
 *
 * @FieldWidget(
 *   id = "paragraph_limiter_widget",
 *   label = @Translation("Paragraphs Classic (Limiter)"),
 *   description = @Translation("A paragraph widget allowing limitations on number of references."),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class ParagraphsLimiterWidget extends InlineParagraphsWidget {

  use MultipleNarrowWidgetTrait;

  /**
   * {@inheritdoc}
   */
  protected $primaryValueKey = 'subform';

  /**
   * Indicates whether the current widget instance is in translation.
   *
   * Redeclare this as it's private and might break parent functionality.
   *
   * @var bool
   */
  private $isTranslating;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings() +
      static::getMultipleNarrowDefaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function form(FieldItemListInterface $items, array &$form, FormStateInterface $form_state, $get_delta = NULL) {
    $formBuild = parent::form($items, $form, $form_state, $get_delta);
    // Paragraphs css styling was set directly for the original plugin.
    $formBuild['#attributes']['class'][] = 'field--widget-' . Html::getClass('entity_reference_paragraphs');

    $this->addLimitToFieldLabel($formBuild['widget']['#title'], $form_state);

    return $formBuild;
  }

  /**
   * {@inheritdoc}
   */
  public function formMultipleElements(FieldItemListInterface $items, array &$form, FormStateInterface $form_state) {
    $formBuild = parent::formMultipleElements($items, $form, $form_state);
    $this->alterAddMoreButtonForm($formBuild, 'add_more', $form_state);
    return $formBuild;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $formBuild = parent::settingsForm($form, $form_state);
    $this->addMultipleNarrowSettings($formBuild, $form_state);
    return $formBuild;
  }

  /**
   * {@inheritdoc}
   */
  public function elementValidate($element, FormStateInterface $form_state, $form) {
    parent::elementValidate($element, $form_state, $form);
    $this->validateMultipleNarrowForm($element['subform'], $form_state);
  }

}
