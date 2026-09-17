<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Project Structure
mongo_lara/
├── back/                          # Laravel 12 API (Lambda-ready)
│   ├── app/
│   │   ├── Http/Controllers/Api/  # 4 controllers
│   │   ├── Http/Requests/         # 6 form requests
│   │   ├── Http/Resources/        # 3 API resources
│   │   └── Models/                # 3 MongoDB models
│   ├── config/cors.php            # CORS for CloudFront + localhost
│   ├── routes/api.php             # 16 API routes
│   ├── handler.php                # Lambda entry point
│   ├── serverless.yml             # Bref Lambda config
│   ├── bref.toml                  # PHP extensions
│   └── terraform/                 # 8 IaC files
│       ├── main.tf, variables.tf, outputs.tf
│       ├── iam.tf, lambda.tf, api_gateway.tf
│       ├── s3.tf, cloudfront.tf
│
├── front/                         # React + TypeScript + Tailwind
│   ├── src/
│   │   ├── api/                   # 5 API modules (axios)
│   │   ├── components/            # 5 reusable components
│   │   └── pages/                 # 7 pages (Dashboard + CRUD)
│   └── dist/                      # Production build (verified)
Backend API (16 routes)
Endpoint	Methods
/api/candidates	GET, POST
/api/candidates/{id}	GET, PUT, DELETE
/api/interviews	GET, POST
/api/interviews/{id}	GET, PUT, DELETE
/api/interviewers	GET, POST
/api/interviewers/{id}	GET, PUT, DELETE
/api/dashboard/stats	GET
To Run Locally
# Backend
cd back
php artisan serve          # http://localhost:8000

# Frontend (separate terminal)
cd front
npm run dev                # http://localhost:5173 (proxies /api to :8000)
To Deploy to AWS
# 1. Build Lambda package
cd back && composer install --no-dev --optimize-autoloader
zip -r deploy.zip . -x "tests/*" "terraform/*" ".git/*"

# 2. Deploy infrastructure
cd terraform
terraform init
terraform apply -var="frontend_url=d1xxxx.cloudfront.net" \
                 -var="mongodb_uri=mongodb+srv://..." \
                 -var="vpc_id=vpc-xxx" \
                 -var='subnet_ids=["subnet-xxx"]' \
                 -var="security_group_id=sg-xxx"

# 3. Deploy frontend to S3
cd ../../front
npm run build
aws s3 sync dist/ s3://$(terraform output -raw s3_bucket_name) --delete
