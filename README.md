## Payments Fee Calculator

This project is a Payments Fee Calculator that helps calculate commissions based on various user types, operation types, and currency exchange rates.


## Local Usage

1. Download the project and install dependencies using Composer:

   ```bash
   composer install
   ```

2. To run the script locally, navigate to the `/bin` directory and execute:

   ```bash
   php script.php input.csv
   ```

3. You can modify the `input.csv` file by placing it in the `/bin/data` directory.

## Usage via Composer

1. Execute available scripts using Composer from the project root:

   ```bash
   composer autoload        # Update class autoloading
   composer fix-style       # Automatically fix code style according to PSR-12
   composer check-style     # Check code style according to PSR-12
   composer phpunit         # Run PHPUnit tests
   composer fix-cs          # Fix code style using PHP-CS-Fixer
   composer test-cs         # Check code style using PHP-CS-Fixer (dry-run mode)
   composer test            # Run PHPUnit tests and check code style
   ```

### Script Descriptions

- `autoload`: Update class autoloading using Composer.
- `fix-style`: Automatically fix code style according to PSR-12 using PHP Code Beautifier and Fixer (phpcbf).
- `check-style`: Check code style according to PSR-12 using PHP_CodeSniffer (phpcs).
- `phpunit`: Run PHPUnit tests to verify functionality.
- `fix-cs`: Fix code style using PHP-CS-Fixer.
- `test-cs`: Check code style using PHP-CS-Fixer (dry-run mode).
- `test`: Run PHPUnit tests and check code style.

These scripts will help you easily perform various tasks within the project and adhere to code style standards.
