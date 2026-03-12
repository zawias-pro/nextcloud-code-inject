# Code Injector for Nextcloud

Code Injector is a small Nextcloud admin app that lets you inject arbitrary HTML into every page.

## Installation

1. Clone this repository into your Nextcloud `custom_apps` directory as `codeinjector`:
   ```bash
   git clone https://github.com/zawias-pro/nextcloud-code-inject.git codeinjector
   ```
2. Enable the app in the Nextcloud Apps settings.
3. Go to **Settings → Administration → Code Injector**.

## Development

There is a docker development environment available. To start it, run `./start-test-environment.sh` from within the `docker` directory.
