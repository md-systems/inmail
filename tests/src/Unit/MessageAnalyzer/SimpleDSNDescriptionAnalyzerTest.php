<?php
/**
 * @file
 * Contains \Drupal\Tests\bounce_processing\Unit\MessageAnalyzer\SimpleDSNDescriptionAnalyzerTest.
 */

namespace Drupal\Tests\bounce_processing\Unit\MessageAnalyzer;

use Drupal\bounce_processing\AnalyzerResult;
use Drupal\bounce_processing\Message;
use Drupal\bounce_processing\MessageAnalyzer\SimpleDSNDescriptionAnalyzer;
use Drupal\Tests\bounce_processing\Unit\BounceProcessingUnitTestBase;

/**
 * Unit tests for the DSN descriptoin classifier.
 *
 * @coversDefaultClass \Drupal\bounce_processing\MessageAnalyzer\SimpleDSNDescriptionAnalyzer
 * @group bounce_processing
 */
class SimpleDSNDescriptionAnalyzerTest extends BounceProcessingUnitTestBase {

  /**
   * Tests the classification.
   *
   * @covers ::classify
   * @dataProvider provideMessages
   */
  public function testClassify($filename, $expected_message) {
    $message = Message::parse($this->getRaw($filename));
    $analyzer = new SimpleDSNDescriptionAnalyzer();
    $result = new AnalyzerResult();
    $analyzer->analyze($message, $result);
    $this->assertEquals($expected_message, $result->getBounceExplanation());
  }

