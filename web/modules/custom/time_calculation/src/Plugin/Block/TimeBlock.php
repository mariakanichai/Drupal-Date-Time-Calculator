<?php

namespace Drupal\time_calculation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\time_calculation\TimeCalculationService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'Time displaying' Block.
 *
 * @Block(
 *   id = "time_block",
 *   admin_label = @Translation("Time block"),
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Variable for timeCalculation sevice.
   *
   * @var string
   */
  protected $timeCalculation;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimeCalculationService $timeCalculation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timeCalculation = $timeCalculation;
  }

  /**
   * {@inheritdoc}
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
      '#cache' => [
        'tags' => $this->getCacheTags(),
        'contexts' => $this->getCacheContexts(),
      ],
    ];
  }

  /**
   * Cache tags.
   */
  public function getCacheTags() {
    // Block will rebuild when node changes.
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      // If there is node add  cachetag.
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $node->id()]);
    }
    else {
      // Return default tags.
      return parent::getCacheTags();
    }
  }

  /**
   * Get CacheContext.
   */
  public function getCacheContexts() {
    // Every new route this block will rebuild.
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

}
