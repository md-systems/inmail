<?php
/**
 * @file
 * Contains \Drupal\inmail\Plugin\inmail\Handler\BrokenHandler.
 */

namespace Drupal\inmail\Plugin\inmail\Handler;

use Drupal\inmail\Message;
use Drupal\inmail\MessageAnalyzer\Result\AnalyzerResultReadableInterface;

/**
 * Fallback handler plugin.
 *
 * If you create a handler configuration, then uninstall the module that
 * provides the handler, then this will show up as the handler for that
 * configuration.
 *
 * @Handler(
 *   id = "broken",
 *   label = @Translation("Missing handler"),
 *   description = @Translation("The handler plugin for this configuration is missing.")
 * )
 */
class BrokenHandler extends HandlerBase {

  /**
   * {@inheritdoc}
   */
  public function help() {
    return array(
      '#type' => 'item',
      '#markup' => $this->t('The actual handler plugin used with this configuration entry is missing. Perhaps you uninstalled the module that provided it.'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function invoke(Message $message, AnalyzerResultReadableInterface $result) {
    // Do nothing.
  }

}
