<?php

namespace Drupal\time_calculation;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Service with config.
 */
class TimeCalculationService {

  /**
   * Configuration settings variable.
   *
   * @var string
   */
  protected $config;

  /**
   * DateTime variable.
   *
   * @var string
   */
  protected $date;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('time_calculation.settings');
    $this->date = new DrupalDateTime();

  }

  /**
   * Calculate time based on the timezone config values.
   */
  public function dateTimeCalculate() {
    $country = $this->config->get('country');
    $timeZone = $this->config->get('timezone');
    $city = $this->config->get('city');

    $this->date->setTimezone(new \DateTimeZone($timeZone));
    $currentDate = $this->date->format('jS M - Y H:i A');

    $details = [
      "city" => $city,
      "country" => $country,
      "dateTime" => $currentDate,
    ];

    return $details;
  }

}
