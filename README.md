Laravel + Vue.js CMS Demo
This project is a Laravel-based CMS with a Vue.js frontend that allows you to manage pages in a nested tree structure. It supports CRUD operations for pages and dynamically resolves nested routes (e.g., /page1, /page1/page2).

Table of Contents
Features

Prerequisites

Setup Instructions

Running the Application

API Endpoints

Frontend Integration

Testing

License

Features
Dynamic Tree View: Render nested pages in a tree structure.

CRUD Operations: Create, Read, Update, and Delete pages.

Nested Routes: Dynamically resolve page content based on the URL structure (e.g., /page1, /page1/page2).

Frontend in Vue.js: Interactive and responsive user interface.

API-Driven: Backend provides RESTful API endpoints for page management.

Prerequisites
Before you begin, ensure you have the following installed:

PHP 8.0 or higher

Composer

Node.js and npm

MySQL or SQLite

Git

Setup Instructions
Step 1: Clone the Repository
Clone the repository to your local machine:


git clone https://github.com/mitsuchak/laravel-vue-cms-demo.git
cd laravel-vue-cms-demo
Step 2: Install Backend Dependencies
Install PHP dependencies using Composer:


composer install
Step 3: Set Up Environment
Copy the .env.example file to .env:


cp .env.example .env
Update the .env file with your database credentials:

env
Copy
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tree_view_cms
DB_USERNAME=root
DB_PASSWORD=
Generate an application key:


php artisan key:generate
Step 4: Run Migrations
Run database migrations to create the necessary tables:


php artisan migrate
Step 5: Seed the Database (Optional)
Seed the database with sample data:


php artisan db:seed
Step 6: Install Frontend Dependencies
Install JavaScript dependencies using npm:


npm install
Step 7: Compile Frontend Assets
Compile the frontend assets for development:


npm run dev
Running the Application
Start the Laravel development server:


php artisan serve
Access the application in your browser:

Copy
http://localhost:8000
API Endpoints
The backend provides the following API endpoints:

Method	Endpoint	Description
GET	/api/pages	Fetch all pages in a tree view.
POST	/api/pages	Create a new page.
PUT	/api/pages/{id}	Update a page.
DELETE	/api/pages/{id}	Delete a page.
GET	/api/slug-pages/{slug}	Fetch page content by slug.
Frontend Integration
The frontend is built using Vue.js and integrates with the Laravel backend via API calls. Key features include:

Dynamic Tree View: Render nested pages recursively.

CRUD Operations: Forms for creating, updating, and deleting pages.

Nested Routes: Navigate to pages using dynamic routes (e.g., /page1, /page1/page2).

Testing
To run the tests, use the following command:


php artisan test
License
This project is open-sourced under the MIT License.

Acknowledgments
Laravel for providing an excellent framework.

Vue.js for making frontend development a breeze.

The TDD approach for ensuring robust and maintainable code.

Thank you for using Laravel + Vue.js CMS Demo! 🚀

Additional Notes
If you encounter any issues during setup, please check the Laravel and Vue.js documentation or open an issue on the repository.

Feel free to contribute to the project by submitting pull requests or suggesting new features.

Let me know if you need further assistance! 🚀

