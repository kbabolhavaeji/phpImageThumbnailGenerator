# PHP Image Thumbnail Generator

A lightweight PHP class for generating image thumbnails using the GD library. This tool supports multiple image formats, including JPEG, PNG, and GIF. It ensures simplicity, performance, and compatibility for your PHP projects.

---

## Features

- **Supported Formats**: JPEG, PNG, GIF, and BMP.
- **Auto-scaling**: Automatically scales and crops images to fit the desired thumbnail dimensions.
- **Error Handling**: Ensures robust handling of invalid inputs and missing dependencies.
- **Customizable**: Set file permissions and output paths.

---

## Requirements

- PHP 8.1 or higher
- GD library enabled on the server

---

## Installation

1. Clone or download the repository.
2. Include the `ImageThumbnailGenerator.php` class in your project:

```php
require_once 'ImageThumbnailGenerator.php';
```

---

## Usage

### Basic Example

```php
try {
    // Initialize the generator with the relative path to the image
    $thumbnailGenerator = new ImageThumbnailGenerator('/images/sample.jpg');

    // Generate and save a thumbnail
    $thumbnailGenerator->generateThumbnail(
        '/thumbnails/', // Path to save the thumbnail
        200,            // Thumbnail width
        200             // Thumbnail height
    );

    echo "Thumbnail created successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

---

## Methods

### Constructor
```php
__construct(string $relativePath)
```
- **Parameters**:
  - `relativePath`: Path to the image file relative to the current working directory.
- **Exceptions**:
  - Throws an exception if the GD library is missing, the file does not exist, or the file is not a valid image.

---

### Generate Thumbnail
```php
generateThumbnail(string $thumbnailPath, int $thumbnailWidth, int $thumbnailHeight, string $chmod = '0644'): void
```
- **Parameters**:
  - `thumbnailPath`: Directory path to save the thumbnail.
  - `thumbnailWidth`: Width of the thumbnail.
  - `thumbnailHeight`: Height of the thumbnail.
  - `chmod`: Optional file permission string (default: `0644`).
- **Exceptions**:
  - Throws an exception if the thumbnail cannot be generated or saved.

---

## Error Handling

This class uses exceptions to handle errors, such as:
- GD library not being enabled.
- File not found.
- Unsupported image format.

You can catch these exceptions and handle them as needed.

---

## License

This project is licensed under the MIT License. You are free to use, modify, and distribute this project with attribution.

---

## Author

**Kiumars Babolhavaeji**  
Email: [k.babolhavaeji@gmail.com](mailto:k.babolhavaeji@gmail.com)  
Date: 30 December 2023  

---

Enjoy using this tool! 🎉
