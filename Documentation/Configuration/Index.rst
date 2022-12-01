.. include:: ../Includes.txt

.. _configuration:

=============
Configuration
=============

First of all:

- Include the static template of `hCaptcha` via :guilabel:`Web>Template` module

.. note::
   If you prefer to include the files directly, include:

   - :file:`EXT:hcaptcha/Configuration/TypoScript/setup.typoscript` as setup
   - :file:`EXT:hcaptcha/Configuration/TypoScript/constants.typoscript` as constants

TypoScript Configuration
========================

No further configuration is necessary, however, it is recommended to create your own
`hCaptcha` account, which allows you to adjust settings like difficulty or the types of
puzzles to use.

When using your own account, adjust the private key (hcaptcha wording: "Secret key") and public key (hcaptcha wording: "Site Key") *constants*:

.. code-block:: typoscript

   plugin.tx_hcaptcha {
     settings {
       publicKey = <your-hcaptcha-site-key>
       privateKey = <your-hcaptcha-secret-key>
     }
   }

Environment Variables
=====================

Instead of TypoScript constants, the extension is also able to work with environment
variables (if you want installation wide configuration).

.. code-block:: bash

   HCAPTCHA_PUBLIC_KEY=<your-hcaptcha-site-key>
   HCAPTCHA_PRIVATE_KEY=<your-hcaptcha-secret-key>

.. note::

   This extension does not provide a .env loader.
