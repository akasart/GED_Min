

## Introduction
Task Manager is an open-source Laravel application designed to simplify the process of managing project alone with task. The task page is designed like clickup or trello board, so developer will get a very flexbility to handle all This documentation provides a step-by-step guide on how to set up the project.

### Prerequisites
- PHP 8.1 or higher
- Composer
- Laravel 10 or higher
- MySQL or any other supported database system

## Setup Instructions

### Step 1: Clone the Repository
```
git clone https://github.com/arafat-web/Task-Manager.git
cd Task-Manager
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Configure Environment Variables
Duplicate the `.env.example` file and rename it to `.env`. Update the following variables:


### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Run Migrations and Seed Database
```bash
php artisan migrate --seed
```

### Step 6: Serve the Application
```bash
php artisan serve
```

Access the application in your browser at `http://localhost:8000`.

 



### Contact Me
[![Email](https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:arafat.122260@gmail.com)
[![Facbook](https://img.shields.io/badge/Facebook-1877F2?style=for-the-badge&logo=facebook&logoColor=white)](https://www.facebook.com/arafathossain000)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/arafat-hossain-ar-a174b51a6/)
[![Sololearn](https://img.shields.io/badge/-Sololearn-3a464b?style=for-the-badge&logo=Sololearn&logoColor=white)](https://www.sololearn.com/profile/4703319)
[![Website](https://img.shields.io/badge/website-000000?style=for-the-badge&logo=About.me&logoColor=white)](https://arafatdev.com)
