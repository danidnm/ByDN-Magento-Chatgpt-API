# Magento 2 OpenAI ChatGPT Integration Module

Welcome to the Magento 2 OpenAI ChatGPT Integration Module repository! This module provides an API connection with OpenAI's ChatGPT, allowing developers to use it as a foundation to create other extensions. The module supports the usage of both the completions API and the moderation API, which are free at the moment.

## Features

- Seamless integration with OpenAI's ChatGPT API.
- Utilization of the completions API for generating text based on prompts.
- Utilization of the moderation API for content moderation and filtering.
- Easy to extend and customize for developing additional modules.

## Installation

To install this module, follow these steps:

1. Clone the repository to your Magento 2 `app/code` directory:
    ```bash
    git clone https://github.com/danidnm/ByDN-Magento-Chatgpt-API app/code/Bydn/OpenAi
    ```
2. Enable the module:
    ```bash
    php bin/magento module:enable Bydn_OpenAi
    ```
3. Run the setup upgrade command:
    ```bash
    php bin/magento setup:upgrade
    ```
4. Deploy static content:
    ```bash
    php bin/magento setup:static-content:deploy
    ```
5. Clear the cache:
    ```bash
    php bin/magento cache:clean
    ```

## Configuration

After installation, you need to configure the module with your OpenAI API credentials:

1. Go to the Magento Admin panel.
2. Navigate to `Stores` > `Configuration` > `AI Extensions (ByDN)` > `OpenAI API Configuration`.
3. Enable the module and enter your OpenAI API key in the provided field.
4. Save the configuration.

## Usage

Once configured, you can start using the OpenAI ChatGPT API in your custom modules. Here is an example module that uses this integration to automatically approbe or reject reviews in your store: [ByDN Magento Chatgpt Review Validator](https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator).

## Contributing

We welcome contributions to this project. If you have an idea for an improvement or find a bug, please open an issue or submit a pull request.

## License

This project is licensed under the MIT License.

## Acknowledgments

- [OpenAI](https://www.openai.com/) for providing the ChatGPT API.
- The Magento community for their continuous support and contributions.

Thank you for using the Magento 2 OpenAI ChatGPT Integration Module!
