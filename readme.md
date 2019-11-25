## Huddled Install guide
> You must ensure that your webhost is set up for wildcard subdomains in order to run the multi tenant system correctly. For MAMP there is a good article here : https://www.kevinleary.net/wildcard-dns-hosts-mamp-pro/
> Alternately you can ensure that whatever webserver you use has the subdomain pointing to the source code as well as the top level domain, example.com and example.example.com will need to point at the same source. Bear this in mind if you need to create a second, third, nth tenant. All subdomains will need to point at the same source.
> For DB setups please follow the install instructions here : https://tenancy.dev/docs/hyn/5.4/installation

- Clone into folder from repo : **git clone https://gitlab.huddled.tech/fk-group/form-admin-panel.git**
- **cd ./form-admin-panel**
- **composer install**
- Setup DB as you normally would, set up your .env file to connect. ***Please Note:*** You must ensure that your DB user must have elevated permissions and the "GRANT OPTION" priviledges for the multi-tenant system to work.
- Ensure that you have setup your mail settings in your .env file, during the tenant setup process an email is sent to the tenant admin, you will need this email to set the password.
- additional .env variables needed
    - **APP_URL_BASE={*replace with your dev top level hostname*}** - this needs to be your top level dev domain, example.com
    - **APP_URL=http://${APP_URL_BASE}** - note this is a change from the existing value generated
    - **LIMIT_UUID_LENGTH_32=true**
- Once the .env file is setup you can migrate : **php artisan migrate**
- **npm install**
- **npm run dev**
- - We then need to generate the Passport Keys : **php artisan passport:keys**
- Time to create a new tenant
    - **php artisan tenant:create *{tenant name}* *{tenant admin@email address}***
    - replace {tenant name} with what will become the sub-domain, example will be example.example.com
    - the second parameter will be the tenant admin email address, admin@example.com
- You should recieve an invite email to the specified email address, this will include a link through to the tenant system's password reset page for the admin user
    - Follow the instructions to set the admin password and log into the system


<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1400 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
