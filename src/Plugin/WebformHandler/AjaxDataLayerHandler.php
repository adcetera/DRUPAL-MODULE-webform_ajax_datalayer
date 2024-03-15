<?php

namespace Drupal\webform_ajax_datalayer\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Annotation\WebformHandler;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Form submission handler.
 *
 * @WebformHandler(
 *   id = "ajax_datalayer",
 *   label = @Translation("Push to data layer"),
 *   category = @Translation("Form Handler"),
 *   description = @Translation("Pushes event information to the dataLayer on form submit"),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class AjaxDataLayerHandler extends WebformHandlerBase {

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected \Drupal\Core\Utility\Token $token;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['execute_on_error'] = [
      '#type' => 'checkbox',
      '#required' => FALSE,
      '#title' => $this->t('Execute even if the form has errors'),
      '#default_value' => $this->configuration['execute_on_error']
    ];

    if (!empty($this->configuration['custom_params'])) {
      $paramsValue = explode(",", $this->configuration['custom_params']);
      $params = implode("\n", $paramsValue);
    }

    $form['custom_params'] = [
      '#type' => 'textarea',
      '#required' => FALSE,
      '#title' => $this->t('Custom parameters'),
      '#description' => $this->t(
        'Specify additional key/value pairs to pass to the dataLayer here. 
          Enter one key/value pair on each line. For example: 
          "my_parameter": "my value". You can also use tokens for passing form data 
          such as "my_parameter": "[first_name]" where "[first_name]" is the field machine name.'
      ),
      '#default_value' => $params ?? ''
    ];

    $this->elementTokenValidate($form);
    return $this->setSettingsParents($form);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $customParams = [];
    $paramsArray = explode("\n", $form_state->getValue('custom_params'));
    foreach ($paramsArray as $key => $value) {
      $customParams[$key] = trim(str_replace('\r', '', $value));
    }

    $this->configuration['execute_on_error'] = $form_state->getValue('execute_on_error');
    $this->configuration['custom_params'] = $form_state->getValue('custom_params') ? implode(',', $customParams) : '';
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'execute_on_error' => FALSE,
      'custom_params' => ''
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission){
    parent::submitForm($form, $form_state, $webform_submission);
  }

}