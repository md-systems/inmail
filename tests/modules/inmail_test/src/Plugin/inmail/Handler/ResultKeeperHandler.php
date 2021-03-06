<?php
/**
 * @file
 * Contains \Drupal\inmail_test\Handler\ResultKeeperHandler.
 */

namespace Drupal\inmail_test\Plugin\inmail\Handler;

use Drupal\inmail\Message;
use Drupal\inmail\MessageAnalyzer\Result\AnalyzerResultReadableInterface;
use Drupal\inmail\Plugin\inmail\Handler\HandlerBase;

/**
 * Stores analysis results to let them be easily evaluated by tests.
 *
 * @Handler(
 *   id = "result_keeper",
 * )
 */
class ResultKeeperHandler extends HandlerBase {

  /**
   * The processed message.
   *
   * @var \Drupal\inmail\Message
   */
  public static $message;

  /**
   * The analysis result.
   *
   * @var \Drupal\inmail\MessageAnalyzer\Result\AnalyzerResultWritableInterface
   */
  public static $result;

  /**
   * {@inheritdoc}
   */
  public function help() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function invoke(Message $message, AnalyzerResultReadableInterface $result) {
    static::$message = $message;
    static::$result = $result;
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    return TRUE;
  }

}
