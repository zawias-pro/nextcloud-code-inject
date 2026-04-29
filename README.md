# Code Injector for Nextcloud

Code Injector is a small Nextcloud admin app that lets you inject arbitrary HTML into every page.

## Installation

### From Nextcloud App Store

The easiest way to install Code Injector is to use the Nextcloud App Store: https://apps.nextcloud.com/apps/codeinjector

It's recommended to use the App Store, as it will automatically keep the app up to date. If you install it manually, you will have to update it yourself.

### Manually

1. Clone this repository into your Nextcloud `custom_apps` directory as `codeinjector`:
   ```bash
   git clone https://github.com/zawias-pro/nextcloud-code-inject.git codeinjector
   ```
2. Enable the app in the Nextcloud Apps settings.
3. Go to **Settings → Administration → Code Injector**.

## Development

There is a docker development environment available. To start it, run `./start-test-environment.sh` from within the `docker` directory.
