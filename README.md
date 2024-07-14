# SUIIZ App Contacts CRUD API

Welcome to the Contacts CRUD API repository for the SUIIZ App backend internship project. This PHP Laravel project serves as a robust API for managing contact information, built with a focus on scalability, reliability, and adherence to industry best practices.

## Features

- **CRUD Operations:** Create, Read, Update, and Delete operations for managing contact information.
- **Test-Driven Development (TDD):** All functionalities are developed using TDD principles to ensure code reliability and maintainability.
- **Scalable Architecture:** Designed to handle large volumes of data efficiently, ensuring optimal performance.
- **Documentation:** Comprehensive API documentation for seamless integration and usage.

## Technologies Used

- **PHP Laravel:** Leveraging Laravel's powerful framework for rapid development and robustness.
- **PHPUnit:** Utilizing PHPUnit for automated testing to validate functionality and ensure code quality.
- **RESTful API:** Following REST principles for clear and consistent API endpoints.
- **Git:** Version control using Git for collaborative development and code management.

## Getting Started

To get started with the SUIIZ App Contacts CRUD API, follow these steps:

1. **Clone the repository:**
   ```
   git clone https://github.com/Mohamedfathi3060/Contact_API_Laravel
   ```

2. **Install dependencies:**
   ```
   composer install
   ```

3. **Set up your environment:**
   - Copy `.env.example` to `.env` and configure your database settings.
   - Generate an application key:
     ```
     php artisan key:generate
     ```

4. **Run database migrations:**
   ```
   php artisan migrate
   ```

5. **Start the development server:**
   ```
   php artisan serve
   ```

6. **Explore the API documentation:**
   - Visit `http://localhost:8000/docs` to view detailed API documentation and endpoints.

## Contributing

Contributions to improve the Contacts CRUD API are welcome! To contribute:

1. Fork the repository and create your feature branch.
2. Commit your changes and push to your fork.
3. Submit a pull request with a detailed description of your changes.

Please adhere to the project's coding standards and include relevant tests.

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.
