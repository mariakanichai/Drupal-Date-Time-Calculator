<?php

namespace Drupal\time_calculation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\time_calculation\TimeCalculationService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Time displaying' Block.
 *
 * @Block(
 *   id = "time_block",
 *   admin_label = @Translation("Time block"),
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  protected $timeCalculation;

  /**
   * Constructor to inject the time calculation service
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimeCalculationService $timeCalculation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timeCalculation = $timeCalculation;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('time_calculation.custom_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('time_calculation.settings');
    $city = $config->get('city');
    $details = $this->timeCalculation->dateTimeCalculate();

    return [
      '#theme' => 'time_block',
      '#city' => $details['city'],
      '#dateTime' => $details['dateTime'],
      '#country' => $details['country'],

    ];
  }

}
