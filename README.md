# Bootstrap Toolbox Image Enlarger (bt_enlarger)

**Author:** Carlos Espino  
**Drupal.org Profile:** [Carlos Espino](https://www.drupal.org/u/carlos-espino)

## Introduction

The **Bootstrap Toolbox Image Enlarger** module provides a field formatter that allows users to display an image that can be enlarged to full screen by clicking on an icon with a magnifying glass. This feature enhances user interaction by providing a simple and intuitive way to zoom into images within the content.

## Features

- Custom field formatter for image fields.
- Displays a clickable image that enlarges to full screen when clicked.
- Utilizes AJAX to load the enlarged image in a modal, improving user experience.
- Configurable image styles for both normal and enlarged images.
- Seamless integration with Bootstrap for a responsive design.

## Installation

1. Download and install the module via Composer:
    ```bash
    composer require drupal/bt_enlarger
    ```
2. Enable the module:
    ```bash
    drush en bt_enlarger
    ```

3. Clear the cache:
    ```bash
    drush cr
    ```

## Usage

1. Navigate to the Manage Display settings of your content type.
2. Select the **BT Image Enlarger** formatter for any image field.
3. Configure the image styles for both the normal and enlarged versions of the image.

## Configuration

The formatter settings allow you to choose different image styles for the normal and enlarged versions. This can be configured directly from the field display settings of your content type.

## Customization

You can override the default template by copying the template file to your theme's templates directory and modifying it as needed. The template file is located at:

```/modules/custom/bt_enlarger/templates/bt-enlarger-image.html.twig```

## Dependencies

- **Bootstrap** (for modal functionality)
- **Bootstrap Toolbox**

## Maintainers

This module is maintained by [Carlos Espino](https://www.drupal.org/u/carlos-espino). Feel free to reach out through my Drupal profile for any support or contributions.

## License

This project is licensed under the GPLv2. See the LICENSE.txt file for more details.