  /**
   * Provides expected DSN description messages for test message files.
   */
  public function provideMessages() {
    return [
      ['accessdenied.eml',
        "This is the Postfix program at host kyle.greenpeace.org.

I'm sorry to have to inform you that your message could not
be delivered to one or more recipients. It's attached below.

For further assistance, please send mail to <postmaster>

If you do so, please include this problem report. You can
delete your own text from the attached returned message.

			The Postfix program

<user@example.org>: host mx1.example.org[62.94.82.91] said:
    554 5.7.1 <kyle.greenpeace.org[194.0.197.22]>: Client host rejected: Access
    denied (in reply to RCPT TO command)",
      ],
      ['full.eml',
        "- These recipients of your message have been processed by the mail server:
user@example.org; Failed; 4.2.2 (mailbox full)

    Remote MTA ms5.han.skanova.net: SMTP diagnostic: 552 RCPT TO:<masked4@pne.telia.com> Mailbox disk quota exceeded",
      ],
      ['normal.eml', NULL],
      ['nouser.eml',
        // @todo Decode base64 body in Message::parse().
        "WW91ciBtZXNzYWdlDQoNCiAgU3ViamVjdDogV2Ugd2FudCBhIHRveGljLWZyZWUgZnV0dXJlDQoN
CndhcyBub3QgZGVsaXZlcmVkIHRvOg0KDQogIGVudmlyb25tZW50QGx2bWguZnINCg0KYmVjYXVz
ZToNCg0KICBVc2VyIGVudmlyb25tZW50IChlbnZpcm9ubWVudEBsdm1oLmZyKSBub3QgbGlzdGVk
IGluIERvbWlubyBEaXJlY3RvcnkNCg0KCiMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMj
IyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMj
IyMKQ2UgbWVzc2FnZSBldCBzZXMgZXZlbnR1ZWxsZXMgcGllY2VzIGpvaW50ZXMgc29udCBhZHJl
c3NlcyBleGNsdXNpdmVtZW50IGEgbCdpbnRlbnRpb24gZGUgbGV1cihzKSBkZXN0aW5hdGFpcmUo
cykgZXQgbGV1ciBjb250ZW51IGVzdCBzdHJpY3RlbWVudCBjb25maWRlbnRpZWwuIFNpIHZvdXMg
cmVjZXZleiBjZSBtZXNzYWdlIHBhciBlcnJldXIsIG1lcmNpIGRlIGxlIGRldHJ1aXJlIGV0IGQn
ZW4gYXZlcnRpciBpbW1lZGlhdGVtZW50IGwnZXhwZWRpdGV1ci4gTCdJbnRlcm5ldCBuZSBwZXJt
ZXR0YW50IHBhcyBkJ2Fzc3VyZXIgbCdpbnRlZ3JpdGUgZGUgY2UgbWVzc2FnZSBldC9vdSBkZXMg
cGllY2VzIGpvaW50ZXMsIExWTUgsIGFpbnNpIHF1ZSBsZXMgZW50aXRlcyBxdSdpbCBjb250cm9s
ZSBldCBxdWkgbGUgY29udHJvbGVudCAoY2ktYXByZXMgbGUgZ3JvdXBlIExWTUgpLGRlY2xpbmVu
dCB0b3V0ZSByZXNwb25zYWJpbGl0ZSBkYW5zIGwnaHlwb3RoZXNlIG91IGlsKHMpIGF1cmFpKGVu
dCkgZXRlIGludGVyY2VwdGUgb3UgbW9kaWZpZSBwYXIgcXVpY29ucXVlLiBMZXMgcHJlY2F1dGlv
bnMgcmFpc29ubmFibGVzIGF5YW50IGV0ZSBwcmlzZXMgcG91ciBldml0ZXIgcXVlIGRlcyB2aXJ1
cyBuZSBzb2llbnQgdHJhbnNtaXMgcGFyIGNlIG1lc3NhZ2UgZXQvb3UgZCdldmVudHVlbGxlcyBw
aWVjZXMgam9pbnRlcywgbGUgZ3JvdXBlIExWTUggZGVjbGluZSB0b3V0ZSByZXNwb25zYWJpbGl0
ZSBwb3VyIHRvdXQgZG9tbWFnZSBjYXVzZSBwYXIgbGEgY29udGFtaW5hdGlvbiBkZSB2b3RyZSBz
eXN0ZW1lIGluZm9ybWF0aXF1ZS4gVGhpcyBtZXNzYWdlIGFuZCBpdHMgcG9zc2libGUgYXR0YWNo
bWVudHMgYXJlIGludGVuZGVkIHNvbGVseSBmb3IgdGhlIGFkZHJlc3NlZXMgYW5kIGFyZSBjb25m
aWRlbnRpYWwuIElmIHlvdSByZWNlaXZlIHRoaXMgbWVzc2FnZSBpbiBlcnJvciwgcGxlYXNlIGRl
bGV0ZSBpdCBhbmQgaW1tZWRpYXRlbHkgbm90aWZ5IHRoZSBzZW5kZXIuIFRoZSBJbnRlcm5ldCBj
YW4gbm90IGd1YXJhbnRlZSB0aGUgaW50ZWdyaXR5IG9mIHRoaXMgbWVzc2FnZSBhbmQvb3IgaXRz
IHBvc3NpYmxlIGF0dGFjaG1lbnRzLiBMVk1IIGFuZCBhbnkgb2YgaXRzIHN1YnNpZGlhcmllcyBv
ciBob2xkaW5nIGNvbXBhbmllcyAoaGVyZWluYWZ0ZXIgTFZNSCBHcm91cCkgc2hhbGwgbm90IHRo
ZXJlZm9yZSBiZSBsaWFibGUgZm9yIHRoaXMgbWVzc2FnZSBpZiBtb2RpZmllZCBvciBpbnRlcmNl
cHRlZCBieSBhbnlvbmUuQXMgcmVhc29uYWJsZSBwcmVjYXV0aW9uYXJ5IG1lYXN1cmVzIGhhdmUg
YmVlbiBpbXBsZW1lbnRlZCB0byBwcmV2ZW50IHRoZSB0cmFuc21pc3Npb24gb2YgdmlydXNlcyB3
aXRoaW4gdGhpcyBtZXNzYWdlIGFuZC9vciBpdHMgcG9zc2libGUgYXR0YWNobWVudHMsIExWTUgg
R3JvdXAgcmVmdXNlcyB0byBhY2NlcHQgYW55IHJlc3BvbnNpYmlsaXR5IGZvciBhbnkgZGFtYWdl
IGNhdXNlZCBieSB0aGUgY29udGFtaW5hdGlvbiBvZiB5b3VyIGluZm9ybWF0aW9uIHN5c3RlbS4g
CiMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMj
IyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMgCgo=",
      ],
    ];
  }

}