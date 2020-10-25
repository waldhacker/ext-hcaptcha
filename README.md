# hCaptcha for TYPO3 EXT:form

[![codecov](https://codecov.io/gl/susannemoog/hcaptcha/branch/main/graph/badge.svg?token=QPAS36XVEM)](https://codecov.io/gl/susannemoog/hcaptcha/)
[![pipeline](https://gitlab.com/sunny-sunshine/hcaptcha/badges/main/pipeline.svg)]()
[![license](https://img.shields.io/badge/license-GPL%20v3-brightgreen)](https://choosealicense.com/licenses/gpl-3.0/)
[![phpstan](https://img.shields.io/badge/PHPStan-lvl%20max-blueviolet)](https://phpstan.org/)

Provides [hCaptcha](https://hcaptcha.com) integration for TYPO3 EXT:form.

hCaptcha is a free to use alternative to Google reCaptcha with a bigger focus on privacy. It supports initiatives like [PrivacyPass](https://www.hcaptcha.com/privacy-pass).

For more information, see [the hCaptcha website](https://hcaptcha.com).

Additionally, hCaptcha provides earnings for solved captchas - that can be donated to
the Wikimedia foundation automatically (which is the case for the default settings of this extension).

## Quick Setup

hCaptcha is configured with a default key to make the setup as easy as possible.
It is recommended to create a custom account for your site with hCaptcha and add your own keys.

- Install the extension and activate it
- Include the static template in TypoScript
- Add a `hCaptcha` element to a form

### TypoScript Constants

Set the following constants if you are using your own account:

```typo3_typoscript
plugin.tx_hcaptcha {
  settings {
    publicKey = <your-public-key>
    privateKey = <your-private-key>
  }
}
```

### Environment variables
As an alternative to the TypoScript configuration, you can also use environment variables:
* `HCAPTCHA_PUBLIC_KEY`
* `HCAPTCHA_PRIVATE_KEY`

### Content Security Policy

If you are using CSP, make sure to adjust them accordingly:

* script-src should include `https://hcaptcha.com, https://*.hcaptcha.com`
* frame-src should include `https://hcaptcha.com, https://*.hcaptcha.com`
* style-src should include `https://hcaptcha.com, https://*`

## Privacy

Make sure to inform your users of your usage of hCaptcha and what that means - especially if you
are using the invisible Enterprise version.

For more info see: https://docs.hcaptcha.com/faq

### Help & Support

* Issues: https://gitlab.com/susannemoog/hcaptcha/-/issues
* Slack: #typo3-cms

Follow me on [Twitter](twitter.com/sasunegomo) or read [my blog entry about hCaptcha at susi.dev](https://susi.dev/hcaptcha).
