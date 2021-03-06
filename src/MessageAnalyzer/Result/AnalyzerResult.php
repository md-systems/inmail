<?php
/**
 * @file
 * Contains \Drupal\inmail\MessageAnalyzer\Result\AnalyzerResult.
 */

namespace Drupal\inmail\MessageAnalyzer\Result;

use Drupal\inmail\DSNStatus;

/**
 * Contains analyzer results.
 *
 * The setter methods only have effect the first time they are called, so values
 * are only writable once.
 *
 * @ingroup analyzer
 */
class AnalyzerResult implements AnalyzerResultWritableInterface, AnalyzerResultReadableInterface {

  protected $properties = array();

  /**
   * {@inheritdoc}
   */
  public function setBounceRecipient($recipient) {
    $this->set('bounce_recipient', $recipient);
  }

  /**
   * {@inheritdoc}
   */
  public function setBounceStatusCode(DSNStatus $code) {
    if ($this->set('bounce_status_code', $code)) {
      return;
    }

    // If subject and detail are 0 (like X.0.0), allow overriding those.
    /** @var \Drupal\inmail\DSNStatus $current_code */
    $current_code = $this->get('bounce_status_code');
    if ($current_code->getSubject() + $current_code->getDetail() == 0) {
      $new_code = new DSNStatus($current_code->getClass(), $code->getSubject(), $code->getDetail());
      $this->properties['bounce_status_code'] = $new_code;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setBounceReason($reason) {
    $this->set('bounce_reason', $reason);
  }

  /**
   * {@inheritdoc}
   */
  public function getBounceRecipient() {
    return $this->get('bounce_recipient');
  }

  /**
   * {@inheritdoc}
   */
  public function getBounceStatusCode() {
    return $this->get('bounce_status_code');
  }

  /**
   * {@inheritdoc}
   */
  public function getBounceReason() {
    return $this->get('bounce_reason');
  }

  /**
   * Set an arbitrary property.
   *
   * The property is only modified if it has not already been set.
   *
   * @param string $key
   *   The name of the property to set.
   * @param mixed $value
   *   The value of the property.
   *
   * @return bool
   *   TRUE if the property was set, FALSE if it had already been set before.
   */
  protected function set($key, $value) {
    if (!isset($this->properties[$key])) {
      $this->properties[$key] = $value;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get an arbitrary property.
   *
   * @param string $key
   *   The name of the property to get.
   *
   * @return mixed
   *   The property value, or NULL if it has not been set.
   */
  protected function get($key) {
    if (isset($this->properties[$key])) {
      return $this->properties[$key];
    }
    return NULL;
  }
}
