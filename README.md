# TYPO3 Extension to add hCaptcha to EXT:form - The privacy friendly captcha alternative.

:warning:

**This repository is no longer maintained.  
No Issues or Pull Requests will be considered or approved.  
The maintenance and further development is thankfully the responsibility of [@dreistromland](https://github.com/dreistromland).  
The new code base can be found [in this repository](https://github.com/waldhacker/ext-hcaptcha).
Read all the details about the migration there.
Many thanks to [@dreistromland](https://github.com/dreistromland) for taking over the further development and many thanks to all users for their trust.**

:warning:

[![ci](https://github.com/waldhacker/ext-hcaptcha/actions/workflows/ci.yml/badge.svg)](https://github.com/waldhacker/ext-hcaptcha/actions/workflows/ci.yml)
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

* Issues: https://github.com/waldhacker/ext-hcaptcha
* Slack: #typo3-cms

Visit our [website](https://waldhacker.dev)
 or read [Susi's blog entry about hCaptcha at susi.dev](https://susi.dev/hcaptcha).
